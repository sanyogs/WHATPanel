<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\Sms\Controllers;

use App\Models\App;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use Config\Services;

// Make sure to use the correct namespace for Twilio\Client
use Twilio\Rest\Client as TwilioClient;

class Sms extends WhatPanel
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();

        // Use the Config\Services class to load the required services
        $this->load = Services::load();
        $this->template = Services::template();
    }

    public function twilio($payload)
    {
        // Use the Config class to access configuration items
        $sid = config('Twilio.sid');
        $token = config('Twilio.token');

        // Use the TwilioClient namespace instead of 'use Twilio\Rest\Client;'
        $client = new TwilioClient($sid, $token);

        $phone = strpos($payload['phone'], '+') === false ? '+' . $payload['phone'] : $payload['phone'];

        try {
            $message = $client->messages->create(
                $phone,
                [
                    'from' => config('Twilio.phone'),
                    'body' => $payload['message'],
                ]
            );
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        $data = [
            'user' => 1,
            'module' => 'invoices',
            'module_field_id' => 1,
            'activity' => $message,
            'icon' => 'fa-paperplane',
            'value1' => $payload['phone'],
            'value2' => '',
        ];

        // Use the App class to call the Log method
        App::Log($data);

        return $message;
    }
}