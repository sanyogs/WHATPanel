<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\Pages\Controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Client;
use App\Models\FAQS;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Order;
use App\Models\Page;
use App\Models\Payment;
use App\Models\User;
use App\Modules\Layouts\Libraries\Template;
use App\ThirdParty\MX\WhatPanel;
use App\Models\Menu;
use App\ThirdParty\MX\Modules;
use DateInterval;
use DateTime;
use Config\Services;
use App\Models\Categories;
use App\Helpers\custom_name_helper;

class Pages extends WhatPanel
{
  //echo 132;die;
  //protected $Page; // Declare the property

  protected $load;
  protected $db;
  protected $layouts;
  protected $template;
  protected $validation;
  protected $PageModel;
  protected $Menu;

  public function __construct()
  {
    error_reporting(E_ALL);
    ini_set("display_errors", "1");
    // parent::__construct();

    $session = Services::session();

    // Modify the 'default' property	

    $this->db = \Config\Database::connect();

    // Load the necessary services and helpers here
    helper(['form', 'url']); // Add more helpers as needed

    // Check if the user is logged in
    $userModel = new User(); // Assuming you have a User model
    if (!$userModel->is_logged_in()) {
      // Handle not logged in user, redirect or show an error
    }

    // Load layouts module
    //$this->layouts = service('layouts');

    // Load other necessary services and libraries
    $this->template = service('template');
    // Load the necessary libraries
    //$this->load = Services::load();
    // $template = new Template();

    $this->validation = Services::validation();

    $this->PageModel = new Page();
    // echo "<pre>";
    // print_r($this->PageModel);
    // die;
    $this->initTemplate(); // Initialize the template
    $this->Menu = new Menu(); // Adjust this line based on your model loading method
  }

  protected function initTemplate()
  {
    $this->template = service('template');
    // You might need to load necessary views or do other setup here
  }

  function index()
  {
    if (User::is_client()) {
      AppLib::go_to('clients', 'error', lang('hd_lang.access_denied'));
    }
    $PageModel = new Page();
    $helper = new custom_name_helper();
    $template = new Template();
    $data['pages'] = $PageModel->get();
    $template->title(lang('hd_lang.page') . ' - ' . $helper->getconfig_item('company_name'));
    $data['page'] = lang('hd_lang.pages');
    $data['datatables'] = true;
	$request = \Config\Services::request();
	  
	// Pagination Configuration
	  $page = $request->getGet('page') ? $request->getGet('page') : 1;

	  $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;

	  // Search Filter
	  $search = $request->getGet('search');
	  $data['search'] = $search;

	  $query = $PageModel->listItems([], $search, $perPage, $page);

	  // Get items for the current page
	  $data['pages'] = array_map(function($item) {
		  return (object) $item;
	  }, $query['items']);

	  $data['pager'] = $query['pager'];

	  $data['message'] = $query['message'];

	  $data['perPage'] = $perPage;

    // $language = Services::language();
    // $language->load('hd', 'en');

    //echo "<pre>";print_r($data);die;

    echo view('modules/pages/index', $data);
  }

  public function add()
  {
    $helper = new custom_name_helper();
    $template = new Template();
    $data['menu_groups'] = $this->Menu->get_menu_groups();
    $template->title(lang('page') . ' - ' . $helper->getconfig_item('company_name'));
    $data['page'] = lang('page');
    $data['pages'] = true;
    $data['editor'] = true;
    $data['sidebar_right'] = true;
    $data['sidebar_left'] = true;
    // echo"<pre>";print_r($data);die;
    // return view('App\Modules\Pages\Views\add');
    echo view('modules/pages/add', $data);
  }

