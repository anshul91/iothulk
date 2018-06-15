<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_device extends CI_Controller {
    protected $tbl_device = 'tbl_device';
    protected $tbl_device_reading = 'tbl_device_reading';

    public function __construct() {
        parent::__construct();
        $this->load->model('Device_model');
        $this->lang->load(array("button", "heading", "site_label"));
        if ($this->session->userdata("admin_userdata") === NULL) {
            redirect("login");
            exit;
        }

    }

    public function index() {       
        $data['main_content'] = 'admin/device/index';
        $this->load->view('templates/adminTemplate',$data);
    }
    
    public function get_device_list(){
        $data = $this->Device_model->get_device_list();
        echo json_encode($data);
        exit;
    }
    public function add_device_detail(){

        if ($this->input->post()) {
            $this->form_validation->set_rules("title", "Title", "trim|required");
            $this->form_validation->set_rules('sub_title', 'Sub Title', 'trim|required');

            $this->form_validation->set_rules("signal_type", "Signal Type", "trim|required");
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required');
            $this->form_validation->set_rules("sensor_name", "Sensor Name", "trim|required");
            $this->form_validation->set_rules("purpose", "Purpose", "trim|required");
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
             $this->form_validation->set_rules("short_desc", "Short Description", "trim|required");
             //print_r($this->session->userdata('admin_userdata')['userdata'][0]->user_id);die;
            if ($this->form_validation->run()) {
                $data_to_store = array(
                    "title" => rq("title"),
                    "sub_title" => rq("sub_title"),
                    "signal_type" => rq("signal_type"),
                    "sub_title" => rq("sub_title"),
                    "device_type" => rq("device_type"),
                    "purpose" => rq("purpose"),
                    "description" => rq("description"),
                    "short_desc" => rq("sub_description"),
                    "device_code"=>time().rand(0,999),
                    "user_id"=>$this->session->userdata('admin_userdata')['userdata'][0]->user_id
                );
                if(!$this->Device_model->add_device_detail($data_to_store)){
                    echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => "Something unexpected happened!"));
                exit;
                }else{
                    echo json_encode(array("status" => 1, "msg_type" => "success", "msg" => "Device Detail Added Successfully!"));
                exit;
                }
            }else {
                echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => validation_errors()));
                exit;
            }
        }
    }

