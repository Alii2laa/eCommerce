<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProfileRequest;
use App\Models\Admin;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function editAdminProfile(){
        $adminId = auth('admin')->user()->id;
        $adminData = Admin::find($adminId);
        return view('dashboard.profile.edit',compact('adminData'));
    }
    public function updateAdminProfile(AdminProfileRequest $request){
        try {
                $adminId = auth('admin')->user()->id;
                $adminDataUpdate = Admin::find($adminId);

                unset($request['id'],$request['password_confirmation']);

                if($request->filled('password')){

                    $request->merge(['password'=>bcrypt($request->password)]);

                    $adminDataUpdate -> update($request->all());
                } else{
                    $adminDataUpdate -> update($request->only(['name','email']));
                }


                return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);
            } catch (\Exception $ex) {
                return redirect()->back()->with(['error' => 'هناك خطا ما يرجي المحاولة فيما بعد']);
        }
    }
}
