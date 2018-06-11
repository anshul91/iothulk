
function get_device_list(csrfname, csrfhash) {
    var sendData = {};
    sendData[csrfname] = csrfhash;
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