<?php

namespace App\Mail;

use App\Model\Company\CompanyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ImportUsersErrorMail extends Mailable
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
        return $this->subject('Ocorreu um erro na importação - ' . config('app.name'))
            ->markdown('emails.imports_error')
            ->with([
                'company' => $this->user->company->razao,
                'name' => $this->user->person->name,
                'email' => $this->user->email,
                'message' => $this->event->getException()->getMessage(),
            ]);
    }
}
