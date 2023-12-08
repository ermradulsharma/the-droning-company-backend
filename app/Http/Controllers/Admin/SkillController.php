<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Skill;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;

class SkillController extends Controller
{
    public function index()
    {
//        abort_if(Gate::denies('pilot_profile_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $skills = Skill::orderBy('skill_name','ASC')->get();
        return view('admin.pilot.skills.index', compact('skills'));
    }

    public function create()
    {
        return view('admin.pilot.skills.create');
    }

    public function store(Request $req, Skill $pilotSkills)
    {
        $rules = ['skills' => 'required|unique:skills,skill_name|max:255'];

        if($req->validate($rules)) // true
        {
            $pilotSkills->skill_name= $req->skills;

            $pilotSkills->save();       

            return redirect()->action([SkillController::class, 'index'])->with('success', 'Successfully Skills Created');
        }        
    }

    public function edit(Request $req)
    {
//        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $skill = Skill::find($req->skill);
        return view('admin.pilot.skills.edit', compact('skill'));
    }

    public function update(Request $req)
    {
//        abort_if(Gate::denies('pilot_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $pilotSkl = Skill::find($req->skill);
        
        $pilotSkl->skill_name = $req->skill_name;
        $pilotSkl->status = $req->status;

        $pilotSkl->save();        
        
        return redirect()->action([SkillController::class, 'index'])->with('success', 'Successfully Skills Updated');

    }

    public function show(Request $req)
    {
        abort_if(Gate::denies('pilot_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $skill = Skill::find($req->skill);

        return view('admin.pilot.skills.show', compact('skill'));
    }

    public function destroy(Request $req)
    {
//        abort_if(Gate::denies('pilot_profile_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pilotSk = Skill::find($req->skill);
        
        $pilotSk->delete();

        return redirect()->action([SkillController::class, 'index'])->with('success', 'Successfully Skills Deleted');
    }


}
