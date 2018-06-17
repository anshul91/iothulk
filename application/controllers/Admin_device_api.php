 <?php

 class Admin_device_api extends CI_Controller {
    protected $tbl_device = 'tbl_device';
    protected $tbl_device_reading = 'tbl_device_reading';

    public function __construct() {
        parent::__construct();
        $this->load->model('Device_model');
        $this->lang->load(array("button", "heading", "site_label"));
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
                    exit(json_encode(array("status"=>0,'msg_type'=>'info','msg'=>'This is not Sensor Device (please change your device type to GPS or other).')));
                }
               else if($this->Device_model->store_device_reading(array("device_code"=>$device_code,"sensor_reading"=>$sensor_reading,'user_id'=>$user_id))){
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