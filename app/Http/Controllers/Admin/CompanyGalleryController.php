<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyGallery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Storage;
use Image;

class CompanyGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company_galleries = CompanyGallery::latest()->get();
        return view('admin.company.gallery.index', compact('company_galleries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $company_id=$request->input('id');
        return view('admin.company.gallery.create', compact('company_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company_id = $request->company_id;
        $destinationPath=public_path('/images/company/'.$company_id.'/gallery');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        if ($request->has('image')) {
            foreach ($request->image as $image) {
                $original_image_size=getimagesize($image);
                list($width, $height)=getimagesize($image);
                $sizes_arr=["400,400"];
                $time=time();
                $image_name =$time.'.' . $image->getClientOriginalName();
                $image_n[] = $image_name;
                $resize_image = Image::make($image->getRealPath());
                foreach ($sizes_arr as $size_value) {
                    $array = explode(',', $size_value);
                    $new_width = $array[0];
                    $new_height = $array[1];
                    $new_image_name =$time.'-'.$new_width.'x'.$new_height.'.'.$image->getClientOriginalName();
                    $resize_image->resize($new_width, $new_height, function ($constraint) {
                        //$constraint->aspectRatio();
                    })->save($destinationPath . '/' . $new_image_name);
                }
                $image->move($destinationPath, $image_name);
                CompanyGallery::create([
                    'company_id'    =>  $company_id,
                    'image'         =>  '/images/company/'.$company_id.'/gallery/'.$image_name,
                    'status'        =>  '1',
                ]);
            }
        }
        return redirect()->action([CompanyProfileController::class, 'index'])->with('success', 'Company Gallery added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyGallery  $companyGallery
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyGallery $companyGallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyGallery  $companyGallery
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyGallery $companyGallery)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.company.gallery.edit', compact('companyGallery'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyGallery  $companyGallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyGallery $companyGallery)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $company_id = $companyGallery->company_id;
        if ($request->hasFile('image')) {
            $img = Storage::disk('public_uploads')->put('/images/company/'.$company_id.'/gallery', $request->image);
            $companyGallery->image = $img ;
        }
        $companyGallery->status = $request->status;
        $companyGallery->save();
        return redirect()->action([CompanyProfileController::class, 'index'])->with('success', 'Gallery Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyGallery  $companyGallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyGallery $companyGallery)
    {
        $companyGallery->delete();
        return redirect()->action([CompanyProfileController::class, 'index'])->with('success', 'Gallery Deleted Successfully.');
    }

    public function massDestroy(Request $request)
    {
        CompanyGallery::whereIn('id', $request->ids)->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
