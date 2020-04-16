<?php

namespace App\Mail;

use App\Model\Company\CompanyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    private $exception;

    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function build()
    {
        $user = auth('company')->user();
        $date = date('d/m/Y H:i');
        $file = $this->exception->getPrevious()->getFile();
        $line = $this->exception->getPrevious()->getLine();
        $message = $this->exception->getPrevious()->getMessage();

        return $this->subject('Ocorreu um erro no sistema - ' . config('app.name'))
            ->markdown('emails.error')
            ->with([
                'name' => $user->person->name ?? 'Não autenticado',
                'email' => $user->email ?? 'Não autenticado',
                'date' => $date,
                'file' => $file,
                'line' => $line,
                'message' => $message,
            ]);
    }
}
