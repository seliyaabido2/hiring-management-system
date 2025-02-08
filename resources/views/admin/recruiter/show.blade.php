@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Recruiter Details
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                Client Name
                            </th>
                            <td>
                                {{ $recruiterDetail->first_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Authorized Name
                            </th>
                            <td>
                                {{  $recruiterDetail->RecruiterDetail->authorized_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Phone Number
                            </th>
                            <td>
                                {{ $recruiterDetail->RecruiterDetail->phone_no }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Email
                            </th>
                            <td>
                                {{ $recruiterDetail->email }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Company Name
                            </th>
                            <td>
                                {{ $recruiterDetail->RecruiterDetail->company_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Location
                            </th>
                            <td>

                                {{ !empty($recruiterDetail->RecruiterDetail->location) ? $recruiterDetail->RecruiterDetail->location : '' }}


                            </td>
                        </tr>

                        <tr>
                            <th>
                               Total Candidates
                            </th>
                            <td>
                                {{ $recruiterDetail->recruiter_candidates_count }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                            Closed Positions
                            </th>
                            <td>
                                {{ $recruiterDetail->candidate_job_status_comments_count }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Status
                            </th>
                            <td>
                                {{ $recruiterDetail->status }}
                            </td>
                        </tr>

                    </tbody>


            </table>
        </div>
    </div>

    <div class="card-header">
        Contract Details
    </div>

    <div class="card-body">
        <div class="mb-2">

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            Contract Type
                        </th>
                        <td>
                            {{ $recruiterDetail->AssignedOneContractDetail->ContractDetail->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Start Date
                        </th>
                        <td>
                            {{  $recruiterDetail->AssignedOneContractDetail->start_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            End Date
                        </th>
                        <td>
                            {{ $recruiterDetail->AssignedOneContractDetail->end_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Status
                        </th>
                        <td>
                            {{ $recruiterDetail->AssignedOneContractDetail->status }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            contract upload
                        </th>
                        <td>

                            <a href="{{ asset('contract_upload').'/'.$recruiterDetail->AssignedOneContractDetail->contract_upload}}" download>
                                Download
                            </a>

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
