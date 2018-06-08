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
                <li class="nav-label">My devices</li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-envelope"></i><span class="hide-menu">Email</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="device-list">Devices</a></li>
<!--                        <li><a href="email-read.html">Read</a></li>
                        <li><a href="email-inbox.html">Inbox</a></li>-->
                    </ul>
                </li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-bar-chart"></i><span class="hide-menu">Charts</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="chart-flot.html">Flot</a></li>
                        <li><a href="chart-morris.html">Morris</a></li>
                        <li><a href="chart-chartjs.html">ChartJs</a></li>
                        <li><a href="chart-chartist.html">Chartist </a></li>
                        <li><a href="chart-amchart.html">AmChart</a></li>
                        <li><a href="chart-echart.html">EChart</a></li>
                        <li><a href="chart-sparkline.html">Sparkline</a></li>
                        <li><a href="chart-peity.html">Peity</a></li>
                    </ul>
                </li>
                <li class="nav-label">Features</li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-suitcase"></i><span class="hide-menu">Bootstrap UI <span class="label label-rouded label-warning pull-right">6</span></span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="ui-alert.html">Alert</a></li>
                        <li><a href="ui-button.html">Button</a></li>
                        <li><a href="ui-dropdown.html">Dropdown</a></li>
                        <li><a href="ui-progressbar.html">Progressbar</a></li>
                        <li><a href="ui-tab.html">Tab</a></li>
                        <li><a href="ui-typography.html">Typography</a></li>
                    </ul>
                </li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-suitcase"></i><span class="hide-menu">Components <span class="label label-rouded label-danger pull-right">6</span></span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="uc-calender.html">Calender</a></li>
                        <li><a href="uc-datamap.html">Datamap</a></li>
                        <li><a href="uc-nestedable.html">Nestedable</a></li>
                        <li><a href="uc-sweetalert.html">Sweetalert</a></li>
                        <li><a href="uc-toastr.html">Toastr</a></li>
                        <li><a href="uc-weather.html">Weather</a></li>
                    </ul>
                </li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-wpforms"></i><span class="hide-menu">Forms</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="form-basic.html">Basic Forms</a></li>
                        <li><a href="form-layout.html">Form Layout</a></li>
                        <li><a href="form-validation.html">Form Validation</a></li>
                        <li><a href="form-editor.html">Editor</a></li>
                        <li><a href="form-dropzone.html">Dropzone</a></li>
                    </ul>
                </li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-table"></i><span class="hide-menu">Tables</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="table-bootstrap.html">Basic Tables</a></li>
                        <li><a href="table-datatable.html">Data Tables</a></li>
                    </ul>
                </li>
                <li class="nav-label">Layout</li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-columns"></i><span class="hide-menu">Layout</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="layout-blank.html">Blank</a></li>
                        <li><a href="layout-boxed.html">Boxed</a></li>
                        <li><a href="layout-fix-header.html">Fix Header</a></li>
                        <li><a href="layout-fix-sidebar.html">Fix Sidebar</a></li>
                    </ul>
                </li>
                <li class="nav-label">EXTRA</li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-book"></i><span class="hide-menu">Pages <span class="label label-rouded label-success pull-right">8</span></span></a>
                    <ul aria-expanded="false" class="collapse">

                        <li><a href="#" class="has-arrow">Authentication <span class="label label-rounded label-success">6</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="page-login.html">Login</a></li>
                                <li><a href="page-register.html">Register</a></li>
                                <li><a href="page-invoice.html">Invoice</a></li>
                            </ul>
                        </li>
                        <li><a href="#" class="has-arrow">Error Pages</a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="page-error-400.html">400</a></li>
                                <li><a href="page-error-403.html">403</a></li>
                                <li><a href="page-error-404.html">404</a></li>
                                <li><a href="page-error-500.html">500</a></li>
                                <li><a href="page-error-503.html">503</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-map-marker"></i><span class="hide-menu">Maps</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="map-google.html">Google</a></li>
                        <li><a href="map-vector.html">Vector</a></li>
                    </ul>
                </li>
                <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-level-down"></i><span class="hide-menu">Multi level dd</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="#">item 1.1</a></li>
                        <li><a href="#">item 1.2</a></li>
                        <li> <a class="has-arrow" href="#" aria-expanded="false">Menu 1.3</a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="#">item 1.3.1</a></li>
                                <li><a href="#">item 1.3.2</a></li>
                                <li><a href="#">item 1.3.3</a></li>
                                <li><a href="#">item 1.3.4</a></li>
                            </ul>
                        </li>
                        <li><a href="#">item 1.4</a></li>
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
                    <input type="text" readonly="true" class="btn btn-warning btn-xs" style="width:170px" data-toggle="tooltip" title="Click to Copy Key!" onclick="copy_clipboard()" id="apikey" value="<?php echo $api_key; ?>">
                </span></h3> 
            
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
               
            </ol>
        </div>
    </div>
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
        function copy_clipboard() {
            var copyText = document.getElementById("apikey");
            copyText.select();
            //alert(copyText.select());

            document.execCommand("copy");
            fancyAlert("Copied to clipboard: " + copyText.value, "success");
        }
        jQuery(document).ready(function () {
            jQuery('[data-toggle="tooltip"]').tooltip();
        });
    </script>