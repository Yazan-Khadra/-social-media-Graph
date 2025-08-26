<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IssueResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $issue;
    public $response;

    public function __construct($issue, $response)
    {
        $this->issue = $issue;
        $this->response = $response;
    }

    public function build()
    {
        return $this->subject('Response to your Technical Support Issue')
                    ->view('emails.issue_response');
    }
}
