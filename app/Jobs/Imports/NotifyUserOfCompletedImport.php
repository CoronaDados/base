<?php

namespace App\Jobs\Imports;

use App\Model\Company\CompanyUser;
use App\Notifications\Imports\ImportHasFinishedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUserOfCompletedImport implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(CompanyUser $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $this->user->notify(new ImportHasFinishedNotification());
    }
}
