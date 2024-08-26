<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Controllers;

use CodeIgniter\Controller;

class TestController extends Controller
{
    public function index()
    {
        // Your index method logic here
        echo "This is the index page";
    }

    public function article($id = null)
    {
        // Your article method logic here
        echo "Viewing article with ID: " . $id;
    }
}
