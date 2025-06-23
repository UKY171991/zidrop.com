<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('smtp_configurations')) {
            $mail = DB::table('smtp_configurations')->first();
            if ($mail) //checking if table is not empty
            {
                $config = array(
                    'driver' => 'smtp',
                    'host' => $mail->mail_host,
                    'port' => $mail->mail_port,
                    'from' => array('address' => $mail->mail_from_address, 'name' => $mail->mail_from_name),
                    'encryption' => $mail->mail_encryption,
                    'username' => $mail->mail_username,
                    'password' => $mail->mail_password,
                    'sendmail' => '/usr/sbin/sendmail -bs',
                    'pretend' => false,
                );
                Config::set('mail', $config);
            }
        }
    }
}
