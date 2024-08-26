<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url(
        "images/logo_favicon.png"
    ) ?>">
    <title> WHAT PANEL  Setup</title>
    <meta name="description" content=" WHAT PANEL  is a Client Management and Invoicing System for Web Hosting businesses." />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="<?= base_url(
        "css/install.css"
    ) ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url(
        "js/fuelux/fuelux.css"
    ) ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url(
        "css/plugins/fuelux.min.css"
    ) ?>" type="text/css" />
    <link rel="stylesheet" href="<?= base_url(
        "css/font-awesome.min.css"
    ) ?>" type="text/css">
</head>
<body>
	<?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-info">
            <?php echo esc(session()->getFlashdata('message')); ?>
        </div>
    <?php endif; ?>
<!--main content start-->
<section id="content" class="m-t-lg wrapper-md animated fadeInUp">
<div class="container" style="width:60%">
    <section class="panel panel-default bg-white m-t-lg">
        <header class="panel-heading text-center">
            <strong>WHAT PANEL Setup</strong>
        </header>
        <div class="panel-body wrapper-lg">
            <?php
            $step1 = $step2 = $step3 = $step4 = $step5 = "";
            $badge1 = $badge2 = $badge3 = $badge4 = $badge5 = "badge";
            if (isset($step)) {
                switch ($step) {
                    case "2":
                        $step2 = "active";
                        $badge2 = "badge badge-success";
                        break;
                    case "3":
                        $step3 = "active";
                        $badge3 = "badge badge-success";
                        break;
                    case "4":
                        $step4 = "active";
                        $badge4 = "badge badge-success";
                        break;
                    case "5":
                        $step5 = "active";
                        $badge5 = "badge badge-success";
                        break;
                    default:
                        $step1 = "active";
                        $badge1 = "badge badge-success";
                        break;
                }
            } else {
                $step1 = "active";
                $badge1 = "badge";
            }
            ?>

            <div class="panel panel-default wizard">
                <div class="wizard-steps clearfix" id="form-wizard">
                    <ul class="steps">
                        <li class="<?= $step1 ?>"><span class="<?= $badge1 ?>">1</span>System Check</li>
                        <li class="<?= $step2 ?>"><span class="<?= $badge2 ?>">2</span>Validate Licence</li>
                        <li class="<?= $step3 ?>"><span class="<?= $badge3 ?>">3</span>Database Settings</li>
                        <li class="<?= $step4 ?>"><span class="<?= $badge4 ?>">4</span>Install</li>
                        <li class="<?= $step5 ?>"><span class="<?= $badge5 ?>">5</span>Finish</li>
                    </ul>
                </div>
                <div class="step-content clearfix" style="background-color: #fff;">
                    <div class="step-pane <?= $step1 ?>" id="step1">
                        <?php
                        $config_file = APPPATH . "Config/App.php";
                        $database_file = APPPATH . "Config/Database.php";
                        $autoload_file = APPPATH . "Config/Autoload.php";
                        $route_file = APPPATH . "Config/Routes.php";
                        $htaccess_file = ".htaccess";
                        $error = false;
                        $writable_path = base_url() . "uploads/";
                        ?>

                        <div class="row">
                            <div class="col-lg-6">
                                <?php
                                if (phpversion() < "5.3") {
                                    $error = true;
                                    echo "<div class='alert alert-danger'>Your PHP version is " .
                                        phpversion() .
                                        "! PHP 5.3 or higher required!</div>";
                                } else {
                                    echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> You are running PHP " .
                                        phpversion() .
                                        "</div>";
                                }

                                if (!extension_loaded("mysqli")) {
                                    $error = true;
                                    echo "<div class='alert alert-danger'>Mysqli PHP extension missing!</div>";
                                } else {
                                    echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Mysqli PHP extension loaded!</div>";
                                }

                                if (!extension_loaded("imap")) {
                                    $error = true;
                                    echo "<div class='alert alert-danger'>IMAP PHP extension missing!</div>";
                                } else {
                                    echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> IMAP PHP extension loaded!</div>";
                                }

                                if (!extension_loaded("mbstring")) {
                                    $error = true;
                                    echo "<div class='alert alert-danger'>MBString PHP extension missing!</div>";
                                } else {
                                    echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> MBString PHP extension loaded!</div>";
                                }

                                if (!extension_loaded("zip")) {
                                    $error = true;
                                    echo "<div class='alert alert-danger'>ZIP PHP extension missing!</div>";
                                } else {
                                    echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> ZIP PHP extension loaded!</div>";
                                }

                                if (!extension_loaded("gd")) {
                                    echo "<div class='alert alert-danger'>GD PHP extension missing!</div>";
                                } else {
                                    echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> GD PHP extension loaded!</div>";
                                }

                                if (!extension_loaded("pdo")) {
                                    $error = true;
                                    echo "<div class='alert alert-danger'>PDO PHP extension missing!</div>";
                                } else {
                                    echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> PDO PHP extension loaded!</div>";
                                }
                                ?>
                            </div>
                            <div class="col-lg-6">
                                    <?php
                                    if (!extension_loaded("curl")) {
                                        $error = true;
                                        echo "<div class='alert alert-danger'>CURL PHP extension missing!</div>";
                                    } else {
                                        echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> CURL PHP extension loaded!</div>";
                                    }
                                    if (!is_writeable($database_file)) {
                                        $error = true;
                                        echo "<div class='alert alert-danger'>Database File (app/Config/Database.php) is not writeable!</div>";
                                    } else {
                                        echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Database file is writeable!</div>";
                                    }
                                    if (!is_writeable($config_file)) {
                                        $error = true;
                                        echo "<div class='alert alert-danger'>Config File (app/Config/config.php) is not writeable!</div>";
                                    } else {
                                        echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Config file is writeable!</div>";
                                    }
                                    if (!is_writeable($route_file)) {
                                        $error = true;
                                        echo "<div class='alert alert-danger'>Route File (application/config/routes.php) is not writeable!</div>";
                                    } else {
                                        echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Routes file is writeable!</div>";
                                    }
                                    if (!is_writeable($autoload_file)) {
                                        $error = true;
                                        echo "<div class='alert alert-danger'>Autoload File (application/config/autoload.php) is not writeable!</div>";
                                    } else {
                                        echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Autoload file is writeable!</div>";
                                    }
                                    if (!is_writeable($htaccess_file)) {
                                        $error = true;
                                        echo "<div class='alert alert-danger'>HTACCESS File (.htaccess) is not writeable!</div>";
                                    } else {
                                        echo "<div class='alert alert-success'><i class='fa fa-check-circle'></i> HTACCESS file is writeable!</div>";
                                    } ?>
                            </div>
                        </div>

                        <div class="actions pull-right">
                        <a href="<?= base_url() . "start" ?>" class="btn btn-danger">Next</a>
                        </div>
                    </div>

                    <div class="step-pane <?= $step2 ?>" id="step2">
                        <?php
                        $attributes = [
                            "class" => "m-b-sm form-horizontal",
                            "id" => "database",
                            "novalidate" => "novalidate",
                        ];
                        echo form_open(base_url() . "license", [
                            "class" => "m-b-sm form-horizontal",
                            "id" => "database",
                            "novalidate" => "novalidate",
                        ]);
                        ?>
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Key</label>
                                <div class="col-lg-3">
                                    <input type="text" class="form-control"  placeholder="enter your key" name="key">
                                </div>
                            </div>
							<div class="actions pull-left">
                                <a href="<?= base_url() ?>" class="btn btn-danger btn-sm">Previous</a>

                                <!-- <button type="submit" class="btn btn-danger btn-sm">Next</button> -->
                                <a href="<?= base_url() . 'start' ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Next</button>
                                </a>

                            </div>
						<?php
						echo form_close();
						?>
                    </div>

                    <div class="step-pane <?= $step3 ?>" id="step3">
							<?php
							$attributes = [
								"class" => "m-b-sm form-horizontal",
								"id" => "database",
								"novalidate" => "novalidate",
							];
							echo form_open(base_url() . "db_setup", [
								"class" => "m-b-sm form-horizontal",
								"id" => "database",
								"novalidate" => "novalidate",
							]);
							?>

						<div class="col-md-8 mainparent" style="float: none;display: flex;flex-direction: column;margin: 0 auto;">
							<div class="form-group">
                                 <label class="col-lg-4 control-label">Database Host</label>
                                <div class="col-lg-8">
                                 <input type="text" class="form-control"  placeholder="localhost" name="set_hostname">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Database Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="set_database" id="set_database">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Database Username</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="set_db_user">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Database Password</label>
                                <div class="col-lg-8">
                                    <input type="password" class="form-control" name="set_db_pass">
                                </div>
                            </div>
							 <div class="actions" style="padding: 0 15px;">
                                <a href="<?= base_url() . "installer_steps/2" ?>" class="btn btn-danger btn-sm">Previous</a>

                                <!-- <button type="submit" class="btn btn-danger btn-sm">Next</button> -->
                                <a href="<?= base_url() . 'start' ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Next</button>
                                </a>

                            </div>
						</div>
                        <?php
						echo form_close();
						?>
                    </div>

                    <div class="step-pane <?= $step4 ?>" id="step4">
                        <h4>Ready to install</h4>
                        <hr>
                        <?php
                        $attributes = array('class' => 'm-b-sm form-horizontal','id' => 'verify','novalidate'=>'novalidate');
                        // echo form_open(base_url().config_item('index_page').'/installer/install',$attributes);
                        echo form_open(base_url() . 'installerr', $attributes);
                        ?>
                          <button type="submit" class="btn btn-success btn-lg btn-block">Install</button>

                        <?php
						echo form_close();
						?>
                        <div class="actions pull-left">
                            <a href="<?= base_url() . "installer_steps/3"?>" class="btn btn-danger btn-sm">Previous</a>
                        </div>
                    </div>

                    <div class="step-pane <?= $step5 ?>" id="step5">
                        <?php
                        $attributes = [
                            "class" => "m-b-sm form-horizontal",
                            "id" => "complete",
                            "novalidate" => "novalidate",
                        ];
                        //   echo form_open(base_url().config_item('index_page').'/installer/complete',$attributes);

                        echo form_open(base_url() . "complete", $attributes);
                        ?>

                            <?php
                            $base_url =
                                isset($_SERVER["HTTPS"]) &&
                                $_SERVER["HTTPS"] == "on"
                                    ? "https"
                                    : "http";
                            $base_url .= "://" . $_SERVER["HTTP_HOST"];
                            $base_url .= str_replace(
                                basename($_SERVER["SCRIPT_NAME"]),
                                "",
                                $_SERVER["SCRIPT_NAME"]
                            );
                            ?>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Company Domain</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" value="<?= $base_url ?>" name="set_base_url">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Full Name</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" name="set_admin_fullname">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Admin Username</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" name="set_admin_username">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Admin Password</label>
                                <div class="col-lg-7">
                                    <input type="password" class="form-control" name="set_admin_pass">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-lg-3 control-label">Admin Email</label>
                                <div class="col-lg-7">
                                    <input type="email" class="form-control" name="set_admin_email">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Company Name</label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" name="set_company_name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3 control-label">Company Email</label>
                                <div class="col-lg-7">
                                    <input type="email" class="form-control" name="set_company_email">
                                </div>
                            </div>


                            <div class="actions pull-left">
                                <button type="submit" class="btn btn-danger btn-sm">Complete</button>
                            </div>
						
						<?php
						echo form_close();
						?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</section>
