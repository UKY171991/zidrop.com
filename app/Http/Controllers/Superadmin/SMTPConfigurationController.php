<?php

namespace App\Http\Controllers\Superadmin;

use App\SmtpConfiguration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class SMTPConfigurationController extends Controller
{
    public function showConfiguration()
    {
        $smtp_configuration = SmtpConfiguration::first();
        if (empty($smtp_configuration)) {
            $smtp_configuration = new SmtpConfiguration();
        }
        return view('backEnd.superadmin.smtp-configuration.show')
            ->with(compact(
                'smtp_configuration'
            ));
    }

    public function updateConfiguration(Request $request)
    {
        $request->validate([
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_encryption' => 'required',
            'mail_from_address' => 'required',
            'mail_from_name' => 'required',
        ]);

        try {

            $smtp_configuration = SmtpConfiguration::first();
            if (empty($smtp_configuration)) {
                $smtp_configuration = new SmtpConfiguration();
            }
            $smtp_configuration->mail_host = $request->mail_host;
            $smtp_configuration->mail_port = $request->mail_port;
            $smtp_configuration->mail_username = $request->mail_username;
            $smtp_configuration->mail_password = $request->mail_password;
            $smtp_configuration->mail_encryption = $request->mail_encryption;
            $smtp_configuration->mail_from_address = $request->mail_from_address;
            $smtp_configuration->mail_from_name = $request->mail_from_name;
            $smtp_configuration->save();

        } catch (\Exception $exception) {
            Toastr::error('message', $exception->getMessage());
            return redirect()->back();
        }

        Toastr::success('message', 'Configuration Updated Success!');
        return redirect()->back();
    }
}
