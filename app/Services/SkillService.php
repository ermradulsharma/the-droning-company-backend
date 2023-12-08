<?php
namespace App\Services;

use App\Models\PilotSkills;
use Illuminate\Support\Str;

class SkillService
{
    public function pilot(int $pilot_profile_id)
    {
        if (!$pilot_profile_id) {
            return '';
        }
        $pilot_skills = PilotSkills::query()
            ->select('pilot_skills.skill_id as id', 'skills.skill_name as name')
            ->join('skills', 'pilot_skills.skill_id', '=', 'skills.id')
            ->where('pilot_skills.pilot_profile_id', $pilot_profile_id)
            ->where('pilot_skills.status', '1')
            ->get();

        return   Collect($pilot_skills)->implode('name', ',');
    }

    public function pilotAsArray(int $pilot_profile_id)
    {
        if (!$pilot_profile_id) {
            return '';
        }
        return   $pilot_skills = PilotSkills::query()
            ->select('pilot_skills.skill_id as id', 'skills.skill_name as name')
            ->join('skills', 'pilot_skills.skill_id', '=', 'skills.id')
            ->where('pilot_skills.pilot_profile_id', $pilot_profile_id)
            ->where('pilot_skills.status', '1')
            ->get();
    }
	public function pilotSkillsArray(int $pilot_profile_id)
    {
        if (!$pilot_profile_id) {
            return '';
        }
        return   $pilot_skills = PilotSkills::query()
            ->select('pilot_skills.skill_id as id', 'skills.skill_name')
            ->join('skills', 'pilot_skills.skill_id', '=', 'skills.id')
            ->where('pilot_skills.pilot_profile_id', $pilot_profile_id)
            ->where('pilot_skills.status', '1')
            ->get();
    }
}
