<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
require(__DIR__.'/../src/Twilio/autoload.php');

use Twilio\Rest\Client;

$sid = getenv('TWILIO_ACCOUNT_SID');
$token = getenv('TWILIO_AUTH_TOKEN');
$client = new Client($sid, $token);

// Get last 10 records
$recordList = $client->usage->records->read([], 10);
foreach ($recordList as $record) {
    print_r("Record(accountSid=" . $record->accountSid . ", apiVersion=" . $record->apiVersion . ", asOf=" . $record->asOf . ", category=" . $record->category . ", count=" . $record->count . ", countUnit=" . $record->countUnit . ", description=" . $record->description . ", endDate=" . $record->endDate->format("Y-m-d H:i:s") . ", price=" . $record->price . ", priceUnit=" . $record->priceUnit . ", startDate=" . $record->startDate->format("Y-m-d H:i:s") . ", uri=" . $record->uri . ", usage=" . $record->usage . ", usageUnit=" . $record->usageUnit . "\n");
}