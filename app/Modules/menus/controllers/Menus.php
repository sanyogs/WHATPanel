<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\menus\controllers;

use App\Libraries\AppLib;
use App\Models\App;
use App\Models\Client;
use App\Models\FAQS;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Menu;
use App\Models\MenuGroup;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\ThirdParty\MX\Modules;
use DateInterval;
use DateTime;
use stdClass;
use App\Modules\Layouts\Libraries\Template;
use App\Helpers\custom_name_helper;


class Menus extends WhatPanel
{
    public $temp = array();

    protected $layouts;
    protected $template;
    protected $Menu;
    protected $MenuGroup;

    function __construct()
    {	
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
        // echo 1;
        // die;
        parent::__construct();

        User::logged_in();

        $session = \Config\Services::session();

        // Connect to the database	
        $db = \Config\Database::connect();

        $this->layouts = service('layouts'); // Assuming layouts is a library
        $this->template = new Template(); // Assuming template is a library
        helper('url');

        $this->Menu = new Menu();
        $this->MenuGroup = new MenuGroup();
    }


    public function getMenuData()
    {
        $request = \Config\Services::request();
        $menuId = $request->getPost('menuId');

        $session = \Config\Services::session();

        // Connect to the database	
        $db = \Config\Database::connect();

        $builder = new Menu($db);
        $data['row'] = $builder->where('id', $menuId)->get();

        // Encode data as JSON and return
        return json_encode($data);
    }


    public function add_menu()
    {
        // echo 232;die;
        if (isset($_POST['title'])) {
            $request = \Config\Services::request();
            $data['title'] = $request->getPost('title');

            if (!empty($data['title'])) {
                Applib::is_demo();
                $session = \Config\Services::session();

                // Connect to the database
                $db = \Config\Database::connect();

                // Use Query Builder for the insertion
                $db->table('hd_menu_group')->insert($data);
                $id = $db->insertID();

                $blockData = [
                    'name' => $request->getPost('title'),
                    'param' => 'menus_' . $id,
                    'type' => 'Module',
                    'module' => 'Menus'
                ];

                $db->table('hd_blocks_modules')->insert($blockData);

                $session->setFlashdata('response_status', 'success');
                $session->setFlashdata('message', lang('hd_lang.menu_created'));
                // return redirect()->to(base_url('menus/' . $id));
                $url = site_url('menus'); // Use site_url() to generate the full URL based on the URI
                return redirect()->to($url)->with('success', 'You added menu successfully here');
            }
        } else {
            // echo 3434;die;
            // $this->template->title(lang('hd_lang.menu').' - '.config_item('company_name'));
            $data['page'] = lang('hd_lang.menu');
            return view('modules/menus/views/add_menu', $data);
        }
    }


    public function edit_menus($id)
    {
        // echo $id; die;
        $session = \Config\Services::session();

        // Connect to the database	
        $db = \Config\Database::connect();

        $builder = new Menu($db);
        // echo"<pre>";print_r($builder);die;
        $data['row']   = $builder->where('id', $id)->get();  // Produces: SELECT * FROM mytable 
        // echo"<pre>";print_r($data); die;
        echo view('modules/menus/views/modal/menu_edit', $data);
    }

