<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandsRequest;
use App\Http\Requests\MainCategoryRequest;
use App\Http\Requests\TagsRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderBy('id','desc')->paginate(PAGINATION_COUNT);
        return view('dashboard.tags.index',compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagsRequest $request)
    {

        $localLan = app()->getLocale();
        try {
            DB::beginTransaction();

            $tags = Tag::create(['slug'=>$request->slug]);

            $tags->translateOrNew($localLan)->name = $request->name;
            $tags->save();
            DB::commit();
            return redirect()->route('adminTags')->with(['success' => 'تم الإضافة بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('adminTags')->with(['error' => 'عفواً هناك خطاً']);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tagsData = Tag::orderBy('id','desc')->find($id);
        if(!$tagsData)
            return redirect()->route('adminTags')->with(['error'=>'هذه العلامة غير موجود']);

        return view('dashboard.tags.edit',compact('tagsData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagsRequest $request, $id)
    {
        $localLan = app()->getLocale();
        try {

            $tagsData = Tag::find($id);
            if(!$tagsData)
                return redirect()->route('adminTags')->with(['error'=>'هذه العلامة غير موجود']);
            DB::beginTransaction();

            $tagsData->update($request->except('_token','id'));

            $tagsData->translateOrNew($localLan)->name = $request->name;
            $tagsData->save();
            DB::commit();
            return redirect()->route('adminTags')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('adminTags')->with(['error' => 'هذه العلامة غير موجود']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $tagsData = Tag::find($id);
            if(!$tagsData)
                return redirect()->route('adminTags')->with(['error' => 'هذه العلامة غير موجود']);

            $tagsData->delete();
            $tagsData->deleteTranslations();


            return redirect()->route('adminTags')->with(['success' => 'تم الحذف بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('adminTags')->with(['error' => 'هذه العلامة غير موجود']);
        }
    }
}
