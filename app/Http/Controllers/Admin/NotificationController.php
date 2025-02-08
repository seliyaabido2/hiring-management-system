<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contract;
use App\Notification;
use Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;


class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('notifications_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $readAllNotification = Notification::where(['receiver_id'=>auth()->user()->id,'status'=>'0'])->update(['status' => 1]);

        return view('admin.notifications.index');

    }

    public function getNotificationDatatable(Request $request){

       
       $query = Notification::select([
        'id',
       
        'title',
            'message',
        'created_at', // Keep the original created_at column
        // Format created_at as 'd-m-Y H:i:s' and give it an alias 'formatted_created_at'
        DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y %H:%i:%s") as formatted_created_at')
        ])
        ->where('receiver_id', auth()->user()->id)
        ->orderBy('id', 'desc');
      
    
        $searchableColumns = [
            'id',
            'title',
            'message',
            'created_at', // Update this line
        ];
        

        // Apply search conditions to the query
        foreach ($searchableColumns as $column) {
            if ($request->filled($column)) {
                // Use the original column name for the search condition
                $query->where($column, 'like', '%' . $request->input($column) . '%');
            }
        }
        

        // Return the DataTables response
        return DataTables::of($query)

            ->rawColumns([])
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('notifications_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  dd($request->all());
        abort_if(Gate::denies('notifications_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $this->validate($request,[
            'name'=>'required|unique:notifications',
            'related_to'=>'required',
            'expire_alert'=>'required'
        ]);



        $data = array(
                    'name'=> $request->input('name'),
                    'description'=> $request->input('description'),
                    'related_to'=> $request->input('related_to'),
                    'expire_alert'=> $request->input('expire_alert'),

                    );

        $notifications = Contract::create($data);

        return redirect()->route('admin.notifications.index')->with('success', 'Contract created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort_if(Gate::denies('notifications_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notification = Notification::where('id',$id)->first();


        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(Gate::denies('notifications_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $contract = Contract::where('id', $id)->first();

        return view('admin.notifications.edit', compact('contract'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        abort_if(Gate::denies('notifications_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('contract_id');
        $id = decrypt_data($id);
        $ContractsUpdate = Contract::where('id',$id)->first();
        $ContractsUpdate->name = $request->input('name');
        $ContractsUpdate->description = $request->input('description');
        $ContractsUpdate->related_to = $request->input('related_to');
        $ContractsUpdate->expire_alert = $request->input('expire_alert');

        $ContractsUpdate->save();

        $msg = "Contract Update successfully.";

        return redirect()->route('admin.notifications.index')->with('success', $msg);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        abort_if(Gate::denies('notifications_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('id');
        Contract::find($id)->delete();

        return back()->with('success', 'Contract deleted successfully.');
    }

    public function markAsRead($id)
    {
        $id =decrypt_data($id);
        if($id == 'All'){
            $notification = Notification::where(['receiver_id'=>auth()->user()->id,'status'=>'0'])->update(['status' => 1]);
        }else{
            $notification = Notification::find($id);
            $notification->update(['status' => 1]);
        }

        return redirect()->route('admin.notifications.index');
    }

    public function massDestroy(Request $request)
    {
        
        $ids = explode(',',request('ids'));
        Notification::whereIn('id', $ids)->delete();

        return redirect()->route('admin.notifications.index')->with('success', 'Notifications deleted successfully.');
    }
}
