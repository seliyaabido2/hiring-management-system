<br><br>

<!-- Admin -->

@if(isset($user->roles[0]->title) && $user->roles[0]->title == 'Admin')

<div class="admin-div" style="display:block">
    <h6> Admin Details</h6>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
                <label for="company_name">{{ trans('cruds.user.fields.company_name') }}*</label>
                <input type="text" id="company_name" name="company_name" class="form-control" value="{{ $userDetail->AdminDetail['company_name'] ?? '' }}" placeholder="Company Name">
                @if($errors->has('company_name'))
                <em class="invalid-feedback">
                    {{ $errors->first('company_name') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.company_name_helper') }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('company_type') ? 'has-error' : '' }}">
                <label for="company_type">{{ trans('cruds.user.fields.company_type') }}*</label>
                <input type="text" id="company_type" name="company_type" class="form-control" value="{{ $userDetail->AdminDetail['company_type'] ?? '' }}" placeholder="Company Type">
                @if($errors->has('company_type'))
                <em class="invalid-feedback">
                    {{ $errors->first('company_type') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.company_type_helper') }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('phone_no') ? 'has-error' : '' }}">
                <label for="phone_no">{{ trans('cruds.user.fields.phone_no') }}*</label>
                <input type="number" id="phone_no" name="phone_no" class="form-control" value="{{ old('phone_no', isset($userDetail) ? $userDetail->AdminDetail['phone_no'] : '') }}" placeholder="Phone Number">
                @if($errors->has('phone_no'))
                <em class="invalid-feedback">
                    {{ $errors->first('phone_no') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.phone_no_helper') }}
                </p>
            </div>
        </div>

    </div>
</div>

<!-- Employer  -->

@elseif(isset($user->roles[0]->title) && $user->roles[0]->title == 'Employer')

<div class="employee-div" style="display:block">
    <h6> Employee Details</h6>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('emp_company_name') ? 'has-error' : '' }}">
                <label for="emp_company_name">{{ trans('cruds.user.fields.company_name') }}*</label>

                <input type="text" id="emp_company_name" name="emp_company_name" class="form-control" value="{{ $userDetail->EmployerDetail['company_name'] }}" placeholder="Company Name">
                @if($errors->has('emp_company_name'))
                <em class="invalid-feedback">
                    {{ $errors->first('emp_company_name') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.emp_company_name_helper') }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('phone_no') ? 'has-error' : '' }}">
                <label for="phone_no">{{ trans('cruds.user.fields.phone_no') }}*</label>
                <input type="number" id="phone_no" name="phone_no" class="form-control" value="{{ $userDetail->EmployerDetail['phone_no'] }}" placeholder="Phone Number">
                @if($errors->has('phone_no'))
                <em class="invalid-feedback">
                    {{ $errors->first('phone_no') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.phone_no_helper') }}
                </p>
            </div>
        </div>
    </div>
</div>


<!-- Recruiter -->

@elseif(isset($user->roles[0]->title) && $user->roles[0]->title == 'Recruiter')

<div class="recruiter-div" style="display:block">
    <h6> Recruiter Details</h6>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('req_company_name') ? 'has-error' : '' }}">
                <label for="req_company_name">{{ trans('cruds.user.fields.req_company_name') }}*</label>
                <input type="text" id="req_company_name" name="req_company_name" class="form-control" value="{{ $userDetail->RecruiterDetail['company_name'] }}" placeholder="Company Name">
                @if($errors->has('req_company_name'))
                <em class="invalid-feedback">
                    {{ $errors->first('req_company_name') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.req_company_name_helper') }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('req_phone_no') ? 'has-error' : '' }}">
                <label for="req_phone_no">{{ trans('cruds.user.fields.req_phone_no') }}*</label>
                <input type="number" id="req_phone_no" name="req_phone_no" class="form-control" value="{{ $userDetail->RecruiterDetail['phone_no'] }}" placeholder="Phone Number">
                @if($errors->has('req_phone_no'))
                <em class="invalid-feedback">
                    {{ $errors->first('req_phone_no') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.req_phone_no_helper') }}
                </p>
            </div>
        </div>

    </div>
</div>

@else
<!-- Admin -->

<div class="admin-div" style="display:block">
    <h6> Admin Details</h6>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('company_name') ? 'has-error' : '' }}">
                <label for="company_name">{{ trans('cruds.user.fields.company_name') }}*</label>
                <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name', isset($user) ? $user->company_name : '') }}" placeholder="Company Name">
                @if($errors->has('company_name'))
                <em class="invalid-feedback">
                    {{ $errors->first('company_name') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.company_name_helper') }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('company_type') ? 'has-error' : '' }}">
                <label for="company_type">{{ trans('cruds.user.fields.company_type') }}*</label>
                <input type="text" id="company_type" name="company_type" class="form-control" value="{{ old('company_type', isset($user) ? $user->company_type : '') }}" placeholder="Company Type">
                @if($errors->has('company_type'))
                <em class="invalid-feedback">
                    {{ $errors->first('company_type') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.company_type_helper') }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Employer  -->

<div class="employee-div" style="display:none">
    <h6> Employee Details</h6>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('emp_company_name') ? 'has-error' : '' }}">
                <label for="emp_company_name">{{ trans('cruds.user.fields.company_name') }}*</label>
                <input type="text" id="emp_company_name" name="emp_company_name" class="form-control" value="{{ old('company_name', isset($user) ? $user->company_name : '') }}" placeholder="Company Name">
                @if($errors->has('emp_company_name'))
                <em class="invalid-feedback">
                    {{ $errors->first('emp_company_name') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.emp_company_name_helper') }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('phone_no') ? 'has-error' : '' }}">
                <label for="phone_no">{{ trans('cruds.user.fields.phone_no') }}*</label>
                <input type="text" id="phone_no" name="phone_no" class="form-control" value="{{ old('phone_no', isset($user) ? $user->phone_no : '') }}" placeholder="Phone Number">
                @if($errors->has('phone_no'))
                <em class="invalid-feedback">
                    {{ $errors->first('phone_no') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.phone_no_helper') }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('emp_address') ? 'has-error' : '' }}">
                <label for="emp_address">{{ trans('cruds.user.fields.emp_address') }}*</label>
                <input type="text" id="emp_address" name="emp_address" class="form-control" value="{{ old('address', isset($user) ? $user->address : '') }}" placeholder="Address">
                @if($errors->has('address'))
                <em class="invalid-feedback">
                    {{ $errors->first('emp_address') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.emp_address_helper') }}
                </p>
            </div>
        </div>
      
    </div>
</div>

<!-- Recruiter -->

<div class="recruiter-div" style="display:none">
    <h6> Recruiter Details</h6>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('req_company_name') ? 'has-error' : '' }}">
                <label for="req_company_name">{{ trans('cruds.user.fields.req_company_name') }}*</label>
                <input type="text" id="req_company_name" name="req_company_name" class="form-control" value="{{ old('company_name', isset($user) ? $user->company_name : '') }}" placeholder="Company Name">
                @if($errors->has('req_company_name'))
                <em class="invalid-feedback">
                    {{ $errors->first('req_company_name') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.req_company_name_helper') }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('req_phone_no') ? 'has-error' : '' }}">
                <label for="req_phone_no">{{ trans('cruds.user.fields.req_phone_no') }}*</label>
                <input type="text" id="req_phone_no" name="req_phone_no" class="form-control" value="{{ old('phone_no', isset($user) ? $user->phone_no : '') }}" placeholder="Phone Number">
                @if($errors->has('req_phone_no'))
                <em class="invalid-feedback">
                    {{ $errors->first('req_phone_no') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.req_phone_no_helper') }}
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('req_address') ? 'has-error' : '' }}">
                <label for="req_address">{{ trans('cruds.user.fields.req_address') }}*</label>
                <input type="text" id="req_address" name="req_address" class="form-control" value="{{ old('address', isset($user) ? $user->address : '') }}" placeholder="Address">
                @if($errors->has('req_address'))
                <em class="invalid-feedback">
                    {{ $errors->first('req_address') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.req_address_helper') }}
                </p>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>Country*</label>
            <select class="form-control select2bs4" id="country_id" name="country_id" style="width: 100%;" onchange="getState(this);">
                <option value="">Select Country</option>

                @foreach ($country as $countryValue)
                <option value="{{ $countryValue->id }}">{{ $countryValue->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>State</label>
            <select class="form-control select2bs4" id="state_id" name="state_id" style="width: 100%;" onchange="getCity(this);">
                <option value="">Select State</option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label>City</label>
            <select class="form-control select2bs4" id="city_id" name="city_id" style="width: 100%;">
                <option value="">Select City</option>
            </select>
        </div>
    </div>
</div>


@endif
