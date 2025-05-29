<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SeoAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageText;

    public function __construct($messageText)
    {
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject('SEO Alert from Rankolab')
                    ->view('emails.seo_alert')
                    ->with(['messageText' => $this->messageText]);
    }
}
