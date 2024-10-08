<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Numbers
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


namespace Twilio\Rest\Numbers\V1;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Values;
use Twilio\Version;
use Twilio\Deserialize;


/**
 * @property string|null $portInRequestSid
 * @property string|null $url
 * @property string|null $accountSid
 * @property string[]|null $notificationEmails
 * @property \DateTime|null $targetPortInDate
 * @property string|null $targetPortInTimeRangeStart
 * @property string|null $targetPortInTimeRangeEnd
 * @property array|null $losingCarrierInformation
 * @property array[]|null $phoneNumbers
 * @property string[]|null $documents
 */
class PortingPortInFetchInstance extends InstanceResource
{
    /**
     * Initialize the PortingPortInFetchInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $portInRequestSid The SID of the Port In request. This is a unique identifier of the port in request.
     */
    public function __construct(Version $version, array $payload, string $portInRequestSid = null)
    {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = [
            'portInRequestSid' => Values::array_get($payload, 'port_in_request_sid'),
            'url' => Values::array_get($payload, 'url'),
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'notificationEmails' => Values::array_get($payload, 'notification_emails'),
            'targetPortInDate' => Deserialize::dateTime(Values::array_get($payload, 'target_port_in_date')),
            'targetPortInTimeRangeStart' => Values::array_get($payload, 'target_port_in_time_range_start'),
            'targetPortInTimeRangeEnd' => Values::array_get($payload, 'target_port_in_time_range_end'),
            'losingCarrierInformation' => Values::array_get($payload, 'losing_carrier_information'),
            'phoneNumbers' => Values::array_get($payload, 'phone_numbers'),
            'documents' => Values::array_get($payload, 'documents'),
        ];

        $this->solution = ['portInRequestSid' => $portInRequestSid ?: $this->properties['portInRequestSid'], ];
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return PortingPortInFetchContext Context for this PortingPortInFetchInstance
     */
    protected function proxy(): PortingPortInFetchContext
    {
        if (!$this->context) {
            $this->context = new PortingPortInFetchContext(
                $this->version,
                $this->solution['portInRequestSid']
            );
        }

        return $this->context;
    }

    /**
     * Fetch the PortingPortInFetchInstance
     *
     * @return PortingPortInFetchInstance Fetched PortingPortInFetchInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): PortingPortInFetchInstance
    {

        return $this->proxy()->fetch();
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name)
    {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Numbers.V1.PortingPortInFetchInstance ' . \implode(' ', $context) . ']';
    }
}

