<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */ //if (config_item('timezone')) { date_default_timezone_set(config_item('timezone')); } 

use App\ThirdParty\MX\Modules;
use App\Models\User;
use App\Helpers\AuthHelper;
use App\Modules as app_modules;

?>
<!DOCTYPE html>
<html lang="<?= lang('hd_lang.lang_code') ?>" class="app">

<head>
    <meta charset="utf-8" />
    <meta name="description" content="">
    <meta name="author" content="<? //=config_item('site_author')?>">
    <meta name="keyword" content="<? //=config_item('site_desc')?>">
    <?php //$favicon = config_item('site_favicon'); $ext = substr($favicon, -4); ?>
    <?php //if ( $ext == '.ico') : ?>
    <!-- <link rel="shortcut icon" href="<?= base_url() ?>resource/images/<? //=config_item('site_favicon')?>"> -->
    <?php //endif; ?>
    <?php //if ($ext == '.png') : ?>
    <!-- <link rel="icon" type="image/png" href="<?= base_url() ?>resource/images/<? //=config_item('site_favicon')?>"> -->
    <?php //endif; ?>
    <?php //if ($ext == '.jpg' || $ext == 'jpeg') : ?>
    <!-- <link rel="icon" type="image/jpeg" href="<?= base_url() ?>resource/images/<? //=config_item('site_favicon')?>"> -->
    <?php //endif; ?>
    <?php //if (config_item('site_appleicon') != '') : ?>
    <!-- <link rel="apple-touch-icon" href="<?= base_url() ?>resource/images/<? //=config_item('site_appleicon')?>" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?= base_url() ?>resource/images/<? //=config_item('site_appleicon')?>" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?= base_url() ?>resource/images/<? //=config_item('site_appleicon')?>" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?= base_url() ?>resource/images/<? //=config_item('site_appleicon')?>" /> -->
    <?php //endif; ?>
    <title>
        <?php  //echo $template['title'];?>
    </title>
    <!-- Bootstrap core CSS -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="<?= base_url('css/font-awesome.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('css/pace.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('js/chosen/chosen.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('css/plugins/sweetalert.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('css/plugins/toastr.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('css/plugins/select2.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('css/plugins/select2-bootstrap.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('css/typeahead.css') ?>" type="text/css" />
    <?php if(isset($fuelux)) { ?>
    <link rel="stylesheet" href="<?= base_url('css/plugins/fuelux.min.css') ?>" type="text/css" />
    <link rel="stylesheet" media="screen" href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/css/bootstrap.min.css' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <?php } ?>
    <?php if(isset($nouislider)) { ?>
    <script src="<?= base_url('js/nouislider/jquery.nouislider.min.css') ?>" type="text/javascript"></script>
    <?php } ?>
    <?php if(isset($editor)) { ?>
    <link rel="stylesheet" href="<?= base_url('css/plugins/summernote.css') ?>" type="text/css" />
    <?php } ?>
    <?php if(isset($datepicker)) { ?>
    <link rel="stylesheet" href="<?= base_url('js/slider/slider.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('js/datepicker/datepicker.css') ?>" type="text/css" />
    <?php } ?>
    <?php if(isset($iconpicker)) { ?>
    <link rel="stylesheet" href="<?= base_url('js/iconpicker/fontawesome-iconpicker.min.css') ?>"
        type="text/css" />
    <?php } ?>

    <?php if(isset($datatables)) { ?>
    <link rel="stylesheet" href="<?= base_url('css/plugins/dataTables.bootstrap.min.css') ?>" type="text/css" />
    <?php } ?>
    <link rel="stylesheet" href="<?= base_url('css/AdminLTE.min.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('css/skins/_all-skins.min.css') ?>" type="text/css" />

    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url('css/custom.css') ?>" type="text/css" />

    <?php //if( $this->uri->segment(2) == 'fields') {?>
    <!-- <link rel="stylesheet" href="<?= base_url() ?>resource/css/formbuilder.css" type="text/css"/> -->
    <?php //}  ?>

    <?php //if($this->uri->segment(1) == 'accounts') {?>
    <!-- <link rel="stylesheet" href="<?= base_url() ?>resource/css/cart.css" type="text/css"/>
		<link rel="stylesheet" href="<?= base_url() ?>resource/css/pricing.css" type="text/css"/> -->
    <?php //}  ?>
    <?php //if( $this->uri->segment(1) == 'orders') {?>
    <!-- <link rel="stylesheet" href="<?= base_url() ?>resource/css/pricing.css" type="text/css"/> -->
    <?php //}  ?>
    <?php
	$family = 'Lato';
	// $font = config_item('system_font');
	// switch ($font) {
	// 	case "open_sans": $family="Open Sans";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,latin-ext,greek-ext,cyrillic-ext' rel='stylesheet' type='text/css'>"; break;
	// 	case "open_sans_condensed": $family="Open Sans Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
	// 	case "roboto": $family="Roboto";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
	// 	case "roboto_condensed": $family="Roboto Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
	// 	case "ubuntu": $family="Ubuntu";  echo "<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
	// 	case "lato": $family="Lato";  echo "<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>"; break;
	// 	case "oxygen": $family="Oxygen";  echo "<link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>"; break;
	// 	case "pt_sans": $family="PT Sans";  echo "<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
	// 	case "source_sans": $family="Source Sans Pro";  echo "<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
	//}
	?>

    <style type="text/css">
    body {
        font-family: '<?= $family ?>';
    }

    .datepicker {
        z-index: 99999 !important;
    }
    </style>

    <!--[if lt IE 9]>
	<script src="js/ie/html5shiv.js">
	</script>
	<script src="js/ie/respond.min.js">
	</script>
	<script src="js/ie/excanvas.js">
	</script> <![endif]-->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Delete modal for Menus starts here-->
    <script>
        function openEditModal(menuId) {
            // Open the Bootstrap modal using jQuery
            $('#editModal').modal('show');

            $.ajax({
                url: "<?php echo base_url('menus/getMenuId'); ?>",
                type: 'POST',
                data: {menuId: menuId},
                dataType: 'json',
                success: function(data) {
                    // Process the data
                    console.log(data);
                    // You can further manipulate or display the data here
                },

                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }
    </script>

     <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
		            <h4 class="modal-title" id="editModalLabel"><?=lang('hd_lang.delete_menu')?></h4>
                </div>
                <div class="modal-body">
                    <!-- Your form content goes here -->
                    <form method="post" action="<?php echo site_url('menus/save'); ?>">
                        <p><?=lang('hd_lang.delete_menu_item')?></p>
                        <input type="hidden" name="slide_id" value="">
                        <input type="hidden" name="current_image" value="">
                    </form>
                    <!-- </form> -->
                </div>
                <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('hd_lang.close')?></a>
			        <button type="submit" class="btn btn-<?=config_item('theme_color')?>"><?=lang('hd_lang.delete_button')?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete modal for Menus ends here-->

    <!-- Edit modal for Menus starts here-->
    <script>
        function openEditModal(menuId) {
            // Open the Bootstrap modal using jQuery
            $('#editModal').modal('show');

            $.ajax({
                url: "<?php echo base_url('menus/getMenuId'); ?>",
                type: 'POST',
                data: {menuId: menuId},
                dataType: 'json',
                success: function(data) {
                    // Process the data
                    console.log(data);

                    // Populate the form fields
                    $('#edit-menu-title').val(data.row[0].title);
                    $('#edit-menu-url').val(data.row[0].url);

                    <?php if (isset($data['row'][0]->parent_id) && $data['row'][0]->parent_id == 0) : ?>
                        // Assuming you have a select option for group_id
                        $('#select_group_id').val(data.row[0].group_id);
                        // Update the hidden field for old_group_id
                        $('input[name="old_group_id"]').val(data.row[0].old_group_id);
                    <?php endif; ?>

                    // Update the hidden field for menu_id
                    $('input[name="menu_id"]').val(data.row[0].id);

                    // You can further manipulate or display the data here
                },

                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }
    </script>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Menu Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Your form content goes here -->
                    <form method="post" action="<?php echo site_url('menus/save'); ?>">
                        <div class="form-group">
                            <label for="edit-menu-title">Title</label>
                            <input required type="text" name="title" id="edit-menu-title" class="form-control modalTitle" style="width: 100%"
                                value="<?php echo isset($data['title']) ? htmlspecialchars($data['title']) : ''; ?>" />
                        </div>

                        <div class="form-group">
                            <label for="edit-menu-url">URL</label>
                            <input type="text" name="url" class="form-control" style="width: 100%" id="edit-menu-url"
                                value="<?php echo isset($data['url']) ? htmlspecialchars($data['url']) : ''; ?>" />
                        </div>

                        <?php if (isset($data['group_id'])) : ?>
                            <div class="form-group">
                                <label for="select_group_id">Group</label>
                                <select name="group_id" id="select_group_id" class="form-control">
                                    <!-- Populate select options based on your data -->
                                    <option value="<?php echo htmlspecialchars($data['group_id']); ?>" selected>
                                        <?php echo htmlspecialchars($data['group_id']); ?>
                                    </option>
                                    <!-- Add other options as needed -->
                                </select>
                            </div>
                            <input type="hidden" name="old_group_id" value="<?php echo htmlspecialchars($data['old_group_id']); ?>" />
                        <?php endif; ?>

                        <input type="hidden" name="menu_id" value="<?php echo isset($data['id']) ? htmlspecialchars($data['id']) : ''; ?>">
                    </form>
                    <!-- </form> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit modal for Menus ends here-->
</div>





    <!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script type="text/javascript" src='https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.3.min.js'></script>
    <script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/js/bootstrap.min.js'></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(.edit-menu).on('click', function(e) {
                e.preventDefault();
            var menuId = $('.edit-menu').data('menu-id');
            var rowId = $('.edit-menu').attr('id').split('_')[1]; // Extract the row_id from the id attribute
            
            alert(menuId);

            alert("menuId " + menuId);

                // Load modal content using AJAX
                $.ajax({
                    url: '<?php echo site_url("menus/edit_menu/"); ?>' + rowId,
                    type: 'GET',
                    success: function(response) {
                        // Append modal content to the body
                        $('div#loading').append(response);

                        // Show the modal (you may use a modal library)
                        $('#sampleModal').show();
                    }
                });
            });
        });
    </script> -->
    <script src="<?= base_url('js/jquery.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/bootstrap.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/libs/sweetalert.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/libs/toastr.min.js') ?>" type="text/javascript"></script>
    <script type="text/javascript">
    (function($) {

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    });
    </script>
</head>

<body class="hold-transition <? //=config_item('top_bar_color')?>  sidebar-mini">
    <div class="wrapper">
        <!--header start-->
        <?php  //echo Modules::run('sidebar/top_header'); ?>
        <!--header end-->

        <?php

		use App\Modules\sidebar\controllers\Sidebar;

		$role = 'admin';

		if($role == 'admin') {
			//echo Modules::run('sidebar/admin_menu');
			$sidebar = new Sidebar();
			$sidebar->admin_menu();
		} elseif(AuthHelper::is_staff()) {
			$sidebar = new Sidebar();
			$sidebar->staff_menu();
		} elseif(AuthHelper::is_client()) {
			$sidebar = new Sidebar();
			$sidebar->client_menu();
		} else {
			redirect('login');
		}
		?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <? //= $page ?>
                </h1>
                <ol class="breadcrumb mt-5">
                    <li><a href="<?= base_url() ?>"><i class="fa fa-dashboard"></i>
                            <?= lang('hd_lang.home') ?>
                        </a></li>
                    <li class="active">
                        <? //= $page ?>
                    </li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php  //echo $template['body'];?>
                <?php //include($content); ?>
                <?= $this->renderSection('content') ?>
            </section>

        </div>

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>
                    <?= lang('hd_lang.version') ?>
                </b>
                <? //= config_item('version')?>
            </div>
            <strong>
                <? //= config_item('website_name')?>
            </strong>

        </footer>
    </div>

    <script>
    var locale = '<?= lang('hd_lang.lang_code') ?>';
    var base_url = '<?= base_url() ?>';
    </script>

    <script src="<?= base_url('js/libs/moment.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/bootstrap.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/scroll/smoothscroll.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/app.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/adminlte.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/charts/easypiechart/jquery.easy-pie-chart.js') ?>" type="text/javascript">
    </script>
    <script src="<?= base_url('js/libs/jquery.sparkline.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/libs/typeahead.jquery.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/libs/jquery.textarea_autosize.min.js') ?>" type="text/javascript"></script>

    <script src="<?= base_url('js/custom.js') ?>" type="text/javascript"></script>

    <?php if(isset($fuelux)) { ?>
    <script src="<?= base_url('js/fuelux/fuelux.min.js') ?>" type="text/javascript"></script>
    <?php } ?>
    <?php if(isset($editor)) {
		//if(config_item('default_editor') == 'ckeditor') { ?>
    <!-- <script src="<?= base_url() ?>resource/js/ckeditor/ckeditor.js"></script> 
				<script type="text/javascript"> -->
    <!-- $(document).ready(function() {
						var textarea = document.getElementsByClassName('foeditor')[0];
						CKEDITOR.replace(textarea, {
						height: 300,
						filebrowserUploadUrl: "<?= base_url() ?>media/upload"
						});
					}); -->
    <!-- </script> -->

    <?php //} else { ?>

    <script src="<?= base_url('js/wysiwyg/summernote.min.js') ?>" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.foeditor').summernote({
            height: 200,
            codemirror: {
                theme: 'monokai'
            }
        });
        $('.foeditor-550').summernote({
            height: 550,
            codemirror: {
                theme: 'monokai'
            }
        });
        $('.foeditor-500').summernote({
            height: 500,
            codemirror: {
                theme: 'monokai'
            }
        });
        $('.foeditor-400').summernote({
            height: 400,
            codemirror: {
                theme: 'monokai'
            }
        });
        $('.foeditor-300').summernote({
            height: 300,
            codemirror: {
                theme: 'monokai'
            }
        });
        $('.foeditor-100').summernote({
            height: 100,
            codemirror: {
                theme: 'monokai'
            }
        });
    });
    </script>
    <?php //} 
	
	} ?>


    <?php if(isset($show_links)) { ?>
    <script type="text/javascript">
    (function($) {
        "use strict";
        // Check the main container is ready
        $('.activate_links').ready(function() {
            // Get each div
            $('.activate_links').each(function() {
                // Get the content
                var str = $(this).html();
                // Set the regex string
                var regex = /(https?:\/\/([-\w\.]+)+(:\d+)?(\/([\w\/_\.]*(\?\S+)?)?)?)/ig
                // Replace plain text links by hyperlinks
                var replaced_text = str.replace(regex, "<a href='$1' target='_blank'>$1</a>");
                // Echo link
                $(this).html(replaced_text);
            });
        });
    });
    </script>
    
    <?php } ?>
    <!-- Bootstrap -->
    <!-- js placed at the end of the document so the pages load faster -->
    <?php  //echo Modules::run('sidebar/scripts');?>
    <script src="<?= base_url('js/apps/pace.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/libs/jquery.maskMoney.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/chosen/chosen.jquery.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('js/libs/select2.min.js') ?>" type="text/javascript"></script>
    <script>
    (function($) {
        "use strict";
        $('.money').maskMoney();
        $(".chosen-select").chosen();
        $('#select2').select2();

    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find the flash message element
        var flashMessage = document.getElementById('flash-message');

        // Check if the element exists
        if (flashMessage) {
            // Set a timeout to remove the element after 5 seconds
            setTimeout(function() {
                flashMessage.style.display = 'none';
            }, 5000);
        }
    });
    </script>




</body>

</html>