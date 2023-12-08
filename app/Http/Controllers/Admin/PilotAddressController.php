<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\User;
use App\Models\State;
use App\Models\Country;
use App\Models\RoleUser;
use App\Jobs\FindLatAndLng;
use App\Models\PilotAddress;
use App\Models\PilotProfile;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StorePilotRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdatePilotProfileRequest;

class PilotAddressController extends Controller
{
    public function index(PilotAddress $request)
    {
        $country = Country::all()->pluck('name', 'id');
        $users = PilotAddress::with(['users'])->get();

        return view('admin.pilot.address.index', compact('users', 'country'));
    }

    public function create(Request $req)
    {
        $uId = str_rot13(base64_decode($req->id));
        $requestId = $req->id;

        $state = State::all()->pluck('name', 'id');
        $country = Country::all()->pluck('name', 'id');
        return view('admin.pilot.address.create', compact('requestId', 'country', 'state'));
    }

    public function store(Request $req, PilotAddress $pilotAddress)
    {
        $uId = str_rot13(base64_decode($req->user_id));

        $pilotAddress->pilot_profile_id= $uId;
        $pilotAddress->address_line1 = $req->address1;
        $pilotAddress->address_line2 = $req->address2;
        $pilotAddress->city = $req->city;
        $pilotAddress->state = $req->state;
        $pilotAddress->country = $req->country_id;
        $pilotAddress->zip = $req->zip;

        $pilotAddress->save();

        $newGeo=PilotAddress::find($pilotAddress->id);

        $geoExist=PilotAddress::where('city', $pilotAddress->city)
                        ->whereNotIn('id', [$pilotAddress->id])
                        ->whereNotNull('latitude')
                        ->whereNotNull('longitude')
                        ->first();

        if ($geoExist) {
            $latitude=$geoExist->latitude;
            $longitude=$geoExist->longitude;
        } else {
            [$latitude,$longitude]=$newGeo->getLatitudeAndLongitude();
        }
     
        $newGeo->latitude=$latitude;
        $newGeo->longitude=$longitude;
        $newGeo->save();

      
        return redirect()->action([PilotProfileController::class, 'index'])->with('success', 'Successfully pilot address created');
    }

    public function edit(Request $req)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = PilotAddress::with('userOne')->get()->find($req->pilot_address);
        $state = State::where("country_id", $user->country)->pluck('name', 'id');
        $country = Country::all()->pluck('name', 'id');
        
        return view('admin.pilot.address.edit', compact('user', 'country', 'state'));
    }

    public function update(Request $req)
    {
        $pilotAddress = PilotAddress::find($req->pilot_address);
        
        $pilotAddress->address_line1 = $req->address1;
        $pilotAddress->address_line2 = $req->address2;
        $pilotAddress->city = $req->city;
        $pilotAddress->state = $req->state;
        $pilotAddress->country = $req->country_id;
        $pilotAddress->zip = $req->zip;

        $pilotAddress->save();

        FindLatAndLng::dispatch($pilotAddress)
                   ->delay(now()->addSeconds(3));

        return redirect()->action([PilotProfileController::class, 'show'], $pilotAddress->pilot_profile_id)->with('success', 'Successfully Pilot Address Updated');
    }

    public function show(Request $req)
    {
        abort_if(Gate::denies('pilot_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $country = Country::all()->pluck('name', 'id');
        $user = PilotAddress::with('userOne')->get()->find($req->pilot_address);
        
        return view('admin.pilot.address.show', compact('user', 'country'));
    }

    public function destroy(Request $req)
    {
        $pilotAdd = PilotAddress::find($req->pilot_address);
        $pilotAdd->delete();
        return back();
    }

    public function getStates(Request $req)
    {
        $data['states'] = State::where("country_id", $req->country_id)
                    ->get(["name","id"]);
        return response()->json($data);
    }
}