  function append_page()
  {	
	$db = \Config\Database::connect();
  	$filePath = APPPATH."/Config/Routes.php";
		
		$file = fopen($filePath, "r") or die("Unable to read file!");

		$updatedContent = '';
		$foundRouteGroup = false;

		while (!feof($file)) {
			$line = fgets($file);

			// Check if the line contains the target route group
			if (strpos($line, "\$routes->group('pages', ['filter' => 'sessionsCheck','namespace' => 'App\Modules\pages\controllers'], function (\$subroutes) {") !== false) {
				$foundRouteGroup = true;
				$updatedContent .= $line; // Add the route group line

				// Append your code
				$result = $db->table('hd_menu')->select('hd_menu.*, hd_posts.*')->join('hd_posts', 'hd_posts.id = hd_menu.page')->get()->getResult();

				foreach ($result as $row) {
					$slug = $row->slug;
					$updatedContent .= "\t\$subroutes->get('$slug', 'Pages::page/$slug');\n";
				}
			} else {
				$updatedContent .= $line;
			}
		}
		// Close the file after reading
		fclose($file);

		// Write the updated content back to the file
		file_put_contents($filePath, $updatedContent);
  }
	
	
  public function edit($id = NULL)
  {
    error_reporting(E_ALL);
    ini_set("display_errors", "1");
    // echo 90;die;
    if (User::is_client()) {
      //Applib::go_to('clients', 'error', lang('hd_lang.access_denied'));
    }
    $modal = new Page();
    $request = \Config\Services::request();
    $helper = new custom_name_helper();
    $db = \Config\Database::connect();

    $mode = null;
    if ($id) {
      $data['content'] = $modal->get($id);
      $oldSlug = $data['content'][0]->slug;
      count($data['content']) || $data['errors'][] = 'page could not be found';
      $data['page_title'] = lang('hd_lang.edit');
      $data['mode'] = 'edit';
      $mode = 'edit';
    } else {
      $this->_unique_slug();
      $data['content'][] = $modal->get_new();
      $data['item_id'] = 0;
      $data['page_title'] = lang('hd_lang.add');
      $data['mode'] = 'add';
      $mode = 'add';
      //print_r($request->getPost());die;
    }

    if ($request->getPost()) {
      Applib::is_demo();
      $rules = $modal->rules();
      $validation = \Config\Services::validation();
      $validation->setRules($rules);

      if ($validation->run($request->getPost()) === TRUE) {
        if (!$id && $db->table('hd_posts')->where('slug', $request->getPost('slug'))->get()->getNumRows() > 0) {
          session()->setFlashdata('response_status', 'warning');
          session()->setFlashdata('message', lang('hd_lang.path_exists'));
          return redirect()->back();
        }
      }
      $pageArray = array(
        'title',
        'slug',
        'status',
        'sidebar_right',
        'sidebar_left',
        'meta_title',
        'meta_desc',
        //'knowledge',
       // 'faq',
        'menu',
        //'faq_id',
        //'knowledge_id',
        //'video'
      );
      $data = $modal->array_from_post($pageArray);

      if ($id == null) {
        array_push($pageArray, 'user_id', session()->get('user_id'));
      }

      $data['sidebar_right'] = ($data['sidebar_right'] == 'on') ? 1 : 0;
      $data['sidebar_left'] = ($data['sidebar_left'] == 'on') ? 1 : 0;
      $data['status'] = ($data['status'] == 'on') ? 1 : 0;
     // $data['knowledge'] = ($data['knowledge'] == 'on') ? 1 : 0;
      //$data['faq'] = ($data['faq'] == 'on') ? 1 : 0;
      $data['meta_title'] = ($data['meta_title'] == '') ? $data['title'] : $data['meta_title'];
      $data['meta_desc'] = ($data['meta_desc'] == '') ? $helper->getconfig_item('site_desc') : $data['meta_desc'];
      $data['post_type'] = 'page';
      $data['category_id'] = '0';
      //$data['body'] = $db->escapeString($request->getPost('body'));
      $data['body'] = $request->getPost('body');

	 $query = $db->table('hd_menu')->where('title', $request->getPost('title'))->get()->getRow();
		 
      if ($id == '' || $query != $request->getPost('title')) { 
        if ($db->table('hd_posts')->insert($data)) { 
			$post_id = $db->insertID();
          if ($request->getPost('slug') != 'home') { 
            $menu = array();
            if ($id == null) { 
              $menu['title'] = $request->getPost('title');
              $menu['url'] = $request->getPost('slug');
              $menu['group_id'] = $request->getPost('menu');
              $menu['page'] = $post_id;
              $menu['active'] = 1;
              $db->table('hd_menu')->insert($menu);
			 	$this->append_page();
            }
            if ($id && $request->getPost('menu') > 0) { echo 5;die;
              $menu['title'] = $request->getPost('title');
              $menu['url'] = $request->getPost('slug');
              $menu['group_id'] = $request->getPost('menu');
              $menu['page'] = $id;
              $menu['active'] = 1;
              if ($db->table('hd_menu')->where('page', $id)->get()->getNumRows() == '0') {
                $db->table('hd_menu')->insert($menu);
              } else {
                $db->table('hd_menu')->where('page', $id)->update($menu);
              }
            }
          }
          if ($id && $request->getPost('menu') == 0) { 
            // echo $id;die;
            $db->table('hd_menu')->where('page', $id)->delete();
          }
        }
      } else { 
        if ($request->getPost('slug') != 'home') {
          $db->table('hd_posts')->where('id', $id)->update($data);
        }
      }

      //session()->setFlashdata('message', lang('hd_lang.saved', 'hd_lang.saved'));
      return redirect()->to('pages');
    }
    $menu_modal = new Menu();
    $data['menu_groups'] = $menu_modal->get_menu_groups();
    $template = new Template();
    $template->title(lang('hd_lang.page') . ' - ' . $helper->getconfig_item('company_name'));
    $data['page'] = lang('hd_lang.page');
    $data['pages'] = true;
    $data['editor'] = true;
    $data['sidebar_right'] = true;
    $data['sidebar_left'] = true;
    //echo"<pre>";print_r($data['content']);die;
    echo view('modules/pages/edit', ['data' => isset($data) ? $data : NULL], ['layout' => 'users']);
  }

