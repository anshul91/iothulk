<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="<?php echo JS_URL;?>lib/echart/echarts.js"></script>
    <script src="<?php echo JS_URL;?>lib/echart/echarts-init.js"></script>
<script src="<?php echo JS_URL; ?>chart_selection/chart_selection.js" type="text/javascript"></script>

<div class="container-fluid">
                <!-- Start Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body"> 
                    <h4 class="card-title" style="display: inline;">Choose Chart For Your Device</h4>
                    
                    <p>&nbsp;</p>
                   <!--  <div class="col-4">
                        <div class="form-group">
                            <select class="form-control" id="device_id" class="device_id">
                                <option value="">Select Device For Chart</option>
                                <?php
                                    if(count($devices)>0 && is_array($devices)) 
                                        foreach($devices as $k=>$device){
                                ?>
                                            <option value="<?php echo $device->device_id;?>">       
                                                <?php echo $device->title;?>
                                            </option>
                                <?php }?>
                            </select>
                        </div> -->
                    </div>
                    
                </div>
                <form name="chart_selection_frm" id="chart_selection_frm">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-title">
                                <h4>Line Chart 
                                    
                                </h4>
                            </div>
                            <div class="card-content">
                                <div class="col-12">
                                     <!-- Rectangular switch -->
                                    <input type="checkbox" data-toggle="toggle" data-on="Choose" data-off="Reject" data-onstyle="success" data-offstyle="danger" value="LineChart" name='chart_type[]' id='chart_type'>
                                   
                                     <input type="checkbox" data-toggle="toggle" data-on="Weekwise Min-max value" data-off="Monthwise Min-max Value" data-onstyle="warning" data-offstyle="info" value="1" name="chart_data_criterea[]" id="chart_data_criterea">
                                      <input type="checkbox" data-toggle="toggle" data-on="Show on Dashboard" data-off="Dont show on dashboard" data-onstyle="warning" data-offstyle="info" value="1" name="dashboard_status[]" id="dashboard_status">
                                </div>

                                <div id="b-line" style="height: 370px"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-title">
                                <h4>Bar Chart </h4>
                            </div>
                            <div class="card-content">
                            <div class="col-12">
                                <input type="checkbox" data-toggle="toggle" data-on="Choose" data-off="Reject" data-onstyle="success" data-offstyle="danger" value="BarChart" name='chart_type[]' id='chart_type'>
                                   
                                 <input type="checkbox" data-toggle="toggle" data-on="Weekwise Min-max value" data-off="Monthwise Min-max Value" data-onstyle="warning" data-offstyle="info" value="1" name="chart_data_criterea[]" id="chart_data_criterea">
                                  <input type="checkbox" data-toggle="toggle" data-on="Show on Dashboard" data-off="Dont show on dashboard" data-onstyle="warning" data-offstyle="info" value="1" name="dashboard_status[]" id="dashboard_status">
                            </div>
                                <div id="b-area" style="height: 370px"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-title">
                                <h4>Column Chart </h4>
                            </div>
                            <div class="card-content">
                            <div class="col-12">
                                <input type="checkbox" data-toggle="toggle" data-on="Choose" data-off="Reject" data-onstyle="success" data-offstyle="danger" value="ColumnChart" name='chart_type[]' id='chart_type'>
                                   
                                 <input type="checkbox" data-toggle="toggle" data-on="Weekwise Min-max value" data-off="Monthwise Min-max Value" data-onstyle="warning" data-offstyle="info" value="1" name="chart_data_criterea[]" id="chart_data_criterea">
                                  <input type="checkbox" data-toggle="toggle" data-on="Show on Dashboard" data-off="Dont show on dashboard" data-onstyle="warning" data-offstyle="info" value="1" name="dashboard_status[]" id="dashboard_status">
                            </div>
                            <input type="hidden" name="device_code" id="device_code" value="<?php echo $device_code;?>">
                                <div id="rainfall" style="height: 370px"></div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End PAge Content -->
</div>


