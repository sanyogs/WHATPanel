<?php
 /*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace app\Modules\knowledge\Controllers;

use App\Models\KB;
use App\Models\User;
use App\ThirdParty\MX\WhatPanel;
use App\Modules\Layouts\Libraries\Template;
use App\Helpers\custom_name_helper;


class Knowledge extends WhatPanel
{

  protected $template;

  public function __construct()
  {

    //parent::__construct();
    User::logged_in();

    $custom_helper = new custom_name_helper;

    $this->template = new Template();

    //$this->load->module('layouts');
    //$this->load->model('KB');
    //$this->load->library(array('template')); 
    $this->template->set_theme($custom_helper->getconfig_item('active_theme'));
    $this->template->set_partial('header', 'sections/header');
    $this->template->set_partial('footer', 'sections/footer');
  }


  function index()
  {
    $custom_helper = new custom_name_helper;

    $this->template->title(lang('hd_lang.knowledgebase') . ' - ' . $custom_helper->getconfig_item('company_name'));
    $this->template->set_metadata('description', $custom_helper->getconfig_item('site_desc'));
    $data['page'] = lang('hd_lang.knowledgebase');
    $articles = KB::pages();
    $data['articles'] = array();
    $data['categories'] = KB::categories();
    $data['popular'] = KB::popular();
    $data['latest'] = KB::latest();

    if (count($articles) > 0) {
      foreach ($articles as $article) {
        $cat = str_replace(' ', '_', $article->cat_name);
        if (isset($data['articles'][$cat])) {
          $data['articles'][$cat][] = $article;
        } else {
          $data['articles'][$cat] = array();
          $data['articles'][$cat][] = $article;
        }
      }
    }

    // $this->template->set_layout('main')->build('pages/knowledge', isset($data) ? $data : NULL);
    echo view('themes/original/views/pages/knowledge', $data);
  }



  function article($slug)
  {
    $article = KB::page($slug);
    $this->template->title($article->title . ' | ' . config_item('company_name'));
    $this->template->set_metadata('description', substr(substr($article->body, 0, 140), 0, strrpos(substr($article->body, 0, 140), ' ')));
    $this->template->set_breadcrumb($article->title, base_url() . $slug);
    $data['page'] = $article->title;
    $data['article'] = $article;
    $data['categories'] = KB::categories();
    $data['latest'] = KB::latest();
    $this->template->set_layout('main')->build('pages/article', isset($data) ? $data : NULL);
  }



  function search()
  {
    $searchText = $this->input->post('search');
    $result = KB::search($searchText);
    foreach ($result as $res) {
      $search_arr[] = array("slug" => $res->slug, "title" => $res->title);
    }
    $this->output->set_content_type('application/json')->set_output(json_encode($search_arr));
  }

  function category($name)
  {
    $name = str_replace("_", " ", $name);
    $this->template->title($name);
    $this->template->set_metadata('description', config_item('site_desc'));
    $this->template->set_breadcrumb(ucwords($name), base_url() . $name);
    $data['page'] = $name;
    $data['articles'] = KB::category($name);
    $data['categories'] = KB::categories();
    $data['latest'] = KB::latest();
    $this->template->set_layout('main')->build('pages/articles', isset($data) ? $data : NULL);
  }
}