    public function edit_menu()
    {
        $request = \Config\Services::request();

        $id = $request->getPost('id');
        // print_r($id); die;
        $title = $request->getPost('title');
        // echo 2;die;

        if ($title) {
            // echo 3; die;
            Applib::is_demo();
            $data['title'] = $title;
            $response['success'] = false;

            $session = Services::session();

            $db = \Config\Database::connect();

            $menu = new Menu($db);
            // print_r($menu); die;

            $res = $menu->update_menu_group($data, $id);
            //print_r($res); die;

            if ($res) {

                if ($this->db->where('param', "menus_" . $id)->where('module', 'Menus')->get('hd_blocks_modules')->num_rows() == '0') {
                    $data = array(
                        'name' => $title,
                        'param' => 'menus_' . $id,
                        'type' => 'Module',
                        'module' => 'Menus'
                    );
                    App::save_data('hd_blocks_modules', $data);
                } else {
                    $data = array(
                        'name' => $title
                    );
                    $this->db->where('param', "menus_" . $id);
                    $this->db->update('hd_blocks_modules', $data);
                }


                $this->session->set_flashdata('response_status', 'success');
                $this->session->set_flashdata('message', lang('hd_lang.operation_successfull'));
                // redirect($_SERVER['HTTP_REFERER']);
                // $url = site_url('menus/edit_menu'); // Use site_url() to generate the full URL based on the URI
                // return redirect()->to($url)->with('success', 'You edited successfully message');
            }
            $this->session->set_flashdata('response_status', 'warning');
            $this->session->set_flashdata('message', lang('hd_lang.operation_failed'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function delete_menu()
    {
        $id = $this->input->post('id');
        if ($id) {
            Applib::is_demo();
            if ($id == 1) {
                $response['success'] = false;
                $response['msg'] = 'Cannot delete Group ID = 1';
            } else {
                $delete = $this->Menu->delete_menu_group($id);
                if ($delete) {

                    $this->db->where('param', "menus_" . $id)->where('module', 'Menus')->delete('blocks_modules');
                    $block = $this->db->where('id', 'menus_' . $id)->where('module', 'Menus')->get('blocks')->row();
                    if ($block) {
                        $this->db->where('id', 'menus_' . $id)->where('module', 'Menus')->delete('blocks');
                        $this->db->where('block_id', $block->block_id)->delete('blocks_pages');
                    }

                    $del = $this->Menu->delete_menus($id);
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                }
            }
            header('Content-type: application/json');
            echo json_encode($response);
        }
    }


    /**
     * Show menu Menu
     */
    public function index()
    {	
		error_reporting(E_ALL);
        ini_set("display_errors", "1");
        // service('request')->setLocale('en');

        // Use the lang() function with the nested key
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('hd_lang.access_denied'));
        }

        $group_id = 2;
        $menu = $this->Menu->get_menu($group_id);
        // echo "<pre>";
        // print_r($menu);
        // die;
        $data['menu_ul'] = '<ul id="easymm"></ul>';
        if ($menu) {
            // echo 1; 
            foreach ($menu as $row) {
                // echo 8; die;
                $this->add_row(
                    $row->id,
                    $row->parent_id,
                    ' id="menu-' . $row->id . '" class="sortable "',
                    $this->get_label($row)
                );
            }
            $data['menu_ul'] = $this->generate_list('id="easymm"');
        }
        // echo"<pre>";
        // print_r($menu);
        // die;
        $data['group_id'] = $group_id;
        $data['group_title'] = $this->MenuGroup->get_menu_group_titles($group_id);
        $data['menu_groups'] = $this->MenuGroup->get_menu_groupss();
        // $this->template->title(lang('hd_lang.menu') . ' - ' . config_item('company_name'));
        $data['page'] = lang('hd_lang.menu');
        $data['menus'] = true;
        $data['menuList'] = $menu;
		
		// echo "<pre>";print_r($data);die;

        echo view('modules/menus/views/menus', $data);
    }

    /**
     * Show menu pages
     */
    public function menu($group_id)
    {   
        $session = \Config\Services::session();

        // Connect to the database
        $dbName = \Config\Database::connect();

        $menuModel = new Menu($dbName); // Replace 'MenuModel' with your actual model class name
        // echo"<pre>";
        // print_r($menuModel);die;
        $menu = $menuModel->get_menu($group_id);
        // echo"<pre>";
        // print_r($menu);die;
        // echo "<pre>".print_r($menu,true);die();
        $data['menu_ul'] = '<ul id="easymm"></ul>';
        if ($menu) {
            foreach ($menu as $row) {
                $this->add_row(
                    $row->id,
                    $row->parent_id,
                    ' id="menu-' . $row->id . '" class="sortable"',
                    $this->get_label($row)
                );
            }
            // echo"<pre>";
            // print_r($menu);
            // die;
            $data['menu_ul'] = $this->generate_list('id="easymm"');
            // echo"<pre>";
            // print_r($data);die;
        }

        $data['group_id'] = $group_id;
        $data['group_title'] = $menuModel->get_menu_group_title($group_id);
        $data['menu_groups'] = $menuModel->get_menu_groups();
        $data['page'] = $data['group_title'];
        $data['menus'] = true;
        $data['menuList'] = $menu;
  
        return view('modules/menus/views/menus', $data);
    }

    /**
     * Generates nested lists
     *
     * @param string $ul_attr
     * @return string
     */
    function generate_list($ul_attr = '')
    {
        return $this->ul(0, $ul_attr);
    }


    function activate($id)
    {
        return $this->db->where('id', $id)->update('menu', array('active' => $this->input->post('active')));
    }

    /**
     * Recursive method for generating nested lists
     *
     * @param int $parent
     * @param string $attr
     * @return string
     */
    function ul($parent = 0, $attr = '')
    {
        static $i = 1;
        $indent = str_repeat("\t\t", $i);
        if (isset($this->temp[$parent])) {
            if ($attr) {
                $attr = ' ' . $attr;
            }
            $html = "\n$indent";
            $html .= "<ul$attr>";
            $i++;
            foreach ($this->temp[$parent] as $row) {
                $child = $this->ul($row['id']);
                $html .= "\n\t$indent";
                $html .= '<li' . $row['li_attr'] . '>';
                $html .= $row['label'];
                if ($child) {
                    $i--;
                    $html .= $child;
                    $html .= "\n\t$indent";
                }
                $html .= '</li>';
            }
            $html .= "\n$indent</ul>";
            return $html;
        } else {
            return false;
        }
    }

    function add_row($id, $parent, $li_attr, $label)
    {
        $this->temp[$parent][] = array('id' => $id, 'li_attr' => $li_attr, 'label' => $label);
    }

    /**
     * Add menu item action
     * For use with ajax
     * Return json data
     */
     public function add()
    {
        helper('url');

        // echo 1; die;
        $data = array();
        $request = \Config\Services::request();
        
        $title = $request->getPost('title');
        $url = $request->getPost('url');
        $group_id = $request->getPost('group_id');
		 $selected_pages = $request->getPost('selected_pages');
        // print_r($title);die;
        // echo 2; die;
        if ($title) {
            Applib::is_demo();
            $data['title'] = $request->getPost('title');
            $data['url'] = $request->getPost('url');
			
			$request = \Config\Services::request();

            if (!empty($data['title'])) {
				
				foreach ($selected_pages as $page_id) {
								
                	$data['url'] = $request->getPost('url');
                	$data['active'] = 1;
                	//$data['class'] =$request->getPost('class');
                	$data['group_id'] = $request->getPost('group_id');
					$data['page'] = $page_id;

                	$session = \Config\Services::session();

					// Modify the 'default' property

					// Connect to the database
					$db = \Config\Database::connect();

					if ($db->table('hd_menu')->insert($data)) {
						$data['id'] = $db->insertID();
						$response['status'] = 1;
						$li_id = 'menu-' . $data['id'];
						$response['li'] = '<li id="' . $li_id . '" class="sortable">' . $this->get_labels($data) . '</li>';
						$response['li_id'] = $li_id;
					} else {
                    	$response['status'] = 2;
                    	$response['msg'] = 'Add menu error.';
                	}
            	}
			}
			else {
                $response['status'] = 3;
            }
        }
        return redirect()->to('menus/menu/' . $request->getPost('group_id'));
    }

    public function edit($id)
    {
        // echo $id;die;
        $data['row'] = $this->Menu->get_row($id);
        $data['menu_groups'] = $this->Menu->get_menu_groups();
		//print_r($data);die;
        // $this->load->view('menu_edit', $data);
        echo view('modules/menus/views/modal/menu_edit', $data);
    }


    public function save()
    {
        $request = \Config\Services::request();

        $menuModel = new Menu();

        $db = \Config\Database::connect();

        $title = $request->getPost('title');
        if ($title) {
            Applib::is_demo();
            $data['title'] = trim($_POST['title']);
            if (!empty($data['title'])) {
                $data['id'] = $request->getPost('menu_id');
                $data['url'] = $request->getPost('url');

                $item_moved = false;
                $group_id = $request->getPost('group_id');
                if ($group_id) {
                    $group_id = $request->getPost('group_id');
                    $old_group_id = $request->getPost('old_group_id');

                    //if group changed
                    if ($group_id != $old_group_id) {
                        $data['group_id'] = $group_id;
                        $data['position'] = $menuModel->getLastPosition($group_id);
                        $item_moved = true;
                    }
                }

                if ($db->table('hd_menu')->where('id', $data['id'])->update($data)) {
                    if ($item_moved) {
                        //move sub items
                        $ids = $menuModel->getDescendants($data['id']);
                        if (!empty($ids)) {
                            $update_sub = $menuModel->whereIn('id', $ids)->set('group_id', $group_id)->update();
                        }
                        $response['status'] = 4;
                    } else {
                        $response['status'] = 1;
                        $d['title'] = $data['title'];
                        $d['url'] = $data['url'];
                        //                        $d['klass'] = $data['class']; //klass instead of class because of an error in js
                        $response['menu'] = $d;
                    }
                } else {
                    $response['status'] = 2;
                    $response['msg'] = 'Edit menu item error.';
                }
            } else {
                $response['status'] = 3;
            }
            // header('Content-type: application/json');
            // echo json_encode($response);
            return redirect()->to('menus/menu/' . $request->getPost('old_group_id'));
        }
    }

    public function delete($id = null)
    {
        $request = \Config\Services::request();

        $menuModel = new Menu();

        $db = \Config\Database::connect();

        if ($request->getPost()) {
            // Applib::is_demo();
            $id = $request->getPost('menu_id_delete');

            $ids = $menuModel->getDescendants($id);

            if (!empty($ids)) {
                $ids = implode(', ', $ids);
                $id = "$id, $ids";
            }

            $menu_detail = $db->table('hd_menu')->where('id', $id)->get()->getRow();

            // $resp = $db->table('hd_menu_group')->where('id', $menu_detail->group_id)->delete();

            $resp1 = $db->table('hd_menu')->where('id', $id)->delete();

            if ($resp1) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
            }

            // header('Content-type: application/json');
            // echo json_encode($response);

            return redirect()->to('menus/menu/' . $request->getPost('group_id'));
        } else {
            // echo $id;die;
            $data['id'] = $id;
            $data['row'] = $menuModel->get_row($id);

            // echo "<pre>";print_r($data);die;
            echo view('modules/menus/views/modal/menu_delete', $data);
        }
    }

    public function edit_menu_group($id = null)
    {
        $request = \Config\Services::request();

        $menuModel = new Menu();

        $menuGroupModel = new MenuGroup();

        $db = \Config\Database::connect();

        if ($request->getPost()) {
            $title = $request->getPost('menu_group_title');

            $id = $request->getPost('menu_group_id');

            $resp = $db->table('hd_menu_group')->where('id', $id)->update(array('title' => $title));

            return redirect()->to('menus/menu/' . $id);
        }
        else {
            $data['id'] = $id;
            $data['row'] = $menuGroupModel->get_row($id);
            // echo "<pre>";print_r($data);die;
            echo view('modules/menus/views/modal/menu_group_edit', $data);
        }
    }

    public function delete_menu_group($id = null)
    {
        $request = \Config\Services::request();

        $menuModel = new Menu();

        $menuGroupModel = new MenuGroup();

        $db = \Config\Database::connect();

        if ($request->getPost()) {
            $id = $request->getPost('menu_group_id');

            $resp = $db->table('hd_menu')->where('group_id', $id)->delete();

            $resp1 = $db->table('hd_menu_group')->where('id', $id)->delete();

            return redirect()->to('menus');
        }
        else {
            $data['id'] = $id;
            $data['row'] = $menuGroupModel->get_row($id);
            // echo "<pre>";print_r($data);die;
            echo view('modules/menus/views/modal/menu_group_delete', $data);
        }
    }

    /**
     * new save position method
     */
    public function save_position()
    {
        // echo 1; die;
        $request = \Config\Services::request();

        $menu = $request->getPost('menu');
        // print_r($menu);die;
        if (!empty($menu)) {
            //adodb_pr($menu);
            $menu = $request->getPost('menu');
            // print_r($menu);die;
            foreach ($menu as $k => $v) {
                if ($v == 'null') {
                    $menu2[0][] = $k;
                } else {
                    $menu2[$v][] = $k;
                }
            }
            $success = 0;
            if (!empty($menu2)) {
                // echo 2;die;
                foreach ($menu2 as $k => $v) {
                    $i = 1;
                    foreach ($v as $v2) {
                        $data['parent_id'] = $k;
                        $data['position'] = $i;
                        if ($this->db->update('menu', $data, 'id' . ' = ' . $v2)) {
                            $success++;
                        }
                        $i++;
                    }
                }
            }
        }

        // $this->session->set_flashdata('response_status', 'success');
        // $this->session->set_flashdata('message', lang('hd_lang.item_added_successfully'));
        // redirect($_SERVER['HTTP_REFERER']);
        return redirect()->to('menus');
    }


    public function old_save_position()
    {
        if (isset($_POST['easymm'])) {
            $easymm = $_POST['easymm'];
            $this->update_position(0, $easymm);
        }
    }

    private function update_position($parent, $children)
    {
        $i = 1;
        foreach ($children as $k => $v) {
            $id = (int) $children[$k]['id'];
            $data[MENU_PARENT] = $parent;
            $data[MENU_POSITION] = $i;
            $this->db->update(MENU_TABLE, $data, MENU_ID . ' = ' . $id);
            if (isset($children[$k]['children'][0])) {
                $this->update_position($id, $children[$k]['children']);
            }
            $i++;
        }
    }

    /**
     * Get label for list item in menu Menu
     * this is the content inside each <li>
     *
     * @param array $row
     * @return string
     */


    public function get_label($row = null)
    {
        // echo 1; die;
        $label = '<div class="ns-row">' .
            '<div class="ns-title">' . $row->title . '</div>' .
            '<div class="ns-url">' . $row->url . '</div>' .
            '<div class="actions">' .
            '<a class="edit-menu Update" title="Edit" onclick="openEditModal(' . $row->id . ')" data-menu-id="' . $row->id . '">' .
            '<span class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></span>' .
            '</a>' .
            '<a class="delete-menu" title="Delete" onclick="openDeleteModal(' . $row->id . ')" data-menu-id="' . $row->id . '">' .
            '<span class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>' .
            '</a>' .
            '<a data-rel="tooltip" data-original-title="' . ($row->active == 1 ? lang('hd_lang.deactivate') : lang('hd_lang.activate')) . '" class="activate-item btn btn-xs btn-' . ($row->active == 0 ? 'default' : 'success') . '" href="#" data-href="' . base_url() . 'menus/activate/' . $row->id . '"><i class="fa fa-power-off"></i></a>' .
            '</a>' .
            '<input type="hidden" name="menu_id" value="' . $row->id . '">' .
            '</div>' .
            '</div>';
        return $label;
    }



    public function get_labels($row)
    {
        // echo 1; die;
        $label = '<div class="ns-row">' .
            '<div class="ns-title">' . $row['title'] . '</div>' .
            '<div class="ns-url">' . $row['url'] . '</div>' .
            '<div class="actions">' .
            '<a href="#" class="edit-menu" title="Edit">' .
            '<span class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i></span>' .
            '</a>' .
            '<a href="#" class="delete-menu" title="Delete">' .
            '<span class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>' .
            '</a>' .
            '<a data-rel="tooltip" data-original-title="' . ($row['active'] == 1 ? lang('hd_lang.deactivate') : lang('hd_lang.activate')) . '" class="activate-item btn btn-xs btn-' . ($row['active'] == 0 ? 'default' : 'success') . '" href="#" data-href="' . base_url() . 'menus/activate/' . $row['active'] . '"><i class="fa fa-power-off"></i></a>' .
            '<input type="hidden" name="menu_id" value="' . $row['id'] . '">' .
            '</div>' .
            '</div>';
        return $label;
    }



    public function menus_block($group_id)
    {
        $helper = new custom_name_helper();
        $db = \Config\Database::connect(); // Get database connection
        $object = new \stdClass();
        $object->id = $group_id;
        $main_menu = [];

        $menu = $db->table('hd_menu')->select('*')->where('group_id', $group_id)->orderBy('position', 'DESC')->where('active', 1)->get()->getResult();

        foreach ($menu as $item) {
            if ($item->parent_id == 0) {
                $main_menu[] = $item;
            }
        }

        foreach ($main_menu as $main_item) {
            $parent_menu = [];
            foreach ($menu as $menu_item) {
                if ($menu_item->parent_id == $main_item->id) {
                    $parent_menu[] = $menu_item;
                }
            }
            $main_item->parent_menu = $parent_menu;
        }

        foreach ($main_menu as $main_item) {
            foreach ($main_item->parent_menu as $parent_menu_item) {
                $parent_submenu = [];
                foreach ($menu as $submenu_item) {
                    if ($parent_menu_item->id == $submenu_item->parent_id) {
                        $parent_submenu[] = $submenu_item;
                    }
                }
                $parent_menu_item->parent_submenu = array_unique($parent_submenu, SORT_REGULAR);
            }
        }

        $object->main_menu = $main_menu;

        $data['menu'] = $object;
        //echo"<pre>";print_r($data);die;
        echo view($helper->getconfig_item('active_theme') . '/views/blocks/menu_block', $data);
    }

    function getMenuIdEdit()
    {
        $request = \Config\Services::request();

        $db = \Config\Database::connect();

        // $menu_detail = $db->table('hd_menu')->where('id', $request->getPost('menuId'))->get()->getRow();
        $menuDetail = $db->table('hd_menu')
            ->select('hd_menu.*, hd_menu_group.title as menu_group_title, hd_menu_group.id as menu_group_id')
            ->where('hd_menu.id', $request->getPost('menuId'))
            ->join('hd_menu_group', 'hd_menu.group_id = hd_menu_group.id')
            ->get()
            ->getRow();


        echo json_encode($menuDetail);
    }

    function getMenuIdDelete()
    {
        $request = \Config\Services::request();

        $db = \Config\Database::connect();

        // $menu_detail = $db->table('hd_menu')->where('id', $request->getPost('menuId'))->get()->getRow();
        $menuDetail = $db->table('hd_menu')
            ->select('hd_menu.*, hd_menu_group.title as menu_group_title, hd_menu_group.id as menu_group_id')
            ->where('hd_menu.id', $request->getPost('menuId'))
            ->join('hd_menu_group', 'hd_menu.group_id = hd_menu_group.id')
            ->get()
            ->getRow();


        echo json_encode($menuDetail);
    }
	
    public function selected_page()
    {
        $request = \Config\Services::request();
        $response = \Config\Services::response();
        
        if ($request->getMethod() === 'post') {
            $selected_pages = $request->getPost('selected_pages');
       
            // Check if selected_pages is an array
            if (is_array($selected_pages)) {
                $db = \Config\Database::connect();
                $builder = $db->table('hd_menu');
    
                // Insert or update data in hd_menu table
                foreach ($selected_pages as $page_id) {
                    // Ensure you use the correct column name and value
                    $builder->where('page', $page_id)->update(['group_id' => 1]);
                }
    
                return $response->setJSON(['status' => 'success', 'message' => 'Pages saved successfully']);
            } else {
                return $response->setJSON(['status' => 'error', 'message' => 'No pages selected']);
            }
        }
    
        return $response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
    }
    
    

}
