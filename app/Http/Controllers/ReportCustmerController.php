<?php

namespace App\Http\Controllers;
use App\Models\section;
use Illuminate\Http\Request;
use App\Models\invoices;

class ReportCustmerController extends Controller
{
    public function index(){

        $sections=section::all();
        return view('report.customers_report',compact('sections'));

    }


    public function serch_invoice(Request $req){

        if($req->Section && $req->product && $req->start_at=="" && $req->end_at=="")
            {
            $invoices=  invoices::where('section_id',$req->Section)->where('product',$req->product)->get();
            $sections=section::all();
            
            return view('report.customers_report',compact('sections'))->withDetails($invoices);
            }
            else{


            $invoices=  invoices::whereBetween('invoice_Date',[$req->start_at,$req->end_at])->where('section_id',$req->Section)->where('product',$req->product)->get();
            $sections=section::all();
            
            return view('report.customers_report',compact('sections'))->withDetails($invoices);

            }

}
}
