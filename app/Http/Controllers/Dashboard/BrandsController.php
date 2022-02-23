<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandsRequest;
use App\Http\Requests\MainCategoryRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::orderBy('id','desc')->paginate(PAGINATION_COUNT);
        return view('dashboard.brands.index',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandsRequest $request)
    {

        $localLan = app()->getLocale();
        try {
            DB::beginTransaction();
            if($request->filled('is_active'))
                $request->request->add(['is_active' => 1]);
            else
                $request->request->add(['is_active' => 0]);

            $fileName = "";
            if($request->has('photo')){
                $fileName = uploadImage('brands',$request->photo);
            }
            $brands = Brand::create($request->except('_token','photo','id'));

            $brands->translateOrNew($localLan)->name = $request->name;
            $brands->photo = $fileName;
            $brands->save();
            DB::commit();
            return redirect()->route('adminBrands')->with(['success' => 'تم الإضافة بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('adminBrands')->with(['error' => 'عفواً هناك خطاً']);
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
        $brandsData = Brand::orderBy('id','desc')->find($id);
        if(!$brandsData)
            return redirect()->route('adminBrands')->with(['error'=>'هذه الماركة غير موجود']);

        return view('dashboard.brands.edit',compact('brandsData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandsRequest $request, $id)
    {
        $localLan = app()->getLocale();
        try {

            $brandsData = Brand::find($id);
            if(!$brandsData)
                return redirect()->route('adminBrands')->with(['error'=>'هذه الماركة غير موجود']);
            DB::beginTransaction();
            if($request->has('photo')){
                $file_path = public_path().'/assets/images/brands/'.$brandsData->photo;
                unlink($file_path);
                $fileName = uploadImage('brands',$request->photo);
                Brand::where('id',$id)
                    ->update([
                        'photo' => $fileName
                    ]);

            }
            if($request->filled('is_active'))
                $request->request->add(['is_active' => 1]);
            else
                $request->request->add(['is_active' => 0]);
            $brandsData->update($request->except('_token','photo','id'));

            $brandsData->translateOrNew($localLan)->name = $request->name;
            $brandsData->save();
            DB::commit();
            return redirect()->route('adminBrands')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->route('adminBrands')->with(['error' => 'هذه الماركه غير موجود']);
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
            $brandsData = Brand::find($id);
            if(!$brandsData)
                return redirect()->route('adminBrands')->with(['error' => 'هذه الماركة غير موجود']);

            $file_path = public_path().'/assets/images/brands/'.$brandsData->photo;
            unlink($file_path);
            $brandsData->delete();


            return redirect()->route('adminBrands')->with(['success' => 'تم الحذف بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('adminBrands')->with(['error' => 'هذه الماركة غير موجود']);
        }
    }
}
