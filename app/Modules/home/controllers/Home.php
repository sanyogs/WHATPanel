<?php
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace App\Modules\home\controllers;

use App\Models\FAQS;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use Config\Database;
use App\Helpers\custom_name_helper;
use App\Modules\Layouts\Libraries\Template;

use App\Models\Posts;
use App\Models\Page;


class Home extends WhatPanel
{

  protected $db_con, $template;

  function __construct()
  {
    $this->db_con = Database::connect();
  }


  public function index()
  {
    $helper = new custom_name_helper();
    $db = \Config\Database::connect();
    $data['slider'] = $db->table('hd_sliders')->get()->getRow();
    $data['content'] = view('custom/pages/home');
    return view($helper->getconfig_item('active_theme') . '/pages/home', $data);
  }

  public function pricing_details($cat_id = null, $slug = null)
  {
    $helper = new custom_name_helper();

    $model = new Page();

    $db = \Config\Database::connect();

    $services = $db->table('hd_items_saved')
      ->select('hd_items_saved.*, hd_item_pricing.*, hd_categories.id AS category_id, hd_categories.cat_name AS category_name')
      ->join('hd_item_pricing', 'hd_item_pricing.item_id = hd_items_saved.item_id', 'left')
      ->join('hd_categories', 'hd_categories.id = hd_item_pricing.category', 'left')
      ->where('hd_item_pricing.category', $cat_id)
      ->get()
      ->getResult();

    // echo "<pre>";print_r($services);die;

    $data['cat_id'] = $cat_id;

    $data['view_no'] = $db->table('hd_categories')->select('pricing_table')->where('id', $cat_id)->get()->getRow()->pricing_table;

    $data['services'] = $services;

    $data['content'] = $model->get_by_slug($slug);

    return view($helper->getconfig_item('active_theme') . '/pages/pricing', $data);
  }
}
