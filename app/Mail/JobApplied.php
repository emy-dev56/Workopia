<?php

namespace App\Mail;

use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobApplied extends Mailable
{
    use Queueable, SerializesModels;
    public Applicant $application;
    public Job $job;

    /**
     * Create a new message instance.
     */
    public function __construct(Applicant $application, Job $job)
    {
        $this->application = $application;
        $this->job = $job;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Job Application Applied',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job_applied',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
       $attachments = [];
       if($this->application->resume_path){
        $attachment[] = Attachment::fromPath(storage_path('app/public/'.$this->application->resume_path))->as($this->application->resume_path)->withMime('application/pdf');
       }

       return $attachments;
    }
}
