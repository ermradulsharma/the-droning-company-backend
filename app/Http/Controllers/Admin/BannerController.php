<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\Banner;
use App\Models\BannerPageList;
use App\Models\BannerSection;
use App\Models\Resolution;
use Illuminate\Support\Facades\Log;
use Storage;
use ImageOptimizer;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;


class BannerController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('banner_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banners = Banner::get();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        abort_if(Gate::denies('banner_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $resolutions = Resolution::get();
        $pageNames = BannerPageList::get();
        $pageSectionNames = BannerSection::where('banner_page_list_id',1)->get();

        return view('admin.banners.create',compact('pageNames','pageSectionNames','resolutions'));
    }

    public function store(Request $request)
    {
        // trim(explode("x",$request->image_resolution)[0])
        $rules = [
            'page' => 'required',
            'banner_section_id' => 'required',
            'image_resolution' => 'required',
            'link' => 'required',
            'banner_image' => 'required|mimes:jpeg,png,jpg,gif|dimensions:width='.trim(explode("x",$request->image_resolution)[0]).',height='.trim(explode("x",$request->image_resolution)[1]),
        ];

        $request->validate($rules);
        try {

            $data=$request->all();
            if ($request->hasFile('banner_image')) {
                $data['banner_image']=Storage::disk('public_uploads')->put('images/banner', $request->banner_image);
                ImageOptimizer::optimize(public_path($data['banner_image']));
            }
            $banner = New Banner; 
            $banner->fill($data);
            $banner->save(); 

            if ($request->input('banner_image', false)) {
                $banner->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner_image'))))->toMediaCollection('banner_image');
            }        

            return redirect()->route('admin.ads.index');
        }
        catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function edit(Banner $ad)
    {
        abort_if(Gate::denies('banner_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $resolutions = Resolution::get();
        $pageNames = BannerPageList::get();
        $pageSectionNames = BannerSection::where('banner_page_list_id',$ad->bannerSection->banner_page_list_id)->get();

        return view('admin.banners.edit', compact('ad','pageNames','pageSectionNames','resolutions'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'page' => 'required',
            'banner_section_id' => 'required',
            'image_resolution' => 'required',
            'link' => 'required',
            'banner_image' => 'mimes:jpeg,png,jpg,gif|dimensions:width='.trim(explode("x",$request->image_resolution)[0]).',height='.trim(explode("x",$request->image_resolution)[1]),

        ];

        $request->validate($rules);
        try {
            $banner = Banner::where('id',$id)->first(); 
            $data=$request->all();
            if ($request->hasFile('banner_image')) {
                $data['banner_image']=Storage::disk('public_uploads')->put('images/banner', $request->banner_image);
                ImageOptimizer::optimize(public_path($data['banner_image']));
            }
            else{
                if($banner->image_resolution != $request->image_resolution){
                    return back()->withErrors(['image_resolution' => ['The banner image has invalid image dimensions.']]);
                }
            }

            
            $previous_img = $banner->banner_image;
            $banner->fill($data);
            $banner->save();

        
            if ($request->hasFile('banner_image')) {
                if(isset($previous_img)){
                    $full_path = public_path('/'.$previous_img);
                    // Log::debug("message".print_r($previous_img,true));
                    // Log::debug("message".print_r($full_path,true));
                    unlink($full_path);
                }
                        // Storage::disk('public_uploads')->delete($previous_img);
            }      

            return redirect()->route('admin.ads.index');
        }
        catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    // public function show(ContentPage $contentPage)
    // {
    //     abort_if(Gate::denies('content_page_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $contentPage->load('categories', 'tags');
    //     return view('admin.contentPages.show', compact('contentPage'));
    // }

    public function destroy($id)
    {
        // Log::debug("message".print_r($id,true));
        abort_if(Gate::denies('banner_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $banner = Banner::where('id',$id)->first();

        Banner::where('id',$id)->delete();
        
        if(isset($banner->banner_image)){
            $full_path = public_path('/'.$banner->banner_image);
            unlink($full_path);
        }        

        return back();
    }

    public function massDestroy(Request $request)
    {
    
        $banners = Banner::whereIn('id', request('ids'))->get();
        
        Banner::whereIn('id', request('ids'))->delete();

        foreach($banners as $banner){
            if(isset($banner->banner_image)){
                $full_path = public_path('/'.$banner->banner_image);
                unlink($full_path);
            }
        }        

        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function fetchSection(Request $request)
    {
        $pageSectionNames = BannerSection::where('banner_page_list_id',$request->banner_page_list_id)->get();
        return response()->json($pageSectionNames);
    }
}
