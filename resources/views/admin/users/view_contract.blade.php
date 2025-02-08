@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.view') }} {{ trans('cruds.contracts.title') }}
    </div>
{{-- {{dd($contract)}} --}}
    <div class="card-body">
        <div class="mb-2">

                @if(isset($contract->AssignedContractDetail))
                    @foreach ($contract->AssignedContractDetail as $key => $AssignedContractDetail)
                    
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

                @else
                    <table class="table table-bordered table-striped">
    
                        <tbody>
                            <tr>
                                <th>
                                    {{ trans('cruds.contracts.fields.name') }}
                                </th>
                                <td>
                                    {{ $contract->AssignedOneContractDetail->ContractDetail->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.contracts.fields.description') }}
                                </th>
                                <td>
                                    {{ $contract->AssignedOneContractDetail->ContractDetail->description ?: '-' }}
        
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.contracts.fields.expire_alert') }}
                                </th>
                                <td>
                                    {{ $contract->AssignedOneContractDetail->ContractDetail->expire_alert }} Days
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.contracts.fields.start_date') }}
                                </th>
                                <td>
                                    {{ $contract->AssignedOneContractDetail->start_date ? date('d-m-Y', strtotime($contract->AssignedOneContractDetail->start_date)) : '' }}
        
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.contracts.fields.end_date') }}
                                </th>
                                <td>
                                    {{ $contract->AssignedOneContractDetail->end_date ? date('d-m-Y', strtotime($contract->AssignedOneContractDetail->end_date)) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.contracts.fields.contractLink') }}
                                </th>
                                <td>
                                    @if ($contract->AssignedOneContractDetail->contract_upload != null)
                                    <a href="{{ asset('contract_upload').'/'.$contract->AssignedOneContractDetail->contract_upload}}" download>
                                        Download
                                    </a>
                                    @endif
                                
                                </td>
                            </tr>
                            @if ($contract->AssignedOneContractDetail->checklist_upload != null)
                            <tr>
                                <th>
                                    {{ trans('cruds.contracts.fields.checklistLink') }}
                                </th>
                                <td>
                                    
                                    <a href="{{ asset('checklist_upload').'/'.$contract->AssignedOneContractDetail->checklist_upload}}" download>
                                        Download
                                    </a>
                    
                                </td>
                            </tr>
                            @endif
        
                            <tr>
                                <th>
                                    {{ trans('cruds.contracts.fields.recurring_contracts') }}
                                </th>
                                <td>
                                    
                                    {{ $contract->AssignedOneContractDetail->recurring_contracts == 1 ? 'Yes' : 'No' }}
                    
                                </td>
                            </tr>
        
        
                            <!-- Add more fields as needed -->
                        </tbody>
                    
                    
                    </table>
                @endif
           
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection