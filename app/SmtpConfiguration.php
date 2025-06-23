<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmtpConfiguration extends Model
{
    protected $table = 'smtp_configurations';
    protected $fillable = [
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];
}
