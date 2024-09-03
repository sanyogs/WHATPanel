<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Config;

use CodeIgniter\Router\RouteCollection;
use Config\Database;

error_reporting(E_ALL);
        ini_set("display_errors", "1");
/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Installer::index');

$routes->get('start', 'Installer::start');

$routes->post('license', 'Installer::license');

$routes->get('installer_steps/(:any)', 'Installer::installer_steps/$1');
$routes->get('installer_steps/(:any)', 'Installer::installer_steps/$2');
$routes->get('installer_steps/(:any)', 'Installer::installer_steps/$3');
$routes->get('installer_steps/(:any)', 'Installer::installer_steps/$4');
$routes->get('fetchCpanelPackages', 'PackageController::fetchCpanelPackages');
$routes->get('cache/clear', 'CacheController::clearCache');

$routes->get('suspend', 'CronController::Suspend');
$routes->get('getdomaininformation', 'CronController::GetDomainInformation');

$routes->group('home', ['namespace' => 'App\Modules\home\controllers'], function($subroutes) { 
    $subroutes->get('', 'Home::index');
	$subroutes->get('pricing_details/(:any)', 'Home::pricing_details/$1');
});

// Your other routes here...
/* $routes->group('home', ['filter' => 'beforeSessionCheck','namespace' => 'App\Modules\home\controllers'], function($subroutes) {
    $subroutes->get('', 'Home::index');
	$subroutes->get('pricing_details/(:any)', 'Home::pricing_details/$1');
}); */

$routes->post('db_setup', 'Installer::db_setup');

$routes->post('installerr', 'Installer::install');
$routes->post('complete', 'Installer::complete');

$routes->get('done', 'Installer::done');

$routes->group('dashboard', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\Dashboard\Controllers'], function($subroutes) {
    $subroutes->get('/', 'DashboardController::index');
});

$routes->group('banktransfer', ['namespace' => 'Modules\banktransfer\controllers'], function($subroutes) {
    $subroutes->get('pay/(:any)', 'Banktransfer::pay/$1');
});

// Define the group with filter in your routes
$routes->group('login', ['namespace' => 'App\Modules\auth\controllers'], function ($subroutes) {
    $subroutes->post('loginaaaa', 'Auth::login');
	$subroutes->get('', 'Auth::login_page', ['as' => 'login.page']);
    // $subroutes->post('/logina', 'Auth::login');
});

$routes->group('logout', ['namespace' => 'App\Modules\auth\controllers'], function ($subroutes) {
    $subroutes->get('', 'Auth::logout');
});

$routes->group('hosting', ['filter' => 'sessionsCheck','namespace' => 'App\Modules\items\controllers'], function ($subroutes) {
    $subroutes->get('index/(:any)', 'Items::index/$1', ['as' => 'item.indexPage']);
    $subroutes->get('add_hosting', 'Items::add_hosting');
    $subroutes->post('add_item', 'Items::add_item');
    $subroutes->post('index/(:any)', 'Items::index/$1', ['as' => 'item.indexPage']);
    $subroutes->get('edit_hosting/(:any)', 'Items::edit_hosting/$1', ['as' => 'item.editPage']);
    $subroutes->post('edit_hosting', 'Items::edit_hosting', ['as' => 'item.editPage']);
    $subroutes->post('edit_item', 'Items::edit_item');
    $subroutes->get('delete_hosting/(:any)', 'Items::delete_hosting/$1', ['as' => 'item.deletePage']);
    $subroutes->post('delete_hosting', 'Items::delete_hosting', ['as' => 'item.deletePage']);
    $subroutes->get('duplicate_hosting/(:any)', 'Items::duplicate_hosting/$1', ['as' => 'item.duplicatePage']);
    $subroutes->post('duplicate_item', 'Items::duplicate_item', ['as' => 'item.duplicatePage']);
    $subroutes->post('delete_item', 'Items::delete_item');

    $subroutes->get('add_service', 'Items::add_service');
    $subroutes->post('add_service', 'Items::add_service');
    $subroutes->get('edit_service/(:any)', 'Items::edit_service/$1');
    $subroutes->post('edit_service', 'Items::edit_service');
    $subroutes->get('delete_service/(:any)', 'Items::delete_service/$1');
    $subroutes->post('delete_service', 'Items::delete_service');

    $subroutes->get('add_domain', 'Items::add_domain');
    $subroutes->post('add_domain', 'Items::add_domain');
    $subroutes->get('edit_domain/(:any)', 'Items::edit_domain/$1');
    $subroutes->post('edit_domain', 'Items::edit_domain');
    $subroutes->get('delete_domain/(:any)', 'Items::delete_domain/$1');

    $subroutes->post('showModal', 'Items::showModal');

    $subroutes->post('add_hosting', 'Items::add_hosting');
});

$routes->group('servers', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\servers\controllers'], function ($subroutes) {
    $subroutes->get('', 'Servers::index/$1', ['as' => 'server.indexPage']);
    $subroutes->get('add_servers', 'Servers::add_server');
    $subroutes->post('add_server', 'Servers::add_server');
    $subroutes->get('edit_server/(:any)', 'Servers::edit_server/$1');
    $subroutes->post('edit_server', 'Servers::edit_server');
    $subroutes->post('', 'Servers::index/$1', ['as' => 'server.indexPage']);
    $subroutes->get('delete_server/(:any)', 'Servers::delete_server/$1');
    $subroutes->post('delete_server', 'Servers::delete_server');

    $subroutes->get('index/(:any)', 'Servers::index/$1', ['as' => 'server.indexPageServer']);
    $subroutes->get('import/(:any)', 'Servers::import/$1', ['as' => 'server.import']);
    $subroutes->post('import/(:any)', 'Servers::import/$1', ['as' => 'server.import']);
    $subroutes->get('login/(:any)', 'Servers::login/$1', ['as' => 'server.login']);
});

