/*Feedback js*/

jQuery(document).ready(function(){
	

	jQuery("#btn_feedback").click(function(){
		 var url = BASE_URL + "/Admin_feedback/add_feedback"
	           $.ajax({
	            type: "POST",
	            url: url,
	            dataType: 'json',
	            data: $("#frm_feedback").serialize(), // serializes the form's elements.
	            success: function (data)
	            {
	                fancyAlert(data.msg, data.msg_type);
	                
	            },
	            error: function (data) {
	                alert("err"+data);
	            }
	        });

	        e.preventDefault();
	});
});