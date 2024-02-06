<?php

// app/Mail/SendEmail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $attachmentPath;

    public function __construct($data, $attachmentPath)
    {
        $this->data = $data;
        $this->attachmentPath = $attachmentPath;
    }

    public function build()
    {
        return $this->view('emails.send-email')
                    ->subject($this->data['subject'])
                    ->attachFromStorage($this->attachmentPath, 'document.pdf');
    }
}

