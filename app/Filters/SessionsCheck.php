<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionsCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {	
		
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
		$session = session();
		
        // Do something here if needed after the controller method executes
		if (!$session->has('userdata')) {
            // Redirect to login page
            return redirect()->to('login');
        }
    }
}
