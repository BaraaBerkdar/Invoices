<?php

namespace App\Http\Controllers;
use App\Http\Requests\SectionRequest;
use App\Models\section;
use Illuminate\Http\Request;
use Auth;
class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     function __construct()
     {
         $this->middleware('permission:الاقسام', ['only' => ['index']]);
         $this->middleware('permission:اضافة قسم', ['only' => ['create','store']]);
         $this->middleware('permission:تعديل قسم', ['only' => ['edit','update']]);
         $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);
         
     }










    public function index()
    {   $sections=section::paginate(5);
        return view('sections.sections',compact('sections'));
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
    public function store(SectionRequest $request)
    {
        section::create([
        "section_name"=>$request->section_name,
        "siscribtion"=>$request->description,
        "created_by"=>Auth::user()->name
        ]);
        return redirect()->back()->with(['Add'=>"تم الاضافة بنجاح"]);

       
    }

    /**
     * Display the specified resource.
     */
    public function show(section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(section $section)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request)
    {   
        $section=section::find($request->id);
   
        $section->update([
            'section_name'=>$request->section_name,
            'siscribtion'=>$request->description
        ]);
        return redirect()->back()->with(['edit'=>"تم التعديل بنجاح"]);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $section=section::find($request->id)->delete();
        return redirect()->back()->with(['delete'=>"تم الحذف بنجاح "]); 
    }
}
