<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */
// Copyright 1999-2023. Plesk International GmbH.

namespace PleskX\Api;

/**
 * Internal client for Plesk XML-RPC API (via SDK).
 */
class InternalClient extends Client
{
    public function __construct()
    {
        parent::__construct('localhost', 0, 'sdk');
    }

    /**
     * Setup login to execute requests under certain user.
     *
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }
}
