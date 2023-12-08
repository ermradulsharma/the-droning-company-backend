<?php

namespace App\Http\Controllers\Admin;

use App\Models\CompanyVideo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class CompanyVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $video = CompanyVideo::all()->get(); 
        return view('admin.company.videos.index', compact('video'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $userId = $req->id;
        $profileId = $req->pid;
        return view('admin.company.videos.create1', compact('userId', 'profileId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $totalCount = $request->count;
        $uId = str_rot13(base64_decode($request->user_id));
        $pro_id = str_rot13(base64_decode($request->profile_id));
        if ($request->has('video')) {
            foreach ($request->input('video') as $key => $value) {
                $video_type_position='type_'.$key;
                if ($request->input($video_type_position)=="Youtube") {
                    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $value, $match);
                    $video_key = @$match[1];
                } else {
                    $video_key =$this->getVimeoVideoIdFromUrl($value);
                }
                $company_video= new CompanyVideo();
                $company_video->company_id = $pro_id;
                $company_video->type=$request->input($video_type_position);
                $company_video->video=$value;
                $company_video->video_key=$video_key;
                $company_video->save();
            }
        }
        return redirect()->action([CompanyProfileController::class, 'index'])->with('success', 'Company Video Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyVideo  $companyVideo
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyVideo $companyVideo)
    {
        abort_if(Gate::denies('pilot_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $company = $companyVideo->company_profile;
        return view('admin.company.profile.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyVideo  $companyVideo
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyVideo $companyVideo)
    {
        return view('admin.company.videos.edit', compact('companyVideo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyVideo  $companyVideo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyVideo $companyVideo)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $video_key = '';
        if ($request->type == 'Youtube') {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $request->video, $match);
            if (!empty($match)) {
                $video_key = ($match[1] != '') ? $match[1] : 'NA' ;
            }
        } else {
            preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $request->video, $regs);
            if (!empty($regs)) {
                $video_key = ($regs[3] != '') ? $regs[3] : 'NA' ;
            }
        }
        $companyVideo->type = $request->type;
        $companyVideo->video = $request->video;
        $companyVideo->video_key = $video_key;
        $companyVideo->save();
        
        return redirect()->action([CompanyProfileController::class, 'show'], $companyVideo->company_profile)->with('success', 'Company Video Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyVideo  $companyVideo
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyVideo $companyVideo)
    {
        $companyVideo->delete();
        return redirect()->action([CompanyProfileController::class, 'show'], $companyVideo->company_profile)->with('success', 'Company Video Deleted Successfully.');
    }

    public function getVimeoVideoIdFromUrl($url = '')
    {
        $regs = array();
        $id = '';
        if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs)) {
            $id = $regs[3];
        }
        return $id;
    }
}
