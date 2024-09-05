<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\sliders\controllers;

use App\Helpers\custom_name_helper;
use App\Libraries\AppLib;
use App\Models\Slider;
use App\Models\App;
use App\Models\User;
use App\Models\MSliders;
use App\ThirdParty\MX\WhatPanel;
use Config\Services;
use CodeIgniter\Files\File;


class Sliders extends WhatPanel
{
    protected $helpers = ['form', 'url']; // Add any helpers you need

    protected $Slider; // Assuming your model is named Slider
    protected $db;
    protected $template;
    protected $form_validation;

    public function __construct()
    {
        parent::__construct();

        $session = Services::session();

        $this->db = \Config\Database::connect();

        // Load the required classes and models
        $this->Slider = new Slider($this->db);  // Load the model using the model function

        // $this->models = new Sliders($this->db);  // Load the model using the model function

        $this->template = Services::renderer(); // Load the template library

        $this->form_validation = Services::validation(); // Load the form validation library
    }


    public function get_sliders($status = null)
    {
        // $builder = new Slider($this->db);
        // $builder->select('hd_slider.*, hd_sliders.*, COUNT(slide_id) AS slides');
        // $builder->join('hd_sliders', 'hd_slider.slider_id = hd_sliders.slider', 'left');
        // $builder->groupBy('hd_slider.slider_id');

        $builder = $this->db->table('hd_slider');
        $builder->select('hd_slider.*, hd_sliders.*, COUNT(slide_id) AS slides');
        $builder->join('hd_sliders', 'hd_slider.slider_id = hd_sliders.slider', 'left');
        $builder->groupBy('hd_slider.slider_id');

        // echo "<pre>";
        // print_r($builder);
        // die;

        if (!is_null($status)) {
            $builder->where('status', 1);
        }

        $result = $builder->get()->getResult();
        // echo "<pre>";
        // print_r($result);
        // die;

        if (!$result) {
            $error = $this->db->error();
            log_message('error', 'Database Error: ' . $error['message']);
            return [];
        }

        return $result;
    }

    public function index($id = null)
    {	// echo 2;
        // die;
        if (User::is_client()) {
            return redirect()->to('clients/error')->with('error', lang('hd_lang.access_denied'));
        }
        $data['title'] = lang('hd_lang.sliders');
        // print_r($data['title']);
        // die;
        $data['page'] = lang('hd_lang.sliders');
        $data['datatables'] = true;
        $data['sliders'] = $this->get_sliders(); // Use the initialized 
       		
        return view('modules/sliders/views/index', $data);

        // return view('App\Modules\sliders\Views\index', $data);
    }



