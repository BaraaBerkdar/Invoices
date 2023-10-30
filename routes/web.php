<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ProdectController;
use App\Http\Controllers\InvoicesAttachController;
use App\Http\Controllers\InvoiceDetilsController;
use App\Http\Controllers\ArchifController;
use App\Http\Controllers\ReportInvoiceController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ReportCustmerController;



Route::get('/', function () {
    return view('auth.login');
});





Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['auth','CheckEnable']);


########################### Invoices ######################################


Route::group(["prefix"=>"invoices","middleware"=>'auth'],function(){
 
 Route::resource('invoices',                  InvoicesController::class);
 Route::get('invoice_detiles/{id}/{notf_id}', [InvoiceDetilsController::class,'edit']);     
 Route::get('invoice_paid',                   [InvoicesController::class,'invoice_paid'])->name('invoice_paid');
 Route::get('invoice_no_paid',                [InvoicesController::class,'invoice_no_paid'])->name('invoice_no_paid');
 Route::get('invoice_partail',                [InvoicesController::class,'invoice_partail'])->name('invoice_partail');
 Route::get('Print_invoice/{id}',             [InvoicesController::class,'print_invoice']);
 Route::get('invoice_detiles/{id}',           [InvoiceDetilsController::class,'edit1']);
 Route::get('edit_invoice/{id}',              [InvoicesController::class,'edit']);
 Route::post('invoices/update',               [InvoicesController::class,'update']);
 Route::get('Status_show/{id}',               [InvoicesController::class,'Status_show'])->name('Status_show');
 Route::post('status_update',                 [InvoicesController::class,'Status_update'])->name('status_update');
});

Route::get('MarkAsRead_all',                 [InvoicesController::class,'MarkAsRead_all']);

Route::get('section/{id}',                   [InvoicesController::class,'getprodects']);


Route::resource('section',SectionController::class);

Route::resource('prodect',ProdectController::class);
########################### Attachments ######################################
Route::resource('attach',InvoicesAttachController::class);
Route::post('delete_file',[InvoicesAttachController::class,'destroy']);
Route::get('View_file/{invoice_number}/{file_name}',[InvoiceDetilsController::class,'open_file']);
Route::get('download/{invoice_number}/{file_name}',[InvoiceDetilsController::class,'download_file']);



########################### Invoices Archef ######################################



Route::get('archef_invoices',[ArchifController::class,'index'])->name('archef_invoices');

Route::post('cancle_archif_invoice',[ArchifController::class,'update'])->name('cancle_archif_invoice');

Route::post('delete_archif',[ArchifController::class,'delete'])->name('delete_archif');



########################### Custmer Report ######################################
Route::get('coustmer_report',[ReportCustmerController::class,'index'])->name('coustmer_report');

Route::post('Search_customers',[ReportCustmerController::class,'serch_invoice']);



########################### Invoices Report ######################################

Route::get('reportinvoices',[ReportInvoiceController::class,'index'])->name('reportinvoices');

Route::post('Search_invoices',[ReportInvoiceController::class,'serch_invoice']);


#invoice export execl ########
// Route::get('export_invoices',[InvoicesController::class,'export']);


########################### Permisssion ######################################

Route::resource('users', UsersController::class);

Route::resource('roles', RolesController::class);


########################### Other ######################################

Route::get('/{page}', [AdminController::class,'index']);




