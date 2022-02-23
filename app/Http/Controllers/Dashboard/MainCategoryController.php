<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoriesData = Category::with('MainCat')->orderBy('id','desc')->paginate(PAGINATION_COUNT);
        return view('dashboard.categories.index',compact('categoriesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoriesData = Category::select('id','parent_id')->get();
        return view('dashboard.categories.create',compact('categoriesData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MainCategoryRequest $request)
    {

        $localLan = app()->getLocale();

        try {
            DB::beginTransaction();
            if($request->filled('is_active'))
                $request->request->add(['is_active' => 1]);
            else
                $request->request->add(['is_active' => 0]);

            if($request->type == 1){
                $request->request->add(['parent_id' => null]);
            }

            $catData = Category::create($request->except('_token'));

            $catData->translateOrNew($localLan)->name = $request->name;

            $catData->save();
            DB::commit();
            return redirect()->route('adminMainCategory')->with(['success' => 'تم الإضافة بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('adminMainCategory')->with(['error' => 'عفواً هناك خطاً']);
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
        if(!$catData)
            return redirect()->route('adminMainCategory')->with(['error'=>'هذا القسم غير موجود']);

        return view('dashboard.categories.edit',compact('catData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MainCategoryRequest $request, $id)
    {
        $localLan = app()->getLocale();
        try {
            if($request->filled('is_active'))
                $request->request->add(['is_active' => 1]);
            else
                $request->request->add(['is_active' => 0]);

            $catData = Category::find($id);
            if(!$catData)
                return redirect()->route('adminMainCategory')->with(['error' => 'هذا القسم غير موجود']);

            $catData->update($request->all());

            $catData->translateOrNew($localLan)->name = $request->name;

            $catData->save();

            return redirect()->route('adminMainCategory')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('adminMainCategory')->with(['error' => 'هذا القسم غير موجود']);
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
                return redirect()->route('adminMainCategory')->with(['error' => 'هذا القسم غير موجود']);

            $catData->delete();

            return redirect()->route('adminMainCategory')->with(['success' => 'تم الحذف بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('adminMainCategory')->with(['error' => 'هذا القسم غير موجود']);
        }
    }
}
