<?php

namespace App\Mail;

use App\Models\PilotJob;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobPostApproved extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $jobPost;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PilotJob $jobPost)
    {
        $this->jobPost=$jobPost;
        $this->user= $this->jobPost->user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.jobPostApproved')
         ->subject('NEW JOB POSTED APPROVED')
            ->with([
            'action_url'=>config('app.front_url').'/login/',
            'jobPost'=>$this->jobPost,
            'user'=>$this->user
        ]);
    }
}