$routes->group('addons', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\addons\controllers'], function ($subroutes) {
    $subroutes->get('', 'Addons::index/$1', ['as' => 'addons.indexPage']);
    $subroutes->get('add_servers', 'Addons::add_server');
    $subroutes->post('add_server', 'Addons::add_server');
    $subroutes->get('edit_server/(:any)', 'Addons::edit_server/$1');
    $subroutes->post('edit_server', 'Addons::edit_server');
    $subroutes->post('', 'Addons::index/$1', ['as' => 'addons.indexPage']);
    $subroutes->get('delete_server/(:any)', 'Addons::delete_server/$1');
    $subroutes->post('delete_server', 'Addons::delete_server');

    $subroutes->get('list_items', 'Addons::list_items');
    $subroutes->get('add', 'Addons::add');
    $subroutes->post('add/(:any)', 'Addons::add/$1');
    // echo 1;die;
});

$routes->group('promotions', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\promotions\controllers'], function ($subroutes) {
    $subroutes->get('', 'Promotions::index/$1', ['as' => 'promotions.indexPage']);
    $subroutes->get('add_promotion', 'Promotions::add_promotion');
    $subroutes->post('add_promotion', 'Promotions::add_promotion');
    $subroutes->get('edit/(:any)', 'Promotions::edit/$1');
    $subroutes->post('edit', 'Promotions::edit');
    $subroutes->post('', 'Promotions::index/$1', ['as' => 'promotions.indexPage']);
    $subroutes->get('delete/(:any)', 'Promotions::delete/$1');
    $subroutes->post('delete', 'Promotions::delete');
	$subroutes->post('validate_code', 'Promotions::validate_code');
	$subroutes->post('update_promotion_code', 'Promotions::update_promotion_code');
});

$routes->group('registrars', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\registrars\controllers'], function ($subroutes) {
    $subroutes->get('', 'Registrars::index/$1', ['as' => 'registrars.indexPage']);
    $subroutes->get('add_registrars', 'Registrars::add_registrars');
    $subroutes->post('add_registrars', 'Registrars::add_registrars');
    $subroutes->get('edit_promotion/(:any)', 'Registrars::edit_promotion/$1');
    $subroutes->post('edit_promotion', 'Registrars::edit_promotion');
    $subroutes->post('', 'Registrars::index/$1', ['as' => 'registrars.indexPage']);
    $subroutes->get('delete_promotion/(:any)', 'Registrars::delete_promotion/$1');
    $subroutes->post('delete_promotion', 'Registrars::delete_promotion');
});

$routes->group('orders', ['filter' => 'sessionsCheck','namespace' => 'App\Modules\orders\controllers'], function ($subroutes) {
    $subroutes->get('', 'Orders::index');
    $subroutes->get('/(:any)', 'Orders::index/$1', ['as' => 'orders.indexPage']);
    $subroutes->get('select_client', 'Orders::select_client');
    $subroutes->post('select_client', 'Orders::select_client');
    $subroutes->get('add_order', 'Orders::add_order');
    $subroutes->get('activate/(:any)', 'Orders::activate/$1');
    $subroutes->post('activate', 'Orders::activate');
    $subroutes->get('delete/(:any)', 'Orders::delete/$1');
    $subroutes->post('delete', 'Orders::delete');
    $subroutes->get('cancel/(:any)', 'Orders::cancel/$1');
    $subroutes->post('cancel', 'Orders::cancel');
	$subroutes->get('view/(:any)', 'Orders::view/$1');
	$subroutes->get('status/(:any)/(:any)', 'Orders::status/$1/$2');
});

$routes->group('accounts', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\accounts\controllers'], function ($subroutes) {
    $subroutes->get('', 'Accounts::index/$1', ['as' => 'accounts.indexPage']);
    $subroutes->get('/(:any)', 'Accounts::index/$1', ['as' => 'accounts.indexPage']);
    $subroutes->get('account/(:any)', 'Accounts::account/$1');
    $subroutes->get('activate/(:any)', 'Accounts::activate/$1');
    $subroutes->post('activate', 'Accounts::activate');
    $subroutes->get('cancel/(:any)', 'Accounts::cancel/$1');
    $subroutes->post('cancel', 'Accounts::cancel');
    $subroutes->get('delete/(:any)', 'Accounts::delete/$1');
    $subroutes->post('delete', 'Accounts::delete');
    $subroutes->get('suspend/(:any)', 'Accounts::suspend/$1');
    $subroutes->post('suspend', 'Accounts::suspend');
    $subroutes->get('unsuspend/(:any)', 'Accounts::unsuspend/$1');
    $subroutes->post('unsuspend', 'Accounts::unsuspend');
	$subroutes->get('manage/(:any)', 'Accounts::manage/$1');
    $subroutes->post('manage', 'Accounts::manage');
	$subroutes->get('upload','Accounts::upload');
	$subroutes->post('upload','Accounts::upload');
	$subroutes->get('view_logins/(:any)', 'Accounts::view_logins/$1');
    $subroutes->get('change_password/(:any)', 'Accounts::change_password/$1');
    $subroutes->post('change_password', 'Accounts::change_password');
    $subroutes->get('login/(:any)', 'Accounts::login/$1');
});

$routes->group('profile', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\profile\controllers'], function ($subroutes) {
    $subroutes->post('switch', 'Profile::switch');
    $subroutes->get('switch_back', 'Profile::switch_back');
    $subroutes->get('settings', 'Profile::settings');
    $subroutes->post('settings', 'Profile::settings');
    $subroutes->get('activities', 'Profile::activities');
	$subroutes->post('changeavatar', 'Profile::changeavatar');
});

