@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.user.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.users.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <td>
                            {{ $user->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <td>
                            {{ $user->first_name }} {{ $user->last_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Slug
                        </th>
                        <td>
                            {{ $user->slug }} 
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                           Contact Number
                        </th>
                        <td>
                            {{ $user->mobile }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.email_verified_at') }}
                        </th>
                        <td>
                            {{ $user->email_verified_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.registration_source') }}
                        </th>
                        <td>
                            {{ $user->registration_source }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.country') }}
                        </th>
                        <td>
                            @foreach($country as $id => $cnt)
                                @if($id == $user->country_id)
                                    {{ $cnt }}
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <td>
                            @foreach($user->roles as $key => $roles)
                                <span class="label label-info">{{ $roles->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.user.fields.active_status') }}
                        </th>
                        <td>
                            
                            <?php
                                if ($user->active_status == '1') {
                                    echo '<span class="badge badge-success">Activate</span>';
                                } else {
                                    echo '<span class="badge badge-danger">Deactivate</span>';
                                }
                                ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Yes Agree to send mail
                        </th>
                        <td>
                            {{ $user->yes_send_email==true ? 'Yes' :'No' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Yes I Agree
                        </th>
                        <td>
                            {{ $user->yes_i_agree==true ? "Yes" :'No' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.users.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
