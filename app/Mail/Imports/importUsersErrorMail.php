<?php

namespace App\Mail\Imports;

use App\Model\Company\CompanyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class importUsersErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $event;

    public function __construct(CompanyUser $user, $event)
    {
        $this->user = $user;
        $this->event = $event;
    }

    public function build()
    {
        return $this->markdown('mails.imports.error')
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'message' => $this->event->getException()->getMessage(),
            ]);
    }
}
