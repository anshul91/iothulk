<script src="<?php echo JS_URL; ?>feedback/feedback.js" type="text/javascript"></script>
<div class="col-lg-12">
	<div class="card">

	    <div class="card-body">
	    <h3 class="card-title m-t-15">Feedback & Suggestions</h3>
	    <hr>
	        <div class="form-validation">
	            <form class="form-valide" action="#" method="post" novalidate="novalidate" id="frm_feedback">
	                
	                <div class="form-group row">
	                    <label class="col-lg-4 col-form-label" for="val-suggestions">My Feedback <small> <span class="text-danger">*</span></small></label>
	                    <div class="col-lg-6">
	                        <textarea class="form-control" id="feedback" name="feedback" rows="5" placeholder="Share Your experience!"></textarea>
	                    </div>
	                </div>
	                <div class="form-group row">
	                    <label class="col-lg-4 col-form-label">Suggestion <small> <span class="text-danger">*</span></small></label>
	                    <div class="col-lg-6">
	                        <textarea class="form-control" id="suggestion" name="suggestion" rows="5" placeholder="What can we do to become better?" ></textarea>
	                   </div>
	                </div>
	                <div class="form-group row ">
	                    <div class="col-lg-8  ml-auto">
	                    	 <a href="<?php echo site_url();?>" class="btn btn-primary" id="btn_back"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
	                        <button type="button" class="btn btn-success" id="btn_feedback"><i class="fa fa-check"></i> Save</button>
	                       
	                    </div>
	                </div>
	            </form>
	        </div>

	    </div>
	</div>
</div>