$routes->group('faq', ['filter' => 'sessionsCheck', 'namespace' => 'app\Modules\faq\controllers'], function ($subroutes) {
    $subroutes->get('', 'Faq::index');
	$subroutes->get('category', 'Faq::category');
});

$routes->group('clients', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\clients\controllers'], function ($subroutes) {
    $subroutes->get('', 'Clients::index');
});

$routes->group('domains', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\domains\controllers'], function ($subroutes) {
    $subroutes->get('upload', 'Domains::upload');
    $subroutes->get('import', 'Domains::import');
    $subroutes->post('import_domains', 'Domains::import_domains');
    $subroutes->post('upload', 'Domains::upload');
    $subroutes->get('', 'Domains::index/$1', ['as' => 'domains.indexPage']);
    $subroutes->get('/(:any)', 'Domains::index/$1', ['as' => 'domains.indexPage']);
    $subroutes->post('check_availability', 'Domains::check_availability');
    $subroutes->post('check_availability_text', 'Domains::check_availability_text');
    $subroutes->get('domain/(:any)', 'Domains::domain/$1');
    $subroutes->get('manage_nameservers/(:any)', 'Domains::manage_nameservers/$1');
    $subroutes->post('edit_contact_details', 'Domains::edit_contact_details');
    $subroutes->post('resellerclub_modify_details', 'Domains::resellerclub_modify_details');
    $subroutes->post('edit_domain', 'Domains::edit_domain');
	$subroutes->get('delete/(:any)', 'Domains::delete/$1');
	$subroutes->post('delete', 'Domains::delete');
	$subroutes->get('activate/(:any)', 'Domains::activate/$1');
	$subroutes->post('activate', 'Domains::activate');
	$subroutes->get('cancel/(:any)', 'Domains::cancel/$1');
	$subroutes->post('cancel', 'Domains::cancel');
	$subroutes->get('manage/(:any)', 'Domains::manage/$1');
	$subroutes->post('manage/(:any)', 'Domains::manage/$1');
});

$routes->group('domains1', ['namespace' => 'App\Modules\domains\controllers'], function ($subroutes) {
    $subroutes->get('upload', 'Domains::upload');
    $subroutes->get('import', 'Domains::import');
    $subroutes->post('import_domains', 'Domains::import_domains');
    $subroutes->post('upload', 'Domains::upload');
    $subroutes->get('', 'Domains::index/$1', ['as' => 'domains.indexPage']);
    $subroutes->get('/(:any)', 'Domains::index/$1', ['as' => 'domains.indexPage']);
    $subroutes->post('check_availability', 'Domains::check_availability');
    $subroutes->post('check_availability_text', 'Domains::check_availability_text');
    $subroutes->get('domain/(:any)', 'Domains::domain/$1');
    $subroutes->get('manage_nameservers/(:any)', 'Domains::manage_nameservers/$1');
    $subroutes->post('edit_contact_details', 'Domains::edit_contact_details');
    $subroutes->post('resellerclub_modify_details', 'Domains::resellerclub_modify_details');
    $subroutes->post('edit_domain', 'Domains::edit_domain');
	$subroutes->get('delete/(:any)', 'Domains::delete/$1');
	$subroutes->post('delete/(:any)', 'Domains::delete/$1');
	$subroutes->get('activate/(:any)', 'Domains::activate/$1');
	$subroutes->post('activate/(:any)', 'Domains::activate/$1');
	$subroutes->get('cancel/(:any)', 'Domains::cancel/$1');
	$subroutes->post('cancel/(:any)', 'Domains::cancel/$1');
	$subroutes->get('manage/(:any)', 'Domains::manage/$1');
	$subroutes->post('manage/(:any)', 'Domains::manage/$1');
});

$routes->group('domainsss', ['namespace' => 'App\Modules\domains\controllers'], function ($subroutes) {
    $subroutes->get('', 'Domains::index/$1', ['as' => 'domains.indexPage']);
    $subroutes->get('/(:any)', 'Domains::index/$1', ['as' => 'domains.indexPage']);
    $subroutes->post('check_availability', 'Domains::check_availability');
    $subroutes->post('check_availability_text', 'Domains::check_availability_text');
    $subroutes->get('domain/(:any)', 'Domains::domain/$1');
});

