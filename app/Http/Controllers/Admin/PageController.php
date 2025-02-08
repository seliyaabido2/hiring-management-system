<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CmsPage;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('cms_pages_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cmsPage = CmsPage::all();

        return view('admin.cmsPages.index', compact('cmsPage'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('cms_pages_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cmsPage = CmsPage::all()->pluck('title','content', 'id');

        return view('admin.cmsPages.create', compact('cmsPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  dd($request->all());
        abort_if(Gate::denies('cms_pages_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->validate($request,[
            'title'=>'required|unique:cms_pages',
            'slug'=>'required',
            'content'=>'required'
        ]);

        $data = array(
                    'title'=> $request->input('title'),
                    'slug' => $request->input('slug'),
                    'content' => $request->input('content')
                    );

        $cmsPage= cmsPage::create($data);

        return redirect()->route('admin.cmsPages.index')->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(Gate::denies('cms_pages_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cmsPages = cmsPage::where('id', $id)->first();

        return view('admin.cmsPages.edit', compact('cmsPages'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        abort_if(Gate::denies('cms_pages_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('cmsPage_id');
        $id = decrypt_data($id);
        $cmsPageUpdate = cmsPage::where('id',$id)->first();
        $cmsPageUpdate->title = $request->input('title');
        $cmsPageUpdate->slug = $request->input('slug');
        $cmsPageUpdate->content = $request->input('content');

        $cmsPageUpdate->save();

        $msg = $request->input('title')." Update successfully.";

        return redirect()->route('admin.cmsPages.index')->with('success', $msg);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        abort_if(Gate::denies('cms_pages_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $id = $request->input('id');
        cmsPage::find($id)->delete();

        return back()->with('success', 'Page deleted successfully.');
    }
}