  public function store()
  {
    $request = \Config\Services::request();

    // Create operation - store a new product in the database
    $model = new Page();
    $data = [
      'title' => $request->getPost('title'),
      'slug' => $request->getPost('slug'),
      'body' => $request->getPost('body'),
      'meta_title' => $request->getPost('meta_title'),
      'meta_desc' => $request->getPost('meta_desc'),
      'status' => $request->getPost('status') == 'on' ? 1 : 0, // Convert checkbox value to 1 or 0
      'sidebar_right' => $request->getPost('sidebar_right') == 'on' ? 1 : 0,
      'sidebar_left' => $request->getPost('sidebar_left') == 'on' ? 1 : 0,
      'menu' => $request->getPost('menu'),
      'faq' => $request->getPost('faq') == 'on' ? 1 : 0,
      'faq_id' => $request->getPost('faq_id'),
      'knowledge' => $request->getPost('knowledge') == 'on' ? 1 : 0,
      'knowledge_id' => $request->getPost('knowledge_id'),
      'video' => $request->getPost('video'),
      'created' => date('Y-m-d H:i:s', time()),
      'post_type' => 'page'
    ];
    $this->db->table('hd_posts')->insert($data);
    // echo $this->db->insertID();
    // die;
    // echo "<pre>";
    // print_r($this->PageModel);
    // die;

    // Redirect to the product list page
    // return view('App\Modules\Pages\Views\add');
    // echo view('modules/pages/add');
    $data['pages'] = $model->get();
    $data['page'] = lang('hd_lang.pages');
    $data['datatables'] = true;
    echo view('modules/pages/index', $data);
  }

  public function update($id)
  {
    $db = \Config\Database::connect();

    $request = \Config\Services::request();
    // Fetch the page record from the database based on the given ID
    $model = new Page();
    $page = $model->find($id);
    // Check if the page exists
    if (!$page) {
      // Handle the case where the page is not found, for example, redirect to an error page
      return redirect()->to('/error');
    }

    // Update the page data
    $data = [
      'title' => $request->getPost('title'),
      'slug' => $request->getPost('slug'),
      'body' => $request->getPost('body'),
      'meta_title' => $request->getPost('meta_title'),
      'meta_desc' => $request->getPost('meta_desc'),
      'status' => $request->getPost('status') == 'on' ? 1 : 0,
      'sidebar_right' => $request->getPost('sidebar_right') == 'on' ? 1 : 0,
      'sidebar_left' => $request->getPost('sidebar_left') == 'on' ? 1 : 0,
      'menu' => $request->getPost('menu'),
      'faq' => $request->getPost('faq') == 'on' ? 1 : 0,
      'faq_id' => $request->getPost('faq_id'),
      'knowledge' => $request->getPost('knowledge') == 'on' ? 1 : 0,
      'knowledge_id' => $request->getPost('knowledge_id'),
      'video' => $request->getPost('video'),
      'created' => $page['created'],
      'post_type' => 'page'
    ];

    $model->update($id, $data);
    // Set flash message
    session()->setFlashdata('success', 'Page updated successfully!');
    // Redirect to the product list page or wherever you want after the update

    $data['pages'] = $this->PageModel->get()->getResult();
    $data['page'] = lang('hd_lang.pages');
    $data['datatables'] = true;
    //return view('App\Modules\Pages\Views\index', $data);
    echo view('modules/pages/index', $data);
  }