$routes->group('invoices', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\invoices\controllers'], function ($subroutes) {
    $subroutes->get('', 'Invoices::index');
    $subroutes->get('/(:any)', 'Invoices::index/$1', ['as' => 'invoices.indexPage']);
    $subroutes->get('add', 'Invoices::add');
    $subroutes->post('add', 'Invoices::add', ['as' => 'invoices.add']);
    $subroutes->get('view/(:any)', 'Invoices::view/$1', ['as' => 'invoices.view']);
	$subroutes->post('view/(:any)', 'Invoices::view/$1', ['as' => 'invoices.view']);
    $subroutes->get('edit/(:any)', 'Invoices::edit/$1', ['as' => 'invoices.edit']);
    $subroutes->get('delete/(:any)', 'Invoices::delete/$1', ['as' => 'invoices.delete']);
    $subroutes->post('edit', 'Invoices::edit', ['as' => 'invoices.edit']);
    $subroutes->post('delete', 'Invoices::delete', ['as' => 'invoices.delete']);
    $subroutes->get('mark_as_paid/(:any)', 'Invoices::mark_as_paid/$1', ['as' => 'invoices.mark_as_paid']);
    $subroutes->post('mark_as_paid', 'Invoices::mark_as_paid');
    $subroutes->get('cancel/(:any)', 'Invoices::cancel/$1', ['as' => 'invoices.cancel']);
    $subroutes->post('cancel', 'Invoices::cancel');
    $subroutes->post('items/add', 'Items::add');
    $subroutes->get('items/delete/(:any)/(:any)', 'Items::delete/$1/$2');
    $subroutes->post('items/delete', 'Items::delete');
    $subroutes->get('pdf/(:any)', 'Invoices::pdf/$1');
    $subroutes->get('items/insert/(:any)', 'Items::insert/$1');
    $subroutes->post('items/insert', 'Items::insert');
    $subroutes->get('hide/(:any)', 'Invoices::hide/$1');
    $subroutes->get('show/(:any)', 'Invoices::show/$1');
    $subroutes->post('hide', 'Invoices::hide');
    $subroutes->get('send_invoice/(:any)', 'Invoices::send_invoice/$1');
    $subroutes->post('send_invoice', 'Invoices::send_invoice');
    $subroutes->get('remind/(:any)', 'Invoices::send_invoice/$1');
    $subroutes->get('transactions/(:any)', 'Invoices::transactions/$1');
    $subroutes->get('create', 'Invoices::create');
    $subroutes->get('add_funds/(:any)', 'Invoices::add_funds/$1');
    $subroutes->post('add_funds_invoice', 'Invoices::add_funds_invoice');
    $subroutes->get('pay/(:any)', 'Invoices::pay/$1');
    $subroutes->post('pay', 'Invoices::pay');
    $subroutes->get('payment_status/(:any)', 'Invoices::payment_status/$1');
	$subroutes->post('payment_status', 'Invoices::payment_status');
});

$routes->group('invoices', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\invoices\controllers'], function ($subroutes) {
	$subroutes->get('items/edit/(:any)', 'Items::edit/$1');
    $subroutes->post('items/edit/(:any)', 'Items::edit/$1');
});

$routes->group('companies', ['filter' => 'sessionsCheck','namespace' => 'App\Modules\companies\controllers'], function ($subroutes) {
    $subroutes->get('', 'Companies::index', ['as' => 'companies.indexPage']);
    $subroutes->get('/(:any)', 'Companies::index/$1', ['as' => 'companies.indexPage']);
    $subroutes->get('create', 'Companies::create');
    $subroutes->post('create', 'Companies::create');
    $subroutes->get('view/(:any)', 'Companies::view/$1');
    $subroutes->get('view/(:any)/(:any)', 'Companies::view/$1/$2');
    $subroutes->get('delete/(:any)', 'Companies::delete/$1');
    $subroutes->post('delete', 'Companies::delete');
    $subroutes->get('update/(:any)', 'Companies::update/$1');
    $subroutes->post('update', 'Companies::update');
	$subroutes->get('send_invoice/(:any)', 'Companies::send_invoice/$1');
	$subroutes->post('send_invoice/(:any)', 'Companies::send_invoice/$1');
	$subroutes->get('make_primary/(:any)', 'Companies::make_primary/$1');
	$subroutes->post('send_email', 'Companies::send_email');
	$subroutes->get('file/(:any)', 'Companies::file/$1');
	$subroutes->post('file/(:any)', 'Companies::file/$1');
});

$routes->group('contacts', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\contacts\controllers'], function ($subroutes) {
    $subroutes->get('update/(:any)', 'Contacts::update/$1');
	$subroutes->post('update', 'Contacts::update');
});

$routes->group('payments', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\payments\controllers'], function ($subroutes) {
    $subroutes->get('', 'Payments::index', ['as' => 'payments.indexPage']);
    $subroutes->get('/(:any)', 'Payments::index/$1', ['as' => 'payments.indexPage']);
    $subroutes->get('view/(:any)', 'Payments::view/$1', ['as' => 'payments.view']);
    $subroutes->get('edit/(:any)', 'Payments::edit/$1', ['as' => 'payments.edit']);
    $subroutes->post('edit', 'Payments::edit', ['as' => 'payments.edit']);
    $subroutes->get('delete/(:any)', 'Payments::delete/$1', ['as' => 'payments.delete']);
    $subroutes->post('delete', 'Payments::delete', ['as' => 'payments.delete']);
    $subroutes->get('refund/(:any)', 'Payments::refund/$1', ['as' => 'payments.refund']);
    $subroutes->post('refund', 'Payments::refund', ['as' => 'payments.refund']);
    $subroutes->get('pdf/(:any)', 'Payments::pdf/$1', ['as' => 'payments.pdf']);
});

$routes->group('tickets', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\tickets\controllers'], function ($subroutes) {
    $subroutes->get('', 'Tickets::index');
    $subroutes->get('add', 'Tickets::add');
    $subroutes->post('add/(:any)', 'Tickets::add/$1');
    $subroutes->get('view/(:any)', 'Tickets::view/$1');
    $subroutes->post('reply', 'Tickets::reply');
    $subroutes->get('edit/(:any)', 'Tickets::edit/$1');
    $subroutes->post('edit', 'Tickets::edit');
    $subroutes->get('delete/(:any)', 'Tickets::delete/$1');
    $subroutes->post('delete', 'Tickets::delete');
    $subroutes->get('archive/(:any)/(:any)', 'Tickets::archive/$1/$2');
    $subroutes->get('view_archive/(:any)', 'Tickets::index/$1');
    $subroutes->post('quick_edit', 'Tickets::quick_edit');
    $subroutes->get('status/(:any)/(:any)', 'Tickets::status/$1/$2');
});

