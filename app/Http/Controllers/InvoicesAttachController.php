<?php

namespace App\Http\Controllers;


use App\Models\invoices_attach;
use Illuminate\Http\Request;
use Auth;
use Storage;
class InvoicesAttachController extends Controller
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
       
        $file=$request->file_name;
       $file_name=$file->getClientOriginalName();
       $file->move(public_path('Attachments/'.$request->invoice_number),$file_name);

       $file_name=$file->getClientOriginalName();
       invoices_attach::create([
           'file_name'=>$file_name,
           'invoice_number'=>$request->invoice_number,
           'Created_by'=>Auth::user()->name,
           'invoice_id'=>$request->invoice_id

       ]);
        return redirect()->back()->with(['add'=>'تم اضافة المرفق بنجاح']);

    }

    /**
     * Display the specified resource.
     */
    public function show(invoices_attach $invoices_attach)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices_attach $invoices_attach)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_attach $invoices_attach)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {   $file=invoices_attach::find($request->id_file);
        $file->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
        return redirect()->back()->with(['delete'=>'تم الحذف بنجاح']);
    }

   
}