public function update_device_detail(){

        if ($this->input->post()) {
            $device_id = rqs('device_id');
            $this->form_validation->set_rules("title", "Title", "trim|required");
            $this->form_validation->set_rules('sub_title', 'Sub Title', 'trim|required');

            $this->form_validation->set_rules("signal_type", "Signal Type", "trim|required");
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required');
            $this->form_validation->set_rules("sensor_name", "Sensor Name", "trim|required");
            $this->form_validation->set_rules("purpose", "Purpose", "trim|required");
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
             $this->form_validation->set_rules("short_desc", "Short Description", "trim|required");
            if ($this->form_validation->run()) {
                $data_to_store = array(
                    "title" => rq("title"),
                    "sub_title" => rq("sub_title"),
                    "signal_type" => rq("signal_type"),
                    "sub_title" => rq("sub_title"),
                    "device_type" => rq("device_type"),
                    "purpose" => rq("purpose"),
                    "description" => rq("description"),
                    "sensor_name"=>rq('sensor_name'),
                    "short_desc" => rq("sub_desc"),
                    "modified"=>date('Y-m-d h:i:s'),
                );
                if(!$this->Device_model->update_device_detail($device_id,$data_to_store)){
                    echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => "Something unexpected happened!"));
                exit;
                }else{
                    echo json_encode(array("status" => 1, "msg_type" => "success", "msg" => "Device Detail updated Successfully!"));
                exit;
                }
            }else {
                echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => validation_errors()));
                exit;
            }
        }
    }

    public function delete_device(){
        if($this->input->post()){
        $device_id = rqs('device_id');
        if(!is_numeric($device_id) || empty($device_id)){
            echo json_encode(array('status'=>0,"msg_type"=>"error","msg"=>"Device Id was not found to delete!"));
            exit;
        }
        if($this->Device_model->delete_device($device_id)){
            echo json_encode(array("status"=>1,"msg_type"=>"success","msg"=>"Device Data Deleted Successfully!"));
            exit;
        }else{
            echo json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Somthing unexpected Happened Please Try After Some Time!"));
            exit;
        }
    }else{
        echo json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Request Method Post is allowed only!"));
            exit;
        }
    }

    public function get_device_detail(){
        $device_id = rqs('device_id');
        $device_resp_data[0] = new stdClass();//array();
        if($device_id!='' && is_numeric($device_id)){
            $device_data = getTableData($this->tbl_device,array('device_id'=>$device_id));
            if(is_array($device_data) && count($device_data)){
                //prd($device_id);
                $device_resp_data[0]->device_id = encryptMyData($device_id);
                $device_resp_data[0]->title = $device_data[0]->title;
                $device_resp_data[0]->sub_title = $device_data[0]->sub_title;
                $device_resp_data[0]->signal_type = $device_data[0]->signal_type;
                $device_resp_data[0]->device_type = $device_data[0]->device_type;
                $device_resp_data[0]->description = $device_data[0]->description;
                $device_resp_data[0]->short_desc = $device_data[0]->short_desc;
                $device_resp_data[0]->min_val = $device_data[0]->min_val;
                $device_resp_data[0]->max_val = $device_data[0]->max_val;
                $device_resp_data[0]->sensor_name = $device_data[0]->sensor_name;
                $device_resp_data[0]->purpose = $device_data[0]->purpose;
                $device_resp_data[0]->max_request = $device_data[0]->max_request;

                echo json_encode(array("resp_data"=>$device_resp_data,"status"=>1,"msg_type"=>"success","msg"=>"got response"));
            }
        }else{
            echo json_encode(array('status'=>0,'msg_type'=>'error','msg'=>'Device Id Not found or not correct'));

        }
        exit;
    }


    /*
        =====================================================================================
                            CREATING REST API FUNCTIONS FOR DEVICES MODULE
        ===================================================================================== 
    */
    protected function is_device_found($device_code,$user_id){

        $device_data = getTableData($this->tbl_device,array('device_code'=>$device_code,'user_id'=>$user_id));
        return (count($device_data)>0 && is_array($device_data)) ? $device_data : false;
    } 
       
    public function store_device_reading($api_key,$device_code,$sensor_reading){
        if($this->input->server('REQUEST_METHOD') =='POST' || $this->input->server('REQUEST_METHOD') =='GET' ){
            if(empty($api_key) || empty($device_code) || empty($sensor_reading)){
                echo json_encode(array('status'=>0,"msg"=>"No sufficient Parameters found",'msg_type'=>'error'));
                exit;
            }

            $user_id = check_authUser($api_key);
            if($user_id){
                $device_detail = $this->is_device_found($device_code,$user_id);

                 if(!$device_detail){
                    echo json_encode(array('status'=>0,"msg_type"=>'info','msg'=>'Device Not found with given user id'));
                    exit;
                }
               if($device_detail[0]->device_type !=1){
                    exit(json_encode(array("status"=>0,'msg_type'=>'info','msg'=>'This is not Sensor Device (please change your device type).')));
                }
               else if($this->Device_model->store_device_reading(array("device_id"=>$device_code,"sensor_reading"=>$sensor_reading,'user_id'=>$user_id))){
                    echo json_encode(array('status'=>1,"msg_type"=>'success','msg'=>'Device Value Stored Successfully at:'.date('Y-m-d H:i:s')));
                    exit;
                }else{
                    echo json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Cannot Store Reading Something unexpected Happened!"));
                    log_message('NOT STORED READING - device_id:'.$device_code.'-userid'.$user_id);
                    exit;
                }
                
            }else{
                echo json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Authentication key not matched!"));
            }

        }else{
            echo json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Only Post method is allowed"));
        }
        exit;
    }

    public function store_gps_device_reading($api_key,$device_code,$lat,$lon){
        if($this->input->server('REQUEST_METHOD') =='POST' || $this->input->server('REQUEST_METHOD') =='GET' ){
            if(empty($api_key) || empty($device_code) || empty($lat)||empty($lon)){
                echo json_encode(array('status'=>0,"msg"=>"No sufficient Parameters found",'msg_type'=>'error'));
                exit;
            }

            $user_id = check_authUser($api_key);
            if($user_id){
                $device_detail = $this->is_device_found($device_code,$user_id);
                if(!$device_detail){
                    echo json_encode(array('status'=>0,"msg_type"=>'info','msg'=>'Device Not found with given user id'));
                    exit;
                }

                if($device_detail[0]->device_type !=2){
                    exit(json_encode(array("status"=>0,'msg_type'=>'info','msg'=>'This is not GPS Device.')));
                }
                
                else if($this->Device_model->store_device_reading(array("device_id"=>$device_code,"lat"=>$lat,'lon'=>$lon,'user_id'=>$user_id))){
                    echo json_encode(array('status'=>1,"msg_type"=>'success','msg'=>'Device Value Stored Successfully at:'.date('Y-m-d H:i:s')));
                    exit;
                }else{
                    echo json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Cannot Store Reading Something unexpected Happened!"));
                    log_message('NOT STORED READING - device_id:'.$device_code.'-userid'.$user_id);
                    exit;
                }
                
            }else{
                echo json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Authentication key not matched!"));
            }

        }else{
            echo json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Only Post method is allowed"));
        }
        exit;
    }

    
    public function get_device_reading($api_key,$device_code,$format='json'){
        $device_reading_arr = array();
        if($this->input->server('REQUEST_METHOD') == 'GET'){

            if(strtolower($format) =='json'){
                
                $user_id = check_authUser($api_key);
                if($user_id){

                    $device_detail = $this->is_device_found($device_code,$user_id);

                    if(!$device_detail){
                        exit(json_encode(array('status'=>0,"msg_type"=>'info','msg'=>'Device Not found with given Credentials')));
                    }else if($device_detail[0]->device_type == 1){
                        $device_reading_arr = getTableData($this->tbl_device_reading,array('user_id'=>$user_id,'device_code'=>$device_code,'cols'=>array('sensor_reading','created')));
                    }else if($device_detail[0]->device_type ==2){
                        $device_reading_arr = getTableData($this->tbl_device_reading,array('user_id'=>$user_id,'device_code'=>$device_code,'cols'=>array('lat','lon','created')));
                    
                    }
                    exit(json_encode(array("status"=>1,"msg_type"=>"success","device_reading"=>$device_reading_arr)));
                }else{
                    echo json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Authentication key not matched!"));
                    exit;
                }
            }else{
                echo json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Allowed format: JSON ONLY"));
                    exit;
            }
        }else{
            exit(json_encode(array("status"=>0,"msg_type"=>"error","msg"=>"Only Post method is allowed")));
        }
        exit;
    }
    
}