$routes->group('sliders', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\sliders\controllers'], function ($subroutes) {
    $subroutes->get('/', 'Sliders::index', ['as' => 'slider.page']);

    $subroutes->get('add', 'Sliders::add', ['as' => 'slider.add']);
    $subroutes->post('add', 'Sliders::add');

    $subroutes->get('edit/(:num)', 'Sliders::edit/$1');
    $subroutes->post('edit', 'Sliders::edit');


    $subroutes->get('delete/(:num)', 'Sliders::delete/$1');
    $subroutes->post('delete', 'Sliders::delete');

    $subroutes->get('slider/(:num)', 'Sliders::slider/$1');

    $subroutes->get('add_slide/(:num)', 'Sliders::add_slide/$1', ['as' => 'slider.add_slide']);
    $subroutes->get('add_slide', 'Sliders::add_slide');
    $subroutes->post('add_slide_post', 'Sliders::add_slide_post');

    $subroutes->get('edit_slide/(:num)', 'Sliders::edit_slide/$1');
    $subroutes->post('edit_slide', 'Sliders::edit_slide');
    $subroutes->get('delete_slide/(:num)', 'Sliders::delete_slide/$1');
    $subroutes->post('add', 'Sliders::add');
    $subroutes->post('update', 'Sliders::edit');
    $subroutes->post('delete_slide', 'Sliders::delete_slide');
});

$routes->group('pages', ['filter' => 'sessionsCheck','namespace' => 'App\Modules\pages\controllers'], function ($subroutes) {
	$subroutes->get('help', 'Pages::page/help');
	$subroutes->get('/', 'Pages::index', ['as' => 'pages.page']);
	$subroutes->get('domain_registration', 'Pages::page/domain_registration');
	$subroutes->get('Knowledgebase', 'Pages::Knowledgebase');
    $subroutes->get('add', 'Pages::add', ['as' => 'pages.add']);
    $subroutes->post('store', 'Pages::store');
    $subroutes->get('edit/(:num)', 'Pages::edit/$1');
	$subroutes->get('edit', 'Pages::edit');
	$subroutes->post('edit', 'Pages::edit');
	$subroutes->post('edit/(:num)', 'Pages::edit/$1');
    $subroutes->post('update/(:num)', 'Pages::update/$1');
    $subroutes->get('deleted/(:num)', 'Pages::delete/$1');

});

$routes->group('blocks', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\blocks\controllers'], function ($subroutes) {
    $subroutes->get('/', 'Blocks::index', ['as' => 'blocks.page']);
    $subroutes->get('add', 'Blocks::add', ['as' => 'blocks.add']);
    $subroutes->get('add-code', 'Blocks::add_code', ['as' => 'blocks.add.code']);
    $subroutes->get('edit/(:num)', 'Blocks::edit/$1');
    $subroutes->post('edit', 'Blocks::edit');
    $subroutes->post('update/(:num)', 'Blocks::update/$1');
    $subroutes->get('delete/(:any)', 'Blocks::delete/$1');
    $subroutes->post('delete', 'Blocks::delete');
	$subroutes->get('config/(:any)/(:any)', 'Blocks::configure/$1/$2');
	$subroutes->post('config', 'Blocks::configure');
    $subroutes->post('add', 'Blocks::add', ['as' => 'pages.add']);
});

$routes->group('menus', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\menus\controllers'], function ($subroutes) {
    $subroutes->get('/', 'Menus::index', ['as' => 'menus.page']);
    $subroutes->get('menu/(:num)', 'Menus::menu/$1', ['as' => 'menus.menu']);
    $subroutes->get('add_menu', 'Menus::add_menu', ['as' => 'menus.add.menu']);
    $subroutes->post('add_menu', 'Menus::add_menu', ['as' => 'menus.update']);
    $subroutes->post('add', 'Menus::add', ['as' => 'menus.add']);
    $subroutes->post('save_position', 'Menus::save_position', ['as' => 'menus.save']);
    $subroutes->post('store', 'Menus::store');
    // $subroutes->get('edit/(:num)', 'Menus::edit_menu/$1');
    $subroutes->post('update/(:num)', 'Menus::update/$1');
    $subroutes->get('deleted/(:num)', 'Menus::delete/$1');
    $subroutes->post('getMenuIdEdit', 'Menus::getMenuIdEdit');
    $subroutes->post('getMenuIdDelete', 'Menus::getMenuIdDelete');
    $subroutes->post('save', 'Menus::save');
    $subroutes->post('delete', 'Menus::delete');
    $subroutes->get('edit/(:num)', 'Menus::edit/$1');
    $subroutes->get('delete/(:num)', 'Menus::delete/$1');
    $subroutes->get('edit_menu_group/(:num)', 'Menus::edit_menu_group/$1');
    $subroutes->post('edit_menu_group', 'Menus::edit_menu_group');
    $subroutes->get('delete_menu_group/(:num)', 'Menus::delete_menu_group/$1');
    $subroutes->post('delete_menu_group', 'Menus::delete_menu_group');
	$subroutes->post('selected_page', 'Menus::selected_page');
});

$routes->group('categories', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\categories\controllers'], function ($subroutes) {
    $subroutes->get('/', 'Categories::index', ['as' => 'categories.page']);
});

