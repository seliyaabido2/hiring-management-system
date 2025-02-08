@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Admin Details
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                Sub Admin Name
                            </th>
                            <td>
                                {{ $adminDetail->first_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Email
                            </th>
                            <td>
                                {{  $adminDetail->email }}
                            </td>
                        </tr>



                        <tr>
                            <th>
                                Location
                            </th>
                            <td>

                                {{ !empty($adminDetail->AdminDetail->location) ? $adminDetail->AdminDetail->location : '' }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                               Phone Number
                            </th>
                            <td>
                                {{ $adminDetail->AdminDetail->phone_no }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Designation
                            </th>
                            <td>
                                {{ $adminDetail->AdminDetail->designation }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Status
                            </th>
                            <td>
                                {{ $adminDetail->status }}
                            </td>
                        </tr>

                    </tbody>


            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>
@endsection
