@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Employer Details
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
                                {{ $employerDetail->first_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Authorized Name
                            </th>
                            <td>
                                {{  $employerDetail->EmployerDetail->authorized_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Phone Number
                            </th>
                            <td>
                                {{ $employerDetail->EmployerDetail->phone_no }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Email
                            </th>
                            <td>
                                {{ $employerDetail->email }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Company Name
                            </th>
                            <td>
                                {{ $employerDetail->EmployerDetail->company_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Location
                            </th>
                            <td>

                                {{ !empty($employerDetail->EmployerDetail->location) ? $employerDetail->EmployerDetail->location : '' }}


                            </td>
                        </tr>

                        <tr>
                            <th>
                                Status
                            </th>
                            <td>
                                {{ $employerDetail->status }}
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

           

                @if(isset($employerDetail->AssignedContractDetail))
                @foreach ($employerDetail->AssignedContractDetail as $key => $AssignedContractDetail)
                
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    Contract Type
                                </th>
                                <td>
                                    {{ $AssignedContractDetail->ContractDetail->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Start Date
                                </th>
                                <td>
                                    {{  $AssignedContractDetail->start_date }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    End Date
                                </th>
                                <td>
                                    {{ $AssignedContractDetail->end_date ?: '-' }}

                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Status
                                </th>
                                <td>
                                    {{ $AssignedContractDetail->status }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    contract upload
                                </th>
                                <td>
                                    <a href="{{ asset('contract_upload').'/'.$AssignedContractDetail->contract_upload}}" download>
                                        Download
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Checklist upload
                                </th>
                                <td>
                                    @if ($AssignedContractDetail->checklist_upload != null)
                                    <a href="{{ asset('checklist_upload').'/'.$AssignedContractDetail->checklist_upload}}" download>
                                        Download
                                    </a>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>


                        </tbody>
                    </table>

                @endforeach
                @endif



            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
</div>
@endsection