$routes->group('items', ['filter' => 'sessionsCheck','namespace' => 'App\Modules\items\controllers'], function ($subroutes) {
    $subroutes->post('save_config_items', 'Items::save_config_items');
    $subroutes->get('/', 'Items::categories', ['as' => 'items.page']);
    $subroutes->get('package/(:any)', 'Items::package/$1', ['as' => 'items.package']);
    $subroutes->get('get_config_items/(:any)', 'Items::get_config_items/$1', ['as' => 'items.get_config_items']);
    $subroutes->get('item_links/(:any)', 'Items::item_links/$1', ['as' => 'items.item_links']);
    $subroutes->get('affiliates/(:any)', 'Items::affiliates/$1', ['as' => 'items.affiliates']);
    $subroutes->post('affiliates', 'Items::affiliates', ['as' => 'items.affiliates']);
    $subroutes->post('add_addon', 'Items::add_addon', ['as' => 'add_addon.page']);
    $subroutes->get('edit_addon/(:any)', 'Items::edit_addon/$1', ['as' => 'edit_addon.edit']);
    $subroutes->post('edit_addon/(:any)', 'Items::edit_addon/$1', ['as' => 'edit_addon.post']);
    $subroutes->get('delete_addon/(:any)', 'Items::delete_item/$1');
    $subroutes->get('delete_addons/(:any)', 'Items::delete_addons/$1');
	$subroutes->get('domian_pricing/(:any)', 'Items::domian_pricing/$1');
	$subroutes->post('add_domains', 'Items::add_domains');
    $subroutes->post('edit_domains/(:any)', 'Items::edit_domains/$1');
    $subroutes->post('delete_domain/(:any)', 'Items::delete_domain/$1');
    $subroutes->post('delete_addon/(:any)', 'Items::delete_addon/$1');
    $subroutes->get('delete_domains/(:any)', 'Items::delete_domains/$1');
    $subroutes->get('categories', 'Items::categories');
});

$routes->group('plugins', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\plugins\controllers'], function ($subroutes) {
    $subroutes->get('/', 'Plugins::index', ['as' => 'plugins.page']);
    $subroutes->get('', 'Plugins::index/$1', ['as' => 'plugins.indexPage']);
    $subroutes->get('uninstall/(:any)', 'Plugins::uninstall/$1', ['as' => 'plugins.uninstall']);
    $subroutes->get('activate/(:any)', 'Plugins::activate/$1', ['as' => 'plugins.activate']);
    $subroutes->get('deactivate/(:any)', 'Plugins::deactivate/$1', ['as' => 'plugins.deactivate']);
    $subroutes->get('config/(:any)', 'Plugins::config/$1', ['as' => 'plugins.config']);
    $subroutes->post('config', 'Plugins::config', ['as' => 'plugins.config']);
});

$routes->group('settings', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\settings\controllers'], function ($subroutes) {
	$subroutes->post('fields/module', 'Fields::module');

    $subroutes->get('add_category', 'Settings::add_category');
    $subroutes->post('add_category', 'Settings::add_category');

    $subroutes->get('edit_category/(:any)', 'Settings::edit_category/$1');
    $subroutes->post('edit_category', 'Settings::edit_category');

    $subroutes->get('send_test', 'Settings::send_test');
    $subroutes->post('skin', 'Settings::skin');
    $subroutes->get('', 'Settings::index', ['as' => 'settings.index']);
    $subroutes->get('(:any)', 'Settings::index/$1', ['as' => 'settings.get']);
    $subroutes->post('update', 'Settings::update', ['as' => 'settings.update']);
    $subroutes->post('xrates', 'Settings::xrates', ['as' => 'settings.xrates']);
    $subroutes->get('sms_templates/(:any)', 'Settings::index/$1/$2', ['as' => 'settings.sub']);
    $subroutes->get('templates/(:any)', 'Settings::index/$1', ['as' => 'settings12.sub1']);
    $subroutes->post('templates/(:any)/(:any)(/(:any))?', 'Settings::index/$1/$2/$3', ['as' => 'settings12.sub1']);
    $subroutes->post('hook/(:any)/(:any)', 'Settings::hook/$1/$2', ['as' => 'hook.sub1']);
	$subroutes->get('database', 'Settings::database');
	$subroutes->get('send_test', 'Settings::send_test');
	$subroutes->post('send_test', 'Settings::send_test');
	//echo 134;die;
	$subroutes->get('translations/add/(:any)', 'Settings::translations/$1');
    $subroutes->post('translations/save', 'Settings::translations');
	$subroutes->post('translations/(:any)/(:any)', 'Settings::translations/$1/$2');
	$subroutes->post('translations/delete', 'Settings::delete_translation');
	$subroutes->get('translations/(:any)/(:any)', 'Settings::translations/$1/$2');

});

$routes->group('fields', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\settings\controllers'], function ($subroutes) {
	$subroutes->get('module', 'Fields::module');
});

$routes->group('settings1', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\settings\controllers'], function ($subroutes) {
    $subroutes->get('add_category', 'Settings::add_category');
    $subroutes->post('add_category', 'Settings::add_category');

    $subroutes->get('add_currency', 'Settings::add_currency');
    $subroutes->post('add_currency', 'Settings::add_currency');
    $subroutes->get('edit_currency/(:any)', 'Settings::edit_currency/$1');
    $subroutes->post('edit_currency', 'Settings::edit_currency');
	
	$subroutes->get('edit_dept/(:any)', 'Settings::edit_dept/$1');
	$subroutes->post('edit_dept', 'Settings::edit_dept');
	
	$subroutes->get('(:any)', 'Settings::index/$1', ['as' => 'settings.get']);
});

$routes->group('tax_rates', ['filter' => 'sessionsCheck','namespace' => 'App\Modules\invoices\controllers'], function ($subroutes) {
    $subroutes->get('', 'Tax_rates::index', ['as' => 'tax_rates.indexPage']);
    $subroutes->get('add', 'Tax_rates::add', ['as' => 'tax_rates.add']);
    $subroutes->post('add', 'Tax_rates::add', ['as' => 'tax_rates.add']);
    $subroutes->get('edit/(:any)', 'Tax_rates::edit/$1', ['as' => 'tax_rates.edit']);
    $subroutes->post('edit', 'Tax_rates::edit', ['as' => 'tax_rates.edit']);
    $subroutes->get('delete/(:any)', 'Tax_rates::delete/$1', ['as' => 'tax_rates.delete']);
    $subroutes->post('delete', 'Tax_rates::delete', ['as' => 'tax_rates.delete']);
});

