<?php

namespace App\Mail;

use App\Model\Company\CompanyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Throwable;

class NewCompanyMail extends Mailable
{
    use Queueable, SerializesModels;

    private $companyUser;

    public function __construct(CompanyUser $companyUser)
    {
        $this->companyUser = $companyUser;
    }

    public function build()
    {
        $date = date('d/m/Y H:i');

        return $this->subject('Nova empresa cadastrada - ' . config('app.name'))
            ->markdown('emails.new_company')
            ->with([
                'razao' => $this->companyUser->company->razao,
                'cnpj' => $this->companyUser->company->cnpj,
                'date' => $date,
                'userName' => $this->companyUser->person->name,
                'userEmail' => $this->companyUser->email,
            ]);
    }
}
