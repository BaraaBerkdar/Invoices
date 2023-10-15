<?php

namespace App\Http\Controllers;

use App\Models\prodect;
use App\Models\section;
use Illuminate\Http\Request;

class ProdectController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     function __construct()
     {
         $this->middleware('permission:المنتجات', ['only' => ['index']]);
         $this->middleware('permission:اضافة منتج', ['only' => ['create','store']]);
         $this->middleware('permission:تعديل منتج', ['only' => ['edit','update']]);
         $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);
         
     }


    public function index()
    {   $sections=section::all();
        $prodects=prodect::all();
        return view('prodects.prodects',compact('sections','prodects'));
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
        prodect::create([
            'Product_name'=>$request->section_name,
            'section_id'=>$request->section_id,
            'description'=>$request->description

        ]);
        return redirect()->back()->with(['add'=>"تم الاضافة بنجاح"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(prodect $prodect)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(prodect $prodect)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    { 
        $id=section::where('section_name',$request->section_name)->first()->id;
     
        
        $prodect=prodect::find($request->pro_id);
        $prodect->update([
            'Product_name'=>$request->Product_name,
            'section_id'=>$id,
            'description'=>$request->description

        ]);

        return redirect()->back()->with(['edit'=>"تم التعديل بنجاح "]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {   
        $prodect=prodect::find($request->pro_id);
        $prodect->delete();
        return redirect()->back()->with(['delete'=>"تم الحذف  بنجاح "]);
        

    }
}
