<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use App\Models\invoices;
use App\Models\invoice_detils;
use App\Models\invoices_attach;
use App\Models\section;

use App\Models\prodect;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Storage;
class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function __construct()
    {
        $this->middleware('permission:قائمة الفواتير', ['only' => ['index','invoice_paid','Status_show','invoice_no_paid','invoice_partail']]);
        $this->middleware('permission:اضافة فاتورة', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل الفاتورة', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
        $this->middleware('permission:تغير حالة الدفع', ['only' => ['status_update']]);
        $this->middleware('permission:طباعةالفاتورة', ['only' => ['print_invoice']]);
        $this->middleware('permission:ارشفة الفاتورة', ['only' => ['destroy']]);
        
        

        
    }

     
    public function index()
    {   $invoices=invoices::all();
        return view('invoices.invoices',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {  
         $sections=section::all();
        return view('invoices.add_invoice',compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $invoice= invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 0,
            'note' => $request->note,
        ]);
        invoice_detils::create([
            'id_Invoice' => $invoice->id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 0,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if($request->hasFile('pic')){
            $file=$request->pic;
            $file_name=$file->getClientOriginalName();
        

        invoices_attach::create([
            'file_name'=>$file_name,
            'invoice_number'=>$request->invoice_number,
            'Created_by'=>(Auth::user()->name),
            'invoice_id'=>$invoice->id

        ]);

        #move file 
        $file->move(public_path('Attachments/'.$request->invoice_number),$file_name);

        }


        ###### notification #########
        $user = User::get();
        $user_email=User::get();
        $invoices = $invoice->id;
        Notification::send($user, new \App\Notifications\AddInvoices($invoices));
        // Notification::send($user_email, new \App\Notifications\AddInvoices_email($invoices));




        return redirect()->back()->with(['add'=>"تم الاضافة بنجاح "]);
    }

    /**
     * Display the specified resource.
     */
    public function show(invoices $invoices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice=invoices::find($id);
        $sections=section::all();
        
        return view('invoices.edit_invoices',compact('invoice','sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $invoice=invoices::find($request->invoice_id);
        $invoice->update($request->all());
        return redirect()->back()->with(['edit'=>"تم التعديل بنجاح "]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request) 
    { 
        $invoice=invoices::find($request->invoice_id);
        
        $attch=invoices_attach::where('invoice_id',$request->invoice_id)->first();
        if($request->id_page==2)
        {
        
        $invoice->delete();
        return redirect()->back()->with(['archif'=>"تم اللارشفة بنجاح "]);

        }
        else{
        if(isset($attch))
       { Storage::disk('public_uploads')->deleteDirectory($attch->invoice_number);}
        
       $invoice->forceDelete();
        return redirect()->back()->with(['delete'=>"تم الحذف بنجاح "]);
        }
    }

    public function getprodects($id){

        $prodect=prodect::where('section_id',$id)->pluck("Product_name", "id");
        return json_encode($prodect);

    }

    public function Status_show($id){
        
        $invoice=invoices::find($id);
        return view('invoices.status_update',compact('invoice'));

    }

    public function status_update(Request $request){
       
        $invoice=invoices::find($request->invoice_id);
       if(!$request->Status){return redirect()->back();}
        if($request->Status == "مدفوعة"){
            
            $invoice->update([
                'Status'=>$request->Status,
                'Value_Status'=>1,
                'Payment_Date'=>$request->Payment_Date
            ]);
            $invoice->save();

            invoice_detils::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => ' مدفوعة',
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date'=>$request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);

        }
        else{

        $invoice->update([
                'Status'=>$request->Status,
                'Value_Status'=>'2',
                'Payment_Date'=>$request->Payment_Date
            ]);

            invoice_detils::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => ' مدفوعة جزئيا',
                'Value_Status' => '2',
                'note' => $request->note,
                'Payment_Date'=>$request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);

        }
        return redirect()->to('invoices/invoices');
    }
    public function invoice_paid(){
        
        $invoices=invoices::where('Value_Status',1)->get();
        return view('invoices.invoice_pid',compact('invoices'));

    }
    public function invoice_no_paid(){
        $invoices=invoices::where('Value_Status',0)->get();
        return view('invoices.invoice_nopiad',compact('invoices'));

    }
    public function invoice_partail(){
        $invoices=invoices::where('Value_Status',2)->get();
        return view('invoices.invoice_partail',compact('invoices'));

    }

    public function print_invoice($id){
        $invoices=invoices::find($id);

      return view('invoices.print_invoices',compact('invoices'));
    }

   
    public function MarkAsRead_all (Request $request)
    {
       
        $userUnreadNotification= auth()->user()->unreadNotifications;

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }


    }

}
