<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\Country;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendUserMail;
use App\Mail\SendUserPassMail;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::with(['roles'])->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $country = Country::all()->pluck('name', 'id');

        return view('admin.users.create', compact('roles', 'country'));
    }

    public function store(StoreUserRequest $request)
    {
        $store=$request->all();
        
        $store['slug']=$request->name_slug;
        $user = User::create($store);
        $user->roles()->sync($request->input('roles', []));
        $pass = $request->password;
        
        $mailTo = $request->email;
        
        
        Mail::to($mailTo)->send(new SendUserMail($user, $pass));

        return redirect()->route('admin.users.index')->with('success', 'Successfully user created');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $country = Country::all()->pluck('name', 'id');
        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user', 'country'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data=$request->all();

        if ($request->password!=$user->password) {
            $data['password']=bcrypt($request->password);
        }

        $user->update($data);
        $user->roles()->sync($request->input('roles', []));

        $mailTo = $user->email;
        
        if ($request->send_password=='Yes') {
            Mail::to($mailTo)->send(new SendUserPassMail($user, $request->password));
        }
        
        return redirect()->route('admin.users.index')->with('success', 'Successfully user updated');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $country = Country::all()->pluck('name', 'id');
        $user->load('roles');

        return view('admin.users.show', compact('user', 'country'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back()->with('success', 'Successfully user deleted');
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massActiveStatus(Request $request)
    {
        User::whereIn('id', $request->ids)->update(['active_status' => '1']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
    
    public function massInActiveStatus(Request $request)
    {
        User::whereIn('id', $request->ids)->update(['active_status' => '0']);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
