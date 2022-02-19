<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingsRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function editShippingMethod($type){
        if($type == 'free')
            $shippingMethod = Setting::where('key','free_shipping_label')->first();

        elseif ($type == 'inner')
            $shippingMethod = Setting::where('key','local_label')->first();

        elseif($type == 'outer')
            $shippingMethod = Setting::where('key','outer_label')->first();

        else
            $shippingMethod = Setting::where('key','free_shipping_label')->first();


        return view('dashboard.settings.shipping.edit',compact('shippingMethod'));
    }

    public function updateShippingMethod(ShippingsRequest $request,$id){
        try {
            $shipping_method = Setting::find($id);

            DB::beginTransaction();
            $shipping_method->update(['plain_value' => $request->plain_value]);
            //save translations
            $shipping_method->value = $request->value;
            $shipping_method->save();

            DB::commit();
            return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'هناك خطا ما يرجي المحاولة فيما بعد']);
            DB::rollback();
        }
    }
}
