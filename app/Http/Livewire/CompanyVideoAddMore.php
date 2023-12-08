<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CompanyVideoAddMore extends Component
{
    public $videoAttributes=[];

    public function mount()
    {
        $this->videoAttributes =[
            ['attribute_value' => '', 'attribute_list_id' => '']
        ];
    }
    
    
    public function addLevelAttribute()
    {
        $this->videoAttributes[] = ['level_name' => '', 'level_value' => ''];
    }

    
    public function render()
    {
        return view('livewire.company-video-add-more');
    }
}
