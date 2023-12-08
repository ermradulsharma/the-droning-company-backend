@extends('layouts.admin')
@section('content')
<style>.profile_pic_box{padding: 15px;border:1px solid #bbb; background: #eee;border-radius: 4px;margin-bottom: 30px;}
.card-data .custom-file img{width:100px; height: 80px;object-fit: contain;border: 1px solid #eee;}
textarea {min-height: 50px;}
</style>
<div class="card">
    <div class="card-header">
        Build {{ trans('cruds.pilotProfile.title') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.company.update", $company->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.CompanyManagement.fields.company_user') }}</label>
                <select class="form-control select2 required" name="user_id" id="user_id" required>
                    <option value="">Select {{ trans('cruds.CompanyManagement.fields.company_user') }}</option>
                    @foreach($users as $id => $user)
                        <option value="{{ $user->id }}" {{ $company->user_id == $user->id ? 'selected' : '' }}>{{ $user->first_name .' '.$user->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="hidden" name="slug" id="slug" value="{{ old('slug', $company->slug) }}">

           
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.pilotProfile.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title2" value="{{ old('title', $company->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.title_helper') }}</span>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="address">{{ trans('cruds.CompanyManagement.fields.address') }}</label>
                        <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', $company->address) }}" required>
                        @if($errors->has('address'))
                            <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="suite">{{ trans('cruds.CompanyManagement.fields.suite') }}</label>
                        <input class="form-control {{ $errors->has('suite') ? 'is-invalid' : '' }}" type="text" name="suite" id="suite" value="{{ old('suite', $company->suite) }}" required>
                        @if($errors->has('suite'))
                            <div class="invalid-feedback">{{ $errors->first('suite') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="city">{{ trans('cruds.CompanyManagement.fields.city') }}</label>
                        <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', $company->city) }}" required>
                        @if($errors->has('city'))
                            <div class="invalid-feedback">{{ $errors->first('city') }}</div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="country">{{ trans('cruds.CompanyManagement.fields.country') }}</label>
                        <input class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" type="text" name="country" id="country" value="{{ old('country', $company->country) }}" required>
                        @if($errors->has('country'))
                            <div class="invalid-feedback">{{ $errors->first('country') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="state">{{ trans('cruds.CompanyManagement.fields.state') }}</label>
                        <input class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" type="text" name="state" id="state" value="{{ old('state', $company->state) }}" required>
                        @if($errors->has('state'))
                            <div class="invalid-feedback">{{ $errors->first('state') }}</div>
                        @endif
                    </div>
                </div>
                
                <!--<div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="country">{{ trans('cruds.CompanyManagement.fields.country') }}</label>
                        <select class="form-control select2" name="country" id="country" required>
                            <option value="" >Select Country</option>
                            @foreach($country as $id => $allcountry)
                                <option value="{{ $id }}" {{ (old('country_id', $company->country) == $id) ? 'selected' : ''}}>{{ $allcountry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="state">{{ trans('cruds.CompanyManagement.fields.state') }}</label>
                        <select class="form-control select2" name="state" id="state" required>
                            <option value="" >Select State</option>
                            @foreach($states as $id => $state)
                                <option value="{{ $id }}" {{ (old('state', $company->state) == $id) ? 'selected' : ''}}>{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>-->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="zip_code">{{ trans('cruds.CompanyManagement.fields.zip') }}</label>
                        <input class="form-control {{ $errors->has('zip_code') ? 'is-invalid' : '' }}" type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', $company->zip_code) }}" required>
                        @if($errors->has('zip_code'))
                            <div class="invalid-feedback">{{ $errors->first('zip_code') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="contact_person">{{ trans('cruds.CompanyManagement.fields.contact_person') }}</label>
                        <input class="form-control {{ $errors->has('contact_person') ? 'is-invalid' : '' }}" type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $company->contact_person) }}" required>
                        @if($errors->has('contact_person'))
                            <div class="invalid-feedback">{{ $errors->first('contact_person') }}</div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="website">{{ trans('cruds.CompanyManagement.fields.website') }}</label>
                        <input class="form-control {{ $errors->has('website') ? 'is-invalid' : '' }}" type="text" name="website" id="website" value="{{ old('website', $company->website) }}" required>
                        @if($errors->has('website'))
                            <div class="invalid-feedback">{{ $errors->first('website') }}</div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="email">{{ trans('cruds.CompanyManagement.fields.email') }}</label>
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $company->email) }}" required>
                        @if($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="phone">{{ trans('cruds.CompanyManagement.fields.phone') }}</label>
                        <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="phone" name="phone" id="phone" value="{{ old('phone', $company->phone) }}" required>
                        @if($errors->has('phone'))
                            <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <h4>Social Media</h4>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="" for="facebook">{{ trans('cruds.CompanyManagement.fields.facebook') }}</label>
                        <input class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" type="text" name="facebook" id="facebook" value="{{ old('facebook', $company->facebook) }}">
                        @if($errors->has('facebook'))
                            <div class="invalid-feedback">{{ $errors->first('facebook') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="" for="twitter">{{ trans('cruds.CompanyManagement.fields.twitter') }}</label>
                        <input class="form-control {{ $errors->has('twitter') ? 'is-invalid' : '' }}" type="text" name="twitter" id="twitter" value="{{ old('twitter', $company->twitter) }}">
                        @if($errors->has('twitter'))
                            <div class="invalid-feedback">{{ $errors->first('twitter') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="" for="linkedin">{{ trans('cruds.CompanyManagement.fields.linkedin') }}</label>
                        <input class="form-control {{ $errors->has('linkedin') ? 'is-invalid' : '' }}" type="text" name="linkedin" id="linkedin" value="{{ old('linkedin', $company->linkedin) }}">
                        @if($errors->has('linkedin'))
                            <div class="invalid-feedback">{{ $errors->first('linkedin') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="" for="youtube">{{ trans('cruds.CompanyManagement.fields.youtube') }}</label>
                        <input class="form-control {{ $errors->has('youtube') ? 'is-invalid' : '' }}" type="text" name="youtube" id="youtube" value="{{ old('youtube', $company->youtube) }}">
                        @if($errors->has('youtube'))
                            <div class="invalid-feedback">{{ $errors->first('youtube') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="" for="instagram">{{ trans('cruds.CompanyManagement.fields.instagram') }}</label>
                        <input class="form-control {{ $errors->has('instagram') ? 'is-invalid' : '' }}" type="text" name="instagram" id="instagram" value="{{ old('instagram', $company->instagram) }}">
                        @if($errors->has('instagram'))
                            <div class="invalid-feedback">{{ $errors->first('instagram') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <h4>{{ trans('cruds.CompanyManagement.fields.services') }}</h4>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="service_1">{{ trans('cruds.CompanyManagement.fields.service_1') }}</label>
                        <input class="form-control {{ $errors->has('service_1') ? 'is-invalid' : '' }}" type="text" name="service_1" id="service_1" value="{{ old('service_1', $company->service_1) }}" required>
                        @if($errors->has('service_1'))
                            <div class="invalid-feedback">{{ $errors->first('service_1') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="service_2">{{ trans('cruds.CompanyManagement.fields.service_2') }}</label>
                        <input class="form-control {{ $errors->has('service_2') ? 'is-invalid' : '' }}" type="text" name="service_2" id="service_2" value="{{ old('service_2', $company->service_2) }}" required>
                        @if($errors->has('service_2'))
                            <div class="invalid-feedback">{{ $errors->first('service_2') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required" for="service_3">{{ trans('cruds.CompanyManagement.fields.service_3') }}</label>
                        <input class="form-control {{ $errors->has('service_3') ? 'is-invalid' : '' }}" type="text" name="service_3" id="service_3" value="{{ old('service_3', $company->service_3) }}" required>
                        @if($errors->has('service_3'))
                            <div class="invalid-feedback">{{ $errors->first('service_3') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group card-data">
                        <label class="required" for="logo">Company Logo</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input {{ $errors->has('logo') ? 'is-invalid' : '' }}" id="customFile" name="logo" value="{{ old('logo', '') }}" onchange="loadFile(event)">
                            <label class="custom-file-label" for="customFile">Upload Logo Image</label>
                            @if($errors->has('logo'))
                                <div class="invalid-feedback">{{ $errors->first('logo') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group card-data">
                        <div class="custom-file" style="height: auto;">
                            <img id="output" title="Preview Image" src="{{ $company->logo ? asset($company->logo) : asset('pilotNoImage.png') }}" alt="Logo Preview Image" width="100">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group card-data">
                        <label class="required" for="featured_image">Company Featured Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input {{ $errors->has('featured_image') ? 'is-invalid' : '' }}" id="customFile1" name="featured_image" value="{{ old('featured_image', '') }}" onchange="loadFile2(event)">
                            <label class="custom-file-label" for="customFile1">Upload Featured Image</label>
                            @if($errors->has('featured_image'))
                                <div class="invalid-feedback">{{ $errors->first('featured_image') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group card-data">
                        <div class="custom-file" style="height: auto;">
                            <img id="output_featured" title="Preview Image" src="{{ $company->featured_image ? asset($company->featured_image) : asset('pilotNoImage.png') }}" alt="Preview Featured Image" width="100">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="profile_img_1">Profile Image 1</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('profile_img_1') ? 'is-invalid' : '' }}" id="profile_img_1" name="profile_img_1" value="{{ old('profile_img_1', '') }}" onchange="loadFile3(event)">
                                <label class="custom-file-label" for="profile_img_1">Upload Profile Image 1</label>
                                @if($errors->has('profile_img_1'))
                                    <div class="invalid-feedback">{{ $errors->first('profile_img_1') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_profile_img_1" title="Preview Image" src="{{ $company->profile_img_1 ? asset($company->profile_img_1) : asset('pilotNoImage.png') }}" alt="profile_img_1 Preview Image" width="100">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pic_desc_1">Description</label>
                            <textarea rows="8" class="form-control {{ $errors->has('pic_desc_1') ? 'is-invalid' : '' }}" name="pic_desc_1" id="pic_desc_1" placeholder="Profile Image 1 Description">{{ old('pic_desc_1', $company->pic_desc_1) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="profile_img_2">Profile Image 2</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('profile_img_2') ? 'is-invalid' : '' }}" id="profile_img_2" name="profile_img_2" value="{{ old('profile_img_2', '') }}" onchange="loadFile4(event)">
                                <label class="custom-file-label" for="profile_img_2">Upload Profile Image 2</label>
                                @if($errors->has('profile_img_2'))
                                    <div class="invalid-feedback">{{ $errors->first('profile_img_2') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_profile_img_2" title="Preview Image" src="{{ $company->profile_img_2 ? asset($company->profile_img_2) : asset('pilotNoImage.png') }}" alt="Preview profile_img_2 Image" width="100">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pic_desc_2">Description</label>
                            <textarea rows="8" class="form-control {{ $errors->has('pic_desc_2') ? 'is-invalid' : '' }}" name="pic_desc_2" id="pic_desc_2" placeholder="Profile Image 2 Description">{{ old('pic_desc_2', $company->pic_desc_2) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="profile_img_3">Profile Image 3</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('profile_img_3') ? 'is-invalid' : '' }}" id="profile_img_3" name="profile_img_3" value="{{ old('profile_img_3', '') }}" onchange="loadFile5(event)">
                                <label class="custom-file-label" for="profile_img_3">Upload Profile Image 3</label>
                                @if($errors->has('profile_img_3'))
                                    <div class="invalid-feedback">{{ $errors->first('profile_img_3') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_profile_img_3" title="Preview Image" src="{{ $company->profile_img_3 ? asset($company->profile_img_3) : asset('pilotNoImage.png') }}" alt="Preview profile_img_3 Image" width="100">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pic_desc_3">Description</label>
                            <textarea rows="8" class="form-control {{ $errors->has('pic_desc_3') ? 'is-invalid' : '' }}" name="pic_desc_3" id="pic_desc_3" placeholder="Profile Image 3 Description">{{ old('pic_desc_3', $company->pic_desc_3) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="profile_img_4">Profile Image 4</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('profile_img_4') ? 'is-invalid' : '' }}" id="profile_img_4" name="profile_img_4" value="{{ old('profile_img_4', '') }}" onchange="loadFile6(event)">
                                <label class="custom-file-label" for="profile_img_4">Upload Profile Image 4</label>
                                @if($errors->has('profile_img_4'))
                                    <div class="invalid-feedback">{{ $errors->first('profile_img_4') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_profile_img_4" title="Preview Image" src="{{ $company->profile_img_4 ? asset($company->profile_img_4) : asset('pilotNoImage.png') }}" alt="Preview profile_img_4 Image" width="100">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pic_desc_4">Description</label>
                            <textarea rows="8" class="form-control {{ $errors->has('pic_desc_4') ? 'is-invalid' : '' }}" name="pic_desc_4" id="pic_desc_4" placeholder="Profile Image 4 Description">{{ old('pic_desc_4', $company->pic_desc_4) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="profile_img_5">Profile Image 5</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('profile_img_5') ? 'is-invalid' : '' }}" id="profile_img_5" name="profile_img_5" value="{{ old('profile_img_5', '') }}" onchange="loadFile7(event)">
                                <label class="custom-file-label" for="profile_img_5">Upload Profile Image 5</label>
                                @if($errors->has('profile_img_5'))
                                    <div class="invalid-feedback">{{ $errors->first('profile_img_5') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_profile_img_5" title="Preview Image" src="{{ $company->profile_img_5 ? asset($company->profile_img_5) : asset('pilotNoImage.png') }}" alt="Preview profile_img_5 Image" width="100">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pic_desc_5">Description</label>
                            <textarea rows="8" class="form-control {{ $errors->has('pic_desc_5') ? 'is-invalid' : '' }}" name="pic_desc_5" id="pic_desc_5" placeholder="Profile Image 5 Description">{{ old('pic_desc_5', $company->pic_desc_5) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <div class="form-group card-data">
                            <label class="" for="profile_img_6">Profile Image 6</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input {{ $errors->has('profile_img_6') ? 'is-invalid' : '' }}" id="profile_img_6" name="profile_img_6" value="{{ old('profile_img_6', '') }}" onchange="loadFile8(event)">
                                <label class="custom-file-label" for="profile_img_6">Upload Profile Image 6</label>
                                @if($errors->has('profile_img_6'))
                                    <div class="invalid-feedback">{{ $errors->first('profile_img_6') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group card-data">
                            <div class="custom-file" style="height: auto;">
                                <img id="output_profile_img_6" title="Preview Image" src="{{ $company->profile_img_6 ? asset($company->profile_img_6) : asset('pilotNoImage.png') }}" alt="Preview profile_img_6 Image" width="100">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pic_desc_6">Description</label>
                            <textarea rows="8" class="form-control {{ $errors->has('pic_desc_6') ? 'is-invalid' : '' }}" name="pic_desc_6" id="pic_desc_6" placeholder="Profile Image 6 Description">{{ old('pic_desc_6', $company->pic_desc_6) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.CompanyManagement.fields.services') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('services') ? 'is-invalid' : '' }}" name="services[]" id="services" multiple required>
                    @foreach($services as $id => $service)
                        <option value="{{ $id }}" {{ in_array($id, old('services', $total_services)) ? 'selected' : '' }}>{{ $service }}</option>
                    @endforeach
                </select>
                @if($errors->has('services'))
                    <div class="invalid-feedback">
                        {{ $errors->first('services') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.skill_helper') }}</span>
            </div> -->
            
            <div class="form-group">
                <label for="description">{{ trans('cruds.pilotProfile.fields.description') }}</label>
                <textarea rows="15" style="resize: none; width: 100%" name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description', $company->description) }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.description_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="description">Short Description</label>
                <textarea rows="6" class="form-control {{ $errors->has('short_description') ? 'is-invalid' : '' }}" name="short_description">{{ old('short_description', $company->short_description) }}</textarea>
                @if($errors->has('short_description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('short_description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.short_description_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="working_hours">{{ trans('cruds.CompanyManagement.fields.working_hours') }}</label> <!--ckeditor -->
                <textarea rows="4" style="resize: none; width: 100%" name="working_hours" class="form-control {{ $errors->has('working_hours') ? 'is-invalid' : '' }}">{{ old('working_hours', $company->working_hours) }}</textarea>
                @if($errors->has('working_hours'))
                    <div class="invalid-feedback">
                        {{ $errors->first('working_hours') }}
                    </div>
                @endif
            </div>

            <div class="row">
                @php $press_release_1 = json_decode($company->press_release_1); @endphp
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <h4>Press Release 1</h4>
                        <hr>
                        <div class="form-group">
                            <label for="press_release_1_subject">Subject</label>
                            <input class="form-control" type="text" name="press_release_1[subject]" id="press_release_1_subject" value="{{ old('press_release_1.subject', @$press_release_1->subject) }}" required>

                            <label for="press_release_1_date">Date</label>
                            <input class="form-control" type="date" name="press_release_1[date]" id="press_release_1_date" value="{{ old('press_release_1.date', @$press_release_1->date) }}" required>

                            <label for="press_release_1">Description</label>
                            <textarea  class="form-control" name="press_release_1[content]" id="press_release_1_content" rows="8" placeholder="Add your press release URL or content here">{{ old('press_release_1.content', @$press_release_1->content) }}</textarea>
                        </div>
                    </div>
                </div>

                @php $press_release_2 = json_decode($company->press_release_2); @endphp
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <h4>Press Release 2</h4>
                        <hr>
                        <div class="form-group">
                            <label for="press_release_2_subject">Subject</label>
                            <input class="form-control" type="text" name="press_release_2[subject]" id="press_release_2_subject" value="{{ old('press_release_2.subject', @$press_release_2->subject) }}" required>

                            <label for="press_release_2_date">Date</label>
                            <input class="form-control" type="date" name="press_release_2[date]" id="press_release_2_date" value="{{ old('press_release_2.date', @$press_release_2->date) }}" required>

                            <label for="press_release_2">Description</label>
                            <textarea  class="form-control" name="press_release_2[content]" id="press_release_2_content" rows="8" placeholder="Add your press release URL or content here">{{ old('press_release_2.content', @$press_release_2->content) }}</textarea>
                        </div>
                    </div>
                </div>

                @php $press_release_3 = json_decode($company->press_release_3); @endphp
                <div class="col-md-4">
                    <div class="profile_pic_box">
                        <h4>Press Release 3</h4>
                        <hr>
                        <div class="form-group">
                            <label for="press_release_3_subject">Subject</label>
                            <input class="form-control" type="text" name="press_release_3[subject]" id="press_release_3_subject" value="{{ old('press_release_3.subject', @$press_release_3->subject) }}" required>

                            <label for="press_release_3_date">Date</label>
                            <input class="form-control" type="date" name="press_release_3[date]" id="press_release_3_date" value="{{ old('press_release_3.date', @$press_release_3->date) }}" required>

                            <label for="press_release_3">Description</label>
                            <textarea  class="form-control" name="press_release_3[content]" id="press_release_3_content" rows="8" placeholder="Add your press release URL or content here">{{ old('press_release_3.content', @$press_release_3->content) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <h4>Droning Company Articles</h4>
            <div class="row">
                @php $dc_articles = json_decode($company->dc_articles); @endphp
                <div class="col-md-6">
                    <div class="profile_pic_box">
                        <h4>Droning Company Article 1</h4>
                        <hr>
                        <div class="form-group">
                            <label for="dc_articles_0_title">Article URL</label>
                            <textarea  class="form-control" name="dc_articles[0][article]" id="dc_articles_0_article" rows="2" placeholder="Add your Droning Company Article URL here">{{ old('dc_articles.0.article', @$dc_articles[0]->article) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="profile_pic_box">
                        <h4>Droning Company Article 2</h4>
                        <hr>
                        <div class="form-group">
                            <label for="dc_articles_1_title">Article URL</label>
                            <textarea  class="form-control" name="dc_articles[1][article]" id="dc_articles_1_article" rows="2" placeholder="Add your Droning Company Article URL here">{{ old('dc_articles.1.article', @$dc_articles[1]->article) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="profile_pic_box">
                        <h4>Droning Company Article 3</h4>
                        <hr>
                        <div class="form-group">
                            <label for="dc_articles_2_title">Article URL</label>
                            <textarea  class="form-control" name="dc_articles[2][article]" id="dc_articles_2_article" rows="2" placeholder="Add your Droning Company Article URL here">{{ old('dc_articles.2.article', @$dc_articles[2]->article) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="profile_pic_box">
                        <h4>Droning Company Article 4</h4>
                        <hr>
                        <div class="form-group">
                            <label for="dc_articles_3_title">Article URL</label>
                            <textarea  class="form-control" name="dc_articles[3][article]" id="dc_articles_3_article" rows="2" placeholder="Add your Droning Company Article URL here">{{ old('dc_articles.3.article', @$dc_articles[3]->article) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="metatitle">{{ trans('cruds.pilotProfile.fields.metatitle') }}</label>
                <input class="form-control {{ $errors->has('metatitle') ? 'is-invalid' : '' }}" type="text" name="metatitle" id="metatitle" value="{{ old('metatitle', $company->metatitle) }}">
                @if($errors->has('metatitle'))
                    <div class="invalid-feedback">
                        {{ $errors->first('metatitle') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.metatitle_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="metatitle">{{ trans('cruds.pilotProfile.fields.metakeyword') }}</label>
                <input class="form-control {{ $errors->has('metakeyword') ? 'is-invalid' : '' }}" type="text" name="metakeyword" id="metakeyword" value="{{ old('metakeyword', $company->metakeyword) }}">
                @if($errors->has('metakeyword'))
                    <div class="invalid-feedback">
                        {{ $errors->first('metakeyword') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.metakeyword_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label for="metadescription">{{ trans('cruds.pilotProfile.fields.metadescription') }}</label>
                <textarea name="metadescription" class="form-control {{ $errors->has('metadescription') ? 'is-invalid' : '' }}" rows="4" style="resize: none; width: 100%">{{ old('metadescription', $company->metadescription) }}</textarea>
                @if($errors->has('metadescription'))
                    <div class="invalid-feedback">
                        {{ $errors->first('metadescription') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pilotProfile.fields.metadescription_helper') }}</span>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label required">{{ trans('cruds.pilotProfile.fields.is_featured') }}</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_featured1" type="radio" value="Yes" name="is_featured" {{ ($company->is_featured) == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_is_featured1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_featured2" type="radio" value="No" name="is_featured" {{ ($company->is_featured) == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_is_featured2">No</label>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label required">Home Featured</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_home_featured1" type="radio" value="Yes" name="home_featured" {{ ($company->home_featured) == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_is_home_featured1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_is_home_featured2" type="radio" value="No" name="home_featured" {{ ($company->home_featured) == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_is_home_featured2">No</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label required">Status</label>
                <div class="col-md-9 col-form-label">
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_status1" type="radio" value="1" name="status" {{ ($company->status) == "1" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_status1">Activate</label>
                    </div>
                    <div class="form-check form-check-inline mr-1">
                        <input class="form-check-input" id="radio_status2" type="radio" value="0" name="status" {{ ($company->status) == "0" ? "checked" : "" }}>
                        <label class="form-check-label" for="radio_status2">Deactivate</label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <button class="btn btn-success" type="submit">{{ trans('global.update') }}</button>
                <a href="{{ route('admin.company.index') }}"><button class="btn btn-danger" type="button">Cancel</button></a>
            </div>
        </form>
    </div>
</div>


@endsection
@section('scripts')
@parent
<script type="text/javascript">
$( document ).ready(function() {
    $("#title2").change(function(){
        url = "{{ route('admin.company.getSlug') }}";
        var title = $(this).val();
        $.ajax({
            headers: {'x-csrf-token': _token},
            method: 'GET',
            url: url,
            data: { title: title, id: "{{ $company->id }}" },
            success: function(responseText){
                var result = $.parseJSON(responseText);
                $('#slug').val(result.slug);
            },
            error: function(data){
                alert("fail---->"+JSON.stringify(data));
            }
        });
    });

    $('#country').on('change', function() {
        $("#loader").html('<span class="alert alert-info">Please wait...</span>');
        var country = this.value;
        $("#state-dropdown").html('');
        $.ajax({
            url : "{{ route('admin.pilot_address.get-states') }}",
            type: "POST",
            data: { country_id: country,_token: '{{csrf_token()}}' },
            dataType : 'json',
            success: function(result)
            {
                $("#loader").html('');
                $('#state').html('<option value="">Select State</option>'); 
                $.each(result.states,function(key,value){
                    $("#state").append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            }
        });
    });
});

function loadFile2(event){
    var output2 = document.getElementById('output_featured');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile3(event){
    var output2 = document.getElementById('output_profile_img_1');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile4(event){
    var output2 = document.getElementById('output_profile_img_2');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile5(event){
    var output2 = document.getElementById('output_profile_img_3');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile6(event){
    var output2 = document.getElementById('output_profile_img_4');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile7(event){
    var output2 = document.getElementById('output_profile_img_5');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
function loadFile8(event){
    var output2 = document.getElementById('output_profile_img_6');
    output2.src = URL.createObjectURL(event.target.files[0]);
    output2.onload = function() {
        URL.revokeObjectURL(output2.src) // free memory
    }
};
</script>
@endsection
