<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePilotVideoRequest;
use App\Http\Requests\UpdatePilotProfileRequest;
use App\Models\PilotProfile;
use App\Models\PilotVideos;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

use Illuminate\Routing\Redirector;

class PilotVideosController extends Controller
{
    public function index()
    {
//        abort_if(Gate::denies('pilot_profile_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $video = PilotVideos::all()->get();

        return view('admin.pilot.videos.index', compact('video'));
    }

    public function add_more(Request $req)
    {
//        abort_if(Gate::denies('pilot_profile_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $count_id = $req->count+1;
        
        $radio1 = $count_id+1;
        $radio2 = $radio1.$radio1;
        
        $result = '';
        $result .= '<hr>';
        $result .= '<div class="form-group">
                <label class="required" for="travel_option">Video Type</label>                
                <div class="d-inline-block custom-control custom-radio mr-2">
                    <input type="radio" class="custom-control-input videoType_'.$count_id.'" name="type_'.$count_id.'" id="video_type_'.$radio1.'" value="Youtube" required>
                    <label class="custom-control-label" for="video_type_'.$radio1.'">You Tube</label>
                </div>
                <div class="d-inline-block custom-control custom-radio mr-2">
                    <input type="radio" class="custom-control-input videoType_'.$count_id.'" name="type_'.$count_id.'" id="video_type_'.$radio2.'" value="Vimeo">
                    <label class="custom-control-label" for="video_type_'.$radio2.'">Vimeo</label>
                </div><span class="type-block-'.$count_id.'"></span>
            </div>
            
            <div class="form-group">
                <label class="required" for="title">Enter Video Url</label>
                <input class="form-control" type="text" name="video_'.$count_id.'" id="video_'.$count_id.'" required>
                
            </div><span class="help-block-'.$count_id.'"></span>';
        
        $status = ['status'=>'1','result'=>$result,'countId'=>$count_id];
        echo json_encode($status);
    }
    public function create(Request $req)
    {
//        abort_if(Gate::denies('pilot_profile_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userId = $req->id;
        $profileId = $req->pid;

        return view('admin.pilot.videos.create1', compact('userId', 'profileId'));
    }

    public function store(StorePilotVideoRequest $request)
    {
        $totalCount = $request->count;
        $uId = str_rot13(base64_decode($request->user_id));
        $pro_id = str_rot13(base64_decode($request->profile_id));
        
     
        

        if ($request->has('video')) {
            foreach ($request->input('video') as $key => $value) {
                //generate the video type request
                $video_type_position='type_'.$key;

                if ($request->input($video_type_position)=="Youtube") {
                    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $value, $match);
                    $video_key = @$match[1];
                } else {
                    $video_key =$this->getVimeoVideoIdFromUrl($value);
                }

                $pilot_video= new PilotVideos();
                $pilot_video->pilot_profile_id=$pro_id;
                $pilot_video->type=$request->input($video_type_position);
                $pilot_video->video=$value;
                $pilot_video->video_key=$video_key;
                $pilot_video->save();
            }
        }


        
        return redirect()->action([PilotProfileController::class, 'index'])->with('success', 'Successfully Pilot Video Added');
    }

    public function edit(Request $req)
    {
        $videos = PilotVideos::find($req->pilot_video);
        
        return view('admin.pilot.videos.edit', compact('videos'));
    }

    public function update(Request $req)
    {
        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $pilotVideo = PilotVideos::find($req->id);
        
        $video_key = '';
        if ($req->type == 'Youtube') {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $req->video, $match);

            if (!empty($match)) {
                $video_key = ($match[1] != '') ? $match[1] : 'NA' ;
            }
        } else {
            preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $req->video, $regs);
                    
            if (!empty($regs)) {
                $video_key = ($regs[3] != '') ? $regs[3] : 'NA' ;
            }
        }
        
        $pilotVideo->type = $req->type;
        $pilotVideo->video = $req->video;
        
        $pilotVideo->video_key = $video_key;
        
        $pilotVideo->save();
        
        return redirect()->action([PilotProfileController::class, 'show'], $pilotVideo->pilot_profile_id)->with('success', 'Successfully Pilot Video Updated');
    }

    public function show(Request $req)
    {
        abort_if(Gate::denies('pilot_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = PilotProfile::with('userOne')->get()->find($req->pilot);

        return view('admin.pilot.profile.show', compact('user'));
    }

    public function destroy(Request $req)
    {
        $pilotVideo = PilotVideos::find($req->pilot_video);
        
        $pilotVideo->delete();


        return redirect()->action([PilotProfileController::class, 'show'], $pilotVideo->pilot_profile_id)->with('success', 'Successfully Pilot Video Deleted');
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
