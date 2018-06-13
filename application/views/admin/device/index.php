
<script src="<?php echo JS_URL; ?>device/device.js" type="text/javascript"></script>
<div class="container-fluid">
    <!-- Start Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Listing Device</h4>
                    <h6 class="card-subtitle">List Of Device added</h6>
                    <button class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle" aria-hidden="true"></i> New Device</button>
                    <div class="table-responsive m-t-0">
                        <table id="deviceTable" class="table table-bordered table-striped" width='100%'>
                            <thead>
                                <tr>
                                    <th>S.no.</th>
                                    <th>Title</th>
                                    <th>Sub Title</th>
                                    <th>Signal Type</th>
                                    <th>Device Type</th>
                                    <th>Sensor</th>
                                    <th>Min Val.</th>
                                    <th>Max Val.</th>
                                    <th>Purpose</th>
                                    <th>Max Allowed Req.</th>
                                    <th>Created on</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <!------------------ modal starts here ------------------------------->
            <!-- Button to Open the Modal -->


            <!-- The Modal -->
            <div class="modal" id="myModal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <!-- <h4 class="modal-title">Add Device</h4> -->
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="card-body">
                                <form action="#" id="frm_device">
                                    <div class="form-body">
                                        <h3 class="card-title m-t-15">Device Info</h3>
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Device Title</label>
                                                    <input type="text" id="title" name="title" class="form-control" placeholder="Temprature Sensor...">
                                                    <!--<small class="form-control-feedback"> This is inline help </small>--> 
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">Device Sub-title</label>
                                                    <input type="text" class="form-control form-control-danger" placeholder="Combined with arduino" id="sub_title" name="sub_title">
                                                    <!--<small class="form-control-feedback"> This field has error. </small>-->
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Signal Type</label>
                                                    <select class="form-control custom-select" name="signal_type" id="signal_type">
                                                        <option value="1">Digital</option>
                                                        <option value="2">Analog</option>
                                                    </select>
                                                    <!--<small class="form-control-feedback"> Select your gender </small>--> 
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Device Type</label>
                                                        <select class="form-control custom-select" name="device_type" id="device_type">
                                                            <option value="1">Single Value (sensor)</option>
                                                            <option value="2">GPS (LAT. LONG.)</option>
                                                        </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Sensor Name</label>
                                                        <input type="text" class="form-control" placeholder="Sensor Name"name="sensor_name" id="sensor_name">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Purpose</label>
                                                    <textarea class="form-control" name="purpose" id="purpose" placeholder="Purpose of adding device"></textarea>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                            <!--/span-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Description </label>
                                                        <input type="text" class="form-control" placeholder="description"name="description" id="description">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Short Description</label>
                                                    <textarea class="form-control" name="short_desc" id="short_desc" placeholder="Short Description"></textarea>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                    </div>
                                   
                                </form>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="saveDevice"> <i class="fa fa-check"></i> Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>
            <!------------------Model Ends Here----------------------------------->
        </div>
    </div>
    <!-- End PAge Content -->
</div>
<script>
    jQuery("document").ready(function () {
        get_device_list();
    });
</script>