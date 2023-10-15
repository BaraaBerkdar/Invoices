<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoices;
class ArchifController extends Controller
{
public function index(){

    $invoices=invoices::onlyTrashed()->get();
    return view('invoices.archif_invoices',compact('invoices'));

} 

public function update(Request $req){
     $invoices=invoices::withTrashed()->find($req->invoice_id)->restore();
     return redirect()->back()->with(['cancle'=>"تم الغاء الارشفة بنجاح"]);
   
        
}

public function delete(Request $req){
    
    $invoices=invoices::withTrashed()->find($req->invoice_id);
    $invoices->forcedelete();

     return redirect()->back()->with(['delete'=>"تم الحذف  بنجاح"]);


}
}
