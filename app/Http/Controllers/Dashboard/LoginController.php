<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginView(){
        return view('dashboard.auth.login');
    }


    public function loginAdmin(AdminLoginValid $request){

        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->with(['error'=>'هناك خطأ في بيانات الدخول']);
    }

    public function adminLogout(){
        $gaurd = $this->getGaurd();
        $gaurd->logout();
        return redirect()->route('admin.login');
    }

    private function getGaurd()
    {
        return auth('admin');
    }

}
