<?php

namespace App\Mail;

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

        $exception = $this->exception->getPrevious();
        if (!$exception) {
            $exception = $this->exception;
        }

        $file = $exception->getFile();
        $line = $exception->getLine();
        $message = $exception->getMessage();

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
