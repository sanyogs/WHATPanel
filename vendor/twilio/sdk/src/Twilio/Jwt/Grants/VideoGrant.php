<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace Twilio\Jwt\Grants;


class VideoGrant implements Grant {

    private $room;

    /**
     * Returns the room
     *
     * @return string room name or sid set in this grant
     */
    public function getRoom(): string {
        return $this->room;
    }

    /**
     * Set the room to allow access to in the grant
     *
     * @param string $roomSidOrName room sid or name
     * @return $this updated grant
     */
    public function setRoom(string $roomSidOrName): self {
        $this->room = $roomSidOrName;
        return $this;
    }

    /**
     * Returns the grant type
     *
     * @return string type of the grant
     */
    public function getGrantKey(): string {
        return 'video';
    }

    /**
     * Returns the grant data
     *
     * @return array data of the grant
     */
    public function getPayload(): array {
        $payload = [];
        if ($this->room) {
            $payload['room'] = $this->room;
        }
        return $payload;
    }
}
