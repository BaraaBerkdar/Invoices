<?php

namespace App\Http\Controllers;
use App\Models\invoices;
use Illuminate\Http\Request;

class ReportInvoiceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:تقرير الفواتير', ['only' => ['index','serch_invoice']]);
        
    }





    public function index(){

            return view('report.invoice_report');
    }

    
    public function serch_invoice(Request $req){
        $rdio=$req->rdio;
        $type=$req->type;
    
        $start_at=$req->start_at;
        $end_at=$req->end_at;
        if($rdio==1){
            if($type && $start_at=="" && $end_at==""){
               $invoices=invoices::where('Status',$type)->get();
               return view('report.invoice_report',compact('type'))->withDetails($invoices);
            }
            else{
                $invoices=invoices::whereBetween('invoice_Date',[$start_at,$end_at])
                ->where('Status',$type)->get();
               return view('report.invoice_report',compact('type','start_at','end_at'))->withDetails($invoices);

            }
        }
        else{
            $invoice_number=$req->invoice_number;
            $invoices=invoices::where('invoice_number',$invoice_number)->get();
            return view('report.invoice_report')->withDetails($invoices);
            
        }
    }
}
