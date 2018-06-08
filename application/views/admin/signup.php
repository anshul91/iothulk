
<!DOCTYPE html>
<html lang="en">

    <head>
         <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
        <title>GadgetProgrammers Login</title>
        <!-- Bootstrap Core CSS -->
        <link href="<?php echo CSS_URL; ?>lib/bootstrap/bootstrap.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="<?php echo CSS_URL; ?>helper.css" rel="stylesheet">
        <link href="<?php echo CSS_URL; ?>style.css" rel="stylesheet">
        <!--------------------------Fancy ALert popup------------------------>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <script src="<?php echo JS_URL; ?>admin/common.js" type="text/javascript"></script>
        <script src="<?php echo JS_URL; ?>admin/admin_user.js" type="text/javascript"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.2.3/jquery-confirm.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.2.3/jquery-confirm.min.js"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
        <!--[if lt IE 9]>
        <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>

    <body class="fix-header fix-sidebar">
        <!-- Preloader - style you can find in spinners.css -->
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
        </div>
        <!-- Main wrapper  -->
        <div id="main-wrapper">

            <div class="unix-login">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-4">
                            <div class="login-content card">
                                <div class="login-form">
                                    <h4>
                                        <a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('site_heading'); ?>
                                        </a>
                                    </h4>
                                    <!--<h5 style="text-align: center;"><?php echo $this->lang->line("label_register"); ?></h5>-->
                                    <!--<span style="color:red;font-size: 13px;margin-left:20%;" id="error_msg"></span>-->
                                    <?php echo form_open(site_url("signup"), array("method" => "post", "name" => "frm_signup", "id" => "frm_signup")); ?>

                                    <span id="error_email_id" style="color:red;margin-bottom:10px;font-size:10px"></span>


                                    <div class="form-group">
                                        <label>Email address</label>
                                        <!--<input type="text" class="form-control" placeholder="Email" name="email_id" id="email_id" required="" autocomplete="off" onblur="validation(this.id, 'error_email_id', 'Email Id')">-->
                                        <input type="text" class="form-control" placeholder="Email" name="email_id" id="email_id" required="" autocomplete="off">

                                    </div>
                                    <span style='color:red;margin-bottom:10px;font-size:10px' id="error_password"></span>

                                    <div class="form-group">
                                        <label>Password</label>
                                        <!--<input class="form-control"  type="password" name="password" id="password" placeholder="Password" required='' autocomplete="off" onblur="validation(this.id, 'error_password', 'Password')">-->
                                        <input class="form-control"  type="password" name="password" id="password" placeholder="Password" required='' autocomplete="off">
                                        <!--<i class="fa fa-lock"></i>-->
                                    </div>
                                    <span style='color:red;margin-bottom:10px;font-size:10px' id="error_mobile_no"></span>

                                    <div class="form-group">
                                        <label>Mobile No.</label>
                                        <input type="text" name="mobile_no" class="form-control"  id="mobile_no" placeholder="Mobile No." required="" autocomplete="off">
                                        <!--<i class="fa fa-lock"></i>-->
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="checkbox" ><?php echo $this->lang->line("label_agreement"); ?>
                                        </label>
                                                                                <label class="pull-right">
                                                                                    <a href="#">Forgotten Password?</a>
                                                                                </label>

                                    </div>

                                    <input type="submit" value="Submit" name="signup" id="signup" class="btn btn-primary btn-flat m-b-30 m-t-30">
                                </div>
                                <div class="register-link m-t-15 text-center">
                                    <p>Already have account ? <a href="<?php echo site_url('login');?>"> Sign in</a></p>
                                    </div>
                                <!--                <div class="col-md-6 login-do">
                                                    <label class="hvr-shutter-in-horizontal login-sub">
                                                        <input type="submit" value="Submit" name="signup" id="signup">
                                                    </label>
                                                    <p><?php echo $this->lang->line("label_already_register"); ?></p>
                                                    <a href="<?php echo site_url('login'); ?>" class="hvr-shutter-in-horizontal"><?php echo $this->lang->line("btn_login"); ?></a>
                                                </div>-->

                                <!--                <div class="clearfix"> </div>
                                            </div>
                                        </div>-->
                                <?php echo form_close(); ?>
                                <script src="<?php echo JS_URL; ?>lib/jquery/jquery.min.js"></script>
                                <!-- Bootstrap tether Core JavaScript -->
                                <script src="<?php echo JS_URL; ?>lib/bootstrap/js/popper.min.js"></script>
                                <script src="<?php echo JS_URL; ?>lib/bootstrap/js/bootstrap.min.js"></script>
                                <!-- slimscrollbar scrollbar JavaScript -->
                                <script src="<?php echo JS_URL; ?>jquery.slimscroll.js"></script>
                                <!--Menu sidebar -->
                                <script src="<?php echo JS_URL; ?>sidebarmenu.js"></script>
                                <!--stickey kit -->
                                <script src="<?php echo JS_URL; ?>lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
                                <!--Custom JavaScript -->
                                <script src="<?php echo JS_URL; ?>scripts.js"></script>

                                </body>

                                </html>
