<?php
	class Chart_selection_model extends CI_Model{

   
	    public $tbl_users = "tbl_users";
	    public $tbl_device_chart = "tbl_device_chart";
	    public $tbl_device = "tbl_device";
	    public $tbl_device_reading = 'tbl_device_reading';

	public function add_device_chart_detail(array $data,$device_code) {
    $data_to_store = $this->security->xss_clean($data);
    $this->db->delete($this->tbl_device_chart,array("device_code"=>$device_code));
    $ret = $this->db->insert_batch($this->tbl_device_chart, $data_to_store);
//        echo $this->db->last_query();
//        die;
	    if ($ret) {
	        //actionTrail("User Add action performed", User_lang::ACTION_TRAIL_SUCCESS);
	        return true;
	    } else {
        //actionTrail("User Add Action Performed", User_lang::ACTION_TRAIL_ERROR);
        return false;
    	}
    }

    public function get_device_min_max_reading($device_code,$reading_for_days = 7){
    	
    	
    	$qry = "SELECT device_code,min(sensor_reading) min_reading, max(sensor_reading) max_reading,created FROM `tbl_device_reading` where device_code='$device_code' and 
			DATE(created)>=(now()-INTERVAL $reading_for_days DAY) group by date(created)";
    	
    	$res = $this->db->query($qry);
    	return $res->result();
    }
}
?>