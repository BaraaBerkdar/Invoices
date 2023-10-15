<?php

namespace App\Http\Controllers;

use App\Models\invoice_detils;
use App\Models\invoices;
use App\Models\invoices_attach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
class InvoiceDetilsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(invoice_detils $invoice_detils)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id,$notf_id)
    {
        $invoices=invoices::find($id);
        $details=invoice_detils::where('id_Invoice',$id)->get();
        $attachments=invoices_attach::where('invoice_id',$id)->get();
        $userUnreadNotification= auth()->user()->unreadNotifications->find($notf_id);

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
        }
       return view('invoices.detiles_invoices',compact('invoices','details','attachments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoice_detils $invoice_detils)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoice_detils $invoice_detils)
    {
        //
    }


    public function open_file($invoice_number,$file_name)

        {        

    return response()->file('Attachments/'.$invoice_number.'/'.$file_name);
    
          }
public function download_file($invoice_number,$file_name){
    
    return response()->download('Attachments/'.$invoice_number.'/'.$file_name);
     

    }


    public function edit1($id)
    {
        $invoices=invoices::find($id);
        $details=invoice_detils::where('id_Invoice',$id)->get();
        $attachments=invoices_attach::where('invoice_id',$id)->get();
        
       return view('invoices.detiles_invoices',compact('invoices','details','attachments'));
    }


}
