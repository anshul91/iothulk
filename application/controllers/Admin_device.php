<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_device extends CI_Controller {

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
                    "created_by"=>$this->session->userdata('admin_userdata')['userdata'][0]->user_id
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
}