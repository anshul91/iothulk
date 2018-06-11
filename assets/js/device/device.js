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
                        window.location.href = BASE_URL."device-list";
                    }, 2000);
            },
            error: function (data) {
                alert("err"+data);
            }
        });

        e.preventDefault();
    });
});

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