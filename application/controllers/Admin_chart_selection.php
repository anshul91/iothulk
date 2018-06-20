<?php
class Admin_chart_selection extends CI_Controller{
	
	protected $tbl_device = 'tbl_device';
    protected $tbl_device_reading = 'tbl_device_reading';
    protected $tbl_device_chart = 'tbl_device_chart';
    protected $user_id = '';

    public function __construct() {

        parent::__construct();

        $this->load->model('Chart_selection_model');
        $this->lang->load(array("button", "heading", "site_label"));

        //prd($this->session->all_userdata());
        if ($this->session->userdata("admin_userdata") === NULL) {
            
            redirect("login");
            exit;
        }
        $this->user_id = getSessionUserDetail()->user_id;

    }
    public function index(){

    	$devices = getTableData($this->tbl_device,array('user_id'=>$this->user_id,'device_type'=>1));
    	
    	$data['devices'] = $devices;
    	$data['main_content'] = 'admin/chart_selection/index';

        $this->load->view('templates/adminTemplate',$data);
    }
    public function add_device_chart_detail(){

        if ($this->input->post()) {
            $this->form_validation->set_rules("chart_type[]", "Chart Type", "trim|required");
            // $this->form_validation->set_rules('chart_data_criterea', 'Sub Title', 'trim|required');
            // $this->form_validation->set_rules("dashboard_status", "Dashboard Status", "trim|required");
             $this->form_validation->set_rules("device_code", "Device Code", "trim|required");

            if ($this->form_validation->run()) {
            	for($i=0;$i<count(rq('chart_type'));$i++){
	                $data_to_store[] = array(
	                    "chart_name" => rq("chart_type")[$i],
	                    "chart_data_criterea" => rq("chart_data_criterea")?rq("chart_data_criterea")[$i]:0,
	                    "dashboard_status" => rq("dashboard_status")?rq('dashboard_status')[$i]:0,
	                    "device_code"=>rqs('device_code')
	                );

        		}
        		// prd($data_to_store);
                if(!$this->Chart_selection_model->add_device_chart_detail($data_to_store,rq('device_code'))){
                    echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => "Something unexpected happened!"));
                exit;
                }else{
                    echo json_encode(array("status" => 1, "msg_type" => "success", "msg" => "Chart Detail Added Successfully!"));
                exit;
                }
            }else {
                echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => validation_errors()));
                exit;
            }
        }
    }
	public function get_chart_analysis_view(){
		$data['user_id'] = $this->user_id;
		$data['main_content'] = 'admin/chart_selection/chart_analysis';
        $this->load->view('templates/adminTemplate',$data);
    }
   	public function get_chart_for_analysis(){
	   	$resp_data = array();
	   	$user_id = rq('user_id');
	   	$chart_data = getTableData($this->tbl_device_chart,array('user_id'=>$user_id));
	   	$data = array();
	   	foreach($chart_data as $k=>$v){
	   		$nested_arr = array();
	   		$nested_arr['chart_vals'] = $this->Chart_selection_model->get_device_min_max_reading($v->device_code);
	   		$nested_arr['chart_name'] = $v->chart_name;
	   		$nested_arr['chart_data_criterea'] = $v->chart_data_criterea;
	   		$nested_arr['chart_dashboard_status'] = $v->dashboard_status;
	   		$resp_data[] = $nested_arr;
	   	}
	   	echo json_encode(array('msg'=>'SUCCESS','msg_type'=>'success','status'=>1,'response'=>$resp_data));
	   	exit;
   }
}

?>