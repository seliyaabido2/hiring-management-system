<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Contract;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('contracts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contracts = Contract::all();

        return view('admin.contracts.index', compact('contracts'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('contracts_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.contracts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  dd($request->all());
        abort_if(Gate::denies('contracts_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $this->validate($request,[
            'name'=>'required',
            'related_to'=>'required',
            'expire_alert'=>'required'
        ]);



        $data = array(
                    'name'=> $request->input('name'),
                    'description'=> $request->input('description'),
                    'related_to'=> $request->input('related_to'),
                    'expire_alert'=> $request->input('expire_alert'),

                    );

        $contracts = Contract::create($data);

        return redirect()->route('admin.contracts.index')->with('success', 'Contract created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(Gate::denies('contracts_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contract = Contract::where('id', $id)->first();

        return view('admin.contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(Gate::denies('contracts_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contract = Contract::where('id', $id)->first();

        return view('admin.contracts.edit', compact('contract'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        abort_if(Gate::denies('contracts_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('contract_id');
        $id = decrypt_data($id);
        $ContractsUpdate = Contract::where('id',$id)->first();
        $ContractsUpdate->name = $request->input('name');
        $ContractsUpdate->description = $request->input('description');
        $ContractsUpdate->related_to = $request->input('related_to');
        $ContractsUpdate->expire_alert = $request->input('expire_alert');

        $ContractsUpdate->save();

        $msg = "Contract Update successfully.";

        return redirect()->route('admin.contracts.index')->with('success', $msg);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        abort_if(Gate::denies('contracts_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('id');
        Contract::find($id)->delete();

        return back()->with('success', 'Contract deleted successfully.');
    }
}
