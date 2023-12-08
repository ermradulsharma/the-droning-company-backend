<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavelBox;
use App\Models\FavelBoxDetail;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use ImageOptimizer;
use Storage;
use Gate;


class FavelFootnoteContentController extends Controller
{
    use MediaUploadingTrait;

    public function index($favel_box_id)
    {
        abort_if(Gate::denies('favelboxcontent_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $content = FavelBoxDetail::where('favel_box_id', $favel_box_id)->orderBy('id','desc')->get();
        return view('admin.favelBoxesContent.index', compact('content', 'favel_box_id'));
    }

    public function create($favel_box_id)
    {
        abort_if(Gate::denies('favelboxcontent_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $favelbox = FavelBox::find($favel_box_id);
        return view('admin.favelBoxesContent.create', compact('favelbox'));
    }

    public function store($favel_box_id, Request $request)
    {
        $favelbox = FavelBox::find($favel_box_id);

        if($favelbox->slug == "articles"){
            $rules = [
                'title' => 'required',
                'page_video_link' => 'required',
                'image' => 'required|mimes:jpeg,png,jpg,gif',
            ];
        }
        else{
            $rules = [
                'page_video_link' => 'required',
            ];
        }

        $request->validate($rules);
        try {

            $data=$request->all();
            if ($request->hasFile('image')) {
                $data['image']=Storage::disk('public_uploads')->put('images/box', $request->image);
                ImageOptimizer::optimize(public_path($data['image']));
            }    
            $boxDetails = New FavelBoxDetail; 
            $boxDetails->fill($data);
            $boxDetails->favel_box_id = $favel_box_id;
            $boxDetails->save();

            if ($request->input('image', false)) {
                $boxDetails->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }        

            return redirect()->route('admin.favel-footnote-boxes.content.index', $favel_box_id);
        }
        catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    public function edit($favel_box_id, $favelboxdetailId)
    {
        abort_if(Gate::denies('favelboxcontent_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');  
        $favelbox = FavelBox::find($favel_box_id);
        $favelboxdetail = FavelBoxDetail::find($favelboxdetailId);
        return view('admin.favelBoxesContent.edit', compact('favelbox', 'favelboxdetail'));
    }

    public function update($favel_box_id, Request $request, $favelboxdetailId)
    {
        $boxDetails = FavelBoxDetail::where('id',$favelboxdetailId)->first(); 
        if($boxDetails->slug == "articles"){
            $rules = [
                'title' => 'required',
                'page_video_link' => 'required',
                'image' => 'mimes:jpeg,png,jpg,gif',
            ];
        }
        else{
            $rules = [
                'page_video_link' => 'required',
            ];
        }

        $request->validate($rules);
        try {
            
            $data=$request->all();
            if ($request->hasFile('image')) {
                $data['image']=Storage::disk('public_uploads')->put('images/box', $request->image);
                ImageOptimizer::optimize(public_path($data['image']));
            }            
            $previous_img = $boxDetails->image;
            $boxDetails->fill($data);
            $boxDetails->save();
        
            if ($request->hasFile('image')) {
                if(isset($previous_img)){
                    $full_path = public_path('/'.$previous_img);
                    unlink($full_path);
                }
            }     

            return redirect()->route('admin.favel-footnote-boxes.content.index', $favel_box_id);
        }
        catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }

    // public function show($boxes_id, City $city)
    // {
    //     return view('admin.cities.show', compact('country_id', 'city'));
    // }

    public function destroy($favel_box_id, $favelboxdetailId)
    {
        abort_if(Gate::denies('favelboxcontent_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $boxdetail = FavelBoxDetail::where('id',$favelboxdetailId)->first();

        FavelBoxDetail::where('id',$favelboxdetailId)->delete();
        
        if(isset($boxdetail->image)){
            $full_path = public_path('/'.$boxdetail->image);
            unlink($full_path);
        }        

        return back();
    }
}
