@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.user.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                @if(isset($user->roles[0]->title) && $user->roles[0]->title == 'Admin')
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
                                Roles
                            </th>
                            <td>
                                @foreach($user->roles as $id => $roles)
                                    <span class="label label-info label-many">{{ $roles->title }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.first_name') }}
                            </th>
                            <td>
                                {{ $user->first_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.last_name') }}
                            </th>
                            <td>
                                {{ $user->last_name }}
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
                                {{ trans('cruds.user.fields.company_name') }}
                            </th>
                            <td>
                                {{ $userDetail->AdminDetail['company_name'] }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.company_type') }}
                            </th>
                            <td>
                                {{ $userDetail->AdminDetail['company_type'] }}
                            </td>
                        </tr>
                    
                    </tbody>
                @elseif(isset($user->roles[0]->title) && $user->roles[0]->title == 'Employer')
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
                                    Roles
                                </th>
                                <td>
                                    @foreach($user->roles as $id => $roles)
                                        <span class="label label-info label-many">{{ $roles->title }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.first_name') }}
                                </th>
                                <td>
                                    {{ $user->first_name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.last_name') }}
                                </th>
                                <td>
                                    {{ $user->last_name }}
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
                                    {{ trans('cruds.user.fields.company_name') }}
                                </th>
                                <td>
                                    {{ $userDetail->EmployerDetail['company_name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.phone_no') }}
                                </th>
                                <td>
                                    {{ $userDetail->EmployerDetail['phone_no'] }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.address') }}
                                </th>
                                <td>
                                    {{ $userDetail->EmployerDetail['address'] }}
                                </td>
                            </tr>
                        
                    </tbody>
                @elseif(isset($user->roles[0]->title) && $user->roles[0]->title == 'Recruiter')
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
                                    Roles
                                </th>
                                <td>
                                    @foreach($user->roles as $id => $roles)
                                        <span class="label label-info label-many">{{ $roles->title }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.first_name') }}
                                </th>
                                <td>
                                    {{ $user->first_name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.last_name') }}
                                </th>
                                <td>
                                    {{ $user->last_name }}
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
                                    {{ trans('cruds.user.fields.company_name') }}
                                </th>
                                <td>
                                    {{ $userDetail->RecruiterDetail['company_name'] }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.phone_no') }}
                                </th>
                                <td>
                                    {{ $userDetail->RecruiterDetail['phone_no'] }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.address') }}
                                </th>
                                <td>
                                    {{ $userDetail->RecruiterDetail['address'] }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.country') }}
                                </th>
                                <td>
                                    {{ getCountryById($user->id) }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.state') }}
                                </th>
                                <td>
                                    {{ getStateById($user->id) }}
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.city') }}
                                </th>
                                <td>
                                    {{ getCityById($user->id) }}
                                </td>
                            </tr>


                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.image') }}
                                </th>
                                <td>
                                     <img src="\user_image\{{$userDetail->RecruiterDetail['image']}}" height="50" width="50">
                                </td>
                            </tr>
                        
                    </tbody>
                @endif
               
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection