<?php
$sess_data = $this->session->userdata("admin_userdata");
$api_key = $sess_data['userdata']['api_key'];
?>
<!-- Left Sidebar  -->
<div class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>
                <li class="nav-label">Learning</li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">How it Works? </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="overview.php">Overview </a></li>
                        <li><a href="#">How to use? </a></li>
                        <li><a href="#">Sandbox </a></li>
                    </ul>
                </li>
                <li class="nav-label" id="devices">My devices</li>
                <li> <a class="has-arrow " href="#" aria-expanded="false"><i class="fa fa-laptop"></i>
                        <span class="hide-menu">Device</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="device-list">List Device</a></li>
<!--                        <li><a href="email-read.html">Read</a></li>
                        <li><a href="email-inbox.html">Inbox</a></li>-->
                    </ul>
                </li>
               
                <li class="nav-label" id="devices">Feedback</li>
                <li> <a class="has-arrow " href="#" aria-expanded="false"><i class="fa fa-laptop"></i>
                        <span class="hide-menu">Feedback</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="feedback">Give Feedback</a></li>
<!--                        <li><a href="email-read.html">Read</a></li>
                        <li><a href="email-inbox.html">Inbox</a></li>-->
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</div>
<!-- End Left Sidebar  -->
<!-- Page wrapper  -->
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Dashboard <span class="pull-right">
                    API-KEY 
                    <i class="fa fa-key"></i>
                    <input type="text" readonly="true" class="btn btn-warning btn-sm" style="width:170px;" data-toggle="tooltip" title="Click to Copy Key!" onclick="copy_clipboard(this.id)" id="apikey" value="<?php echo $api_key; ?>">
                </h3> 
            
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
               
            </ol>
        </div>
    </div>
    <!--- Start Tour-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.11.0/css/bootstrap-tour-standalone.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.11.0/js/bootstrap-tour-standalone.min.js"></script>
<script>
// Instance the tour
var tour = new Tour({
  steps: [
  {
    element: "#devices",
    title: "Device Info",
    content: "Please Add Device info to take sesnor values online."
  },
  {
    element: "#devices1",
    title: "Title of my step",
    content: "Content of my step"
  }
]});

// Initialize the tour
tour.init();

// Start the tour
tour.start();
</script>
    <!--End Tour-->
    <!-- End Bread crumb -->
    <!-- Container fluid  -->


    <!--    <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="content-main">
    
                banner	
                <div class="banner">
    
                    <h2>
                        <a href="index.html">Home</a>
                        <i class="fa fa-angle-right"></i>
                        <span>Dashboard</span>
    
    
                    </h2>
                </div>-->

    <script>
//        var jq = $.noConflict();
        function copy_clipboard(element_id) {
            var copyText = document.getElementById(element_id);
            copyText.select();
            //alert(copyText.select());

            document.execCommand("copy");
            fancyAlert("Copied to clipboard: " + copyText.value, "success");
        }
        jQuery(document).ready(function () {
            jQuery('[data-toggle="tooltip"]').tooltip();
        });
    </script>