$routes->group('account', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\Users\Controllers'], function ($subroutes) {
    $subroutes->get('', 'Account::index', ['as' => 'account.indexPage']);
    $subroutes->get('delete/(:any)', 'Account::delete/$1', ['as' => 'account.delete']);
    $subroutes->post('delete', 'Account::delete', ['as' => 'account.delete']);
    $subroutes->get('update/(:any)', 'Account::update/$1', ['as' => 'account.update']);
    $subroutes->post('update', 'Account::update');
    $subroutes->get('ban/(:any)', 'Account::ban/$1', ['as' => 'account.ban']);
    $subroutes->post('ban', 'Account::ban');
    $subroutes->get('auth/(:any)', 'Account::auth/$1', ['as' => 'account.auth']);
    $subroutes->post('auth', 'Account::auth');
	
});

$routes->group('auth', ['namespace' => 'App\Modules\auth\controllers'], function ($subroutes) {
    $subroutes->post('register_user', 'Auth::register_user', ['as' => 'auth.register_user']);
    $subroutes->get('register', 'Auth::register');
	$subroutes->post('register', 'Auth::register');
	$subroutes->post('change_password', 'Auth::change_password');
	$subroutes->post('change_email', 'Auth::change_email');
	$subroutes->get('forgot_password', 'Auth::forgot_password');
	$subroutes->post('forgot_password', 'Auth::forgot_password');
	//$subroutes->get('login', 'Auth::login');
});

$routes->group('reports', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\reports\controllers'], function ($subroutes) {
    $subroutes->get('', 'Reports::index', ['as' => 'reports.page']);
});

$routes->group('users', ['filter' => 'sessionsCheck','namespace' => 'App\Modules\Users\Controllers'], function ($subroutes) {
    $subroutes->get('account/delete/(:any)', 'Account::delete/$1');
	$subroutes->post('account/delete/(:any)', 'Account::delete/$1');
});

$routes->group('cart', ['filter' => 'sessionsCheck', 'namespace' => 'App\Modules\cart\controllers'], function ($subroutes) {
    $subroutes->get('', 'Cart::index');
    $subroutes->get('options/(:any)', 'Cart::options/$1');
    $subroutes->post('options', 'Cart::options');
    $subroutes->get('add_existing', 'Cart::add_existing');
    $subroutes->post('add_existing', 'Cart::add_existing');
    $subroutes->post('existing_domain', 'Cart::existing_domain');
    $subroutes->get('show_addons', 'Cart::show_addons');
    $subroutes->get('layout', 'Cart::layout');
    $subroutes->get('domain_fields', 'Cart::domain_fields');
    $subroutes->post('domain_fields', 'Cart::domain_fields');
    $subroutes->get('domain', 'Cart::domain');
    $subroutes->get('checkout', 'Cart::checkout');
    $subroutes->get('hosting_packages', 'Cart::hosting_packages');
    $subroutes->get('remove_all', 'Cart::remove_all');
    $subroutes->get('remove/(:segment)', 'Cart::remove/$1');
    $subroutes->post('add_domain', 'Cart::add_domain');
    $subroutes->post('add_domain_only', 'Cart::add_domain_only');
    $subroutes->get('domain_only', 'Cart::domain_only');
    $subroutes->get('nameservers', 'Cart::nameservers');
    $subroutes->get('default_nameservers', 'Cart::default_nameservers');
    $subroutes->post('add_nameservers', 'Cart::add_nameservers');
    $subroutes->get('shopping_cart', 'Cart::shopping_cart');
});

//This Route is for Frontend
$routes->group('carts', ['namespace' => 'App\Modules\cart\controllers'], function ($subroutes) {
    $subroutes->get('', 'Cart::index');
    $subroutes->get('options/(:any)', 'Cart::options/$1');
    $subroutes->post('options', 'Cart::options');
    $subroutes->get('add_existing', 'Cart::add_existing');
    $subroutes->post('add_existing', 'Cart::add_existing');
    $subroutes->post('existing_domain', 'Cart::existing_domain');
    $subroutes->get('show_addons', 'Cart::show_addons');
    $subroutes->get('layout', 'Cart::layout');
    $subroutes->get('domain_fields', 'Cart::domain_fields');
    $subroutes->post('domain_fields', 'Cart::domain_fields');
    $subroutes->get('domain', 'Cart::domain');
    $subroutes->get('checkout', 'Cart::checkout');
    $subroutes->get('hosting_packages', 'Cart::hosting_packages');
    $subroutes->get('remove_all', 'Cart::remove_all');
    $subroutes->get('remove/(:segment)', 'Cart::remove/$1');
    $subroutes->post('add_domain', 'Cart::add_domain');
    $subroutes->post('add_domain_only', 'Cart::add_domain_only');
    $subroutes->get('domain_only', 'Cart::domain_only');
    $subroutes->get('nameservers', 'Cart::nameservers');
    $subroutes->get('default_nameservers', 'Cart::default_nameservers');
    $subroutes->post('add_nameservers', 'Cart::add_nameservers');
    $subroutes->get('shopping_cart', 'Cart::shopping_cart');
});

$routes->group('fomailer', ['filter' => 'sessionsCheck','namespace' => 'Modules\fomailer\controllers'], function ($subroutes) {
    $subroutes->get('', 'Fomailer::send_email');
});

