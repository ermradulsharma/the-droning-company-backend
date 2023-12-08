<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavelBox;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use ImageOptimizer;
use Storage;
use Gate;

class FavelFootnoteController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('favelbox_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $boxes = FavelBox::get();
        return view('admin.favelBoxes.index', compact('boxes'));
    }


    public function edit($id)
    {
        abort_if(Gate::denies('favelbox_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');  
        $box = FavelBox::find($id);
        return view('admin.favelBoxes.edit', compact('box'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'box_name' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif',
        ];

        $request->validate($rules);
        try {
            $box = FavelBox::where('id',$id)->first(); 
            $data=$request->all();
            if ($request->hasFile('image')) {
                $data['image']=Storage::disk('public_uploads')->put('images/box', $request->image);
                ImageOptimizer::optimize(public_path($data['image']));
            }            
            $previous_img = $box->image;
            $box->fill($data);
            $box->save();
        
            if ($request->hasFile('image')) {
                if(isset($previous_img)){
                    $full_path = public_path('/'.$previous_img);
                    unlink($full_path);
                }
            }     

            return redirect()->route('admin.favel-footnote-boxes.index');
        }
        catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            abort(500);
        }
    }
}
