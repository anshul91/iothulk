 jQuery("document").ready(function () {
 $("#saveDevice").on('click',function(){
            var url = BASE_URL + "/Admin_device/add_device_detail"
           $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: $("#frm_device").serialize(), // serializes the form's elements.
            success: function (data)
            {
                fancyAlert(data.msg, data.msg_type);
                if (data.status === 1)
                    setTimeout(function () {
                        // show response from the php script.
                        window.location.href = BASE_URL+"device-list";
                    }, 2000);
            },
            error: function (data) {
                alert("err"+data);
            }
        });

        e.preventDefault();
    });
$("#newDevice").on('click',function(){
    $("#saveDevice").show();
    $("#updateDevice").hide();
});

 $("#saveChart").on('click',function(){
            var url = BASE_URL + "/Admin_chart_selection/add_device_chart_detail"
           $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: $("#chart_selection_frm").serialize(), // serializes the form's elements.
            success: function (data)
            {
                fancyAlert(data.msg, data.msg_type);
                if (data.status === 1)
                    setTimeout(function () {
                        // show response from the php script.
                        window.location.href = BASE_URL+"device-list";
                    }, 2000);
            },
            error: function (data) {
                alert("err"+data);
            }
        });

        e.preventDefault();
    });
$("#newDevice").on('click',function(){
    $("#saveDevice").show();
    $("#updateDevice").hide();
});
});

function update_device(device_id){
            var url = BASE_URL + "/Admin_device/update_device_detail"
           $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: $("#frm_device").serialize(), // serializes the form's elements.
            success: function (data)
            {
                fancyAlert(data.msg, data.msg_type);
                if (data.status === 1)
                    setTimeout(function () {
                        // show response from the php script.
                        window.location.href = BASE_URL+"device-list";
                    }, 2000);
            },
            error: function (data) {
                alert("err"+data);
            }
        });

        e.preventDefault();

}
function open_update_device_popup(device_id){
    if(device_id==''){
        fancyAlert('Device Id not found!');
        return false;
    }

    var url = BASE_URL + "/Admin_device/get_device_detail"
   $.ajax({
        type: "POST",
        url: url,
        dataType: 'json',
        data: {'device_id':device_id}, // serializes the form's elements.
        success: function (data)
        {

            if(data.status == 1){
                //jQuery.noConflict();
                //jQuery('#myModal').modal('show').addClass('show');
                jQuery.each(data.resp_data,function(index,val_arr){
                    jQuery.each(val_arr,function(i,v){
                        $("#"+i).val(v);
                    });
                });
                $("#saveDevice").hide();
                $("#updateDevice").show();
            }else{
                fancyAlert(data.msg,data.msg_type);
            }
            console.log(data.resp_data);
        },
        onComplete:function(){

        },
        error: function (data) {
            console.log("err"+data);
        }
    });
}

function get_device_list() {
    var sendData = {};
    //sendData[csrfname] = csrfhash;
    $("#deviceTable").dataTable().fnDestroy();
    var dataTable = $('#deviceTable').DataTable({
//        "processing": true,
        "serverSide": true,
//        "scrollX": true,
//        "scrollY": 250,
        "lengthMenu": [[10,50,100,100000], ['10','50','100',"All"]],
        "ajax": {
            url: BASE_URL + "Admin_device/get_device_list", // json datasource
            type: "post", // method  , by default get
            asyc: false,
            data: sendData,
            error: function () {     // error handling
                $.fn.dataTable.ext.errMode = 'throw';
                $("#deviceTable").html("");
                $("#deviceTable").append('<tbody class="employee-grid-error" colspan="10"><tr  ><th >No data found on server</th></tr></tbody>');
                $("#deviceTable").css("display", "none");
            },
        },
//            "fnRowCallback": function (nRow, aData, iDisplayIndex) {
//                var info = $(this).DataTable().page.info();
//                $("td:nth-child(1)", nRow).html(info.start + iDisplayIndex + 1);
//                return nRow;
//            }
    });

}
function delete_device(device_id){
    if(confirm("Are You sure to delete This record")){
     var url = BASE_URL + "Admin_device/delete_device"
           $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: {'device_id':device_id}, // serializes the form's elements.
            success: function (data)
            {
                setTimeout(function(){fancyAlert(data.msg, data.msg_type);
                //$(".table-responsive m-t-0").load(location.href + " .table-responsive m-t-0");
                location.reload();
            },3000);
            },
            error: function (data) {
                console.log("err in delete device:"+data);
            }
        });
       }else
       return false;
}

function change_device_type(){
    if($("#signal_type :selected").val()==1){
        $("#device_type").find("[value=2]").hide();
    }else{
        $("#device_type").find("[value=2]").show();
    }

}

/*
    Device Reading Function Starts here
*/
/*Show view with list of reading sensor values*/
function get_device_reading_view(device_code,device_name){
    if($.trim(device_code)=='' || device_code == 'undefined'){
        fancyAlert('Device code is not defined!','warning');
        return false;
    }
    var url = BASE_URL + "device-reading-view"
           $.ajax({
            type: "POST",
            url: url,
            data: {'device_code':device_code}, // serializes the form's elements.
            success: function (data)
            {

                $("#device_heading").empty().append(" "+device_name);
               $("#device_reading_data").html(data);
            },
            error: function (data) {
                fancyAlert('Something unexpected happened please try after sometime.','error');
                console.log("err in delete device:"+data);
            }
        });
}

/*Show datatable for sensor reading*/
function get_device_reading_list(device_code) {
    var sendData = {device_code:device_code};
    $("#device_reading_list_tbl").dataTable().fnDestroy();
    var dataTable = $('#device_reading_list_tbl').DataTable({
        "serverSide": true,
        "lengthMenu": [[10,50,100,100000], ['10','50','100',"All"]],
        "ajax": {
            url: BASE_URL + "Admin_device/get_device_reading_list", // json datasource
            type: "post",
            asyc: false,
            "scrollX": true,
            "scrollY": 250,
            data: sendData,
            error: function () {     // error handling
                $.fn.dataTable.ext.errMode = 'throw';
                $("#device_reading_list_tbl").html("");
                $("#device_reading_list_tbl").append('<tbody class="employee-grid-error" colspan="10"><tr  ><th >No data found on server</th></tr></tbody>');
                $("#device_reading_list_tbl").css("display", "none");
            },
        },

    });

}




/*Show view with list of reading sensor values*/
function get_chart_selection_view(device_code,device_name){
    if($.trim(device_code)=='' || device_code == 'undefined'){
        fancyAlert('Device code is not defined!','warning');
        return false;
    }
    var url = BASE_URL + "Admin_device/get_chart_selection_view"
           $.ajax({
            type: "POST",
            url: url,
            data: {'device_code':device_code}, // serializes the form's elements.
            success: function (data)
            {

                $("#chart_heading").empty().append(" "+device_name);
               $("#chart_selection").html(data);
            },
            error: function (data) {
                fancyAlert('Something unexpected happened please try after sometime.','error');
                console.log("err in delete device:"+data);
            }
        });
}
function validation(id, error_id, msg_name) {
    if ($("#" + id).val() === "") {
        msg = msg_name + " cannot be empty.";
        $("#" + error_id).text(msg);
        //$("#"+id).css("border","1px solid red");
    } else {
        $("#" + error_id).text('');
        //$("").css("border","1px solid #E9E9E9");
    }
}