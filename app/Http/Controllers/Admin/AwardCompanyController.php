<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AwardCompany;
use App\Models\AwardCompanyCategory;
use App\Models\AwardCategory;
use Illuminate\Http\Request;

class AwardCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = AwardCompany::orderBy('id','DESC')->get();
        return view('admin.award.company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$categories = AwardCategory::orderBy('title','ASC')->get();
        return view('admin.award.company.create', compact('categories'));
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
            'title' => 'required|unique:award_companies',
        ]);
        $companay = AwardCompany::Create($request->all());
		foreach($request->categories as $category){
			AwardCompanyCategory::updateOrCreate(
				['category_id' => $category, 'company_id' => $companay->id], 
				['category_id' => $category, 'company_id' => $companay->id]
			);
		}
        return redirect()->action([AwardCompanyController::class, 'index'])->with('success', 'Companay Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AwardCompany  $awardCompany
     * @return \Illuminate\Http\Response
     */
    public function show(AwardCompany $awardCompany)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AwardCompany  $awardCompany
     * @return \Illuminate\Http\Response
     */
    public function edit(AwardCompany $awardCompany)
    {
		$categories = AwardCategory::orderBy('title','ASC')->get();
		$selected_categories = AwardCompanyCategory::where('company_id', $awardCompany->id)->pluck('category_id')->toArray();
        return view('admin.award.company.edit', compact('awardCompany', 'categories','selected_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AwardCompany  $awardCompany
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AwardCompany $awardCompany)
    {
        $request->validate([
            'title' => 'required|unique:award_companies,title,'.$awardCompany->id
        ]);
        $awardCompany->update($request->all());
		
		foreach($request->categories as $category){
			AwardCompanyCategory::updateOrCreate(
				['category_id' => $category, 'company_id' => $awardCompany->id], 
				['category_id' => $category, 'company_id' => $awardCompany->id]
			);
		}
		
        return redirect()->action([AwardCompanyController::class, 'index'])->with('success', 'Companay Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AwardCompany  $awardCompany
     * @return \Illuminate\Http\Response
     */
    public function destroy(AwardCompany $awardCompany)
    {
        $awardCompany->delete();
		AwardCompanyCategory::where('id', $awardCompany->id)->delete();
        return redirect()->action([AwardCompanyController::class, 'index'])->with('success', 'Companay Deleted Successfully');
    }
	
	public function votingResult()
    {
		$categories = AwardCategory::with('companies', 'companies.company_detail')->orderBy('title','ASC')->get();
        return view('admin.award.company.show', compact('categories'));
    }
	
}