<!--main content end-->
<script src="<?= base_url(
    "js/jquery.min.js"
) ?>" type="text/javascript"></script>
<script src="<?= base_url("js/app.js") ?>" type="text/javascript"></script>
<script src="<?= base_url(
    "js/jquery.validate.min.js"
) ?>" type="text/javascript"></script>

<script>

    $(document).ready(function() {

        $("#database").validate({
            rules: {
                set_hostname: "required",
                set_database: "required",
                set_db_user: "required"
            },

            // Specify the validation error messages
            messages: {
                set_hostname: "Please enter your hostname usually localhost",
                set_database: "Please specify your database name",
                set_db_user: "Please specify your database username"
            },

            submitHandler: function(form) {
                form.submit();
            }
        });

        $("#verify").validate({
            rules: {
                set_envato_license: "required",
            },

            // Specify the validation error messages
            messages: {
                set_envato_license: "Enter your envato purchase code here"
            },

            submitHandler: function(form) {
                form.submit();
            }
        });

        $("#complete").validate({
            rules: {
                set_admin_username: "required",
                set_admin_fullname: "required",
                set_admin_pass: "required",
                set_admin_email: {
                    required: true,
                    email: true
                },
                set_company_name: "required",
                set_company_email: {
                    required: true,
                    email: true
                },
            },

            // Specify the validation error messages
            messages: {
                set_admin_username: "Please enter admin username",
                set_admin_fullname: "Set your admin full name",
                set_admin_pass: "Set your admin password",
                set_admin_email: "Set admin email address",
                set_company_name: "Set your company name",
                set_company_email: "Enter your company email address e.g info@domain.com",
            },

            submitHandler: function(form) {
                form.submit();
            }
        });

    });

</script>
<!-- Bootstrap -->
<!-- App -->
</body>
</html>