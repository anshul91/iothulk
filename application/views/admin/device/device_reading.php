<?php
if(!isset($_SERVER['HTTP_REFERER']))
	die("Not allowed direct access!");
?>
<script src="<?php echo JS_URL; ?>device/device.js" type="text/javascript"></script>
<!--<div class="container-fluid">-->
    <!-- Start Page Content -->
    <div class="row">
        <div class="col-12">
            
                    
                    <div class="table-responsive m-t-0">
                        <table id="device_reading_list_tbl" class="table table-bordered table-striped" width='100%'>
                            <thead>
                                <tr>
                                    <th>S.no.</th>
                                    <th>Sensor Reading</th>
                                    <th>lat.</th>
                                    <th>lon.</th>
                                    <th>Reading At</th>
                                </tr>
                            </thead>
                        </table>
                   
            </div>


<script>
    jQuery("document").ready(function () {
        get_device_reading_list('<?php echo $device_code;?>');
    });
</script>