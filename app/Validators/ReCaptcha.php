<?php

namespace App\Validators;

use GuzzleHttp\Client;

class ReCaptcha
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        $client = new Client;

        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' => [
                    'secret' => config('google_captcha.secret_key'),
                    'response' => $value
                ]
            ]
        );

        // Optionally handle the response here
        $body = json_decode((string)$response->getBody());

        return $body->success;
    }
}
