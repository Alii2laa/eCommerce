<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoriesData = Category::child()->orderBy('id','desc')->paginate(PAGINATION_COUNT);
        return view('dashboard.subcategories.index',compact('categoriesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoriesData = Category::parent()->orderBy('id','desc')->get();
        return view('dashboard.subcategories.create',compact('categoriesData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubCategoryRequest $request)
    {
        $localLan = app()->getLocale();

        try {
            DB::beginTransaction();
            if($request->filled('is_active'))
                $request->request->add(['is_active' => 1]);
            else
                $request->request->add(['is_active' => 0]);


            $catData = Category::create($request->except('_token'));

            $catData->translateOrNew($localLan)->name = $request->name;

            $catData->save();
            DB::commit();
            return redirect()->route('adminSubCategory')->with(['success' => 'تم الإضافة بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('adminSubCategory')->with(['error' => 'عفواً هناك خطاً']);
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
        $catData = Category::orderBy('id','desc')->find($id);
        $categoriesData = Category::parent()->orderBy('id','desc')->get();
        if(!$catData)
            return redirect()->route('adminSubCategory')->with(['error'=>'هذا القسم غير موجود']);

        return view('dashboard.subcategories.edit',compact('catData','categoriesData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubCategoryRequest $request, $id)
    {
        $localLan = app()->getLocale();
        try {
            if($request->filled('is_active'))
                $request->request->add(['is_active' => 1]);
            else
                $request->request->add(['is_active' => 0]);

            $catData = Category::find($id);
            if(!$catData)
                return redirect()->route('adminSubCategory')->with(['error' => 'هذا القسم غير موجود']);

            $catData->update($request->all());

            $catData->translateOrNew($localLan)->name = $request->name;

            $catData->save();

            return redirect()->route('adminSubCategory')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('adminSubCategory')->with(['error' => 'هذا القسم غير موجود']);
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
            $catData = Category::find($id);
            if(!$catData)
                return redirect()->route('adminSubCategory')->with(['error' => 'هذا القسم غير موجود']);

            $catData->delete();

            return redirect()->route('adminSubCategory')->with(['success' => 'تم الحذف بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('adminSubCategory')->with(['error' => 'هذا القسم غير موجود']);
        }
    }
}
