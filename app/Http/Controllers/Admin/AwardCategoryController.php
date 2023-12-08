<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AwardCategory;
use Illuminate\Http\Request;

class AwardCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = AwardCategory::orderBy('id','DESC')->get();
        return view('admin.award.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.award.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:award_categories',
        ]);
        $category = AwardCategory::Create($request->all());
        return redirect()->action([AwardCategoryController::class, 'index'])->with('success', 'Award Category Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AwardCategory  $awardCategory
     * @return \Illuminate\Http\Response
     */
    public function show(AwardCategory $awardCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AwardCategory  $awardCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(AwardCategory $awardCategory)
    {
        return view('admin.award.category.edit', compact('awardCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AwardCategory  $awardCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AwardCategory $awardCategory)
    {
        $request->validate([
            'title' => 'required|unique:award_categories,title,'.$awardCategory->id
        ]);
        $awardCategory->update($request->all());
        return redirect()->action([AwardCategoryController::class, 'index'])->with('success', 'Award Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AwardCategory  $awardCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(AwardCategory $awardCategory)
    {
        $awardCategory->delete();
        return redirect()->action([AwardCategoryController::class, 'index'])->with('success', 'Award Category Deleted Successfully');
    }
}
