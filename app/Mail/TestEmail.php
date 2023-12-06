<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    public function build()
    {
        // Recipient for use with variables and/or personalization
        return $this
            ->mailersend(
                template_id: 'neqvygmrwjdl0p7w',
                precedenceBulkHeader: true,
                sendAt: new Carbon('2022-01-28 11:53:20'),
            );
    }
}