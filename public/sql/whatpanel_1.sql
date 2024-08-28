CREATE TABLE `hd_account_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale` varchar(100) COLLATE utf8_unicode_ci DEFAULT 'en_US',
  `address` varchar(64) COLLATE utf8_unicode_ci DEFAULT '-',
  `phone` varchar(32) COLLATE utf8_unicode_ci DEFAULT '-',
  `mobile` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `skype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(40) COLLATE utf8_unicode_ci DEFAULT 'english',
  `department` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` mediumtext COLLATE utf8_unicode_ci,
  `use_gravatar` enum('Y','N') COLLATE utf8_unicode_ci DEFAULT 'Y',
  `as_company` enum('false','true') COLLATE utf8_unicode_ci DEFAULT 'false',
  `allowed_modules` text COLLATE utf8_unicode_ci,
  `hourly_rate` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `hd_activities`
--

CREATE TABLE `hd_activities` (
  `activity_id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `module` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_field_id` int(11) DEFAULT NULL,
  `activity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activity_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `icon` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'fa-coffee',
  `value1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hd_additional_fields`
--

CREATE TABLE `hd_additional_fields` (
  `id` int(11) NOT NULL,
  `domain` int(100) NOT NULL,
  `field_name` text NOT NULL,
  `field_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hd_blocks`
--

CREATE TABLE `hd_blocks` (
  `block_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `id` varchar(100) NOT NULL,
  `module` varchar(100) NOT NULL,
  `theme` varchar(100) NOT NULL,
  `section` varchar(100) NOT NULL,
  `weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hd_blocks`
--

INSERT INTO `hd_blocks` (`block_id`, `type`, `name`, `id`, `module`, `theme`, `section`, `weight`) VALUES
(10, 'Module', '', 'domain_availability_checker', 'Domains', 'original', 'full_width_top', 1),
(21, 'Module', '', 'domain_pricing_table', 'Domains', 'original', 'content_top', 0),
(34, 'Module', 'Home Slider', 'sliders_7', 'Sliders', 'original', 'full_width_top', 0),
(35, 'Module', 'Main Menu', 'menus_1', 'Menus', 'original', 'main_menu', 0),
(43, 'Module', 'Website Packages', 'items_13', 'Items', 'original', 'content_top', 0),
(44, 'Custom', 'Sidebar Right', '18', 'Block', 'original', 'sidebar_right', 0),
(45, 'Custom', 'Left Sidebar', '19', 'Block', 'original', 'sidebar_left', 0),
(47, 'Custom', 'Google Map', '17', 'Block', 'original', 'full_width_top', 0),
(48, 'Custom', 'Contact Page Image', '16', 'Block', 'original', 'sidebar_right', 0),
(51, 'Custom', 'Top icons', '24', 'Block', 'original', 'header_top_right', 1),
(54, 'Module', 'cPanel Hosting', 'items_12', 'Items', 'original', 'footer_top', 1),
(62, 'Custom', 'Domains Image', '28', 'Block', 'original', 'sidebar_right', 0),
(69, 'Custom', 'Demo Login', '32', 'Block', 'original', 'header_top_left', 0),
(70, 'Custom', 'Our Service Guarantee', '33', 'Block', 'original', 'page_bottom', 1),
(72, 'Module', 'Installation FAQ', 'faq_34', 'FAQ', 'original', 'footer_top', 0),
(73, 'Module', 'Installation', 'faq_30', 'FAQ', 'original', 'footer_top', 0),
(74, 'Custom', 'Contact Details Top', '12', 'Block', 'original', 'header_top_left', 0),
(75, 'Module', '', 'cart_icon_block', 'Cart', 'original', 'floating_icon', 0),
(77, 'Custom', 'Home', '34', 'Block', 'original', 'content_top', 0),
(78, 'Module', 'Plesk Hosting', 'items_19', 'Items', 'original', 'content_top', 1),
(82, 'Module', 'DirectAdmin Hosting', 'items_20', 'Items', 'original', 'content_bottom', 0);

-- --------------------------------------------------------

--
-- Table structure for table `hd_blocks_custom`
--

CREATE TABLE `hd_blocks_custom` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `code` longtext CHARACTER SET utf8 NOT NULL,
  `format` varchar(20) NOT NULL,
  `type` varchar(6) NOT NULL DEFAULT 'Custom',
  `module` varchar(5) NOT NULL DEFAULT 'Block'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hd_blocks_custom`

INSERT INTO `hd_blocks_modules` (`id`, `name`, `settings`, `param`, `type`, `module`) VALUES
    (6, 'Main Menu', '', 'menus_1', 'Module', 'Menus'),
    (7, 'Home Slider', 'a:1:{s:5:\"title\";s:2:\"no\";}', 'sliders_7', 'Module', 'Sliders'),
    (9, 'cPanel Hosting', 'a:1:{s:5:\"title\";s:2:\"no\";}', 'items_12', 'Module', 'Items'),
    (10, 'Website Packages', 'a:1:{s:5:\"title\";s:2:\"no\";}', 'items_13', 'Module', 'Items'),
    (12, 'Plesk Hosting', 'a:1:{s:5:\"title\";s:2:\"no\";}', 'items_19', 'Module', 'Items'),
    (13, 'DirectAdmin Hosting', 'a:1:{s:5:\"title\";s:2:\"no\";}', 'items_20', 'Module', 'Items'),
    (23, 'Web Hosting', 'a:1:{s:5:\"title\";s:3:\"yes\";}', 'faq_33', 'Module', 'FAQ'),
    (29, 'Domain Names', '', 'faq_39', 'Module', 'FAQ');

-- --------------------------------------------------------

--
-- Table structure for table `hd_blocks_modules`
--

CREATE TABLE `hd_blocks_modules` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `settings` varchar(255) NOT NULL,
  `param` varchar(20) NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'Module',
  `module` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hd_blocks_modules`
--

INSERT INTO hd_blocks_custom (id, name, code, format, type, module) VALUES
(5, 'Block with PHP', '$address = config_item(\'company_address\')
<br>$phone = config_item(\'company_phone\')
<br>$email = config_item(\'company_email\')
<br>$phone = config_item(\'company_phone\')
<br>echo \'<h3 class="section-header section-title">Our Address</h3>
<br> <ul class="contact-info"><br> <li><span><i class="fa fa-home"></i>\' . $address . \'</span></li><br> <li><span><i class="fa fa-phone"></i>\' . $phone . \'</span></li>
<br> <li><i class="fa fa-envelope-o" aria-hidden="true"></i><a href="#">\' . $email . \'</a></li><br> <br> </ul>\'', 'php', 'Custom', 'Block'),
(12, 'Contact Details Top', '<ul class="list-inline top-contact"><br> <li><br> <p> <span><i class ="fa fa-phone"></i>+ 255 545 11222</span><br> </p><br> </li><br> <li>
<br> <p><span><i class ="fa fa-envelope"></i> noreply@whatpanel.com</span><br> </p><br> </li><br> </ul>', 'js', 'Custom', 'Block'),
(16, 'Contact Page Image', '<img src="resource/images/contact.png">', 'rich_text', 'Custom', 'Block'),
(17, 'Google Map', '<div class="embed-responsive embed-responsive-100x400px"><br><iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d35818.719732048536!2d-4.25169!3d55.868392!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x488815562056ceeb%3A0x71e683b805ef511e!2sGlasgow%2C+Glasgow+City%2C+UK!5e0!3m2!1sen!2sus!4v1448625188752\" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe><br> </div>', 'js', 'Custom', 'Block'),
(19, 'Left Sidebar', '<h2>Left sidebar</h2>\r\n<h4>Example with code</h4>\r\n\r\n<div id=\"MyClockDisplay\" class=\"clock\" onload=\"showTime()\"></div>\r\n<script>\r\n\r\nfunction showTime(){\r\n    var date = new Date()\r\n    var h = date.getHours() // 0 - 23\r\n    var m = date.getMinutes() // 0 - 59\r\n    var s = date.getSeconds() // 0 - 59\r\n    var session = \"AM\"\r\n    \r\n    if(h == 0){\r\n        h = 12\r\n    }\r\n    \r\n    if(h > 12){\r\n        h = h - 12\r\n        session = \"PM\"\r\n    }\r\n    \r\n    h = (h < 10) ? \"0\" + h : h\r\n    m = (m < 10) ? \"0\" + m : m\r\n    s = (s < 10) ? \"0\" + s : s\r\n    \r\n    var time = h + \":\" + m + \":\" + s + \" \" + session\r\n    document.getElementById(\"MyClockDisplay\").innerText = time\r\n    document.getElementById(\"MyClockDisplay\").textContent = time\r\n    \r\n    setTimeout(showTime, 1000)\r\n    \r\n}\r\n\r\nshowTime()\r\n\r\n</script>', 'js', 'Custom', 'Block'),
(24, 'Top icons', 'echo \'<ul class=\"list-inline top-widget\">\n        <li class=\"top-social\">\n         <a href=\"#\"><i class=\"fa fa-facebook\"></i></a>\n         <a href=\"#\"><i class=\"fa fa-twitter\"></i></a>\n         <a href=\"#\"><i class=\"fa fa-google-plus\"></i></a>\n         <a href=\"#\"><i class=\"fa fa-linkedin\"></i></a>\n        </li>\n        <li>\n         <a class=\"button-quote\" href=\"\'.base_url().\'auth/login\" id=\"\">Sign in</a>\n        </li>\n       </ul>\'', 'php', 'Custom', 'Block'),
(28, 'Domains Image', '<p><img style=\"width: 100%\" src=\"data:image/jpegbase64,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\" data-filename=\"domains.jpg\"><br></p>', 'rich_text', 'Custom', 'Block'),
(32, 'Demo Login', '<span></span>', 'js', 'Custom', 'Block'),
(33, 'Why Choose Us', '<div class=\"btgrid\">\n<div class=\"row row-1\">\n<div class=\"col-md-6\">\n<div class=\"content\">\n<h1>Why Choose Us?</h1>\n\n<ul>\n	<li><big>&nbsp&nbsp&nbsp Easy to use Control Panel</big></li>\n	<li><big>&nbsp&nbsp&nbsp 24/7 Support</big></li>\n	<li><big>&nbsp&nbsp&nbsp DDOS Protection</big></li>\n	<li><big>&nbsp&nbsp&nbsp Money Back Guarantee</big></li>\n	<li><big>&nbsp&nbsp&nbsp Free Nightly Backups</big></li>\n	<li><big>&nbsp&nbsp&nbsp Outstanding reliability</big></li>\n	<li><big>&nbsp&nbsp&nbsp Powerful, Fast Servers</big></li>\n	<li><big>&nbsp&nbsp&nbsp Superior technology</big></li>\n	<li><big>&nbsp&nbsp&nbsp Secured Web site</big></li>\n	<li><big>&nbsp&nbsp&nbsp WordPress hosting</big></li>\n	<li><big>&nbsp&nbsp&nbsp No Overloaded Servers</big></li>\n</ul>\n</div>\n</div>\n\n<div class=\"col-md-6\">\n<div class=\"content\">\n<p><img alt=\"\" src=\"/resource/uploads/638478249.png\" style=\"height:410px width:573px\" /></p>\n</div>\n</div>\n</div>\n</div>\n', 'rich_text', 'Custom', 'Block');


-- --------------------------------------------------------

--
-- Table structure for table `hd_blocks_pages`
--

CREATE TABLE `hd_blocks_pages` (
  `id` int(11) NOT NULL,
  `block_id` int(11) NOT NULL,
  `page` varchar(100) NOT NULL,
  `mode` varchar(10) NOT NULL DEFAULT 'show',
  `theme` varchar(100) NOT NULL,
  `module` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hd_blocks_pages`
--

INSERT INTO `hd_blocks_pages` (`id`, `block_id`, `page`, `mode`, `theme`, `module`) VALUES
(48, 10, 'home', 'show', 'original', 'Domains'),
(49, 10, 'domain_registration', 'show', 'original', 'Domains'),
(50, 21, 'domain_registration', 'show', 'original', 'Domains'),
(74, 35, 'all', 'hide', 'original', 'Menus'),
(84, 44, 'layouts', 'show', 'original', 'Block'),
(85, 45, 'layouts', 'show', 'original', 'Block'),
(87, 47, 'contact', 'show', 'original', 'Block'),
(88, 48, 'contact', 'show', 'original', 'Block'),
(94, 51, 'all', 'hide', 'original', 'Block'),
(141, 43, 'web_design', 'show', 'original', 'Items'),
(147, 62, 'domain_registration', 'show', 'original', 'Block'),
(152, 34, 'home', 'show', 'original', 'Sliders'),
(153, 34, 'about', 'show', 'original', 'Sliders'),
(160, 69, 'all', 'hide', 'original', 'Block'),
(181, 72, 'home', 'show', 'original', 'FAQ'),
(182, 73, 'home', 'show', 'original', 'FAQ'),
(183, 74, 'all', 'hide', 'original', 'Block'),
(184, 75, 'all', 'hide', 'original', 'Cart'),
(186, 77, 'home', 'show', 'original', 'Block'),
(190, 54, 'home', 'show', 'original', 'Items'),
(195, 78, 'home', 'show', 'original', 'Items'),
(202, 82, 'web_hosting', 'show', 'original', 'Items'),
(203, 70, 'home', 'show', 'original', 'Block'),
(204, 70, 'web_hosting', 'show', 'original', 'Block'),
(205, 70, 'domain_registration', 'show', 'original', 'Block');

-- --------------------------------------------------------

--
-- Table structure for table `hd_captcha`
--

CREATE TABLE `hd_captcha` (
  `captcha_id` bigint(13) UNSIGNED NOT NULL,
  `captcha_time` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(16) COLLATE utf8_unicode_ci DEFAULT '0',
  `word` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hd_categories`
--

CREATE TABLE `hd_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `cat_name` varchar(255) DEFAULT NULL,
  `parent` int(11) NOT NULL,
  `module` varchar(32) DEFAULT 'items',
  `pricing_table` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_categories`
--

INSERT INTO `hd_categories` (`id`, `cat_name`, `parent`, `module`, `pricing_table`) VALUES
(5, 'Addons', 0, 'items', ''),
(6, 'FAQ', 0, 'pages', ''),
(7, 'Knowledgebase', 0, 'pages', ''),
(8, 'Domains', 0, 'items', ''),
(9, 'Hosting', 0, 'items', ''),
(10, 'Products & Services', 0, 'items', ''),
(12, 'cPanel Hosting', 9, 'items', 'one'),
(13, 'Website Packages', 10, 'items', 'one'),
(14, 'Domains', 8, 'items', ''),
(19, 'Plesk Hosting', 9, 'items', 'three'),
(20, 'DirectAdmin Hosting', 9, 'items', 'five'),
(27, 'General', 7, 'items', ''),
(33, 'Web Hosting', 6, 'items', ''),
(39, 'Domain Names', 6, 'items', '');

-- --------------------------------------------------------

--
-- Table structure for table `hd_companies`
--

CREATE TABLE `hd_companies` (
  `co_id` int(11) UNSIGNED NOT NULL,
  `company_ref` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `individual` tinyint(4) DEFAULT '0',
  `transaction_value` decimal(10,2) DEFAULT '0.00',
	`first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	`middle_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	`last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `primary_contact` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_email` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_phone` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_mobile` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_fax` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_address_two` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_website` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'USD',
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `VAT` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `zip` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `params` text COLLATE utf8_unicode_ci,
  `imported` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hd_config`
--

CREATE TABLE `hd_config` (
  `config_key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `categories` varchar(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_config`
--

INSERT INTO `hd_config` (`config_key`, `value`) VALUES
('2checkout_private_key', ''),
('2checkout_publishable_key', ''),
('2checkout_seller_id', ''),
('accept_coin', 'LTCT'),
('active_theme', 'original'),
('allowed_files', 'gif|png|jpeg|jpg|pdf|txt|zip|rar'),
('allow_client_registration', 'TRUE'),
('allow_js_php_blocks', 'FALSE'),
('apply_credit', 'TRUE'),
('automatic_activation', 'TRUE'),
('automatic_bug_fixes', 'FALSE'),
('automatic_email_on_recur', 'FALSE'),
('automatic_updates', 'FALSE'),
('auto_backup_db', 'TRUE'),
('auto_close_ticket', '30'),
('beta_updates', 'TRUE'),
('billing_email', 'support@whatpanel.com'),
('billing_email_name', ' WHAT PANEL  Sales'),
('bitcoin_active', 'TRUE'),
('bitcoin_address', 'xpub6DQ3AnPkH6mfroevvepugCtiLQoddnF5ZJeNTcMzcwntJ3tHQL8M7bki5fp8GDyNU3f7ZpzkYX64ecp6inuSwW5Q87fhDj2gn9YqV32Ai8w'),
('bitcoin_api_key', '33f80c9e-8e08-437a-8e93-3e5812ff98fd'),
('bitcoin_live', 'TRUE'),
('build', '0'),
('button_color', 'success'),
('captcha_login', 'FALSE'),
('captcha_registration', 'FALSE'),
('cart', 'php'),
('chart_color', '#11A7DB'),
('coinpayments_active', 'TRUE'),
('coinpayments_live', 'TRUE'),
('coinpayments_private_key', '8395262B2f59424A638C839ad8AE7dFF29872d1E69c3d9af1bc1c57fEf5ac388'),
('coinpayments_public_key', '161eb244ecf8abef157cc681bbc7649aabab1e9151168eb54e74540a85b15f08'),
('company_address', 'No 1, Official Building, Sample Street'),
('company_address_french', ''),
('company_city', ''),
('company_city_french', ''),
('company_country', 'India'),
('company_country_french', ''),
('company_domain', ''),
('company_domain_french', ''),
('company_email', ''),
('company_email_french', ''),
('company_fax', ''),
('company_fax_french', ''),
('company_id_prefix', 'COM'),
('company_legal_name', ' WHAT PANEL '),
('company_legal_name_french', ''),
('company_logo', 'logo.png'),
('company_mobile', ''),
('company_mobile_french', ''),
('company_name', ' WHAT PANEL '),
('company_name_french', ''),
('company_phone', ''),
('company_phone_2', ''),
('company_phone_2_french', ''),
('company_phone_french', ''),
('company_registration', ''),
('company_registration_french', ''),
('company_state', ''),
('company_state_french', ''),
('company_vat', ''),
('company_vat_french', ''),
('company_zip_code', ''),
('company_zip_code_french', ''),
('contact_person', ' WHAT PANEL '),
('contact_person_french', ''),
('contact_sidebar_right', 'TRUE'),
('cron_key', 'FHF6545AV75223G97JB17GKGSH89'),
('currency_decimals', '2'),
('currency_position', 'before'),
('date_format', '%Y-%m-%d'),
('date_php_format', 'Y-m-d'),
('date_picker_format', 'yyyy-mm-dd'),
('decimal_separator', '.'),
('default_currency', 'USD'),
('default_currency_symbol', '$'),
('default_editor', 'ckeditor'),
('default_language', 'english'),
('default_project_settings', '{\"show_team_members\":\"off\",\"show_milestones\":\"on\",\"show_project_tasks\":\"on\",\"show_project_files\":\"on\",\"show_timesheets\":\"off\",\"show_project_bugs\":\"on\",\"show_project_history\":\"off\",\"show_project_calendar\":\"on\",\"show_project_comments\":\"on\",\"show_project_links\":\"on\",\"client_add_tasks\":\"on\",\"show_project_gantt\":\"on\",\"show_project_hours\":\"on\"}'),
('default_tax', '14.00'),
('default_tax2', '0.00'),
('default_terms', '<p>Thank you for your business. Please process this invoice before the due date.</p><br>'),
('demo_mode', 'FALSE'),
('developer', 'ig63Yd/+yuA8127gEyTz9TY4pnoeKq8dtocVP44+BJvtlRp8Vqcetwjk51dhSB6Rx8aVIKOPfUmNyKGWK7C/gg=='),
('disable_emails', 'FALSE'),
('display_invoice_badge', 'TRUE'),
('domainscoza_apikey', 'c695683a41445d342be713387f51edb1'),
('domainscoza_live', 'TRUE'),
('domain_admin_address_1', ''),
('domain_admin_address_2', ''),
('domain_admin_city', ''),
('domain_admin_company', ''),
('domain_admin_country', 'South Africa'),
('domain_admin_email', ''),
('domain_admin_firstname', ''),
('domain_admin_lastname', ''),
('domain_admin_phone', ''),
('domain_admin_state', ''),
('domain_admin_zip', ''),
('domain_checker', 'default'),
('email_account_details', 'TRUE'),
('email_invoice_message', 'Hello {CLIENT}<br>Here is the invoice of {CURRENCY} {AMOUNT}<br>You can view the invoice online at:<br>{LINK}<br>Best Regards,<br>{COMPANY}'),
('email_piping', 'FALSE'),
('email_staff_tickets', 'TRUE'),
('enable_languages', 'FALSE'),
('file_max_size', '80000'),
('increment_invoice_number', 'TRUE'),
('installed', 'FALSE'),
('instamojo_active', 'TRUE'),
('instamojo_api_key', 'test_e1aca867f696f9d889b63c0f4e1'),
('instamojo_hash', '44532b09bd374481b58ab65954029367'),
('instamojo_live', 'TRUE'),
('instamojo_oath_token', 'test_8ba6a245f5b4e94f54d2c515cbc'),
('invoices_due_after', '7'),
('invoices_due_before', '2'),
('invoice_color', '#53B567'),
('invoice_footer', 'Thank you.'),
('invoice_language', 'en'),
('invoice_logo', 'invoice_logo.png'),
('invoice_logo_height', '72'),
('invoice_logo_width', '210.433'),
('invoice_prefix', 'INV'),
('invoice_start_no', '1'),
('languages', ''),
('last_check', '1585557268'),
('last_seen_activities', '1626037461'),
('locale', 'aa_DJ'),
('login_bg', 'bg_login.jpg'),
('login_title', ''),
('logo_or_icon', 'logo_title'),
('mailbox', 'INBOX'),
('mail_encryption', 'tls'),
('mail_flags', '/novalidate-cert'),
('mail_imap', 'FALSE'),
('mail_imap_host', ''),
('mail_password', 'df13a5f137e01490a9c3ed278f16d9bf57053fc2faa80bbfdc10d2254aa1353c872fd28cbc40420113be96ad99dc1fa617183a9a996e9db58c95933b1324a9eb48ebQ0qTGFQFO7bDfId1YXI='),
('mail_port', '993'),
('mail_search', 'UNSEEN'),
('mail_ssl', 'TRUE'),
('mail_username', 'admin'),
('mollie_active', 'TRUE'),
('mollie_api_key', 'test_G7meUPuywH8GGBxn57fEVjQJwgafMQ'),
('mollie_live', 'TRUE'),
('namecheap_apikey', '12345'),
('namecheap_ip', '41.113.83.52'),
('namecheap_live', 'TRUE'),
('namecheap_username', 'test'),
('nameserver_five', ''),
('nameserver_four', ''),
('nameserver_one', ''),
('nameserver_three', ''),
('nameserver_two', ''),
('notify_payment_received', 'FALSE'),
('notify_ticket_closed', 'TRUE'),
('notify_ticket_reopened', 'TRUE'),
('notify_ticket_reply', 'TRUE'),
('order_tax', 'FALSE'),
('payfast_active', 'TRUE'),
('payfast_live', 'TRUE'),
('payfast_merchant_id', '10018446'),
('payfast_merchant_key', 'untw67wiis04u'),
('payfast_passphrase', 'SomeString-123_Random'),
('paypal_active', 'TRUE'),
('paypal_cancel_url', 'paypal/cancel'),
('paypal_email', 'support@whatpanel.com'),
('paypal_ipn_url', 'paypal/t_ipn/ipn'),
('paypal_live', 'TRUE'),
('paypal_success_url', 'paypal/success'),
('paystack_active', 'TRUE'),
('paystack_live', 'TRUE'),
('paystack_secret_key', 'sk_test_96916efaf1525ebcc0f56b79851aab2640e08157 '),
('pdf_engine', 'invoicr'),
('protocol', 'mail'),
('purchase_code', ''),
('quantity_decimals', '0'),
('razorpay_active', 'TRUE'),
('razorpay_api_key', 'stri'),
('razorpay_live', 'TRUE'),
('recaptcha_public_key', '6LexGeIUAAAAAJ70v7Am0fGQcgHDmresRKP2WOw5'),
('reminder_message', 'Hello {CLIENT}<br>This is a friendly reminder to pay your invoice of {CURRENCY} {AMOUNT}<br>You can view the invoice online at:<br>{LINK}<br>Best Regards,<br>{COMPANY}'),
('remote_login_expires', '72'),
('resellerclub_apikey', 'Vif1XMeq3AnCC278PlElAxirdM4oAYRn'),
('resellerclub_live', 'TRUE'),
('resellerclub_resellerid', '994161'),
('reset_key', 'FHF6545AV75223G97JB17GKGSH89'),
('rows_per_table', '25'),
('settings', 'theme'),
('show_invoice_tax', 'TRUE'),
('show_login_image', 'TRUE'),
('show_only_logo', 'FALSE'),
('show_time_ago', 'TRUE'),
('sidebar_theme', 'dark'),
('site_appleicon', 'logo_favicon.png'),
('site_author', 'Hosting Domain'),
('site_desc', 'Web Hosting Access Terminal WHATPanel Billing is a domain and web hosting billing system.'),
('site_favicon', 'logo_favicon.png'),
('site_icon', 'fa-globe'),
('smtp_encryption', 'ssl'),
('smtp_host', ''),
('smtp_pass', '76160b3e422b94da94bc87254ff8a894f26cb8a0b9a6499c8944551076c45ef272b6c439678b67b77dfc6bcc4010a032710a5fb3f21ed63648e6c1bcd14c616cT0Ps6v2VdUsK99hKD39u4A=='),
('smtp_port', ''),
('smtp_user', ''),
('stop_timer_logout', 'FALSE'),
('stripe_active', 'TRUE'),
('stripe_live', 'TRUE'),
('stripe_private_key', ''),
('stripe_public_key', ''),
('support_email', 'support@whatpanel.com'),
('support_email_name', ' WHAT PANEL  Support'),
('suspend_after', '3'),
('suspend_due', 'TRUE'),
('swap_to_from', 'FALSE'),
('system_font', 'source_sans'),
('tax_decimals', '0'),
('tax_name', 'GST'),
('terminate_after', '10'),
('terminate_due', 'FALSE'),
('theme_color', 'primary'),
('thousand_separator', ','),
('ticket_default_department', '2'),
('ticket_start_no', '1'),
('timezone', 'Africa/Abidjan'),
('top_bar_color', 'skin-black'),
('two_checkout_active', 'FALSE'),
('two_checkout_live', 'FALSE'),
('updates', '1'),
('update_xrates', 'TRUE'),
('use_alternate_emails', 'FALSE'),
('use_recaptcha', 'TRUE'),
('use_whoisxmlapi', 'FALSE'),
('valid_license', 'TRUE'),
('website_name', ' WHAT PANEL '),
('website_name_french', 'ABC'),
('whoisxmlapi_key', ''),
('xrates_app_id', ''),
('xrates_check', '2020-07-02'),
('salt_key', ''),
('salt_domain', ''),
('salt_status', '');

-- --------------------------------------------------------

--
-- Table structure for table `hd_countries`
--

CREATE TABLE `hd_countries` (
  `id` int(6) NOT NULL,
  `value` varchar(250) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hd_countries`
--

INSERT INTO `hd_countries` (`id`, `value`, `phone`, `code`) VALUES
(1, 'Afghanistan', '93', 'AF / AFG'),
(2, 'Albania', '355', 'AL / ALB'),
(3, 'Algeria', '213', 'DZ / DZA'),
(4, 'American Samoa', '1-684', 'AS / ASM'),
(5, 'Andorra', '376', 'AD / AND'),
(6, 'Angola', '244', 'AO / AGO'),
(7, 'Anguilla', '1-264', 'AI / AIA'),
(8, 'Antarctica', '672', 'AQ / ATA'),
(9, 'Antigua and Barbuda', '1-268', 'AG / ATG'),
(10, 'Argentina', '54', 'AR / ARG'),
(11, 'Armenia', '374', 'AM / ARM'),
(12, 'Aruba', '297', 'AW / ABW'),
(13, 'Australia', '61', 'AU / AUS'),
(14, 'Austria', '43', 'AT / AUT'),
(15, 'Azerbaijan', '994', 'AZ / AZE'),
(16, 'Bahamas', '1-242', 'BS / BHS'),
(17, 'Bahrain', '973', 'BH / BHR'),
(18, 'Bangladesh', '880', 'BD / BGD'),
(19, 'Barbados', '1-246', 'BB / BRB'),
(20, 'Belarus', '375', 'BY / BLR'),
(21, 'Belgium', '32', 'BE / BEL'),
(22, 'Belize', '501', 'BZ / BLZ'),
(23, 'Benin', '229', 'BJ / BEN'),
(24, 'Bermuda', '1-441', 'BM / BMU'),
(25, 'Bhutan', '975', 'BT / BTN'),
(26, 'Bolivia', '591', 'BO / BOL'),
(27, 'Bosnia and Herzegovina', '387', 'BA / BIH'),
(28, 'Botswana', '267', 'BW / BWA'),
(29, 'Brazil', '55', 'BR / BRA'),
(30, 'British Indian Ocean Territory', '246', 'IO / IOT'),
(31, 'British Virgin Islands', '1-284', 'VG / VGB'),
(32, 'Brunei', '673', 'BN / BRN'),
(33, 'Bulgaria', '359', 'BG / BGR'),
(34, 'Burkina Faso', '226', 'BF / BFA'),
(35, 'Burundi', '257', 'BI / BDI'),
(36, 'Cambodia', '855', 'KH / KHM'),
(37, 'Cameroon', '237', 'CM / CMR'),
(38, 'Canada', '1', 'CA / CAN'),
(39, 'Cape Verde', '238', 'CV / CPV'),
(40, 'Cayman Islands', '1-345', 'KY / CYM'),
(41, 'Central African Republic', '236', 'CF / CAF'),
(42, 'Chad', '235', 'TD / TCD'),
(43, 'Chile', '56', 'CL / CHL'),
(44, 'China', '86', 'CN / CHN'),
(45, 'Christmas Island', '61', 'CX / CXR'),
(46, 'Cocos Islands', '61', 'CC / CCK'),
(47, 'Colombia', '57', 'CO / COL'),
(48, 'Comoros', '269', 'KM / COM'),
(49, 'Cook Islands', '682', 'CK / COK'),
(50, 'Costa Rica', '506', 'CR / CRI'),
(51, 'Croatia', '385', 'HR / HRV'),
(52, 'Cuba', '53', 'CU / CUB'),
(53, 'Curacao', '599', 'CW / CUW'),
(54, 'Cyprus', '357', 'CY / CYP'),
(55, 'Czech Republic', '420', 'CZ / CZE'),
(56, 'Democratic Republic of the Congo', '243', 'CD / COD'),
(57, 'Denmark', '45', 'DK / DNK'),
(58, 'Djibouti', '253', 'DJ / DJI'),
(59, 'Dominica', '1-767', 'DM / DMA'),
(60, 'Dominican Republic', '1-809, 1-829, 1-849', 'DO / DOM'),
(61, 'East Timor', '670', 'TL / TLS'),
(62, 'Ecuador', '593', 'EC / ECU'),
(63, 'Egypt', '20', 'EG / EGY'),
(64, 'El Salvador', '503', 'SV / SLV'),
(65, 'Equatorial Guinea', '240', 'GQ / GNQ'),
(66, 'Eritrea', '291', 'ER / ERI'),
(67, 'Estonia', '372', 'EE / EST'),
(68, 'Ethiopia', '251', 'ET / ETH'),
(69, 'Falkland Islands', '500', 'FK / FLK'),
(70, 'Faroe Islands', '298', 'FO / FRO'),
(71, 'Fiji', '679', 'FJ / FJI'),
(72, 'Finland', '358', 'FI / FIN'),
(73, 'France', '33', 'FR / FRA'),
(74, 'French Polynesia', '689', 'PF / PYF'),
(75, 'Gabon', '241', 'GA / GAB'),
(76, 'Gambia', '220', 'GM / GMB'),
(77, 'Georgia', '995', 'GE / GEO'),
(78, 'Germany', '49', 'DE / DEU'),
(79, 'Ghana', '233', 'GH / GHA'),
(80, 'Gibraltar', '350', 'GI / GIB'),
(81, 'Greece', '30', 'GR / GRC'),
(82, 'Greenland', '299', 'GL / GRL'),
(83, 'Grenada', '1-473', 'GD / GRD'),
(84, 'Guam', '1-671', 'GU / GUM'),
(85, 'Guatemala', '502', 'GT / GTM'),
(86, 'Guernsey', '44-1481', 'GG / GGY'),
(87, 'Guinea', '224', 'GN / GIN'),
(88, 'Guinea-Bissau', '245', 'GW / GNB'),
(89, 'Guyana', '592', 'GY / GUY'),
(90, 'Haiti', '509', 'HT / HTI'),
(91, 'Honduras', '504', 'HN / HND'),
(92, 'Hong Kong', '852', 'HK / HKG'),
(93, 'Hungary', '36', 'HU / HUN'),
(94, 'Iceland', '354', 'IS / ISL'),
(95, 'India', '91', 'IN / IND'),
(96, 'Indonesia', '62', 'ID / IDN'),
(97, 'Iran', '98', 'IR / IRN'),
(98, 'Iraq', '964', 'IQ / IRQ'),
(99, 'Ireland', '353', 'IE / IRL'),
(100, 'Isle of Man', '44-1624', 'IM / IMN'),
(101, 'Israel', '972', 'IL / ISR'),
(102, 'Italy', '39', 'IT / ITA'),
(103, 'Ivory Coast', '225', 'CI / CIV'),
(104, 'Jamaica', '1-876', 'JM / JAM'),
(105, 'Japan', '81', 'JP / JPN'),
(106, 'Jersey', '44-1534', 'JE / JEY'),
(107, 'Jordan', '962', 'JO / JOR'),
(108, 'Kazakhstan', '7', 'KZ / KAZ'),
(109, 'Kenya', '254', 'KE / KEN'),
(110, 'Kiribati', '686', 'KI / KIR'),
(111, 'Kosovo', '383', 'XK / XKX'),
(112, 'Kuwait', '965', 'KW / KWT'),
(113, 'Kyrgyzstan', '996', 'KG / KGZ'),
(114, 'Laos', '856', 'LA / LAO'),
(115, 'Latvia', '371', 'LV / LVA'),
(116, 'Lebanon', '961', 'LB / LBN'),
(117, 'Lesotho', '266', 'LS / LSO'),
(118, 'Liberia', '231', 'LR / LBR'),
(119, 'Libya', '218', 'LY / LBY'),
(120, 'Liechtenstein', '423', 'LI / LIE'),
(121, 'Lithuania', '370', 'LT / LTU'),
(122, 'Luxembourg', '352', 'LU / LUX'),
(123, 'Macau', '853', 'MO / MAC'),
(124, 'Macedonia', '389', 'MK / MKD'),
(125, 'Madagascar', '261', 'MG / MDG'),
(126, 'Malawi', '265', 'MW / MWI'),
(127, 'Malaysia', '60', 'MY / MYS'),
(128, 'Maldives', '960', 'MV / MDV'),
(129, 'Mali', '223', 'ML / MLI'),
(130, 'Malta', '356', 'MT / MLT'),
(131, 'Marshall Islands', '692', 'MH / MHL'),
(132, 'Mauritania', '222', 'MR / MRT'),
(133, 'Mauritius', '230', 'MU / MUS'),
(134, 'Mayotte', '262', 'YT / MYT'),
(135, 'Mexico', '52', 'MX / MEX'),
(136, 'Micronesia', '691', 'FM / FSM'),
(137, 'Moldova', '373', 'MD / MDA'),
(138, 'Monaco', '377', 'MC / MCO'),
(139, 'Mongolia', '976', 'MN / MNG'),
(140, 'Montenegro', '382', 'ME / MNE'),
(141, 'Montserrat', '1-664', 'MS / MSR'),
(142, 'Morocco', '212', 'MA / MAR'),
(143, 'Mozambique', '258', 'MZ / MOZ'),
(144, 'Myanmar', '95', 'MM / MMR'),
(145, 'Namibia', '264', 'NA / NAM'),
(146, 'Nauru', '674', 'NR / NRU'),
(147, 'Nepal', '977', 'NP / NPL'),
(148, 'Netherlands', '31', 'NL / NLD'),
(149, 'Netherlands Antilles', '599', 'AN / ANT'),
(150, 'New Caledonia', '687', 'NC / NCL'),
(151, 'New Zealand', '64', 'NZ / NZL'),
(152, 'Nicaragua', '505', 'NI / NIC'),
(153, 'Niger', '227', 'NE / NER'),
(154, 'Nigeria', '234', 'NG / NGA'),
(155, 'Niue', '683', 'NU / NIU'),
(156, 'North Korea', '850', 'KP / PRK'),
(157, 'Northern Mariana Islands', '1-670', 'MP / MNP'),
(158, 'Norway', '47', 'NO / NOR'),
(159, 'Oman', '968', 'OM / OMN'),
(160, 'Pakistan', '92', 'PK / PAK'),
(161, 'Palau', '680', 'PW / PLW'),
(162, 'Palestine', '970', 'PS / PSE'),
(163, 'Panama', '507', 'PA / PAN'),
(164, 'Papua New Guinea', '675', 'PG / PNG'),
(165, 'Paraguay', '595', 'PY / PRY'),
(166, 'Peru', '51', 'PE / PER'),
(167, 'Philippines', '63', 'PH / PHL'),
(168, 'Pitcairn', '64', 'PN / PCN'),
(169, 'Poland', '48', 'PL / POL'),
(170, 'Portugal', '351', 'PT / PRT'),
(171, 'Puerto Rico', '1-787, 1-939', 'PR / PRI'),
(172, 'Qatar', '974', 'QA / QAT'),
(173, 'Republic of the Congo', '242', 'CG / COG'),
(174, 'Reunion', '262', 'RE / REU'),
(175, 'Romania', '40', 'RO / ROU'),
(176, 'Russia', '7', 'RU / RUS'),
(177, 'Rwanda', '250', 'RW / RWA'),
(178, 'Saint Barthelemy', '590', 'BL / BLM'),
(179, 'Saint Helena', '290', 'SH / SHN'),
(180, 'Saint Kitts and Nevis', '1-869', 'KN / KNA'),
(181, 'Saint Lucia', '1-758', 'LC / LCA'),
(182, 'Saint Martin', '590', 'MF / MAF'),
(183, 'Saint Pierre and Miquelon', '508', 'PM / SPM'),
(184, 'Saint Vincent and the Grenadines', '1-784', 'VC / VCT'),
(185, 'Samoa', '685', 'WS / WSM'),
(186, 'San Marino', '378', 'SM / SMR'),
(187, 'Sao Tome and Principe', '239', 'ST / STP'),
(188, 'Saudi Arabia', '966', 'SA / SAU'),
(189, 'Senegal', '221', 'SN / SEN'),
(190, 'Serbia', '381', 'RS / SRB'),
(191, 'Seychelles', '248', 'SC / SYC'),
(192, 'Sierra Leone', '232', 'SL / SLE'),
(193, 'Singapore', '65', 'SG / SGP'),
(194, 'Sint Maarten', '1-721', 'SX / SXM'),
(195, 'Slovakia', '421', 'SK / SVK'),
(196, 'Slovenia', '386', 'SI / SVN'),
(197, 'Solomon Islands', '677', 'SB / SLB'),
(198, 'Somalia', '252', 'SO / SOM'),
(199, 'South Africa', '27', 'ZA / ZAF'),
(200, 'South Korea', '82', 'KR / KOR'),
(201, 'South Sudan', '211', 'SS / SSD'),
(202, 'Spain', '34', 'ES / ESP'),
(203, 'Sri Lanka', '94', 'LK / LKA'),
(204, 'Sudan', '249', 'SD / SDN'),
(205, 'Suriname', '597', 'SR / SUR'),
(206, 'Svalbard and Jan Mayen', '47', 'SJ / SJM'),
(207, 'Swaziland', '268', 'SZ / SWZ'),
(208, 'Sweden', '46', 'SE / SWE'),
(209, 'Switzerland', '41', 'CH / CHE'),
(210, 'Syria', '963', 'SY / SYR'),
(211, 'Taiwan', '886', 'TW / TWN'),
(212, 'Tajikistan', '992', 'TJ / TJK'),
(213, 'Tanzania', '255', 'TZ / TZA'),
(214, 'Thailand', '66', 'TH / THA'),
(215, 'Togo', '228', 'TG / TGO'),
(216, 'Tokelau', '690', 'TK / TKL'),
(217, 'Tonga', '676', 'TO / TON'),
(218, 'Trinidad and Tobago', '1-868', 'TT / TTO'),
(219, 'Tunisia', '216', 'TN / TUN'),
(220, 'Turkey', '90', 'TR / TUR'),
(221, 'Turkmenistan', '993', 'TM / TKM'),
(222, 'Turks and Caicos Islands', '1-649', 'TC / TCA'),
(223, 'Tuvalu', '688', 'TV / TUV'),
(224, 'U.S. Virgin Islands', '1-340', 'VI / VIR'),
(225, 'Uganda', '256', 'UG / UGA'),
(226, 'Ukraine', '380', 'UA / UKR'),
(227, 'United Arab Emirates', '971', 'AE / ARE'),
(228, 'United Kingdom', '44', 'GB / GBR'),
(229, 'United States', '1', 'US / USA'),
(230, 'Uruguay', '598', 'UY / URY'),
(231, 'Uzbekistan', '998', 'UZ / UZB'),
(232, 'Vanuatu', '678', 'VU / VUT'),
(233, 'Vatican', '379', 'VA / VAT'),
(234, 'Venezuela', '58', 'VE / VEN'),
(235, 'Vietnam', '84', 'VN / VNM'),
(236, 'Wallis and Futuna', '681', 'WF / WLF'),
(237, 'Western Sahara', '212', 'EH / ESH'),
(238, 'Yemen', '967', 'YE / YEM'),
(239, 'Zambia', '260', 'ZM / ZMB'),
(240, 'Zimbabwe', '263', 'ZW / ZWE');

-- --------------------------------------------------------

--
-- Table structure for table `hd_currencies`
--

CREATE TABLE `hd_currencies` (
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `symbol` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `xrate` decimal(12,5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hd_currencies`
--

INSERT INTO `hd_currencies` (`code`, `name`, `symbol`, `xrate`) VALUES
('AED', 'United Arab Emirates Dirham', 'د.إ', '3.67300'),
('AUD', 'Australian Dollar', '$', '1.44323'),
('BRL', 'Brazilian Real', 'R$', '5.31780'),
('CAD', 'Canadian Dollar', '$', '1.35913'),
('CHF', 'Swiss Franc', 'Fr', '0.94359'),
('CLP', 'Chilean Peso', '$', '808.60018'),
('CNY', 'Chinese Yuan', '¥', '7.06690'),
('CZK', 'Czech Koruna', 'Kč', '23.57940'),
('DKK', 'Danish Krone', 'Kr', '6.60086'),
('EUR', 'Euro', '€', '0.88598'),
('GBP', 'British Pound', '£', '0.79893'),
('HKD', 'Hong Kong Dollar', '$', '7.75015'),
('HUF', 'Hungarian Forint', 'Ft', '311.92134'),
('IDR', 'Indonesian Rupiah', 'Rp', '14413.05000'),
('ILS', 'Israeli New Shekel', '₪', '3.44597'),
('INR', 'Indian Rupee', '₹', '74.85630'),
('JPY', 'Japanese Yen', '¥', '107.44100'),
('KES', 'Kenya shillings', ' KSh', '106.70000'),
('KN', 'Hrvatska kuna', 'kn', '7.00000'),
('KRW', 'Korean Won', '₩', '1199.24000'),
('MXN', 'Mexican Peso', '$', '22.57976'),
('MYR', 'Malaysian Ringgit', 'RM', '4.28600'),
('NGN', 'Nigerian Naira', '₦', '386.41000'),
('NOK', 'Norwegian Krone', 'kr', '9.45566'),
('NZD', 'New Zealand Dollar', '$', '1.53426'),
('PHP', 'Philippine Peso', '₱', '49.75000'),
('PKR', 'Pakistan Rupee', '₨', '166.91480'),
('PLN', 'Polish Zloty', 'zł', '3.95848'),
('RUB', 'Russian Ruble', '₽', '70.24900'),
('SEK', 'Swedish Krona', 'kr', '9.25487'),
('SGD', 'Singapore Dollar', '$', '1.39272'),
('THB', 'Thai Baht', '฿', '31.07250'),
('TRY', 'Turkish Lira', '₺', '6.85480'),
('TWD', 'Taiwan Dollar', '$', '29.44950'),
('USD', 'US Dollar', '$', '1.00000'),
('VEF', 'Bolívar Fuerte', 'Bs.', '248487.64224'),
('ZAR', 'South African Rand', 'R', '16.91680');

-- --------------------------------------------------------

--
-- Table structure for table `hd_departments`
--

CREATE TABLE `hd_departments` (
  `deptid` int(10) NOT NULL,
  `deptname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `depthidden` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hd_departments`
--

INSERT INTO `hd_departments` (`deptid`, `deptname`, `depthidden`) VALUES
(2, 'Support', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hd_email_templates`
--

CREATE TABLE `hd_email_templates` (
  `template_id` int(11) NOT NULL,
  `email_group` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_body` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hd_email_templates`
--

INSERT INTO `hd_email_templates` (`template_id`, `email_group`, `subject`, `template_body`) VALUES
(1, 'registration', 'Registration successful', '                                        <table id=\"backgroundTable\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n  <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n       <tbody>\r\n         <tr>\r\n            <td width=\"600\" height=\"50\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td style=\"color:#999999\" width=\"600\" height=\"90\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" width=\"600\" valign=\"top\" height=\"200\" bgcolor=\"whitesmoke\">           <table style=\"margin-left:15px\" align=\"center\">\r\n             <tbody>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  <h4>New Account</h4>\r\n                  <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {USERNAME},</p>\r\n               <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">      Thank you for registering at {SITE_NAME}. Please find your login details below:<br>Home page: <a href=\"{SITE_URL}\" style=\"color: #11A7DB text-decoration: none\"><strong>{SITE_NAME}</strong></a><br><br>Link doesn\'t work? Copy the link below and paste it in your browser address bar:<br><br>{SITE_URL}<br><br>     Your username: {USERNAME}<br>     Your email address: {EMAIL}<br>     Your password: {PASSWORD}<br><br><br>                   Best Regards,<br>                                   {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td width=\"600\" height=\"10\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(2, 'forgot_password', 'Forgot Password', '                    <table id=\"backgroundTable\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n       <tbody>\r\n         <tr>\r\n            <td width=\"600\" height=\"50\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td style=\"color:#999999\" width=\"600\" height=\"90\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" width=\"600\" valign=\"top\" height=\"200\" bgcolor=\"whitesmoke\">           <table style=\"margin-left:15px\" align=\"center\">\r\n             <tbody>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  <h4>New Password</h4>\r\n                                 <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">      Forgot your password, huh? No big deal.<br>To create a new password, just follow this link:<br>     <a href=\"{PASS_KEY_URL}\" style=\"color: #11A7DB text-decoration: none\"><strong>Create new password</strong></a><br><br>Link doesn\'t work? Copy the link below and paste it in your browser address bar:<br>     {PASS_KEY_URL}<br><br>      You received this email, because it was requested by a {SITE_NAME} user.This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same.<br><br>Thank you,<br><br>                   Best Regards,<br>                                   {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td width=\"600\" height=\"10\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(3, 'change_email', 'Change Email', '                    <table id=\"backgroundTable\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n       <tbody>\r\n         <tr>\r\n            <td width=\"600\" height=\"50\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td style=\"color:#999999\" width=\"600\" height=\"90\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" width=\"600\" valign=\"top\" height=\"200\" bgcolor=\"whitesmoke\">           <table style=\"margin-left:15px \" align=\"center\">\r\n              <tbody>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  <h4>Change Email</h4>\r\n                 <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {NEW_EMAIL},</p>\r\n                <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">      You have changed your email address for {SITE_NAME}.<br>Please follow the link below to confirm your new email address:<br>     <a href=\"{NEW_EMAIL_KEY_URL}\" style=\"color: #11A7DB text-decoration: none\"><strong>Confirm Email</strong></a><br><br>     Link doesn\'t work? Copy the link below and paste it in your browser address bar:<br>     {NEW_EMAIL_KEY_URL}<br><br>     Your email address: {NEW_EMAIL}<br><br>     You received this email, because it was requested by a {SITE_NAME} user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.<br><br>Thank you,<br><br>                    Best Regards,<br>                                   {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td width=\"600\" height=\"10\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(4, 'activate_account', 'Activate Account', '                                                                                <table id=\"backgroundTable\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n       <tbody>\r\n         <tr>\r\n            <td width=\"600\" height=\"50\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td style=\"color:#999999\" width=\"600\" height=\"90\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" width=\"600\" valign=\"top\" height=\"200\" bgcolor=\"whitesmoke\">           <table style=\"margin-left:15px \" align=\"center\">\r\n              <tbody>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  <h4>Activate Account</h4>\r\n                 <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {USERNAME},</p>\r\n               <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">      Thanks for registering at {SITE_NAME}.&nbsp Please follow the link below to verify your email address:<br>      <a href=\"{ACTIVATE_URL}\" style=\"color: #11A7DB text-decoration: none\"><strong>Complete Registration</strong></a><br><br>      Link doesn\'t work? Copy the link below and paste it in your&nbsp browser address bar:<br>      {ACTIVATE_URL}<br>      Please verify your email within {ACTIVATION_PERIOD} hours, otherwise your registration will become invalid and you will have to register again.<br><br>     Your username: {USERNAME}<br>     Your email address: {EMAIL}<br>     Your password: {PASSWORD}<br><br><br>                                         Best Regards,<br>                                   {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td width=\"600\" height=\"10\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(5, 'reset_password', 'Reset Password', '                    <table id=\"backgroundTable\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n       <tbody>\r\n         <tr>\r\n            <td width=\"600\" height=\"50\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td style=\"color:#999999\" width=\"600\" height=\"90\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" width=\"600\" valign=\"top\" height=\"200\" bgcolor=\"whitesmoke\">           <table style=\"margin-left:15px\" align=\"center\">\r\n             <tbody>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  <h4>New Password</h4>\r\n                 <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {USERNAME},</p>\r\n               <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">      You have successfully changed your password.<br><br>Your username: {USERNAME}<br>Your email address: {EMAIL}<br>Your new password: {NEW_PASSWORD}<br><br><br>                   Best Regards,<br>                                   {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td width=\"600\" height=\"10\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(16, 'payment_email', 'Payment Received', '<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" id=\"backgroundTable\">\r\n\r\n <tbody>\r\n\r\n   <tr>\r\n\r\n      <td valign=\"top\">     <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n\r\n       <tbody>\r\n\r\n         <tr>\r\n\r\n            <td height=\"50\" width=\"600\">&nbsp</td>\r\n\r\n          </tr>\r\n\r\n         <tr>\r\n\r\n            <td height=\"90\" style=\"color:#999999\" width=\"600\">{INVOICE_LOGO}</td>\r\n\r\n         </tr>\r\n\r\n         <tr>\r\n\r\n            <td bgcolor=\"whitesmoke\" height=\"200\" style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" valign=\"top\" width=\"600\">           <table align=\"center\" style=\"margin-left:15px\">\r\n\r\n             <tbody>\r\n\r\n               <tr>\r\n\r\n                  <td height=\"10\" width=\"560\">&nbsp</td>\r\n\r\n                </tr>\r\n\r\n               <tr>\r\n\r\n                  <td width=\"560\">                  <h4>Invoice {REF} Payment</h4>\r\n\r\n                  <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Dear Customer,</p>\r\n\r\n               <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">                We have received your payment of {INVOICE_CURRENCY}{PAID_AMOUNT}.<br>               Thank you for your Payment and business. We look forward to working with you again.<br>               --------------------------<br>                                    <br><br>                                    Best Regards,<br>                                   {SITE_NAME}</p>\r\n\r\n                 </td>\r\n\r\n               </tr>\r\n\r\n               <tr>\r\n\r\n                  <td height=\"10\" width=\"560\">&nbsp</td>\r\n\r\n                </tr>\r\n\r\n             </tbody>\r\n\r\n            </table>\r\n\r\n            </td>\r\n\r\n         </tr>\r\n\r\n         <tr>\r\n\r\n            <td height=\"10\" width=\"600\">&nbsp</td>\r\n\r\n          </tr>\r\n\r\n         <tr>\r\n\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n\r\n         </tr>\r\n\r\n       </tbody>\r\n\r\n      </table>\r\n\r\n      </td>\r\n\r\n   </tr>\r\n\r\n </tbody>\r\n\r\n</table>'),
(17, 'invoice_message', 'Customer Invoice', '                                                            <table id=\"backgroundTable\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n       <tbody>\r\n         <tr>\r\n            <td width=\"600\" height=\"50\"> </td>\r\n          </tr>\r\n         <tr>\r\n            <td style=\"color:#999999\" width=\"600\" height=\"90\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" width=\"600\" valign=\"top\" height=\"200\" bgcolor=\"whitesmoke\">           <table style=\"margin-left:15px\" align=\"center\">\r\n             <tbody>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\"> </td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  \r\n                  <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">Dear {CLIENT},<br><br>This is a notice that invoice {REF} has been generated.<br><br>Invoice Reference: {REF}<br>Amount Due: {CURRENCY}{AMOUNT}<br><br>You can use the link below to view and pay the invoice.<br><a href=\"{INVOICE_LINK}\" style=\"color: #11A7DB text-decoration: none\"><strong>View Invoice</strong></a><br>                 <br>                  Best Regards,<br>                 {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\"> </td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td width=\"600\" height=\"10\"> </td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(18, 'invoice_reminder', 'Invoice Reminder', '                    <table id=\"backgroundTable\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n  <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n       <tbody>\r\n         <tr>\r\n            <td width=\"600\" height=\"50\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td style=\"color:#999999\" width=\"600\" height=\"90\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" width=\"600\" valign=\"top\" height=\"200\" bgcolor=\"whitesmoke\">           <table style=\"margin-left:15px\" align=\"center\">\r\n             <tbody>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  <h4>Invoice {REF} Reminder</h4>\r\n                 <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Dear {CLIENT},</p>\r\n               <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">                  This is a friendly reminder to pay your invoice of {CURRENCY}{AMOUNT}<br>                 You can view the invoice online at:<br>                                   <a href=\"{INVOICE_LINK}\" style=\"color: #11A7DB text-decoration: none\"><strong>View Invoice</strong>                 </a><br><br>                                    Best Regards,<br>                                   {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td width=\"600\" height=\"10\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(19, 'message_received', 'Message Received', '<table align=\"center\" id=\"backgroundTable\">\n <tbody>\n   <tr>\n      <td valign=\"top\">     <table align=\"center\">\n        <tbody>\n         <tr>\n            <td height=\"50\" width=\"600\">&nbsp</td>\n          </tr>\n         <tr>\n            <td height=\"90\" style=\"color:#999999\" width=\"600\">{INVOICE_LOGO}</td>\n         </tr>\n         <tr>\n            <td bgcolor=\"whitesmoke\" height=\"200\" style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" valign=\"top\" width=\"600\">           <table align=\"center\" style=\"margin-left:15px \">\n              <tbody>\n               <tr>\n                  <td height=\"10\" width=\"560\">&nbsp</td>\n                </tr>\n               <tr>\n                  <td width=\"560\">                  <h4>New message received</h4>\n                 <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {RECIPIENT},</p>\n                <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">You have received a message from <strong>{SENDER}</strong>.<br>------------------------------------------------------------------:<br><span style=\"font-style:italic\">{MESSAGE}</span><br><br><a href=\"{SITE_URL}\" style=\"color: #11A7DB text-decoration: none\"><strong>View Message</strong></a><br><br><br>                 Best Regards,                 <br>                                    {SITE_NAME}</p>\n                 </td>\n               </tr>\n               <tr>\n                  <td height=\"10\" width=\"560\">&nbsp</td>\n                </tr>\n             </tbody>\n            </table>\n            </td>\n         </tr>\n         <tr>\n            <td height=\"10\" width=\"600\">&nbsp</td>\n          </tr>\n         <tr>\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\n         </tr>\n       </tbody>\n      </table>\n      </td>\n   </tr>\n </tbody>\n</table>'),
(21, 'ticket_staff_email', 'Ticket [SUBJECT]', '                    <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" id=\"backgroundTable\">\r\n  <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n       <tbody>\r\n         <tr>\r\n            <td height=\"50\" width=\"600\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td height=\"90\" style=\"color:#999999\" width=\"600\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td bgcolor=\"whitesmoke\" height=\"200\" style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" valign=\"top\" width=\"600\">           <table align=\"center\" style=\"margin-left:15px\">\r\n             <tbody>\r\n               <tr>\r\n                  <td height=\"10\" width=\"560\">&nbsp</td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  <h4>New Ticket</h4>\r\n                 <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {USER_EMAIL},</p>\r\n               <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">Ticket <strong>{SUBJECT}</strong> has been opened.<br>You may view the ticket by clicking on the following link:<br>Client Email : {REPORTER_EMAIL}<br><br><a href=\"{TICKET_LINK}\" style=\"color: #11A7DB text-decoration: none\"><strong>View Ticket</strong></a><br><br><br>                  Best Regards,                 <br>                                    {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td height=\"10\" width=\"560\">&nbsp</td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td height=\"10\" width=\"600\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(22, 'ticket_client_email', 'Ticket [SUBJECT]', '                                                                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" id=\"backgroundTable\">\r\n <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n       <tbody>\r\n         <tr>\r\n            <td height=\"50\" width=\"600\"> </td>\r\n          </tr>\r\n         <tr>\r\n            <td height=\"90\" style=\"color:#999999\" width=\"600\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td bgcolor=\"whitesmoke\" height=\"200\" style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" valign=\"top\" width=\"600\">           <table align=\"center\" style=\"margin-left:15px\">\r\n             <tbody>\r\n               <tr>\r\n                  <td height=\"10\" width=\"560\"> </td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  <h4>Ticket Opened</h4>\r\n                  <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {CLIENT_EMAIL},</p>\r\n               <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">      Your ticket has been opened with us.<br>Ticket <strong>{SUBJECT}</strong><br>Status : Open<br>Click on the below link to see the ticket details and post replies: <br><a href=\"{TICKET_LINK}\" style=\"color: #11A7DB text-decoration: none\"><strong>View Ticket</strong></a><br><br><br>                 Best Regards,                 <br>                                    {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td height=\"10\" width=\"560\"> </td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td height=\"10\" width=\"600\"> </td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(23, 'ticket_reply_email', 'Ticket [SUBJECT] response', '                                                                                <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" id=\"backgroundTable\"> <tbody>   <tr>      <td valign=\"top\">     <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">       <tbody>         <tr>            <td height=\"50\" width=\"600\">&nbsp</td>          </tr>         <tr>            <td height=\"90\" style=\"color:#999999\" width=\"600\">{INVOICE_LOGO}</td>         </tr>         <tr>            <td bgcolor=\"whitesmoke\" height=\"200\" style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" valign=\"top\" width=\"600\">           <table align=\"center\" style=\"margin-left:15px\">             <tbody>               <tr>                  <td height=\"10\" width=\"560\">&nbsp</td>                </tr>               <tr>                  <td width=\"560\">                  <h4>Ticket Response</h4>                  <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi There,</p>                <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">A new response has been added to Ticket <strong>{SUBJECT}</strong><br><br>Ticket : <strong>#{TICKET_CODE}</strong><br>Status : <strong>{TICKET_STATUS}</strong><br><span style=\"font-style:italic\">{TICKET_REPLY}</span><br><br>\r\nTo see the response and post additional comments, click on the link below:<br><a href=\"{TICKET_LINK}\" style=\"color: #11A7DB text-decoration: none\"><strong>View Ticket</strong></a><br><br>               Best Regards,<br>                                   {SITE_NAME}</p>                 </td>               </tr>               <tr>                  <td height=\"10\" width=\"560\">&nbsp</td>                </tr>             </tbody>            </table>            </td>         </tr>         <tr>            <td height=\"10\" width=\"600\">&nbsp</td>          </tr>         <tr>            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>         </tr>       </tbody>      </table>      </td>   </tr> </tbody></table>'),
(24, 'ticket_closed_email', 'Ticket [SUBJECT] closed', '                    <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" id=\"backgroundTable\">  <tbody>   <tr>      <td valign=\"top\">     <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">       <tbody>         <tr>            <td height=\"50\" width=\"600\">&nbsp</td>          </tr>         <tr>            <td height=\"90\" style=\"color:#999999\" width=\"600\">{INVOICE_LOGO}</td>         </tr>         <tr>            <td bgcolor=\"whitesmoke\" height=\"200\" style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" valign=\"top\" width=\"600\">           <table align=\"center\" style=\"margin-left:15px\">             <tbody>               <tr>                  <td height=\"10\" width=\"560\">&nbsp</td>                </tr>               <tr>                  <td width=\"560\">                  <h4>Ticket Closed</h4>                  <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {REPORTER_EMAIL},</p>               <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">      Ticket <strong>{SUBJECT}</strong> has been closed by <strong>{STAFF_USERNAME}</strong><br>Ticket : <strong>#{TICKET_CODE}</strong><br>Status : <strong>{TICKET_STATUS}</strong><br>To see the responses or open the ticket, click on the link below:<br><a href=\"{TICKET_LINK}\" style=\"color: #11A7DB text-decoration: none\"><strong>View Ticket</strong></a><br><br><br>                 Best Regards,                 <br>                                    {SITE_NAME}</p>                 </td>               </tr>               <tr>                  <td height=\"10\" width=\"560\">&nbsp</td>                </tr>             </tbody>            </table>            </td>         </tr>         <tr>            <td height=\"10\" width=\"600\">&nbsp</td>          </tr>         <tr>            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>         </tr>       </tbody>      </table>      </td>   </tr> </tbody></table>'),
(27, 'email_signature', NULL, '                                        Powered by Hosting Domain<br>'),
(28, 'auto_close_ticket', 'Ticket Auto Closed', '                                        <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" id=\"backgroundTable\">\r\n <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n       <tbody>\r\n         <tr>\r\n            <td height=\"50\" width=\"600\"> </td>\r\n          </tr>\r\n         <tr>\r\n            <td height=\"90\" style=\"color:#999999\" width=\"600\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td bgcolor=\"whitesmoke\" height=\"200\" style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" valign=\"top\" width=\"600\">           <table align=\"center\" style=\"margin-left:15px\">\r\n             <tbody>\r\n               <tr>\r\n                  <td height=\"10\" width=\"560\"> </td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  <h4>Ticket Closed</h4>\r\n                  <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {REPORTER_EMAIL},</p>\r\n               <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">Ticket <strong>{SUBJECT}</strong> has been auto closed due to inactivity. <br><br>Ticket : <strong>#{TICKET_CODE}</strong><br>Status : <strong>{TICKET_STATUS}</strong><br>To see the responses or open the ticket, click on the link below:<br><a href=\"{TICKET_LINK}\" style=\"color: #11A7DB text-decoration: none\"><strong>View Ticket</strong></a><br><br><br>                 Best Regards,                 <br>                                    {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td height=\"10\" width=\"560\"> </td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td height=\"10\" width=\"600\"> </td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(32, 'ticket_reopened_email', 'Ticket [SUBJECT] reopened', '                                                            <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" id=\"backgroundTable\">\r\n  <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n       <tbody>\r\n         <tr>\r\n            <td height=\"50\" width=\"600\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td height=\"90\" style=\"color:#999999\" width=\"600\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td bgcolor=\"whitesmoke\" height=\"200\" style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" valign=\"top\" width=\"600\">           <table align=\"center\" style=\"margin-left:15px\">\r\n             <tbody>\r\n               <tr>\r\n                  <td height=\"10\" width=\"560\">&nbsp</td>\r\n                </tr>\r\n               <tr>\r\n                  <td width=\"560\">                  <h4>Ticket re-opened</h4>\r\n                 <p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {RECIPIENT},</p>\r\n                <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">      Ticket <b>{SUBJECT}</b> was re-opened by <b>{USER}</b>.<br>Status : Open<br>Click on the below link to see the ticket details and post replies: <br><a href=\"{TICKET_LINK}\" style=\"color: #11A7DB text-decoration: none\"><strong>View Ticket</strong></a><br><br><br>                 Best Regards,                 <br>                                    {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td height=\"10\" width=\"560\">&nbsp</td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td height=\"10\" width=\"600\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(34, 'hosting_account', 'Hosting Account Information', '                                                                                                                                                                                                                                                                                                                                <table id=\"backgroundTable\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n  <tbody>\r\n   <tr>\r\n      <td valign=\"top\">     <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\r\n       <tbody>\r\n         <tr>\r\n            <td width=\"600\" height=\"50\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td style=\"color:#999999\" width=\"600\" height=\"90\">{INVOICE_LOGO}</td>\r\n         </tr>\r\n         <tr>\r\n            <td style=\"background:whitesmoke border:1px solid #e0e0e0 font-family:Helvetica,Arial,sans-serif\" width=\"600\" valign=\"top\" height=\"200\" bgcolor=\"whitesmoke\">           <table style=\"margin-left:15px \" align=\"center\">\r\n              <tbody>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td></tr><tr><td width=\"560\"><p style=\"font-size:12px font-family:Helvetica,Arial,sans-serif\">Hi {CLIENT},</p>\r\n                <p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\">      Thank you for using {SITE_NAME}. <br>Your hosting account for&nbsp{DOMAIN} has been activated.</p><h3>ACCOUNT DETAILS</h3><p style=\"font-size:12px line-height:20px font-family:Helvetica,Arial,sans-serif\"><b>Username:</b> {ACCOUNT_USERNAME}<br><b>Password:</b> {ACCOUNT_PASSWORD}<br><b>Next Invoice Date</b>: {RENEWAL_DATE}<br><b>Package: </b>{PACKAGE}<br><b>Payment:</b> {RENEWAL}<br><b>Amount:</b> {AMOUNT}<br><br>                                         Best Regards,<br>                                   {SITE_NAME}</p>\r\n                 </td>\r\n               </tr>\r\n               <tr>\r\n                  <td width=\"560\" height=\"10\">&nbsp</td>\r\n                </tr>\r\n             </tbody>\r\n            </table>\r\n            </td>\r\n         </tr>\r\n         <tr>\r\n            <td width=\"600\" height=\"10\">&nbsp</td>\r\n          </tr>\r\n         <tr>\r\n            <td align=\"right\"><span style=\"font-size:10px color:#999999 font-family:Helvetica,Arial,sans-serif\">{SIGNATURE}</span></td>\r\n         </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n   </tr>\r\n </tbody>\r\n</table>'),
(36, 'service_suspended', 'Account Suspended', '<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n <tbody>\r\n  <tr>\r\n   <td xss=removed>\r\n   <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody>\r\n     <tr>\r\n      <td> </td>\r\n     </tr>\r\n     <tr>\r\n      <td>{INVOICE_LOGO}</td>\r\n     </tr>\r\n     <tr>\r\n      <td xss=removed>\r\n      <table align=\"center\">\r\n       <tbody>\r\n        <tr>\r\n         <td> </td>\r\n        </tr>\r\n        <tr>\r\n         <td>\r\n         <p>Dear {CLIENT},</p>\r\n\r\n         <p>This is a notification that your service has been suspended.</p>\r\n\r\n         <p>Service: {PACKAGE}<br>\r\n         Due Date: {RENEWAL_DATE}<br>\r\n         Amount Due: {AMOUNT}</p>\r\n\r\n         <p><br>\r\n         Please contact us as soon as possible to get your service reactivated.<br>\r\n         <br>\r\n         Best Regards,<br>\r\n         {SITE_NAME}</p>\r\n         </td>\r\n        </tr>\r\n        <tr>\r\n         <td> </td>\r\n        </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n     </tr>\r\n     <tr>\r\n      <td> </td>\r\n     </tr>\r\n     <tr>\r\n      <td><span xss=removed>{SIGNATURE}</span></td>\r\n     </tr>\r\n    </tbody>\r\n   </table>\r\n   </td>\r\n  </tr>\r\n </tbody>\r\n</table>\r\n'),
(37, 'service_unsuspended', 'Account Unsuspended', '<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n <tbody>\r\n  <tr>\r\n   <td xss=removed>\r\n   <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody>\r\n     <tr>\r\n      <td> </td>\r\n     </tr>\r\n     <tr>\r\n      <td>{INVOICE_LOGO}</td>\r\n     </tr>\r\n     <tr>\r\n      <td xss=removed>\r\n      <table align=\"center\">\r\n       <tbody>\r\n        <tr>\r\n         <td> </td>\r\n        </tr>\r\n        <tr>\r\n         <td>\r\n         <p>Dear {CLIENT},</p>\r\n\r\n         <p>This is a notification that your service has been unsuspended.</p>\r\n\r\n         <p>Service: {PACKAGE}</p>\r\n\r\n         <p> \r\n         <br>\r\n         Best Regards,<br>\r\n         {SITE_NAME}</p>\r\n         </td>\r\n        </tr>\r\n        <tr>\r\n         <td> </td>\r\n        </tr>\r\n       </tbody>\r\n      </table>\r\n      </td>\r\n     </tr>\r\n     <tr>\r\n      <td> </td>\r\n     </tr>\r\n     <tr>\r\n      <td><span xss=removed>{SIGNATURE}</span></td>\r\n     </tr>\r\n    </tbody>\r\n   </table>\r\n   </td>\r\n  </tr>\r\n </tbody>\r\n</table>\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `hd_fields`
--

CREATE TABLE `hd_fields` (
  `id` int(10) NOT NULL,
  `deptid` int(10) NOT NULL,
  `module` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `form_id` int(11) DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uniqid` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `required` int(11) DEFAULT NULL,
  `field_options` text COLLATE utf8_unicode_ci,
  `cid` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hd_files`
--

CREATE TABLE `hd_files` (
  `file_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT '0',
  `file_name` text COLLATE utf8_unicode_ci,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `path` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ext` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` int(10) DEFAULT NULL,
  `is_image` int(2) DEFAULT NULL,
  `image_width` int(5) DEFAULT NULL,
  `image_height` int(5) DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `uploaded_by` int(11) NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hd_formmeta`
--

CREATE TABLE `hd_formmeta` (
  `id` int(11) UNSIGNED NOT NULL,
  `module` varchar(64) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `field_id` int(11) DEFAULT NULL,
  `meta_key` varchar(64) DEFAULT NULL,
  `meta_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_formmeta`
--

INSERT INTO `hd_formmeta` (`id`, `module`, `client_id`, `ticket_id`, `field_id`, `meta_key`, `meta_value`) VALUES
(1, 'clients', 2, NULL, 1, 'cell', '12345'),
(2, 'clients', 2, NULL, 2, 'tax-number', '1234566'),
(3, 'clients', 1, NULL, 2, 'tax-number', '222222');

-- --------------------------------------------------------

--
-- Table structure for table `hd_hooks`
--

CREATE TABLE `hd_hooks` (
  `module` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `parent` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hook` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `icon` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `route` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order` int(10) DEFAULT NULL,
  `access` int(2) NOT NULL,
  `core` int(2) DEFAULT NULL,
  `visible` int(2) DEFAULT '1',
  `permission` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` int(2) NOT NULL DEFAULT '1',
  `last_run` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hd_hooks`
--

INSERT INTO `hd_hooks` (`module`, `parent`, `hook`, `icon`, `name`, `route`, `order`, `access`, `core`, `visible`, `permission`, `enabled`, `last_run`) VALUES
('cron_backup_db', '', 'cron_job_admin', 'fa-database', 'auto_backup_database', 'crons/backup_db', 7, 1, 1, 1, '', 1, '2021-05-31 07:28:13'),
('cron_close_tickets', '', 'cron_job_admin', 'fa-ticket', 'auto_close_tickets', 'crons/close_tickets', 5, 1, 1, 1, '', 1, '2021-05-31 07:28:12'),
('cron_fetch_tickets', '', 'cron_job_admin', 'fa-ticket', 'fetch_ticket_emails', 'crons/fetch_tickets', 6, 1, 1, 1, '', 1, '2021-05-31 07:28:12'),
('cron_hosting', '', 'cron_job_admin', 'fa-server', 'hosting_renewals', 'crons/accounts', 3, 1, 1, 1, '', 1, '2021-05-31 07:28:12'),
('cron_invoices', '', 'cron_job_admin', 'fa-clock-o', 'overdue_invoices', 'crons/invoices', 3, 1, 1, 1, '', 1, '2021-05-31 07:28:12'),
('cron_outgoing', '', 'cron_job_admin', 'fa-envelope-o', 'pending_emails', 'crons/outgoing_emails', 4, 1, 1, 1, '', 1, '2021-05-31 07:28:12'),
('cron_recurring', '', 'cron_job_admin', 'fa-retweet', 'recurring_invoices', 'crons/recur', 1, 1, 1, 1, '', 1, '2021-05-31 07:28:12'),
('cron_suspensions', '', 'cron_job_admin', 'fa-ban', 'hosting_suspensions', 'crons/suspensions', 3, 1, 1, 1, '', 1, '2021-05-31 07:28:12'),
('cron_terminations', '', 'cron_job_admin', 'fa-trash', 'hosting_terminations', 'crons/terminations', 3, 1, 1, 1, '', 1, '2021-05-31 07:28:12'),
('cron_updates', '', 'cron_job_admin', 'fa-cloud-download', 'updates_bug_fixes', 'crons/updates', 7, 1, 1, 1, '', 1, '2021-05-31 07:28:12'),
('cron_xrates', '', 'cron_job_admin', 'fa-money', 'open_exchange_rates', 'crons/xrates', 8, 1, 1, 1, '', 1, '2021-05-31 07:28:13'),
('menu_accounts', '', 'main_menu_admin', 'fa-server', 'accounts', 'accounts', 3, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_accounts', '', 'main_menu_client', 'fa-server', 'accounts', 'accounts', 4, 2, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_accounts', '', 'main_menu_staff', 'fa-server', 'accounts', 'accounts', 3, 3, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_clients', '', 'main_menu_admin', 'fa-group', 'clients', 'companies', 7, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_clients', '', 'main_menu_staff', 'fa-users', 'clients', 'companies', 6, 3, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_dashboard', '', 'main_menu_admin', 'fa-cloud-download', 'dashboard', 'dashboard', 1, 1, 1, 1, '1', 1, '0000-00-00 00:00:00'),
('menu_dashboard', '', 'main_menu_staff', 'fa-tachometer', 'dashboard', 'dashboard', 1, 3, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_domains', '', 'main_menu_admin', 'fa-tachometer', 'domains', 'domains', 4, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_domains', '', 'main_menu_client', 'fa-globe', 'domains', 'domains', 2, 2, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_domains', '', 'main_menu_staff', 'fa-globe', 'domains', 'domains', 4, 3, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_home', '', 'main_menu_client', 'fa-dashboard', 'dashboard', 'clients', 1, 2, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_invoices', 'menu_sales', 'main_menu_admin', 'fa-money', 'invoices', 'invoices', 5, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_invoices', '', 'main_menu_client', 'fa-list', 'invoices', 'invoices', 5, 2, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_invoices', '', 'main_menu_staff', 'fa-list', 'invoices', 'invoices', 5, 3, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_items', 'menu_sales', 'main_menu_admin', 'fa-align-justify', 'items', 'items', 8, 0, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_orders', '', 'main_menu_admin', 'fa-shopping-basket', 'orders', 'orders', 2, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_orders', '', 'main_menu_client', 'fa-shopping-basket', 'new_order', 'orders/add_order', 7, 2, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_orders', '', 'main_menu_staff', 'fa-shopping-basket', 'orders', 'orders', 2, 3, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_payments', 'menu_sales', 'main_menu_admin', 'fa-credit-card', 'payments', 'payments', 6, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_payments', '', 'main_menu_client', 'fa-money', 'payments', 'payments', 3, 2, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_payments', '', 'main_menu_staff', 'fa-money', 'payments', 'payments', 7, 3, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_plugins', 'menu_system', 'main_menu_admin', 'fa-chain', 'plugins', 'plugins', 1, 1, 1, 1, '', 1, '1000-10-10 10:10:10'),
('menu_reports', '', 'main_menu_admin', 'fa-bar-chart-o', 'reports', 'reports', 13, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_reports_clientinvoices', 'menu_reports', 'main_menu_admin', 'fa-users', 'client_invoices', 'reports/view/invoicesbyclient', 4, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_reports_dashboard', 'menu_reports', 'main_menu_admin', 'fa-tachometer', 'report_dashboard', 'reports', 1, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_reports_invoices', 'menu_reports', 'main_menu_admin', 'fa-list', 'invoice_reports', 'reports/view/invoicesreport', 2, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_reports_payments', 'menu_reports', 'main_menu_admin', 'fa-credit-card', 'payments', 'reports/view/paymentsreport', 3, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_sales', '', 'main_menu_admin', 'fa-credit-card', 'sales', 'invoices', 5, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_settings', 'menu_system', 'main_menu_admin', 'fa-cogs', 'settings', 'settings', 1, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_system', '', 'main_menu_admin', 'fa-cogs', 'system', 'settings', 11, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_tax_rates', 'menu_system', 'main_menu_admin', 'fa-pencil-square-o', 'tax_rates', 'tax_rates', 9, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_tickets', '', 'main_menu_admin', 'fa-ticket', 'support', 'tickets', 10, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_tickets', '', 'main_menu_client', 'fa-ticket', 'tickets', 'tickets', 6, 2, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_tickets', '', 'main_menu_staff', 'fa-ticket', 'tickets', 'tickets', 8, 3, 1, 1, '', 1, '0000-00-00 00:00:00'),
('menu_updates', 'menu_system', 'main_menu_admin', 'fa-cloud-download', 'updates', 'updates', 9, 1, 1, 1, '', 1, '1000-10-10 10:10:10'),
('menu_users', 'menu_system', 'main_menu_admin', 'fa-lock', 'users', 'account', 12, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_cron', '', 'settings_menu_admin', 'fa-rocket', 'cron_settings', 'crons', 13, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_currency', '', 'settings_menu_admin', 'fa-credit-card', 'currency_settings', 'currency', 5, 1, 1, 1, '', 1, '1000-01-01 01:01:01'),
('settings_custom_fields', '', 'settings_menu_admin', 'fa-star-o', 'custom_fields', 'fields', 11, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_departments', '', 'settings_menu_admin', 'fa-sitemap', 'departments', 'departments', 9, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_domain', '', 'settings_menu_admin', 'fa-folder', 'domain_settings', 'domain', 3, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_email', '', 'settings_menu_admin', 'fa-envelope-o', 'email_settings', 'email', 6, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_email_templates', '', 'settings_menu_admin', 'fa-pencil-square', 'email_templates', 'templates', 7, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_general', '', 'settings_menu_admin', 'fa-info-circle', 'general_settings', 'general', 1, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_invoice', 'menu_sales', 'settings_menu_admin', 'fa-money', 'invoice_settings', 'invoice', 6, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_menu', '', 'settings_menu_admin', 'fa-list-alt', 'menu_settings', 'menu', 10, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_search', '', 'settings_menu_admin', 'fa-search', 'search_settings', 'search', 3, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_system', '', 'settings_menu_admin', 'fa-desktop', 'system_settings', 'system', 2, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_theme', '', 'settings_menu_admin', 'fa-code', 'theme_settings', 'theme', 9, 1, 1, 1, '', 1, '0000-00-00 00:00:00'),
('settings_translations', '', 'settings_menu_admin', 'fa-globe', 'translations', 'translations', 12, 1, 1, 1, '', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `hd_domains`
--

CREATE TABLE `hd_domains` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` varchar(255) DEFAULT NULL,
  `parent_category` varchar(255) DEFAULT NULL,
  `ext_name` varchar(255) DEFAULT NULL,
  `registrar` varchar(255) DEFAULT NULL,
  `currency_amt` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`currency_amt`)),
  `registration` float(10,2) DEFAULT NULL,
  `registration_1` float(10,2) DEFAULT NULL,
  `registration_2` float(10,2) DEFAULT NULL,
  `registration_3` float(10,2) DEFAULT NULL,
  `registration_4` float(10,2) DEFAULT NULL,
  `registration_5` float(10,2) DEFAULT NULL,
  `registration_6` float(10,2) DEFAULT NULL,
  `registration_7` float(10,2) DEFAULT NULL,
  `registration_8` float(10,2) DEFAULT NULL,
  `registration_9` float(10,2) DEFAULT NULL,
  `registration_10` float(10,2) DEFAULT NULL,
  `transfer` float(10,2) DEFAULT NULL,
  `transfer_1` float(10,2) DEFAULT NULL,
  `transfer_2` float(10,2) DEFAULT NULL,
  `transfer_3` float(10,2) DEFAULT NULL,
  `transfer_4` float(10,2) DEFAULT NULL,
  `transfer_5` float(10,2) DEFAULT NULL,
  `transfer_6` float(10,2) DEFAULT NULL,
  `transfer_7` float(10,2) DEFAULT NULL,
  `transfer_8` float(10,2) DEFAULT NULL,
  `transfer_9` float(10,2) DEFAULT NULL,
  `transfer_10` float(10,2) DEFAULT NULL,
  `renewal` float(10,2) DEFAULT NULL,
  `renewal_1` float(10,2) DEFAULT NULL,
  `renewal_2` float(10,2) DEFAULT NULL,
  `renewal_3` float(10,2) DEFAULT NULL,
  `renewal_4` float(10,2) DEFAULT NULL,
  `renewal_5` float(10,2) DEFAULT NULL,
  `renewal_6` float(10,2) DEFAULT NULL,
  `renewal_7` float(10,2) DEFAULT NULL,
  `renewal_8` float(10,2) DEFAULT NULL,
  `renewal_9` float(10,2) DEFAULT NULL,
  `renewal_10` float(10,2) DEFAULT NULL,
  `max_years` int(11) DEFAULT NULL,
  `tax_rate` enum('Yes','No') DEFAULT 'No',
  `ext_order` int(11) DEFAULT NULL,
  `display` enum('yes','no') DEFAULT 'yes'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Table structure for table `hd_images`
--

CREATE TABLE `hd_images` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_images`
--

INSERT INTO `hd_images` (`id`, `image`, `thumb`, `post_id`) VALUES
(1, 'no-image.jpg', 'no-image.jpg', 1),
(2, 'no-image.jpg', 'no-image.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `hd_invoices`
--

CREATE TABLE `hd_invoices` (
  `inv_id` int(11) NOT NULL,
  `reference_no` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `due_date` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `allow` int(11) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax2` decimal(10,2) DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
	`discount_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
	`discount_percentage` float(10,2) NULL,
	`discount_amount` float(10,2) NULL,
  `recurring` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `r_freq` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '31',
  `recur_start_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recur_end_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recur_frequency` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recur_next_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USD',
  `status` enum('Unpaid','Paid','Cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Unpaid',
  `archived` int(11) DEFAULT '0',
  `date_sent` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inv_deleted` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `date_saved` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `emailed` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `show_client` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `viewed` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `alert_overdue` int(11) DEFAULT '0',
  `extra_fee` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hd_items`
--

CREATE TABLE `hd_items` (
  `item_id` int(11) NOT NULL,
  `item_tax_rate` decimal(10,2) DEFAULT '0.00',
  `item_tax_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) DEFAULT '0.00',
  `total_cost` decimal(10,2) DEFAULT '0.00',
  `invoice_id` int(11) NOT NULL,
  `item_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Item Name',
  `item_desc` longtext COLLATE utf8_unicode_ci,
  `unit_cost` decimal(10,2) DEFAULT '0.00',
  `item_order` int(11) DEFAULT '0',
  `date_saved` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hd_items_saved`
--

CREATE TABLE `hd_items_saved` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(200) COLLATE utf8_unicode_ci DEFAULT 'Item Name',
  `item_desc` longtext COLLATE utf8_unicode_ci,
  `item_features` longtext COLLATE utf8_unicode_ci,
  `package_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_cost` decimal(10,2) DEFAULT '0.00',
  `item_tax_rate` varchar(255) NULL,
  `item_tax_total` decimal(10,2) DEFAULT '0.00',
  `quantity` decimal(10,2) DEFAULT '0.00',
  `total_cost` decimal(10,2) DEFAULT '0.00',
  `setup_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payments` int(11) NOT NULL,
	`item_type` varchar(255) NULL,
	`account_option` varchar(255) NULL,
	`commission` varchar(255) NOT NULL DEFAULT 'No',
	`commission_amount` decimal(10,2) DEFAULT '0.00',
	`commission_payout` varchar(255) NULL,
	`cat_type` varchar(255) NULL,
  `max_payments` int(11) NOT NULL,
  `max_years` int(11) NOT NULL DEFAULT '1',
  `deleted` enum('Yes','No') COLLATE utf8_unicode_ci DEFAULT 'No',
  `order_by` int(11) NOT NULL,
  `require_domain` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `reseller_package` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `allow_upgrade` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `server` VARCHAR(50) NULL DEFAULT NULL,
  `default_registrar` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_account` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `display` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `active` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Yes',
  `package_config` text COLLATE utf8_unicode_ci NOT NULL,
  `addon` int(11) NOT NULL DEFAULT '0',
  `apply_to` text COLLATE utf8_unicode_ci,
  `items_saved_status` tinyint COLLATE utf8_unicode_ci NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hd_items_saved`
--

-- --------------------------------------------------------

--
-- Table structure for table `hd_item_pricing`
--

CREATE TABLE `hd_item_pricing` (
  `item_pricing_id` int(11) NOT NULL,
  `ext_name` varchar(12) DEFAULT NULL,
  `item_id` int(11) NOT NULL,
  `domain_id` varchar(255) DEFAULT NULL,
  `category` int(11) NOT NULL DEFAULT 0,
  `currency_amt` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`currency_amt`)),
  `monthly` decimal(10,2) DEFAULT 0.00,
  `quarterly` decimal(10,2) DEFAULT 0.00,
  `semi_annually` decimal(10,2) DEFAULT 0.00,
  `annually` decimal(10,2) DEFAULT 0.00,
  `biennially` decimal(10,2) DEFAULT 0.00,
  `triennially` decimal(10,2) DEFAULT 0.00,
  `registration` decimal(10,2) DEFAULT 0.00,
  `transfer` decimal(10,2) DEFAULT 0.00,
  `renewal` decimal(10,2) DEFAULT 0.00,
  `monthly_payments` int(11) NOT NULL,
  `quarterly_payments` int(11) NOT NULL,
  `semi_annually_payments` int(11) NOT NULL,
  `annually_payments` int(11) NOT NULL,
  `biennially_payments` int(11) NOT NULL,
  `triennially_payments` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `hd_item_pricing`
--

-- --------------------------------------------------------

--
-- Table structure for table `hd_languages`
--

CREATE TABLE `hd_languages` (
  `lang_id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locale` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` int(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hd_languages`
--

INSERT INTO `hd_languages` (`code`, `name`, `locale_name`, `locale`, `icon`, `active`) VALUES
('en', 'english', 'English (United States)', 'en_US', 'us', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hd_links`
--

CREATE TABLE `hd_links` (
  `link_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `client` int(11) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `link_title` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `description` text,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hd_locales`
--

CREATE TABLE `hd_locales` (
  `locale` varchar(10) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `name` varchar(250) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_locales`
--

INSERT INTO `hd_locales` (`locale`, `code`, `language`, `name`) VALUES
('aa_DJ', 'aa', 'afar', 'Afar (Djibouti)'),
('aa_ER', 'aa', 'afar', 'Afar (Eritrea)'),
('aa_ET', 'aa', 'afar', 'Afar (Ethiopia)'),
('af_ZA', 'af', 'afrikaans', 'Afrikaans (South Africa)'),
('am_ET', 'am', 'amharic', 'Amharic (Ethiopia)'),
('an_ES', 'an', 'aragonese', 'Aragonese (Spain)'),
('ar_AE', 'ar', 'arabic', 'Arabic (United Arab Emirates)'),
('ar_BH', 'ar', 'arabic', 'Arabic (Bahrain)'),
('ar_DZ', 'ar', 'arabic', 'Arabic (Algeria)'),
('ar_EG', 'ar', 'arabic', 'Arabic (Egypt)'),
('ar_IN', 'ar', 'arabic', 'Arabic (India)'),
('ar_IQ', 'ar', 'arabic', 'Arabic (Iraq)'),
('ar_JO', 'ar', 'arabic', 'Arabic (Jordan)'),
('ar_KW', 'ar', 'arabic', 'Arabic (Kuwait)'),
('ar_LB', 'ar', 'arabic', 'Arabic (Lebanon)'),
('ar_LY', 'ar', 'arabic', 'Arabic (Libya)'),
('ar_MA', 'ar', 'arabic', 'Arabic (Morocco)'),
('ar_OM', 'ar', 'arabic', 'Arabic (Oman)'),
('ar_QA', 'ar', 'arabic', 'Arabic (Qatar)'),
('ar_SA', 'ar', 'arabic', 'Arabic (Saudi Arabia)'),
('ar_SD', 'ar', 'arabic', 'Arabic (Sudan)'),
('ar_SY', 'ar', 'arabic', 'Arabic (Syria)'),
('ar_TN', 'ar', 'arabic', 'Arabic (Tunisia)'),
('ar_YE', 'ar', 'arabic', 'Arabic (Yemen)'),
('ast_ES', 'ast', 'asturian', 'Asturian (Spain)'),
('as_IN', 'as', 'assamese', 'Assamese (India)'),
('az_AZ', 'az', 'azerbaijani', 'Azerbaijani (Azerbaijan)'),
('az_TR', 'az', 'azerbaijani', 'Azerbaijani (Turkey)'),
('bem_ZM', 'bem', 'bemba', 'Bemba (Zambia)'),
('ber_DZ', 'ber', 'berber', 'Berber (Algeria)'),
('ber_MA', 'ber', 'berber', 'Berber (Morocco)'),
('be_BY', 'be', 'belarusian', 'Belarusian (Belarus)'),
('bg_BG', 'bg', 'bulgarian', 'Bulgarian (Bulgaria)'),
('bn_BD', 'bn', 'bengali', 'Bengali (Bangladesh)'),
('bn_IN', 'bn', 'bengali', 'Bengali (India)'),
('bo_CN', 'bo', 'tibetan', 'Tibetan (China)'),
('bo_IN', 'bo', 'tibetan', 'Tibetan (India)'),
('br_FR', 'br', 'breton', 'Breton (France)'),
('bs_BA', 'bs', 'bosnian', 'Bosnian (Bosnia and Herzegovina)'),
('byn_ER', 'byn', 'blin', 'Blin (Eritrea)'),
('ca_AD', 'ca', 'catalan', 'Catalan (Andorra)'),
('ca_ES', 'ca', 'catalan', 'Catalan (Spain)'),
('ca_FR', 'ca', 'catalan', 'Catalan (France)'),
('ca_IT', 'ca', 'catalan', 'Catalan (Italy)'),
('crh_UA', 'crh', 'crimean turkish', 'Crimean Turkish (Ukraine)'),
('csb_PL', 'csb', 'kashubian', 'Kashubian (Poland)'),
('cs_CZ', 'cs', 'czech', 'Czech (Czech Republic)'),
('cv_RU', 'cv', 'chuvash', 'Chuvash (Russia)'),
('cy_GB', 'cy', 'welsh', 'Welsh (United Kingdom)'),
('da_DK', 'da', 'danish', 'Danish (Denmark)'),
('de_AT', 'de', 'german', 'German (Austria)'),
('de_BE', 'de', 'german', 'German (Belgium)'),
('de_CH', 'de', 'german', 'German (Switzerland)'),
('de_DE', 'de', 'german', 'German (Germany)'),
('de_LI', 'de', 'german', 'German (Liechtenstein)'),
('de_LU', 'de', 'german', 'German (Luxembourg)'),
('dv_MV', 'dv', 'divehi', 'Divehi (Maldives)'),
('dz_BT', 'dz', 'dzongkha', 'Dzongkha (Bhutan)'),
('ee_GH', 'ee', 'ewe', 'Ewe (Ghana)'),
('el_CY', 'el', 'greek', 'Greek (Cyprus)'),
('el_GR', 'el', 'greek', 'Greek (Greece)'),
('en_AG', 'en', 'english', 'English (Antigua and Barbuda)'),
('en_AS', 'en', 'english', 'English (American Samoa)'),
('en_AU', 'en', 'english', 'English (Australia)'),
('en_BW', 'en', 'english', 'English (Botswana)'),
('en_CA', 'en', 'english', 'English (Canada)'),
('en_DK', 'en', 'english', 'English (Denmark)'),
('en_GB', 'en', 'english', 'English (United Kingdom)'),
('en_GU', 'en', 'english', 'English (Guam)'),
('en_HK', 'en', 'english', 'English (Hong Kong SAR China)'),
('en_IE', 'en', 'english', 'English (Ireland)'),
('en_IN', 'en', 'english', 'English (India)'),
('en_JM', 'en', 'english', 'English (Jamaica)'),
('en_MH', 'en', 'english', 'English (Marshall Islands)'),
('en_MP', 'en', 'english', 'English (Northern Mariana Islands)'),
('en_MU', 'en', 'english', 'English (Mauritius)'),
('en_NG', 'en', 'english', 'English (Nigeria)'),
('en_NZ', 'en', 'english', 'English (New Zealand)'),
('en_PH', 'en', 'english', 'English (Philippines)'),
('en_SG', 'en', 'english', 'English (Singapore)'),
('en_TT', 'en', 'english', 'English (Trinidad and Tobago)'),
('en_US', 'en', 'english', 'English (United States)'),
('en_VI', 'en', 'english', 'English (Virgin Islands)'),
('en_ZA', 'en', 'english', 'English (South Africa)'),
('en_ZM', 'en', 'english', 'English (Zambia)'),
('en_ZW', 'en', 'english', 'English (Zimbabwe)'),
('eo', 'eo', 'esperanto', 'Esperanto'),
('es_AR', 'es', 'spanish', 'Spanish (Argentina)'),
('es_BO', 'es', 'spanish', 'Spanish (Bolivia)'),
('es_CL', 'es', 'spanish', 'Spanish (Chile)'),
('es_CO', 'es', 'spanish', 'Spanish (Colombia)'),
('es_CR', 'es', 'spanish', 'Spanish (Costa Rica)'),
('es_DO', 'es', 'spanish', 'Spanish (Dominican Republic)'),
('es_EC', 'es', 'spanish', 'Spanish (Ecuador)'),
('es_ES', 'es', 'spanish', 'Spanish (Spain)'),
('es_GT', 'es', 'spanish', 'Spanish (Guatemala)'),
('es_HN', 'es', 'spanish', 'Spanish (Honduras)'),
('es_MX', 'es', 'spanish', 'Spanish (Mexico)'),
('es_NI', 'es', 'spanish', 'Spanish (Nicaragua)'),
('es_PA', 'es', 'spanish', 'Spanish (Panama)'),
('es_PE', 'es', 'spanish', 'Spanish (Peru)'),
('es_PR', 'es', 'spanish', 'Spanish (Puerto Rico)'),
('es_PY', 'es', 'spanish', 'Spanish (Paraguay)'),
('es_SV', 'es', 'spanish', 'Spanish (El Salvador)'),
('es_US', 'es', 'spanish', 'Spanish (United States)'),
('es_UY', 'es', 'spanish', 'Spanish (Uruguay)'),
('es_VE', 'es', 'spanish', 'Spanish (Venezuela)'),
('et_EE', 'et', 'estonian', 'Estonian (Estonia)'),
('eu_ES', 'eu', 'basque', 'Basque (Spain)'),
('eu_FR', 'eu', 'basque', 'Basque (France)'),
('fa_AF', 'fa', 'persian', 'Persian (Afghanistan)'),
('fa_IR', 'fa', 'persian', 'Persian (Iran)'),
('ff_SN', 'ff', 'fulah', 'Fulah (Senegal)'),
('fil_PH', 'fil', 'filipino', 'Filipino (Philippines)'),
('fi_FI', 'fi', 'finnish', 'Finnish (Finland)'),
('fo_FO', 'fo', 'faroese', 'Faroese (Faroe Islands)'),
('fr_BE', 'fr', 'french', 'French (Belgium)'),
('fr_BF', 'fr', 'french', 'French (Burkina Faso)'),
('fr_BI', 'fr', 'french', 'French (Burundi)'),
('fr_BJ', 'fr', 'french', 'French (Benin)'),
('fr_CA', 'fr', 'french', 'French (Canada)'),
('fr_CF', 'fr', 'french', 'French (Central African Republic)'),
('fr_CG', 'fr', 'french', 'French (Congo)'),
('fr_CH', 'fr', 'french', 'French (Switzerland)'),
('fr_CM', 'fr', 'french', 'French (Cameroon)'),
('fr_FR', 'fr', 'french', 'French (France)'),
('fr_GA', 'fr', 'french', 'French (Gabon)'),
('fr_GN', 'fr', 'french', 'French (Guinea)'),
('fr_GP', 'fr', 'french', 'French (Guadeloupe)'),
('fr_GQ', 'fr', 'french', 'French (Equatorial Guinea)'),
('fr_KM', 'fr', 'french', 'French (Comoros)'),
('fr_LU', 'fr', 'french', 'French (Luxembourg)'),
('fr_MC', 'fr', 'french', 'French (Monaco)'),
('fr_MG', 'fr', 'french', 'French (Madagascar)'),
('fr_ML', 'fr', 'french', 'French (Mali)'),
('fr_MQ', 'fr', 'french', 'French (Martinique)'),
('fr_NE', 'fr', 'french', 'French (Niger)'),
('fr_SN', 'fr', 'french', 'French (Senegal)'),
('fr_TD', 'fr', 'french', 'French (Chad)'),
('fr_TG', 'fr', 'french', 'French (Togo)'),
('fur_IT', 'fur', 'friulian', 'Friulian (Italy)'),
('fy_DE', 'fy', 'western frisian', 'Western Frisian (Germany)'),
('fy_NL', 'fy', 'western frisian', 'Western Frisian (Netherlands)'),
('ga_IE', 'ga', 'irish', 'Irish (Ireland)'),
('gd_GB', 'gd', 'scottish gaelic', 'Scottish Gaelic (United Kingdom)'),
('gez_ER', 'gez', 'geez', 'Geez (Eritrea)'),
('gez_ET', 'gez', 'geez', 'Geez (Ethiopia)'),
('gl_ES', 'gl', 'galician', 'Galician (Spain)'),
('gu_IN', 'gu', 'gujarati', 'Gujarati (India)'),
('gv_GB', 'gv', 'manx', 'Manx (United Kingdom)'),
('ha_NG', 'ha', 'hausa', 'Hausa (Nigeria)'),
('he_IL', 'he', 'hebrew', 'Hebrew (Israel)'),
('hi_IN', 'hi', 'hindi', 'Hindi (India)'),
('hr_HR', 'hr', 'croatian', 'Croatian (Croatia)'),
('hsb_DE', 'hsb', 'upper sorbian', 'Upper Sorbian (Germany)'),
('ht_HT', 'ht', 'haitian', 'Haitian (Haiti)'),
('hu_HU', 'hu', 'hungarian', 'Hungarian (Hungary)'),
('hy_AM', 'hy', 'armenian', 'Armenian (Armenia)'),
('ia', 'ia', 'interlingua', 'Interlingua'),
('id_ID', 'id', 'indonesian', 'Indonesian (Indonesia)'),
('ig_NG', 'ig', 'igbo', 'Igbo (Nigeria)'),
('ik_CA', 'ik', 'inupiaq', 'Inupiaq (Canada)'),
('is_IS', 'is', 'icelandic', 'Icelandic (Iceland)'),
('it_CH', 'it', 'italian', 'Italian (Switzerland)'),
('it_IT', 'it', 'italian', 'Italian (Italy)'),
('iu_CA', 'iu', 'inuktitut', 'Inuktitut (Canada)'),
('ja_JP', 'ja', 'japanese', 'Japanese (Japan)'),
('ka_GE', 'ka', 'georgian', 'Georgian (Georgia)'),
('kk_KZ', 'kk', 'kazakh', 'Kazakh (Kazakhstan)'),
('kl_GL', 'kl', 'kalaallisut', 'Kalaallisut (Greenland)'),
('km_KH', 'km', 'khmer', 'Khmer (Cambodia)'),
('kn_IN', 'kn', 'kannada', 'Kannada (India)'),
('kok_IN', 'kok', 'konkani', 'Konkani (India)'),
('ko_KR', 'ko', 'korean', 'Korean (South Korea)'),
('ks_IN', 'ks', 'kashmiri', 'Kashmiri (India)'),
('ku_TR', 'ku', 'kurdish', 'Kurdish (Turkey)'),
('kw_GB', 'kw', 'cornish', 'Cornish (United Kingdom)'),
('ky_KG', 'ky', 'kirghiz', 'Kirghiz (Kyrgyzstan)'),
('lg_UG', 'lg', 'ganda', 'Ganda (Uganda)'),
('li_BE', 'li', 'limburgish', 'Limburgish (Belgium)'),
('li_NL', 'li', 'limburgish', 'Limburgish (Netherlands)'),
('lo_LA', 'lo', 'lao', 'Lao (Laos)'),
('lt_LT', 'lt', 'lithuanian', 'Lithuanian (Lithuania)'),
('lv_LV', 'lv', 'latvian', 'Latvian (Latvia)'),
('mai_IN', 'mai', 'maithili', 'Maithili (India)'),
('mg_MG', 'mg', 'malagasy', 'Malagasy (Madagascar)'),
('mi_NZ', 'mi', 'maori', 'Maori (New Zealand)'),
('mk_MK', 'mk', 'macedonian', 'Macedonian (Macedonia)'),
('ml_IN', 'ml', 'malayalam', 'Malayalam (India)'),
('mn_MN', 'mn', 'mongolian', 'Mongolian (Mongolia)'),
('mr_IN', 'mr', 'marathi', 'Marathi (India)'),
('ms_BN', 'ms', 'malay', 'Malay (Brunei)'),
('ms_MY', 'ms', 'malay', 'Malay (Malaysia)'),
('mt_MT', 'mt', 'maltese', 'Maltese (Malta)'),
('my_MM', 'my', 'burmese', 'Burmese (Myanmar)'),
('naq_NA', 'naq', 'namibia', 'Namibia'),
('nb_NO', 'nb', 'norwegian bokmål', 'Norwegian Bokmål (Norway)'),
('nds_DE', 'nds', 'low german', 'Low German (Germany)'),
('nds_NL', 'nds', 'low german', 'Low German (Netherlands)'),
('ne_NP', 'ne', 'nepali', 'Nepali (Nepal)'),
('nl_AW', 'nl', 'dutch', 'Dutch (Aruba)'),
('nl_BE', 'nl', 'dutch', 'Dutch (Belgium)'),
('nl_NL', 'nl', 'dutch', 'Dutch (Netherlands)'),
('nn_NO', 'nn', 'norwegian nynorsk', 'Norwegian Nynorsk (Norway)'),
('no_NO', 'no', 'norwegian', 'Norwegian (Norway)'),
('nr_ZA', 'nr', 'south ndebele', 'South Ndebele (South Africa)'),
('nso_ZA', 'nso', 'northern sotho', 'Northern Sotho (South Africa)'),
('oc_FR', 'oc', 'occitan', 'Occitan (France)'),
('om_ET', 'om', 'oromo', 'Oromo (Ethiopia)'),
('om_KE', 'om', 'oromo', 'Oromo (Kenya)'),
('or_IN', 'or', 'oriya', 'Oriya (India)'),
('os_RU', 'os', 'ossetic', 'Ossetic (Russia)'),
('pap_AN', 'pap', 'papiamento', 'Papiamento (Netherlands Antilles)'),
('pa_IN', 'pa', 'punjabi', 'Punjabi (India)'),
('pa_PK', 'pa', 'punjabi', 'Punjabi (Pakistan)'),
('pl_PL', 'pl', 'polish', 'Polish (Poland)'),
('ps_AF', 'ps', 'pashto', 'Pashto (Afghanistan)'),
('pt_BR', 'pt', 'portuguese', 'Portuguese (Brazil)'),
('pt_GW', 'pt', 'portuguese', 'Portuguese (Guinea-Bissau)'),
('pt_PT', 'pt', 'portuguese', 'Portuguese (Portugal)'),
('ro_MD', 'ro', 'romanian', 'Romanian (Moldova)'),
('ro_RO', 'ro', 'romanian', 'Romanian (Romania)'),
('ru_RU', 'ru', 'russian', 'Russian (Russia)'),
('ru_UA', 'ru', 'russian', 'Russian (Ukraine)'),
('rw_RW', 'rw', 'kinyarwanda', 'Kinyarwanda (Rwanda)'),
('sa_IN', 'sa', 'sanskrit', 'Sanskrit (India)'),
('sc_IT', 'sc', 'sardinian', 'Sardinian (Italy)'),
('sd_IN', 'sd', 'sindhi', 'Sindhi (India)'),
('seh_MZ', 'seh', 'sena', 'Sena (Mozambique)'),
('se_NO', 'se', 'northern sami', 'Northern Sami (Norway)'),
('sid_ET', 'sid', 'sidamo', 'Sidamo (Ethiopia)'),
('si_LK', 'si', 'sinhala', 'Sinhala (Sri Lanka)'),
('sk_SK', 'sk', 'slovak', 'Slovak (Slovakia)'),
('sl_SI', 'sl', 'slovenian', 'Slovenian (Slovenia)'),
('sn_ZW', 'sn', 'shona', 'Shona (Zimbabwe)'),
('so_DJ', 'so', 'somali', 'Somali (Djibouti)'),
('so_ET', 'so', 'somali', 'Somali (Ethiopia)'),
('so_KE', 'so', 'somali', 'Somali (Kenya)'),
('so_SO', 'so', 'somali', 'Somali (Somalia)'),
('sq_AL', 'sq', 'albanian', 'Albanian (Albania)'),
('sq_MK', 'sq', 'albanian', 'Albanian (Macedonia)'),
('sr_BA', 'sr', 'serbian', 'Serbian (Bosnia and Herzegovina)'),
('sr_ME', 'sr', 'serbian', 'Serbian (Montenegro)'),
('sr_RS', 'sr', 'serbian', 'Serbian (Serbia)'),
('ss_ZA', 'ss', 'swati', 'Swati (South Africa)'),
('st_ZA', 'st', 'southern sotho', 'Southern Sotho (South Africa)'),
('sv_FI', 'sv', 'swedish', 'Swedish (Finland)'),
('sv_SE', 'sv', 'swedish', 'Swedish (Sweden)'),
('sw_KE', 'sw', 'swahili', 'Swahili (Kenya)'),
('sw_TZ', 'sw', 'swahili', 'Swahili (Tanzania)'),
('ta_IN', 'ta', 'tamil', 'Tamil (India)'),
('teo_UG', 'teo', 'teso', 'Teso (Uganda)'),
('te_IN', 'te', 'telugu', 'Telugu (India)'),
('tg_TJ', 'tg', 'tajik', 'Tajik (Tajikistan)'),
('th_TH', 'th', 'thai', 'Thai (Thailand)'),
('tig_ER', 'tig', 'tigre', 'Tigre (Eritrea)'),
('ti_ER', 'ti', 'tigrinya', 'Tigrinya (Eritrea)'),
('ti_ET', 'ti', 'tigrinya', 'Tigrinya (Ethiopia)'),
('tk_TM', 'tk', 'turkmen', 'Turkmen (Turkmenistan)'),
('tl_PH', 'tl', 'tagalog', 'Tagalog (Philippines)'),
('tn_ZA', 'tn', 'tswana', 'Tswana (South Africa)'),
('to_TO', 'to', 'tongan', 'Tongan (Tonga)'),
('tr_CY', 'tr', 'turkish', 'Turkish (Cyprus)'),
('tr_TR', 'tr', 'turkish', 'Turkish (Turkey)'),
('ts_ZA', 'ts', 'tsonga', 'Tsonga (South Africa)'),
('tt_RU', 'tt', 'tatar', 'Tatar (Russia)'),
('ug_CN', 'ug', 'uighur', 'Uighur (China)'),
('uk_UA', 'uk', 'ukrainian', 'Ukrainian (Ukraine)'),
('ur_PK', 'ur', 'urdu', 'Urdu (Pakistan)'),
('uz_UZ', 'uz', 'uzbek', 'Uzbek (Uzbekistan)'),
('ve_ZA', 've', 'venda', 'Venda (South Africa)'),
('vi_VN', 'vi', 'vietnamese', 'Vietnamese (Vietnam)'),
('wa_BE', 'wa', 'walloon', 'Walloon (Belgium)'),
('wo_SN', 'wo', 'wolof', 'Wolof (Senegal)'),
('xh_ZA', 'xh', 'xhosa', 'Xhosa (South Africa)'),
('yi_US', 'yi', 'yiddish', 'Yiddish (United States)'),
('yo_NG', 'yo', 'yoruba', 'Yoruba (Nigeria)'),
('zh_CN', 'zh', 'chinese', 'Chinese (China)'),
('zh_HK', 'zh', 'chinese', 'Chinese (Hong Kong SAR China)'),
('zh_SG', 'zh', 'chinese', 'Chinese (Singapore)'),
('zh_TW', 'zh', 'chinese', 'Chinese (Taiwan)'),
('zu_ZA', 'zu', 'zulu', 'Zulu (South Africa)');

-- --------------------------------------------------------

--
-- Table structure for table `hd_login_attempts`
--

CREATE TABLE `hd_login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(40) NOT NULL,
  `login` varchar(50) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hd_menu`
--

CREATE TABLE `hd_menu` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `parent_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `position` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `group_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `page` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_menu`
--

INSERT INTO `hd_menu` (`id`, `parent_id`, `title`, `url`, `position`, `group_id`, `page`, `active`) VALUES
(37, 0, 'Home', '/', 1, 1, 0, 1),
(54, 60, 'About', 'about', 2, 1, 12, 1),
(53, 0, 'ff', 'ff', 0, 0, 11, 1),
(82, 0, 'Can I host WordPress on shared hosting?', '_can_i_host_wordpress_on_shared_hosting', 0, 0, 25, 1),
(61, 60, 'Hosting Packages', 'cart/hosting_packages', 1, 1, 0, 1),
(84, 0, 'Domain Registration', 'domain_registration', 3, 1, 0, 1),
(83, 0, 'F.A.Q', 'faq', 4, 1, 0, 1),
(69, 0, 'yes', 'yes', 0, 0, 16, 1),
(93, 0, 'Web Hosting', 'web_hosting', 2, 1, 33, 1),
(86, 0, 'Is .com domain still the best?', 'is_.com_domain_still_the_best', 0, 0, 27, 1),
(87, 0, 'Do I need a domain name?', 'do_i_need_a_domain_name', 0, 0, 28, 1),
(88, 0, 'What is a domain registrar', 'what_is_a_domain_registrar', 0, 0, 29, 1),
(89, 0, 'Can I host my website on shared hosting?', 'can_i_host_my_website_on_shared_hosting', 0, 0, 30, 1),
(90, 0, 'Should I use a domain name generator?', 'should_i_use_a_domain_name_generator', 0, 0, 31, 1),
(91, 0, 'Knowledgebase', 'knowledge', 5, 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hd_menu_group`
--

CREATE TABLE `hd_menu_group` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_menu_group`
--

INSERT INTO `hd_menu_group` (`id`, `title`) VALUES
(1, 'Main Menu');

-- --------------------------------------------------------

--
-- Table structure for table `hd_orders`
--

CREATE TABLE `hd_orders` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `processed` date NOT NULL,
  `renewal_date` date NOT NULL,
  `domain` varchar(100) NOT NULL,
  `nameservers` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `item` int(11) NOT NULL,
  `item_parent` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '5',
  `order_status_id` int(11) NOT NULL DEFAULT '5',
  `type` varchar(100) NOT NULL,
  `order_id` bigint(11) NOT NULL,
  `o_id` int(11) NOT NULL,
  `process_id` bigint(11) NOT NULL,
  `counter` int(11) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `renewal` varchar(100) DEFAULT NULL,
  `server` int(11) DEFAULT NULL,
  `registrar` varchar(100) DEFAULT NULL,
  `years` int(11) NOT NULL DEFAULT '1',
  `promo` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `authcode` varchar(100) DEFAULT NULL,
  `additional_fields` int(11) DEFAULT NULL,
  `notes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hd_outgoing_emails`
--

CREATE TABLE `hd_outgoing_emails` (
  `id` int(11) UNSIGNED NOT NULL,
  `sent_to` varchar(64) DEFAULT NULL,
  `sent_from` varchar(64) DEFAULT NULL,
  `subject` text,
  `message` longtext,
  `date_sent` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `delivered` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hd_payments`
--

CREATE TABLE `hd_payments` (
  `p_id` int(11) NOT NULL,
  `invoice` int(11) NOT NULL,
  `paid_by` int(11) NOT NULL,
  `payer_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(64) COLLATE utf8_unicode_ci DEFAULT 'USD',
  `amount` decimal(10,2) DEFAULT '0.00',
  `trans_id` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attached_file` text COLLATE utf8_unicode_ci,
  `payment_date` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `month_paid` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `year_paid` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inv_deleted` enum('Yes','No') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `refunded` enum('Yes','No') COLLATE utf8_unicode_ci DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hd_payment_methods`
--

CREATE TABLE `hd_payment_methods` (
  `method_id` int(11) NOT NULL,
  `method_name` varchar(64) NOT NULL DEFAULT 'Paypal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_payment_methods`
--

INSERT INTO `hd_payment_methods` (`method_id`, `method_name`) VALUES
(1, 'Online'),
(2, 'Cash'),
(3, 'Bank Deposit'),
(5, 'Cheque'),
(6, 'Account Credit');

-- --------------------------------------------------------

--
-- Table structure for table `hd_permissions`
--

CREATE TABLE `hd_permissions` (
  `permission_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hd_permissions`
--

INSERT INTO `hd_permissions` (`permission_id`, `name`, `description`, `status`) VALUES
(1, 'view_all_invoices', 'Allow user access to view all invoices', 'active'),
(2, 'edit_all_invoices', 'Allow user access to edit all invoices', 'active'),
(3, 'add_invoices', 'Allow user access to add invoices', 'active'),
(4, 'delete_invoices', 'Allow user access to delete invoice', 'active'),
(5, 'pay_invoice_offline', 'Allow user access to make offline Invoice Payments', 'active'),
(6, 'view_payments', 'Allow user access to view own payments', 'active'),
(7, 'email_invoices', 'Allow user access to email invoices', 'active'),
(8, 'send_email_reminders', 'Allow user access to send invoice reminders', 'active'),
(23, 'edit_settings', 'Allow user access to edit all settings', 'active'),
(32, 'view_all_payments', 'Allow staff to view all payments', 'active'),
(33, 'edit_payments', 'Allow staff to edit payments', 'active'),
(35, 'manage_accounts', 'Allow user to activate, suspend and delete accounts', 'active'),
(36, 'manage_orders', 'Allow user to activate, cancel and delete orders', 'active'),
(37, 'create_orders', 'Allow user to create orders', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `hd_plugins`
--

CREATE TABLE `hd_plugins` (
  `plugin_id` int(11) NOT NULL,
  `system_name` varchar(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `uri` varchar(255) DEFAULT NULL,
  `version` varchar(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `author_uri` varchar(255) DEFAULT NULL,
  `config` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `hd_posts`
--

CREATE TABLE `hd_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `pubdate` date NOT NULL,
  `body` longtext NOT NULL,
  `post_type` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '1',
  `category_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `parent_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `menu` int(11) NOT NULL,
  `sidebar_right` tinyint(1) NOT NULL,
  `sidebar_left` tinyint(1) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `option` text,
  `meta_title` varchar(255) NOT NULL,
  `meta_desc` varchar(255) NOT NULL,
  `knowledge` int(11) NOT NULL,
  `faq` int(11) NOT NULL,
  `knowledge_id` int(11) NOT NULL,
  `faq_id` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `video` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_posts`
--

-- --------------------------------------------------------

INSERT INTO `hd_posts` (`id`, `title`, `slug`, `pubdate`, `body`, `post_type`, `user_id`, `category_id`, `created`, `modified`, `parent_id`, `status`, `menu`, `sidebar_right`, `sidebar_left`, `order`, `option`, `meta_title`, `meta_desc`, `knowledge`, `faq`, `knowledge_id`, `faq_id`, `views`, `video`) VALUES
(1, 'Home', 'home', '2020-05-16', '<p>&nbsp</p>\r\n\r\n<p>&nbsp</p>\r\n\r\n<div class=\"btgrid\">\r\n<div class=\"row row-1\">\r\n<div class=\"col-md-6\">\r\n<div class=\"content\">\r\n<p><img alt=\"\" src=\"/resource/uploads/125782651.png\" style=\"height:370px width:516px\" /></p>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-6\">\r\n<div class=\"content\">\r\n<h1>Our Service Guarantee</h1>\r\n\r\n<hr />\r\n<h3>Server Level Protection</h3>\r\n\r\n<p>We provide protection for your website from DDoS attacks.</p>\r\n\r\n<hr />\r\n<h3>30 Day Money-back Guarantee</h3>\r\n\r\n<p>If you are unsatisfied with our services, we&rsquoll give you a full refund.</p>\r\n\r\n<hr />\r\n<h3>Dedicated 24/7 Support</h3>\r\n\r\n<p>Our professional support is always ready to provide assistance.</p>\r\n\r\n<hr />\r\n<h3>High-Quality Hardware</h3>\r\n\r\n<p>We use the latest hardware solutions that receive regular maintenance.</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>', 'page', 1, 0, '2020-05-12 05:41:45', '2021-07-11 16:19:24', 0, 1, 1, 0, 0, 0, NULL, ' WHAT PANEL  v1.5 Demo', ' WHAT PANEL  is a domain and web  WHAT PANEL  system.', 0, 0, 0, 0, 0, ''),
(24, 'What is shared hosting?', 'what_is_shared_hosting?', '0000-00-00', '<p>Shared hosting is a form of web hosting in which many web hosting customers share a single (virtual or physical) server.</p>\r\n\r\n<p>The customers in a shared hosting environment are partitioned away form each other, so (when everything goes well), they have absolutely no access to each other&rsquos files, and are ideally not even aware of each other.<br />\r\n&nbsp</p>\r\n\r\n<p>Shared hosting allows for a high customer-to-hardware density, which makes it a very inexpensive way to run a website &mdash shared hosting is the cheapest form of hosting, and relatively high-quality shared hosting plans can be had for less than $10/month (sometimes less than $5/month, with a good coupon).<br />\r\n&nbsp</p>\r\n\r\n<p>The problem with shared hosting is that a limited pool of computer resources is being shared by a large number of customers. This can cause slow-downs and site outages if one or more sites on a shared hosting server gets a lot a of traffic.<br />\r\n&nbsp</p>\r\n\r\n<p>To prevent this, shared hosting providers usually institute some kind of throttling &mdash even on so-called &ldquounlimited plans.&rdquo This usually kicks in if your traffic spikes, which makes shared hosting plans a terrible idea if you are trying to build a highly-scalable, well-trafficked website.</p>', 'page', 1, 0, '2021-03-25 13:59:17', '2021-03-25 14:42:20', 0, 1, 0, 0, 0, 0, NULL, 'What is shared hosting?', ' WHAT PANEL  is a domain and web  WHAT PANEL  system.', 0, 1, 0, 33, 0, ''),
(25, 'Can I host WordPress on shared hosting?', '_can_i_host_wordpress_on_shared_hosting', '0000-00-00', '<p>Yes.</p>\r\n\r\n<p>Because of its popularity, most shared hosting providers are well-equipped to handle a WordPress blog. Many even offer a simple one-click installation script, allowing you to get set up with a new WordPress site very quickly.</p>\r\n\r\n<p>You can use our hosting features comparison tool to find hosting providers that support WordPress.</p>\r\n\r\n<p>&nbsp</p>', 'page', 1, 0, '2021-03-25 14:22:18', '2021-03-25 14:22:18', 0, 1, 0, 0, 0, 0, NULL, 'Can I host WordPress on shared hosting?', ' WHAT PANEL  is a domain and web  WHAT PANEL  system.', 0, 1, 0, 33, 0, ''),
(26, 'Domain Registration', 'domain_registration', '0000-00-00', '<p>It all starts with a domain name</p>', 'page', 1, 0, '2021-03-25 14:26:50', '2021-03-25 14:27:10', 0, 1, 0, 0, 0, 0, NULL, 'Domain Registration', ' WHAT PANEL  is a domain and web  WHAT PANEL  system.', 0, 0, 0, 0, 0, ''),
(31, 'Should I use a domain name generator?', 'should_i_use_a_domain_name_generator', '0000-00-00', '<p>A domain name generator is a tool that takes one or more keywords as inputs and provides a list of possible domain names based on combinations of your keywords and common affixes such as <code>my</code>, <code>i</code>, or <code>best</code>. Some will attempt to create new words by combining letters from your different key words. Usually these tools are combined with a domain name availability checker, so that only available options are presented.</p>\r\n\r\n<p>Domain name generator tools can be a good way to brainstorm ideas, especially if you&rsquore stuck for a creative name or the name you really want is taken. However, ideas from a domain name generator need to evaluated to see if they would be a good fit for your site.</p>', 'page', 1, 0, '2021-03-25 14:40:10', '2021-03-25 14:40:10', 0, 1, 0, 0, 0, 0, NULL, 'Should I use a domain name generator?', ' WHAT PANEL  is a domain and web  WHAT PANEL  system.', 0, 1, 0, 39, 0, ''),
(29, 'What is a domain registrar', 'what_is_a_domain_registrar', '0000-00-00', '<p>A domain name registrar is a company that manages the registration of domain names. When you buy a new domain name, you are buying it &ldquofrom&rdquo a registrar (that is &mdash you are paying the registration fee to a registrar).</p>', 'page', 1, 0, '2021-03-25 14:38:55', '2021-03-25 14:38:55', 0, 1, 0, 0, 0, 0, NULL, 'What is a domain registrar', ' WHAT PANEL  is a domain and web  WHAT PANEL  system.', 0, 1, 0, 39, 0, ''),
(27, 'Is .com domain still the best?', 'is_.com_domain_still_the_best', '0000-00-00', '<p>Even with all the additional options, <code>.com</code> seems to remain the gold standard for domain names. It carries a high-degree of trust with consumers, and communicates a sense of legitimacy that is hard to achieve with other domain name extensions.<br />\r\nBecause of this <code>.com</code> domains continue to have the highest sale price in the domain aftermarket.</p>', 'page', 1, 0, '2021-03-25 14:37:21', '2021-03-25 14:37:21', 0, 1, 0, 0, 0, 0, NULL, 'Is .com domain still the best?', ' WHAT PANEL  is a domain and web  WHAT PANEL  system.', 0, 1, 0, 39, 0, ''),
(28, 'Do I need a domain name?', 'do_i_need_a_domain_name', '0000-00-00', '<p>If you want to set up a website, you probably need a domain name.</p>\r\n\r\n<p>Some people set up free blogs or other types of sites using a domain name that belong to a third-party service like WordPress.com or Tumblr. That&rsquos okay for small personal blogs &mdash but if you want to build a serious online presence, you really should have your own domain name.</p>\r\n\r\n<p>It isn&rsquot hard to get your own domain name, all you need to do is buy one from a good domain name registrar.</p>', 'page', 1, 0, '2021-03-25 14:38:12', '2021-03-25 14:38:12', 0, 1, 0, 0, 0, 0, NULL, 'Do I need a domain name?', ' WHAT PANEL  is a domain and web  WHAT PANEL  system.', 0, 1, 0, 39, 0, ''),
(33, 'Web Hosting', 'web_hosting', '0000-00-00', '<p><img alt=\"\" src=\"/resource/uploads/hosting.jpg\" /></p>\r\n\r\n<p>&nbsp</p>\r\n\r\n<hr />\r\n<p>&nbsp</p>\r\n\r\n<p>&nbsp</p>', 'page', 1, 0, '2021-07-11 16:59:07', '2021-07-11 20:47:25', 0, 1, 1, 0, 0, 0, NULL, 'Web Hosting', ' WHAT PANEL  is a domain and web  WHAT PANEL  system.', 0, 0, 0, 0, 0, ''),
(30, 'Can I host my website on shared hosting?', 'can_i_host_my_website_on_shared_hosting', '0000-00-00', '<p>Usually, yes. The question is whether you want to.</p>\r\n\r\n<p>If you are launching a more-or-less basic site which will have limited traffic &mdash such as a personal blog, a homepage for a small offline business, or a website for local non-profit organization &mdash then shared hosting is a great way to go. It will provide all the hosting power you need for up to several hundred visitors a day, for a reasonably low cost.</p>\r\n\r\n<p>If you need a website that will work with larger traffic numbers &mdash several thousand a day, especially highly engaged visitors on an interactive site (like a store or web app) &mdash then shared hosting is going to be a terrible experience for you. You would be better off, in that case, with a VPS hosting plan.</p>', 'page', 1, 0, '2021-03-25 14:39:35', '2021-03-25 14:39:35', 0, 1, 0, 0, 0, 0, NULL, 'Can I host my website on shared hosting?', ' WHAT PANEL  is a domain and web  WHAT PANEL  system.', 0, 1, 0, 33, 0, '');

--
-- Table structure for table `hd_post_meta`
--

CREATE TABLE `hd_post_meta` (
  `ID` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'post',
  `title_arabic` varchar(255) DEFAULT NULL,
  `body_arabic` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hd_priorities`
--

CREATE TABLE `hd_priorities` (
  `priority` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hd_priorities`
--

INSERT INTO `hd_priorities` (`priority`) VALUES
('Low'),
('Medium'),
('High'),
('Urgent');

-- --------------------------------------------------------

--
-- Table structure for table `hd_promotions`
--

CREATE TABLE `hd_promotions` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `apply_to` text,
  `required` text,
  `billing_cycle` text,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `payment` int(11) NOT NULL,
  `per_order` int(11) NOT NULL,
  `per_client` int(11) NOT NULL,
  `first_order` int(11) NOT NULL,
  `new_customers` int(11) NOT NULL,
  `use_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hd_resellerclub_ids`
--

CREATE TABLE `hd_resellerclub_ids` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `domain` varchar(100) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hd_roles`
--

CREATE TABLE `hd_roles` (
  `r_id` int(11) NOT NULL,
  `role` varchar(64) NOT NULL,
  `default` int(11) NOT NULL,
  `permissions` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_roles`
--

INSERT INTO `hd_roles` (`r_id`, `role`, `default`, `permissions`) VALUES
(1, 'admin', 1, '{\"settings\":\"permissions\",\"role\":\"admin\",\"view_all_invoices\":\"on\",\"edit_invoices\":\"on\",\"pay_invoice_offline\":\"on\",\"view_all_payments\":\"on\",\"email_invoices\":\"on\",\"send_email_reminders\":\"on\"}'),
(2, 'client', 2, '{\"settings\":\"permissions\",\"role\":\"client\"}'),
(3, 'staff', 3, '{\"settings\":\"permissions\",\"role\":\"staff\",\"view_all_invoices\":\"on\",\"edit_invoices\":\"on\",\"add_invoices\":\"on\",\"pay_invoice_offline\":\"on\",\"send_email_reminders\":\"on\",\"view_orders\":\"on\",\"manage_orders\":\"on\"}');

-- --------------------------------------------------------

--
-- Table structure for table `hd_servers`
--

CREATE TABLE `hd_servers` (
  `id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `hostname` varchar(100) NOT NULL,
  `port` int(10) NOT NULL,
  `use_ssl` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `authkey` text NOT NULL,
  `ip` varchar(255) NOT NULL,
  `selected` int(11) NOT NULL DEFAULT '0',
  `ns1` varchar(100) DEFAULT NULL,
  `ns2` varchar(100) DEFAULT NULL,
  `ns3` varchar(100) DEFAULT NULL,
  `ns4` varchar(100) DEFAULT NULL,
  `ns5` varchar(100) DEFAULT NULL,
  `ip1` varchar(100) DEFAULT NULL,
  `ip2` varchar(100) DEFAULT NULL,
  `ip3` varchar(100) DEFAULT NULL,
  `ip4` varchar(100) DEFAULT NULL,
  `ip5` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hd_slider`
--

CREATE TABLE `hd_slider` (
  `slider_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hd_slider`
--

INSERT INTO `hd_slider` (`slider_id`, `name`, `status`) VALUES
(7, 'Home Slider', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hd_sliders`
--

CREATE TABLE `hd_sliders` (
  `slide_id` int(11) NOT NULL,
  `slider` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text CHARACTER SET utf8,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hd_sliders`
--

INSERT INTO `hd_sliders` (`slide_id`, `slider`, `title`, `description`, `image`) VALUES
(5, 0, 'edw', 'vvvv', ''),
(6, 0, 'ff', 'gg', ''),
(7, 0, 'gh', 'bnm', ''),
(15, 7, '', ' ', 'slide1.jpg'),
(16, 7, 'Test', '  ', 'slide2.jpg'),
(17, 7, '', '     Slide with html  caption example <a href=\"auth/login\" class=\"btn btn-sm btn-warning\">Login</a>', 'slide3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `hd_states`
--

CREATE TABLE `hd_states` (
  `state` varchar(25) CHARACTER SET utf8 DEFAULT NULL,
  `code` varchar(2) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hd_states`
--

INSERT INTO `hd_states` (`state`, `code`) VALUES
('Alabama', 'AL'),
('Alaska', 'AK'),
('Arizona', 'AZ'),
('Arkansas', 'AR'),
('California', 'CA'),
('Colorado', 'CO'),
('Connecticut', 'CT'),
('Delaware', 'DE'),
('District of Columbia', 'DC'),
('Florida', 'FL'),
('Georgia', 'GA'),
('Hawaii', 'HI'),
('Idaho', 'ID'),
('Illinois', 'IL'),
('Indiana', 'IN'),
('Iowa', 'IA'),
('Kansas', 'KS'),
('Kentucky', 'KY'),
('Louisiana', 'LA'),
('Maine', 'ME'),
('Maryland', 'MD'),
('Massachusetts', 'MA'),
('Michigan', 'MI'),
('Minnesota', 'MN'),
('Mississippi', 'MS'),
('Missouri', 'MO'),
('Montana', 'MT'),
('Nebraska', 'NE'),
('Nevada', 'NV'),
('New Hampshire', 'NH'),
('New Jersey', 'NJ'),
('New Mexico', 'NM'),
('New York', 'NY'),
('North Carolina', 'NC'),
('North Dakota', 'ND'),
('Ohio', 'OH'),
('Oklahoma', 'OK'),
('Oregon', 'OR'),
('Pennsylvania', 'PA'),
('Rhode Island', 'RI'),
('South Carolina', 'SC'),
('South Dakota', 'SD'),
('Tennessee', 'TN'),
('Texas', 'TX'),
('Utah', 'UT'),
('Vermont', 'VT'),
('Virginia', 'VA'),
('Washington', 'WA'),
('West Virginia', 'WV'),
('Wisconsin', 'WI'),
('Wyoming', 'WY'),
('Armed Forces Africa', 'AE'),
('Armed Forces Americas', 'AA'),
('Armed Forces Canada', 'AE'),
('Armed Forces Europe', 'AE'),
('Armed Forces Middle East', 'AE'),
('Armed Forces Pacific', 'AP'),
('Alberta', 'AB'),
('British Columbia', 'BC'),
('Manitoba', 'MB'),
('New Brunswick', 'NB'),
('Newfoundland and Labrador', 'NL'),
('Northwest Territories', 'NT'),
('Nova Scotia', 'NS'),
('Nunavut', 'NU'),
('Ontario', 'ON'),
('Prince Edward Island', 'PE'),
('Quebec', 'QC'),
('Saskatchewan', 'SK'),
('Yukon', 'YT');

-- --------------------------------------------------------

--
-- Table structure for table `hd_status`
--

CREATE TABLE `hd_status` (
  `id` int(11) NOT NULL,
  `name` varchar(35) DEFAULT NULL,
  `status` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hd_status`
--

INSERT INTO `hd_status` (`id`, `name`, `status`) VALUES
(1, 'Resolved', 'resolved'),
(2, 'Closed', 'closed'),
(3, 'Open', 'open'),
(5, 'Pending', 'pending'),
(6, 'Active', 'active'),
(7, 'Cancelled', 'cancelled'),
(8, 'Deleted', 'deleted'),
(9, 'Suspended', 'suspended'),
(10, 'Status', 'status'),
(11, 'Pending Registration', 'pending_registration'),
(12, 'Pending Transfer', 'pending_transfer'),
(13, 'Grace Period(Expired)', 'grace_period'),
(14, 'Redemption Period(Expired)', 'redemption_period'),
(15, 'Expired', 'expired'),
(16, 'Transferred Away', 'transferred_away'),
(17, 'Fraud', 'fraud');
(17, 'In Process', 'in_process');
(17, 'On Hold', 'on_hold');

-- --------------------------------------------------------

--
-- Table structure for table `hd_tax_rates`
--

CREATE TABLE `hd_tax_rates` (
  `tax_rate_id` int(11) NOT NULL,
  `tax_rate_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `tax_rate_percent` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hd_tax_rates`
--

INSERT INTO `hd_tax_rates` (`tax_rate_id`, `tax_rate_name`, `tax_rate_percent`) VALUES
(2, 'GST', '18.00');

-- --------------------------------------------------------

--
-- Table structure for table `hd_ticketreplies`
--

CREATE TABLE `hd_ticketreplies` (
  `id` int(10) NOT NULL,
  `ticketid` int(10) DEFAULT NULL,
  `body` longtext COLLATE utf8_unicode_ci,
  `replier` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `replierid` int(10) DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `visibility` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hd_tickets`
--

CREATE TABLE `hd_tickets` (
  `id` int(10) NOT NULL,
  `ticket_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `status` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `department` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `reporter` int(10) DEFAULT '0',
  `priority` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `attachment` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `archived_t` int(2) DEFAULT '0',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hd_un_sessions`
--

CREATE TABLE `hd_un_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_un_sessions`
--

INSERT INTO `hd_un_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('2934618fecf7ff55679db5e819fcfcd72c4661f1', '::1', 1474573713, 0x5f5f63695f6c6173745f726567656e65726174657c693a313437343537333634383b7265717565737465645f706167657c733a32343a22687474703a2f2f6c6f63616c686f73742f666f6c6974652f223b757365725f69647c733a313a2231223b757365726e616d657c733a353a2261646d696e223b726f6c655f69647c733a313a2231223b7374617475737c733a313a2231223b70726576696f75735f706167657c733a32343a22687474703a2f2f6c6f63616c686f73742f666f6c6974652f223b);

-- --------------------------------------------------------

--
-- Table structure for table `hd_updates`
--

CREATE TABLE `hd_updates` (
  `build` int(11) NOT NULL DEFAULT '0',
  `code` varchar(50) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `version` varchar(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `filename` varchar(255) DEFAULT NULL,
  `importance` enum('low','medium','high') DEFAULT 'low',
  `dependencies` varchar(255) DEFAULT NULL,
  `installed` int(11) DEFAULT '0',
  `sql` text,
  `files` text,
  `depends` varchar(255) DEFAULT NULL,
  `includes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hd_users`
--

CREATE TABLE `hd_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '2',
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `code` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hd_user_autologin`
--

CREATE TABLE `hd_user_autologin` (
  `key_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expires` timestamp NULL DEFAULT NULL,
  `remote` int(2) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `hd_carts` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `item` int(11) DEFAULT NULL,
  `renewal` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `discount_price` float(10,2) NOT NULL,
  `discount_type` varchar(15) DEFAULT NULL,
  `discount_percentage` float(10,2) DEFAULT NULL,
  `domain` varchar(100) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `tax` float(10,2) DEFAULT NULL,
  `authcode` varchar(100) DEFAULT NULL,
  `nameservers` varchar(255) DEFAULT NULL,
  `registrar` varchar(255) DEFAULT NULL,
  `domain_only` smallint(5) DEFAULT 0,
  `co_id` int(11) DEFAULT NULL,
  `inv_id` int(11) DEFAULT NULL,
  `book_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE `hd_cpanel_account` (
  `id` int(11) NOT NULL,
  `domain` varchar(50) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `hascgi` enum('y','n') DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `cpanelmod` varchar(100) DEFAULT NULL,
  `homeroot` varchar(100) DEFAULT NULL,
  `quota` varchar(100) DEFAULT NULL,
  `nameserver1` varchar(100) DEFAULT NULL,
  `nameserver2` varchar(100) DEFAULT NULL,
  `nameserver3` varchar(100) DEFAULT NULL,
  `nameserver4` varchar(100) DEFAULT NULL,
  `contactemail` varchar(100) DEFAULT NULL,
  `package` varchar(100) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO `hd_currencies_symbol` (`id`, `name`, `code`, `symbol`) VALUES
(1, 'United Arab Emirates Dirham', 'AED', 'د.إ'),
(2, 'Afghan Afghani', 'AFN', '؋'),
(3, 'Albanian Lek', 'ALL', 'L'),
(4, 'Armenian Dram', 'AMD', '֏'),
(5, 'Netherlands Antillean Guilder', 'ANG', 'ƒ'),
(6, 'Angolan Kwanza', 'AOA', 'Kz'),
(7, 'Argentine Peso', 'ARS', '$'),
(8, 'Australian Dollar', 'AUD', '$'),
(9, 'Aruban Florin', 'AWG', 'ƒ'),
(10, 'Azerbaijani Manat', 'AZN', '₼'),
(11, 'Bosnia-Herzegovina Convertible Mark', 'BAM', 'KM'),
(12, 'Barbadian Dollar', 'BBD', '$'),
(13, 'Bangladeshi Taka', 'BDT', '৳'),
(14, 'Bulgarian Lev', 'BGN', 'лв'),
(15, 'Bahraini Dinar', 'BHD', '.د.ب'),
(16, 'Burundian Franc', 'BIF', 'FBu'),
(17, 'Bermudian Dollar', 'BMD', '$'),
(18, 'Brunei Dollar', 'BND', '$'),
(19, 'Bolivian Boliviano', 'BOB', 'Bs.'),
(20, 'Brazilian Real', 'BRL', 'R$'),
(21, 'Bahamian Dollar', 'BSD', '$'),
(22, 'Bitcoin', 'BTC', '₿'),
(23, 'Bhutanese Ngultrum', 'BTN', 'Nu.'),
(24, 'Botswana Pula', 'BWP', 'P'),
(25, 'Belarusian Ruble', 'BYN', 'Br'),
(26, 'Belize Dollar', 'BZD', 'BZ$'),
(27, 'Canadian Dollar', 'CAD', '$'),
(28, 'Congolese Franc', 'CDF', 'FC'),
(29, 'Swiss Franc', 'CHF', 'Fr.'),
(30, 'Chilean Unit of Account (UF)', 'CLF', 'UF'),
(31, 'Chilean Peso', 'CLP', '$'),
(32, 'Chinese Offshore Yuan', 'CNH', 'CN¥'),
(33, 'Chinese Yuan', 'CNY', '¥'),
(34, 'Colombian Peso', 'COP', '$'),
(35, 'Costa Rican Colón', 'CRC', '₡'),
(36, 'Cuban Convertible Peso', 'CUC', '$'),
(37, 'Cuban Peso', 'CUP', '$'),
(38, 'Cape Verdean Escudo', 'CVE', '$'),
(39, 'Czech Republic Koruna', 'CZK', 'Kč'),
(40, 'Djiboutian Franc', 'DJF', 'Fdj'),
(41, 'Danish Krone', 'DKK', 'kr'),
(42, 'Dominican Peso', 'DOP', 'RD$'),
(43, 'Algerian Dinar', 'DZD', 'د.ج'),
(44, 'Egyptian Pound', 'EGP', 'E£'),
(45, 'Eritrean Nakfa', 'ERN', 'Nfk'),
(46, 'Ethiopian Birr', 'ETB', 'Br'),
(47, 'Euro', 'EUR', '€'),
(48, 'Fijian Dollar', 'FJD', '$'),
(49, 'Falkland Islands Pound', 'FKP', '£'),
(50, 'British Pound Sterling', 'GBP', '£'),
(51, 'Georgian Lari', 'GEL', '₾'),
(52, 'Guernsey Pound', 'GGP', '£'),
(53, 'Ghanaian Cedi', 'GHS', '₵'),
(54, 'Gibraltar Pound', 'GIP', '£'),
(55, 'Gambian Dalasi', 'GMD', 'D'),
(56, 'Guinean Franc', 'GNF', 'FG'),
(57, 'Guatemalan Quetzal', 'GTQ', 'Q'),
(58, 'Guyanaese Dollar', 'GYD', '$'),
(59, 'Hong Kong Dollar', 'HKD', '$'),
(60, 'Honduran Lempira', 'HNL', 'L'),
(61, 'Croatian Kuna', 'HRK', 'kn'),
(62, 'Haitian Gourde', 'HTG', 'G'),
(63, 'Hungarian Forint', 'HUF', 'Ft'),
(64, 'Indonesian Rupiah', 'IDR', 'Rp'),
(65, 'Israeli New Shekel', 'ILS', '₪'),
(66, 'Manx pound', 'IMP', '£'),
(67, 'Indian Rupee', 'INR', '₹'),
(68, 'Iraqi Dinar', 'IQD', 'ع.د'),
(69, 'Iranian Rial', 'IRR', '﷼'),
(70, 'Icelandic Króna', 'ISK', 'kr'),
(71, 'Jersey Pound', 'JEP', '£'),
(72, 'Jamaican Dollar', 'JMD', 'J$'),
(73, 'Jordanian Dinar', 'JOD', 'JD'),
(74, 'Japanese Yen', 'JPY', '¥'),
(75, 'Kenyan Shilling', 'KES', 'KSh'),
(76, 'Kyrgystani Som', 'KGS', 'с'),
(77, 'Cambodian Riel', 'KHR', '៛'),
(78, 'Comorian Franc', 'KMF', 'CF'),
(79, 'North Korean Won', 'KPW', '₩'),
(80, 'South Korean Won', 'KRW', '₩'),
(81, 'Kuwaiti Dinar', 'KWD', 'د.ك'),
(82, 'Cayman Islands Dollar', 'KYD', '$'),
(83, 'Kazakhstani Tenge', 'KZT', '₸'),
(84, 'Laotian Kip', 'LAK', '₭'),
(85, 'Lebanese Pound', 'LBP', 'ل.ل'),
(86, 'Sri Lankan Rupee', 'LKR', 'Rs'),
(87, 'Liberian Dollar', 'LRD', '$'),
(88, 'Lesotho Loti', 'LSL', 'L'),
(89, 'Libyan Dinar', 'LYD', 'ل.د'),
(90, 'Moroccan Dirham', 'MAD', 'د.م.'),
(91, 'Moldovan Leu', 'MDL', 'L'),
(92, 'Malagasy Ariary', 'MGA', 'Ar'),
(93, 'Macedonian Denar', 'MKD', 'ден'),
(94, 'Myanma Kyat', 'MMK', 'K'),
(95, 'Mongolian Tugrik', 'MNT', '₮'),
(96, 'Macanese Pataca', 'MOP', 'MOP$'),
(97, 'Mauritanian Ouguiya (pre-2018)', 'MRU', 'UM'),
(98, 'Mauritian Rupee', 'MUR', '₨'),
(99, 'Maldivian Rufiyaa', 'MVR', '.ރ'),
(100, 'Malawian Kwacha', 'MWK', 'MK'),
(101, 'Mexican Peso', 'MXN', '$'),
(102, 'Malaysian Ringgit', 'MYR', 'RM'),
(103, 'Mozambican Metical', 'MZN', 'MT'),
(104, 'Namibian Dollar', 'NAD', '$'),
(105, 'Nigerian Naira', 'NGN', '₦'),
(106, 'Nicaraguan Córdoba', 'NIO', 'C$'),
(107, 'Norwegian Krone', 'NOK', 'kr'),
(108, 'Nepalese Rupee', 'NPR', '₨'),
(109, 'New Zealand Dollar', 'NZD', '$'),
(110, 'Omani Rial', 'OMR', 'ر.ع.'),
(111, 'Panamanian Balboa', 'PAB', 'B/.'),
(112, 'Peruvian Nuevo Sol', 'PEN', 'S/.'),
(113, 'Papua New Guinean Kina', 'PGK', 'K'),
(114, 'Philippine Peso', 'PHP', '₱'),
(115, 'Pakistani Rupee', 'PKR', '₨'),
(116, 'Polish Zloty', 'PLN', 'zł'),
(117, 'Paraguayan Guarani', 'PYG', '₲'),
(118, 'Qatari Rial', 'QAR', 'ر.ق'),
(119, 'Romanian Leu', 'RON', 'lei'),
(120, 'Serbian Dinar', 'RSD', 'дин.'),
(121, 'Russian Ruble', 'RUB', '₽'),
(122, 'Rwandan Franc', 'RWF', 'FRw'),
(123, 'Saudi Riyal', 'SAR', 'ر.س'),
(124, 'Solomon Islands Dollar', 'SBD', '$'),
(125, 'Seychellois Rupee', 'SCR', '₨'),
(126, 'Sudanese Pound', 'SDG', 'ج.س.'),
(127, 'Swedish Krona', 'SEK', 'kr'),
(128, 'Singapore Dollar', 'SGD', '$'),
(129, 'Saint Helena Pound', 'SHP', '£'),
(130, 'Sierra Leonean Leone', 'SLL', 'Le'),
(131, 'Somali Shilling', 'SOS', 'Sh'),
(132, 'Surinamese Dollar', 'SRD', '$'),
(133, 'South Sudanese Pound', 'SSP', '£'),
(134, 'São Tomé and Príncipe Dobra (2018)', 'STD', 'Db'),
(135, 'São Tomé and Príncipe Dobra (pre-2018)', 'STN', 'Db'),
(136, 'Salvadoran Colón', 'SVC', '₡'),
(137, 'Syrian Pound', 'SYP', '£S'),
(138, 'Swazi Lilangeni', 'SZL', 'E'),
(139, 'Thai Baht', 'THB', '฿'),
(140, 'Tajikistani Somoni', 'TJS', 'ЅМ'),
(141, 'Turkmenistani Manat', 'TMT', 'T'),
(142, 'Tunisian Dinar', 'TND', 'د.ت'),
(143, 'Tongan Paʻanga', 'TOP', 'T$'),
(144, 'Turkish Lira', 'TRY', '₺'),
(145, 'Trinidad and Tobago Dollar', 'TTD', 'TT$'),
(146, 'New Taiwan Dollar', 'TWD', 'NT$'),
(147, 'Tanzanian Shilling', 'TZS', 'TSh'),
(148, 'Ukrainian Hryvnia', 'UAH', '₴'),
(149, 'Ugandan Shilling', 'UGX', 'USh'),
(150, 'United States Dollar', 'USD', '$'),
(151, 'Uruguayan Peso', 'UYU', '$U'),
(152, 'Uzbekistan Som', 'UZS', 'UZS'),
(153, 'Venezuelan Bolívar', 'VES', 'Bs.S'),
(154, 'Vietnamese Dong', 'VND', '₫'),
(155, 'Vanuatu Vatu', 'VUV', 'VT'),
(156, 'Samoan Tala', 'WST', 'WS$'),
(157, 'Central African CFA Franc', 'XAF', 'FCFA'),
(158, 'Silver Ounce', 'XAG', 'XAG'),
(159, 'Gold Ounce', 'XAU', 'XAU'),
(160, 'East Caribbean Dollar', 'XCD', '$'),
(161, 'Special Drawing Rights', 'XDR', 'XDR'),
(162, 'CFA Franc BEAC', 'XOF', 'CFA'),
(163, 'Palladium Ounce', 'XPD', 'XPD'),
(164, 'CFP Franc', 'XPF', 'F'),
(165, 'Platinum Ounce', 'XPT', 'XPT'),
(166, 'Yemeni Rial', 'YER', '﷼'),
(167, 'South African Rand', 'ZAR', 'R'),
(168, 'Zambian Kwacha', 'ZMW', 'ZK'),
(169, 'Zimbabwean Dollar', 'ZWL', 'Z$');

CREATE TABLE `hd_customer_pricing` (
  `id` int(11) NOT NULL,
  `domain_name` varchar(255) DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `duration` int(3) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `hd_customer_pricing_view` (
  `view_id` int(10) UNSIGNED NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `ext_name` varchar(255) DEFAULT NULL,
  `registrar` varchar(255) DEFAULT NULL,
  `registration` float(10,2) DEFAULT NULL,
  `registration_1` float(10,2) DEFAULT NULL,
  `registration_2` float(10,2) DEFAULT NULL,
  `registration_3` float(10,2) DEFAULT NULL,
  `registration_4` float(10,2) DEFAULT NULL,
  `registration_5` float(10,2) DEFAULT NULL,
  `registration_6` float(10,2) DEFAULT NULL,
  `registration_7` float(10,2) DEFAULT NULL,
  `registration_8` float(10,2) DEFAULT NULL,
  `registration_9` float(10,2) DEFAULT NULL,
  `registration_10` float(10,2) DEFAULT NULL,
  `transfer` float(10,2) DEFAULT NULL,
  `transfer_1` float(10,2) DEFAULT NULL,
  `transfer_2` float(10,2) DEFAULT NULL,
  `transfer_3` float(10,2) DEFAULT NULL,
  `transfer_4` float(10,2) DEFAULT NULL,
  `transfer_5` float(10,2) DEFAULT NULL,
  `transfer_6` float(10,2) DEFAULT NULL,
  `transfer_7` float(10,2) DEFAULT NULL,
  `transfer_8` float(10,2) DEFAULT NULL,
  `transfer_9` float(10,2) DEFAULT NULL,
  `transfer_10` float(10,2) DEFAULT NULL,
  `renewal` float(10,2) DEFAULT NULL,
  `renewal_1` float(10,2) DEFAULT NULL,
  `renewal_2` float(10,2) DEFAULT NULL,
  `renewal_3` float(10,2) DEFAULT NULL,
  `renewal_4` float(10,2) DEFAULT NULL,
  `renewal_5` float(10,2) DEFAULT NULL,
  `renewal_6` float(10,2) DEFAULT NULL,
  `renewal_7` float(10,2) DEFAULT NULL,
  `renewal_8` float(10,2) DEFAULT NULL,
  `renewal_9` float(10,2) DEFAULT NULL,
  `renewal_10` float(10,2) DEFAULT NULL,
  `max_years` int(11) DEFAULT NULL,
  `tax_rate` float(10,2) DEFAULT NULL,
  `ext_order` int(11) DEFAULT NULL,
  `display` enum('yes','no') DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE `hd_indian_states` (
  `id` int(11) NOT NULL,
  `state_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hd_indian_states`
--

INSERT INTO `hd_indian_states` (`id`, `state_name`) VALUES
(1, 'Andhra Pradesh'),
(2, 'Arunachal Pradesh'),
(3, 'Assam'),
(4, 'Bihar'),
(5, 'Chhattisgarh'),
(6, 'Goa'),
(7, 'Gujarat'),
(8, 'Haryana'),
(9, 'Himachal Pradesh'),
(10, 'Jharkhand'),
(11, 'Karnataka'),
(12, 'Kerala'),
(13, 'Madhya Pradesh'),
(14, 'Maharashtra'),
(15, 'Manipur'),
(16, 'Meghalaya'),
(17, 'Mizoram'),
(18, 'Nagaland'),
(19, 'Odisha'),
(20, 'Punjab'),
(21, 'Rajasthan'),
(22, 'Sikkim'),
(23, 'Tamil Nadu'),
(24, 'Telangana'),
(25, 'Tripura'),
(26, 'Uttar Pradesh'),
(27, 'Uttarakhand'),
(28, 'West Bengal');

CREATE TABLE `hd_plesk_account` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `server_type` varchar(50) NOT NULL,
  `plesk_user_id` int(11) NOT NULL,
  `plesk_guid` varchar(255) NOT NULL,
  `plesk_domain_id` int(11) NOT NULL,
  `plesk_domain_guid` varchar(255) NOT NULL,
  `plesk_status` varchar(50) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE `hd_resellerclub_customer_contact` (
  `con_id` int(11) UNSIGNED NOT NULL,
  `co_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `email_addr` varchar(255) NOT NULL,
  `zip` int(25) NOT NULL,
  `address1` text NOT NULL,
  `address2` text NOT NULL,
  `telnocc` int(5) NOT NULL,
  `city` varchar(20) NOT NULL,
  `contact_status` varchar(20) NOT NULL,
  `eaqid` int(11) NOT NULL,
  `company` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `tel_no` varchar(15) NOT NULL,
  `state` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `entityid` int(11) NOT NULL,
  `entitytypeid` int(11) NOT NULL,
  `currentstatus` varchar(25) NOT NULL,
  `customerid` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE `hd_resellerclub_customer_details` (
  `id` int(11) NOT NULL,
  `customerid` int(100) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `useremail` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `parentid` int(100) DEFAULT NULL,
  `salespersonshash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `resellerid` int(100) DEFAULT NULL,
  `customerstatus` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `registrant` int(100) DEFAULT NULL,
  `tech` int(100) DEFAULT NULL,
  `billing` int(100) DEFAULT NULL,
  `admin` int(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `hd_user_autologin`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hd_account_details`
--
ALTER TABLE `hd_account_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_activities`
--
ALTER TABLE `hd_activities`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `hd_additional_fields`
--
ALTER TABLE `hd_additional_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_blocks`
--
ALTER TABLE `hd_blocks`
  ADD PRIMARY KEY (`block_id`);

--
-- Indexes for table `hd_blocks_custom`
--
ALTER TABLE `hd_blocks_custom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_blocks_modules`
--
ALTER TABLE `hd_blocks_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_blocks_pages`
--
ALTER TABLE `hd_blocks_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_captcha`
--
ALTER TABLE `hd_captcha`
  ADD PRIMARY KEY (`captcha_id`),
  ADD KEY `word` (`word`);

--
-- Indexes for table `hd_categories`
--
ALTER TABLE `hd_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_companies`
--
ALTER TABLE `hd_companies`
  ADD PRIMARY KEY (`co_id`);

--
-- Indexes for table `hd_config`
--
ALTER TABLE `hd_config`
  ADD PRIMARY KEY (`config_key`);

--
-- Indexes for table `hd_countries`
--
ALTER TABLE `hd_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_currencies`
--
ALTER TABLE `hd_currencies`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `hd_departments`
--
ALTER TABLE `hd_departments`
  ADD PRIMARY KEY (`deptid`);

--
-- Indexes for table `hd_email_templates`
--
ALTER TABLE `hd_email_templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `hd_fields`
--
ALTER TABLE `hd_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_files`
--
ALTER TABLE `hd_files`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `hd_formmeta`
--
ALTER TABLE `hd_formmeta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_hooks`
--
ALTER TABLE `hd_hooks`
  ADD PRIMARY KEY (`module`,`hook`,`access`);

--
-- Indexes for table `hd_images`
--
ALTER TABLE `hd_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_invoices`
--
ALTER TABLE `hd_invoices`
  ADD PRIMARY KEY (`inv_id`),
  ADD UNIQUE KEY `reference_no` (`reference_no`);

--
-- Indexes for table `hd_items`
--
ALTER TABLE `hd_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `hd_items_saved`
--
ALTER TABLE `hd_items_saved`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `hd_item_pricing`
--
ALTER TABLE `hd_item_pricing`
  ADD PRIMARY KEY (`item_pricing_id`);

--
-- Indexes for table `hd_languages`
--
ALTER TABLE `hd_languages`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `hd_links`
--
ALTER TABLE `hd_links`
  ADD PRIMARY KEY (`link_id`);

--
-- Indexes for table `hd_locales`
--
ALTER TABLE `hd_locales`
  ADD PRIMARY KEY (`locale`);

--
-- Indexes for table `hd_login_attempts`
--
ALTER TABLE `hd_login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_menu`
--
ALTER TABLE `hd_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_menu_group`
--
ALTER TABLE `hd_menu_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_orders`
--
ALTER TABLE `hd_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `processed` (`processed`,`renewal_date`),
  ADD KEY `order_id` (`order_id`,`process_id`);

--
-- Indexes for table `hd_outgoing_emails`
--
ALTER TABLE `hd_outgoing_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_payments`
--
ALTER TABLE `hd_payments`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `p_id` (`p_id`);

--
-- Indexes for table `hd_payment_methods`
--
ALTER TABLE `hd_payment_methods`
  ADD PRIMARY KEY (`method_id`);

--
-- Indexes for table `hd_permissions`
--
ALTER TABLE `hd_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `hd_plugins`
--
ALTER TABLE `hd_plugins`
  ADD PRIMARY KEY (`plugin_id`);

--
-- Indexes for table `hd_posts`
--
ALTER TABLE `hd_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_post_meta`
--
ALTER TABLE `hd_post_meta`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `hd_promotions`
--
ALTER TABLE `hd_promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_resellerclub_ids`
--
ALTER TABLE `hd_resellerclub_ids`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domain` (`domain`);

--
-- Indexes for table `hd_roles`
--
ALTER TABLE `hd_roles`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `hd_servers`
--
ALTER TABLE `hd_servers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_slider`
--
ALTER TABLE `hd_slider`
  ADD PRIMARY KEY (`slider_id`);

--
-- Indexes for table `hd_sliders`
--
ALTER TABLE `hd_sliders`
  ADD PRIMARY KEY (`slide_id`);

--
-- Indexes for table `hd_status`
--
ALTER TABLE `hd_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_tax_rates`
--
ALTER TABLE `hd_tax_rates`
  ADD KEY `Index 1` (`tax_rate_id`);

--
-- Indexes for table `hd_ticketreplies`
--
ALTER TABLE `hd_ticketreplies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hd_tickets`
--
ALTER TABLE `hd_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_code` (`ticket_code`);

--
-- Indexes for table `hd_un_sessions`
--
ALTER TABLE `hd_un_sessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `hd_updates`
--
ALTER TABLE `hd_updates`
  ADD PRIMARY KEY (`build`);

--
-- Indexes for table `hd_users`
--
ALTER TABLE `hd_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `hd_user_autologin`
--
ALTER TABLE `hd_user_autologin`
  ADD PRIMARY KEY (`key_id`,`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hd_account_details`
--
ALTER TABLE `hd_account_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_activities`
--
ALTER TABLE `hd_activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_additional_fields`
--
ALTER TABLE `hd_additional_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_blocks`
--
ALTER TABLE `hd_blocks`
  MODIFY `block_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `hd_blocks_custom`
--
ALTER TABLE `hd_blocks_custom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `hd_blocks_modules`
--
ALTER TABLE `hd_blocks_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `hd_blocks_pages`
--
ALTER TABLE `hd_blocks_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `hd_captcha`
--
ALTER TABLE `hd_captcha`
  MODIFY `captcha_id` bigint(13) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_categories`
--
ALTER TABLE `hd_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `hd_companies`
--
ALTER TABLE `hd_companies`
  MODIFY `co_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_countries`
--
ALTER TABLE `hd_countries`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT for table `hd_departments`
--
ALTER TABLE `hd_departments`
  MODIFY `deptid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hd_email_templates`
--
ALTER TABLE `hd_email_templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `hd_fields`
--
ALTER TABLE `hd_fields`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hd_files`
--
ALTER TABLE `hd_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_formmeta`
--
ALTER TABLE `hd_formmeta`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hd_images`
--
ALTER TABLE `hd_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hd_invoices`
--
ALTER TABLE `hd_invoices`
  MODIFY `inv_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_items`
--
ALTER TABLE `hd_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_items_saved`
--
ALTER TABLE `hd_items_saved`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `hd_item_pricing`
--
ALTER TABLE `hd_item_pricing`
  MODIFY `item_pricing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `hd_links`
--
ALTER TABLE `hd_links`
  MODIFY `link_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_login_attempts`
--
ALTER TABLE `hd_login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_menu`
--
ALTER TABLE `hd_menu`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `hd_menu_group`
--
ALTER TABLE `hd_menu_group`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hd_orders`
--
ALTER TABLE `hd_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_outgoing_emails`
--
ALTER TABLE `hd_outgoing_emails`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_payments`
--
ALTER TABLE `hd_payments`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_payment_methods`
--
ALTER TABLE `hd_payment_methods`
  MODIFY `method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hd_permissions`
--
ALTER TABLE `hd_permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `hd_plugins`
--
ALTER TABLE `hd_plugins`
  MODIFY `plugin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `hd_posts`
--
ALTER TABLE `hd_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `hd_post_meta`
--
ALTER TABLE `hd_post_meta`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_promotions`
--
ALTER TABLE `hd_promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_resellerclub_ids`
--
ALTER TABLE `hd_resellerclub_ids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_roles`
--
ALTER TABLE `hd_roles`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hd_servers`
--
ALTER TABLE `hd_servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hd_slider`
--
ALTER TABLE `hd_slider`
  MODIFY `slider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hd_sliders`
--
ALTER TABLE `hd_sliders`
  MODIFY `slide_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `hd_status`
--
ALTER TABLE `hd_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hd_tax_rates`
--
ALTER TABLE `hd_tax_rates`
  MODIFY `tax_rate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hd_ticketreplies`
--
ALTER TABLE `hd_ticketreplies`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hd_tickets`
--
ALTER TABLE `hd_tickets`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hd_users`
--
ALTER TABLE `hd_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `hd_items_saved` ADD `price_change` ENUM('Yes','No') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No' AFTER `allow_upgrade`;

INSERT INTO `hd_hooks` (`module`, `parent`, `hook`, `icon`, `name`, `route`, `order`, `access`, `core`, `visible`, `permission`, `enabled`, `last_run`) VALUES ('settings_sms_templates', '', 'settings_menu_admin', 'fa-list', 'sms_templates', 'sms_templates', '8', '1', '1', '1', '', '1', NULL); 
INSERT INTO `hd_hooks` (`module`, `parent`, `hook`, `icon`, `name`, `route`, `order`, `access`, `core`, `visible`, `permission`, `enabled`, `last_run`) VALUES ('settings_sms', '', 'settings_menu_admin', 'fa-paper-plane', 'sms_gateway', 'sms', '9', '1', '1', '1', '', '1', NULL); 

INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('sms_gateway', 'FALSE');
INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('request_method', 'get');
INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('encoding', 'none');
INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('sms_gateway_url', '');
INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('custom_parameters', ''); 
INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('sms_invoice', 'FALSE'); 
INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('sms_invoice_reminder', 'FALSE');
INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('sms_payment_received', 'FALSE') ;
INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('sms_service_suspended', 'FALSE') ;
INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('sms_service_unsuspended', 'FALSE') ;


CREATE TABLE `hd_sms_templates` (
  `template_id` int(11) NOT NULL,
  `type` text NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `hd_sms_templates` (`template_id`, `type`, `body`) VALUES
(1, 'invoice', 'Dear {CLIENT}, This is a notification that an invoice with the amount of {CURRENCY}{AMOUNT} for invoice: {REF} has been generated. From {SITE_NAME}'),
(2, 'invoice_reminder', 'Dear {CLIENT}, This is a reminder that an amount of {CURRENCY}{AMOUNT} for invoice: {REF} is now overdue. From {SITE_NAME}'),
(3, 'payment_received', 'Dear {CLIENT}, This is to confirm that a payment of  {CURRENCY}{PAID_AMOUNT} towards invoice: {REF} has been received. From {SITE_NAME}'),
(4, 'service_suspended', 'Dear {CLIENT}, This is to notify you that your service has been suspended. Please pay invoice: {REF} to re-activate your service: {SERVICE}. From {SITE_NAME}'),
(5, 'service_unsuspended', 'Dear {CLIENT}, This is to notify you that your service: {SERVICE} has been unsuspended. From {SITE_NAME}');

 
ALTER TABLE `hd_sms_templates`
  ADD PRIMARY KEY (`template_id`);

 
ALTER TABLE `hd_sms_templates`
  MODIFY `template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


INSERT IGNORE INTO `hd_config` (`config_key`, `value`) VALUES ('twilio_sid', '');
INSERT IGNORE INTO `hd_config` (`config_key`, `value`) VALUES ('twilio_token', '');
INSERT IGNORE INTO `hd_config` (`config_key`, `value`) VALUES ('twilio_phone', '');

ALTER TABLE `hd_companies` ADD `affiliate` INT NOT NULL DEFAULT '0' AFTER `individual`; 
ALTER TABLE `hd_companies` ADD `affiliate_id` VARCHAR(20) NULL AFTER `affiliate`; 
ALTER TABLE `hd_orders` ADD `affiliate` INT NULL AFTER `notes`; 
ALTER TABLE `hd_companies` ADD `affiliate_clicks` INT NOT NULL  DEFAULT '0' AFTER `affiliate_id`, ADD `affiliate_signups` INT NOT NULL AFTER `affiliate_clicks`, ADD `affiliate_balance` DECIMAL(10,2) NOT NULL DEFAULT '0.00' AFTER `affiliate_signups`; 
ALTER TABLE `hd_orders` ADD `affiliate_paid` INT NOT NULL DEFAULT '0' AFTER `affiliate`; 

CREATE TABLE `hd_referrals` (
  `referral_id` int(11) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00',
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `hd_referrals`
  ADD PRIMARY KEY (`referral_id`);

 
ALTER TABLE `hd_referrals`
  MODIFY `referral_id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `hd_hooks` (`module`, `parent`, `hook`, `icon`, `name`, `route`, `order`, `access`, `core`, `visible`, `permission`, `enabled`, `last_run`) VALUES
('menu_affiliates', '', 'main_menu_admin', 'fa-money', 'affiliates', 'affiliates', 30, 1, 1, 1, '', 1, NULL);

INSERT INTO `hd_hooks` (`module`, `parent`, `hook`, `icon`, `name`, `route`, `order`, `access`, `core`, `visible`, `permission`, `enabled`, `last_run`) 
VALUES ('menu_affiliates', '', 'main_menu_client', 'fa-line-chart', 'affiliates', 'affiliates', '12', '2', '1', '1', '', '1', NULL); 


CREATE TABLE `hd_affiliates` (
  `withdrawal_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `request_date` date DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` varchar(255) DEFAULT NULL,
  `payment_details` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

 
ALTER TABLE `hd_affiliates`
  ADD PRIMARY KEY (`withdrawal_id`);
 
ALTER TABLE `hd_affiliates`
  MODIFY `withdrawal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
  
ALTER TABLE `hd_languages` CHANGE `code` `code` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `hd_languages` CHANGE `locale` `locale` VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `hd_currencies` ADD `status` ENUM('1','0') NULL DEFAULT '0' AFTER `xrate`;

ALTER TABLE `hd_payments` ADD `razorpay_order_id` VARCHAR(255) NULL DEFAULT NULL AFTER `amount`, ADD `razorpay_payment_id` VARCHAR(255) NULL DEFAULT NULL AFTER `razorpay_order_id`;

ALTER TABLE `hd_posts` CHANGE `faq` `faq` INT(11) NULL DEFAULT NULL;

ALTER TABLE `hd_posts` CHANGE `faq_id` `faq_id` INT(11) NULL DEFAULT NULL;

ALTER TABLE `hd_domains` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);

INSERT INTO `hd_config` (`config_key`, `value`) VALUES
('affiliates', 'TRUE'),
('affiliates_bonus', '0'),
('affiliates_commission', 'recurring'),
('affiliates_email', ''),
('affiliates_links', '<a href="https://whatpanel.com/cart/options?item=46?ref={AFFILIATE}"><img src="https://whatpanel.com/public/images/logo.png"></a> 
<p>
[a href="https://whatpanel.com/cart/options?item=46?ref={AFFILIATE}][img src="https://whatpanel.com/public/images/logo.png"][/a]
</p>
<hr>'),
('affiliates_payout', '200'),
('affiliates_percentage', '15');


INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('recaptcha_sitekey', ''); 
INSERT INTO `hd_config` (`config_key`, `value`) VALUES ('recaptcha_secretkey', '');