$routes->group('plesk', ['filter' => 'sessionsCheck','namespace' => 'Modules\plesk\controllers'], function ($subroutes) {
    $subroutes->get('getPackageList', 'Plesk_WHMCS::getPackageList');
});

//API for ResellerClub
$routes->post('transfer', 'APIController::transfer');
$routes->post('renew', 'APIController::renew');
$routes->post('check_availability', 'APIController::checkavailability');
$routes->get('getdomaininformation', 'APIController::resellerclub_GetDomainInformation');
$routes->get('getDomainName', 'APIController::resellerclub_getDomainName');
$routes->get('resellerclub_SendCommand', 'APIController::resellerclub_SendCommand');
$routes->get('resellerclub_getOrderID', 'APIController::resellerclub_getOrderID');
$routes->get('resellerclub_getnameservers', 'APIController::resellerclub_GetCustomerNameservers');
$routes->get('resellerclub_getregistrarLock', 'APIController::resellerclub_GetRegistrarLock');
$routes->get('resellerclub_geteepCode', 'APIController::resellerclub_GetEPPCode');
$routes->get('resellerclub_getDNS', 'APIController::resellerclub_GetDNS');
$routes->get('resellerclub_GetContactDetails', 'APIController::resellerclub_GetContactDetails');
$routes->get('resellerclub_GetEmailForwarding', 'APIController::resellerclub_GetEmailForwarding');
$routes->get('resellerclub_getinfo', 'APIController::resellerclub_GetInfo');
$routes->post('resellerclubtldtype/(:any)', 'APIController::resellerclub_tld_type/$1');
$routes->get('resellerclub_getip', 'APIController::resellerclub_GetIP');
$routes->post('modifynameserver', 'APIController::modifynameserver');
$routes->post('resellerclub_modifycontactdetails', 'APIController::resellerclub_ModifyContactDetails');
$routes->get('register', 'APIController::register');
$routes->get('transfer', 'APIController::transfer');
$routes->post('activationdnsservice', 'APIController::activationdnsservice');
$routes->post('adding_ipv4_address_record', 'APIController::adding_ipv4_address_record');
$routes->post('adding_ipv6_address_record', 'APIController::adding_ipv6_address_record');
$routes->post('add_cname_record', 'APIController::add_cname_record');
$routes->post('add_mx_record', 'APIController::add_mx_record');
$routes->post('add_ns_record', 'APIController::add_ns_record');
$routes->post('add_txt_record', 'APIController::add_txt_record');
$routes->post('add_srv_record', 'APIController::add_srv_record');
$routes->post('modifying_ipv4_address_record', 'APIController::modifying_ipv4_address_record');
$routes->post('modifying_ipv6_address_record', 'APIController::modifying_ipv6_address_record');
$routes->post('modifying_cname_record', 'APIController::modifying_cname_record');
$routes->post('modifying_mx_record', 'APIController::modifying_mx_record');
$routes->post('modifying_ns_record', 'APIController::modifying_ns_record');
$routes->post('modifying_txt_record', 'APIController::modifying_txt_record');
$routes->post('modifying_srv_record', 'APIController::modifying_srv_record');
$routes->post('modifying_soa_record', 'APIController::modifying_soa_record');
$routes->get('searching_dns_record', 'APIController::searching_dns_record');
$routes->get('delete_dns_record', 'APIController::delete_dns_record');
$routes->post('delete_ipv4_address_record', 'APIController::delete_ipv4_address_record');
$routes->post('delete_ipv6_address_record', 'APIController::delete_ipv6_address_record');
$routes->post('delete_cname_record', 'APIController::delete_cname_record');
$routes->post('delete_mx_record', 'APIController::delete_mx_record');
$routes->post('delete_ns_record', 'APIController::delete_ns_record');
$routes->post('delete_txt_record', 'APIController::delete_txt_record');
$routes->post('delete_srv_record', 'APIController::delete_srv_record');
$routes->post('cust_sign_up/(:any)', 'APIController::cust_sign_up/$1');
$routes->get('cust_details_by_username', 'APIController::cust_details_by_username');
$routes->post('get_contact_details', 'APIController::get_contact_details');
$routes->get('tlds_customer_pricing', 'APIController::tlds_customer_pricing');
$routes->get('domain_name_is_premium', 'APIController::domain_name_is_premium');
$routes->get('get_tlds_details', 'APIController::get_tlds_details');
$routes->get('x_rates/(:any)', 'APIController::x_rates/$1');
$routes->get('xrate', 'CronController::xrate');
$routes->get('consent', 'CronController::consent');


$routes->get('api_customerprice', 'Modules\customerprice\controllers\Customerprice::api_customerprice');


//Razorpay Payment Gateway
$routes->group('razorpay', ['filter' => 'sessionsCheck', 'namespace' => 'Modules\razorpay\controllers'], function ($subroutes) {
    $subroutes->get('pay/(:any)', 'Razorpay::pay/$1');
    $subroutes->post('process_payment', 'Razorpay::process_payment');
    $subroutes->post('verify_payment', 'Razorpay::verify_payment');
});

$routes->get('getPackageList', 'PleskController::getPackageList');

$routes->post('getResellerClubContactDetails/(:any)', 'APIController::get_contact_details/$1');

$routes->post('resellerclub_renewal_info', 'APIController::resellerclub_renewal_info');

$routes->post('submit_renewal_details', 'APIController::submit_renewal_details');

$routes->post('get_eep_code', 'APIController::get_eep_code');
$routes->post('transfer_page', 'APIController::transfer_page');
$routes->post('transfer', 'APIController::transfer');