    function slider($id)
    {	
		$request = \Config\Services::request();
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('hd_lang.access_denied'));
        }
        // $this->template->title(lang('hd_lang.sliders'));
        $data['page'] = lang('hd_lang.sliders');
        $data['datatables'] = TRUE;
        $data['slider'] = $this->Slider->get_slides($id);
        $data['slider_id'] = $id;
		
		 // Pagination Configuration
		$page = $request->getGet('page') ? $request->getGet('page') : 1;

        $perPage = $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) ? $request->getGet('recordsPerPage', FILTER_SANITIZE_NUMBER_INT) : 10;
		
		$slider = new MSliders();

		// Search Filter
		$search = $request->getGet('search');
		$data['search'] = $search;

        $query = $slider->listItems([], $search, $perPage, $page);

        // Get items for the current page
		$data['servers'] = array_map(function($item) {
			return (object) $item;
		}, $query['items']);

        $data['pager'] = $query['pager'];

		$data['message'] = $query['message'];

		$data['perPage'] = $perPage;
        // echo "<pre>";
        // print_r($data);
        // die;
        return view('modules/sliders/views/slider', $data);
    }


    function sliders_block($id)
    {
        $data['slider'] = $this->Slider->get_slides($id);
        // $this->load->view('slider_block', $data);
        return view('App\Modules\Sliders\Views\slider_block', $data);
    }



    function add()
    {
        $request = Services::request();
        if ($request->getPost()) {
            $sliderData = [
                'name' => $request->getPost('name'),
            ];

            $session = Services::session();

            $this->db = \Config\Database::connect();

            // Use the SliderModel for database operations
            $sliderModel = new slider($this->db);
            // echo"<pre>";
            // print_r($sliderModel);die;

            if ($sliderModel->insert($sliderData)) {
                $slider_id = $sliderModel->getInsertID();

                $data = [
                    'name' => $request->getPost('name'),
                    'param' => "sliders_" . $slider_id,
                    'type' => 'Module',
                    'module' => 'Sliders'
                ];
                $this->db->table('hd_blocks_modules')->insert($data);

                AppLib::go_to('sliders', 'success', lang('hd_lang.slider_created'));

                // Inside your controller or wherever you want to redirect with a success message
                $url = site_url('sliders'); // Use site_url() to generate the full URL based on the URI
                return redirect()->to($url)->with('success', 'Your success message here');
            }
        } else {
            echo view('modules/sliders/views/modal/add_slider');
        }
    }


    function edit($id = null)
    {
        $request = service('request');

        if ($request->getPost()) {
            AppLib::is_demo();

            $session = Services::session();


            $db = \Config\Database::connect();

            $sliderModel = new Slider();

            // Update slider data
            $sliderData = ['name' => $request->getPost('name')];

            $db->table('hd_slider')->where('slider_id', $request->getPost('slider_id'))->update($sliderData);

            // Check if the record exists in hd_blocks_modules
            $blocksModulesCount = $db->table('hd_blocks_modules')
                ->where('param', "sliders_" . $request->getPost('slider_id'))
                ->where('module', 'Sliders')
                ->countAllResults();

            if ($blocksModulesCount == 0) {
                // Insert record into hd_blocks_modules
                $blocksModulesData = [
                    'name' => $request->getPost('name'),
                    'param' => "sliders_" . $request->getPost('slider_id'),
                    'type' => 'Module',
                    'module' => 'Sliders'
                ];
                $db->table('hd_blocks_modules')->insert($blocksModulesData);
            } else {
                // Update record in hd_blocks_modules
                $blocksModulesData = ['name' => $request->getPost('name')];
                $db->table('hd_blocks_modules')
                    ->where('param', "sliders_" . $request->getPost('slider_id'))
                    ->where('module', 'Sliders')
                    ->update($blocksModulesData);
            }

            $session->setFlashdata('success', lang('hd_lang.slider_updated'));
            return redirect()->to($request->getServer('HTTP_REFERER'));
            // Applib::go_to('sliders', 'success', lang('hd_lang.slider_updated'));
        } else {
            $model = new Slider();
            $data['slider'] = $model->get_slide($id);
            // echo "<pre>";print_r($data); die;
            echo view('modules/sliders/views/modal/edit_slider', $data);
        }
    }

    function delete($id = null)
    {
        error_reporting(E_ALL);
        ini_set("display_errors", "1");

        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('hd_lang.access_denied'));
        }

        $request = Services::request();

        if ($request->getPost()) {
            //Applib::is_demo();
            $id = $request->getPost('slider_id');

            $session = \Config\Services::session();

            $db = \Config\Database::connect();

            $c = $db->table('hd_sliders')->where('slider', $id)->get()->getResult();

            if ($c) {
                $db->table('hd_blocks_modules')->where('param', "sliders_" . $id)->where('module', 'Sliders')->delete();
                $block = $db->table('hd_blocks')->where('id', "sliders_" . $id)->where('module', 'Sliders')->get()->getRow();


                if ($block) {
                    $db->table('hd_blocks')->where('id', "sliders_" . $id)->where('module', 'Sliders')->delete();
                    $db->table('hd_blocks_pages')->where('block_id', $block->block_id)->delete();
                }

                $slides = $db->table('hd_sliders')->where('slider', $id)->get()->getResult();

                foreach ($slides as $slide) {
                    $fullpath = base_url() . 'uploads/images/' . $slide->image;

                    if (file_exists($fullpath)) {
                        unlink($fullpath);
                    }
                }

                $db->table('hd_sliders')->where('slider', $id)->delete();

                $db->table('hd_slider')->where('slider_id', $id)->delete();

                $session->setFlashdata('success', lang('hd_lang.slider_deleted'));
                return redirect()->to('sliders');

                // AppLib::go_to('sliders', 'success', lang('hd_lang.slider_deleted'));
            }
            else {
                $db->table('hd_sliders')->where('slider', $id)->delete();

                $db->table('hd_slider')->where('slider_id', $id)->delete();
            }
            return redirect()->to('sliders');
        } else {
            $data['slider_id'] = $id;
            echo view('modules/sliders/views/modal/delete_slider', $data);
        }
    }

    public function add_slide($id = null)
    {
        $request = \Config\Services::request();

        helper(['form', 'url']);

        $db = \Config\Database::connect();
        $data['slider_id'] = $id;

        // Load the view for displaying the form
        return view('modules/sliders/views/modal/add_slide', $data);
    }

    public function add_slide_post()
    {      
        $request = \Config\Services::request();
        helper(['form', 'url']);
        $db = \Config\Database::connect();
        $helper = new custom_name_helper();
        
        //if ($request->getPost() && $this->validate(['images' => 'uploaded[images]|max_size[images,1024]'])) {
           // $validation = \Config\Services::validation();
           // $validation->setRules([
               // 'slider' => 'required',
               // 'title' => 'required',
               // 'description' => 'required'
           // ]);

            //if (!$validation->withRequest($request)->run()) {  echo 7689;die;
                // Validation failed
               // return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            //} else { echo 9;die;
                $file = $request->getFile('images');

                $newName = $file->getRandomName();

                if ($file->isValid() && !$file->hasMoved()) {
                    $file->move(ROOTPATH . 'public/uploads', $newName);
                    $data['image'] = $newName;
                } else {
                    echo 'Error uploading file.';
                }

                $data['title'] = $request->getPost('title');
                $data['description'] = $request->getPost('description');
                $data['slider'] = $request->getPost('slider');
                $data['btname_one'] = $request->getPost('btname_one');
                $data['btname_two'] = $request->getPost('btname_two');
                $data['btn_redirect_one'] = $request->getPost('btn_redirect_one');
                $data['btn_redirect_two'] = $request->getPost('btn_redirect_two');

                // Insert data into the database
                $db->table('hd_sliders')->insert($data);

                return redirect()->to($request->getServer('HTTP_REFERER'));
           // }
        //}
    }

    public function edit_slide($id = null)
    {
        $helper = new custom_name_helper(); // Ensure this path is correct
        $request = \Config\Services::request();
        $db = \Config\Database::connect();
        if ($request->getMethod() === 'post') {
            $data = [];
            $file = $request->getFile('images');
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                
                $uploadConfig = [
                    'uploadPath' => ROOTPATH . 'public/uploads',
                    'allowedTypes' => 'gif|png|jpeg|jpg',
                    'maxSize' => $helper->getconfig_item('file_max_size'),
                    'overwrite' => false,
                ];

                $file->move($uploadConfig['uploadPath']); 

                if (!$file->isValid()) {
                    $errors = $file->getErrorString();
                    print_r($errors);die;
                    $response = [
                        'response_status' => 'error',
                        'message' => lang('hd_lang.operation_failed'),
                        'form_error' => '<span class="text-danger">' . $errors . '</span><br>',
                    ];
                    session()->setFlashdata($response);
                    return redirect()->back();
                }

                $data['image'] = $file->getName();
                
                // Remove old image if it exists
                $currentImage = $request->getPost('current_image');
                print_r($currentImage);die;
                $fullPath = ROOTPATH . 'public/uploads' . $currentImage;

                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            $data['title'] = $request->getPost('title');
            $data['description'] = $request->getPost('description');
            $slideId = $request->getPost('slide_id');
            $data['btname_one'] = $request->getPost('btname_one');
            $data['btname_two'] = $request->getPost('btname_two');
            $data['btn_redirect_one'] = $request->getPost('btn_redirect_one');
            $data['btn_redirect_two'] = $request->getPost('btn_redirect_two');

            // Update the slider data in the database
            $db->table('hd_sliders')->where('slide_id', $slideId)->update($data);

            return redirect()->to($request->getServer('HTTP_REFERER'));

        } else {
            $data['slide'] = Slider::get_slide($id);
            // echo "<pre>";print_r($data);die;
            return view('modules/sliders/views/modal/edit_slide', $data);
        }
    }

    function delete_slide($id = null)
    {
        if (User::is_client()) {
            Applib::go_to('clients', 'error', lang('hd_lang.access_denied'));
        }
        $request = Services::request();

        // echo "<pre>";print_r($request->getPost());die;
        if ($request->getPost()) {
            Applib::is_demo();
            $data = array();

            $db = \Config\Database::connect();

            $c = $db->table('hd_sliders')->where('slide_id', $request->getPost('slide_id'));

            if ($c->delete()) {
                $fullpath = base_url() . 'uploads/images/' . $request->getPost('current_image');
                if (file_exists($fullpath)) {
                    unlink($fullpath);
                }
            }

            //Applib::go_to($_SERVER['HTTP_REFERER'], 'success', lang('hd_lang.slide_deleted!'));
            $url = site_url('sliders');
            return redirect()->to($request->getServer('HTTP_REFERER'))->with('success', 'Your success message here');
        } else {
            $data['slide'] = Slider::get_slide($id);
            return view('modules/sliders/views/modal/delete_slide', $data);
        }
        // End file add
    }
}
