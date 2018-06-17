jQuery("document").ready(function () {
    /*Calling signup function*/

    $("#signup").click(function (e) {
        var flag = true;
        if ($("#email_id").val() == "") {
            msg = "Email Id cannot be empty.";
            flag = false;
        } else if ($("#password").val() == '') {
            msg = "Password cannot be empty.";
            flag = false;
        } else if ($("#mobile_no").val() == "") {
            msg = "Mobile No. cannot be empty.";
            flag = false;
        }
        if (flag === false) {
            fancyAlert(msg, 'warning');
            return;
        }
        var url = BASE_URL + "signup"; // the script where you handle the form input.

        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: $("#frm_signup").serialize(), // serializes the form's elements.
            success: function (data)
            {
                fancyAlert(data.msg, data.msg_type);
                if (data.status === 1)
                    setTimeout(function () {
                        // show response from the php script.
                        window.location.href = data.redirect_url;
                    }, 2000);
            },
            error: function (data) {
                alert("err");
            }
        });

        e.preventDefault();
    });
    $("#login").click(function (e) {

        e.preventDefault();
        //var plain_pass = $("#password").val();
        //$("#password").val(sha512(plain_pass));
        var flag = true;
        if ($("#email_id").val() == "") {
            msg = "Email Id cannot be empty.";
            flag = false;
        } else if ($("#password").val() == '') {
            msg = "Password cannot be empty.";

            flag = false;
        }
        if (flag === false) {
            $("#error_msg").text(msg);
            fancyAlert(msg, 'warning');
            return;
        }
        var url = BASE_URL + "login"; // the script where you handle the form input.

        $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: $("#frm_login").serialize(), // serializes the form's elements.
            success: function (data)
            {
                if (data.status != '1') {
                   //fancyAlert(data.msg, data.msg_type); // show response from the php script.
                } else {
                    $("#login").css('disabled',true);
                    $("#success_alert").show().html(data.msg);
//                    $("#error_msg").html('');

                }
//               console.log(data);
                
                if (data.hasOwnProperty('redirect_url'))
                    setTimeout(function () {
                        window.location.href = data.redirect_url;
                    }, 1000)
//                
            },
            error: function (data) {

                fancyAlert("Something unexpected happened! Try later.", "error");
                return;
            }
        });
    });
});

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
function userSignup(e) {

    // avoid to execute the actual submit of the form.

}