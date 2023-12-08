<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GearReviews;

use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class GearReviewController extends Controller
{
    public function index()
    {
//        abort_if(Gate::denies('pilot_profile_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $allGear = GearReviews::all();

        return view('admin.gear.index', compact('allGear'));
    }

    
    public function create(Request $req)
    {
//        abort_if(Gate::denies('pilot_profile_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.gear.create');
    }

    public function store(Request $req, GearReviews $gearReviews)
    {
        
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $req->video, $match);
        
        if(!empty($match))
        {
            $youtube_id = ($match[1] != '') ? $match[1] : 'NA' ;
        }
        else
        {
            $youtube_id =  'NA' ;
        }
        
        $gearReviews->name = $req->name;
        $gearReviews->video = $req->video;
        $gearReviews->video_key = $youtube_id;
        
        $gearReviews->save();       
        
        
        return redirect()->action([GearReviewController::class, 'index'])->with('success', 'Successfully Gear Video Added');
    }

    public function edit(Request $req)
    {
//        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        
        $gearReviews = GearReviews::find($req->gear_review);
        
        return view('admin.gear.edit', compact('gearReviews'));
    }

    public function update(Request $req)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $gearReviews = GearReviews::find($req->id);
        
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $req->video, $match);
        if(!empty($match))
        {
            $youtube_id = ($match[1] != '') ? $match[1] : 'NA' ;
        }
        else
        {
            $youtube_id =  'NA' ;
        }
        
        $gearReviews->name = $req->name;
        $gearReviews->video = $req->video;
        $gearReviews->video_key = $youtube_id;
        $gearReviews->save();       

        return redirect()->action([GearReviewController::class, 'index'])->with('success', 'Successfully Gear Video Updated');
    }

    public function destroy(Request $req)
    {
        $gear_review = GearReviews::find($req->gear_review);
        $gear_review->delete();

        return redirect()->action([GearReviewController::class, 'index'])->with('success', 'Successfully Gear Video Deleted');
    }

}