  public function page($slug = null)
  {
    $model = new Page();
    $helper = new custom_name_helper();
    $data['content'] = $model->get_by_slug($slug);
    $template = new Template();
    $template->title((empty($data['content']->meta_title)) ? $data['content']->title : $data['content']->meta_title . ' | ' . $helper->getconfig_item('site_name'));
    $template->set_metadata('description', (empty($data['content']->meta_desc)) ? $helper->getconfig_item('site_desc') : $data['content']->meta_desc);
    $template->set_breadcrumb($data['content']->title, base_url() . $slug);
    // $data['page'] = $data['content']->title;
    //$this->template->set_theme($helper->getconfig_item('active_theme'));
    //$this->template->set_partial('header', 'sections/header');
    //$this->template->set_partial('footer', 'sections/footer');
    //echo "<pre>";print_r($data);die;
    echo view($helper->getconfig_item("active_theme") . '/views/pages/page', $data);
  }

  public function delete_multi_old()
  {

    $id = $this->input->post('id');

    if (config_item('demo_mode') != "TRUE") {
      $this->Page->delete_multi($id);
      $this->session->set_flashdata('response_status', 'success');
      $this->session->set_flashdata('message', lang('hd_lang.operation_succesfull'));
      redirect($_SERVER['HTTP_REFERER']);
    } else {
      $this->session->set_flashdata('response_status', 'warning');
      $this->session->set_flashdata('message', lang('hd_lang.demo_warning'));
      redirect($_SERVER['HTTP_REFERER']);
    }
  }

  public function delete_multi()
  {
    $request = service('request');
    $id = $request->getPost('id');
    $helper = new custom_name_helper();

    if ($helper->getconfig_item('demo_mode') !== true) {
      $pageModel = Page(); // Adjust this according to your model name
      $pageModel->delete_multi($id);
      session()->setFlashdata('response_status', 'success');
      session()->setFlashdata('message', lang('hd_lang.operation_succesfull'));
    } else {
      session()->setFlashdata('response_status', 'warning');
      session()->setFlashdata('message', lang('hd_lang.demo_warning'));
    }

    return redirect()->to(site_url('pages/'));
  }

  public function delete($id)
  {
    $helper = new custom_name_helper();
    if (User::is_client()) {
      return redirect()->to('clients')->with('error', lang('hd_lang.access_denied'));
    }

    if ($helper->getconfig_item('demo_mode') !== true) {
      $pageModel = new Page(); // Adjust this according to your model name
      $pageModel->delete($id);
      session()->setFlashdata('response_status', 'success');
      session()->setFlashdata('message', lang('hd_lang.page_deleted'));
    } else {
      session()->setFlashdata('response_status', 'warning');
      session()->setFlashdata('message', lang('hd_lang.demo_warning'));
    }

    return redirect()->to(site_url('pages/'));
  }

  public function _unique_slug()
  {
    $uri = service('uri');
    $segments = $uri->getSegments();
    $id = isset($segments[3]) ? $segments[3] : null;

    $db = \Config\Database::connect();
    $builder = $db->table('posts'); // Assuming 'posts' is your table name
    $builder->where('slug', service('request')->getPost('slug'));
    $builder->where('post_type', 'page');

    if ($id) {
      $builder->where('id !=', $id);
    }

    $query = $builder->get();

    if ($query !== false) {
      $post = $query->getResult();
      if (count($post)) {
        $validation = Services::validation();
        $validation->setError('slug', 'This {field} is currently used for another page.');
        return false;
      }
    } else {
      log_message('error', 'Database query failed in _unique_slug method.');
    }

    return true;
  }
	
}
