<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_feedback extends CI_Controller {
    protected $tbl_device = 'tbl_device';
    protected $tbl_device_reading = 'tbl_device_reading';

    public function __construct() {
        parent::__construct();
        $this->load->model('Feedback_model');
        $this->lang->load(array("button", "heading", "site_label"));
        if ($this->session->userdata("admin_userdata") === NULL) {
            redirect("login");
            exit;
        }

    }

    public function index() {       
        $data['main_content'] = 'admin/feedback/index';
        $this->load->view('templates/adminTemplate',$data);
    }
    
    public function get_feedback_list(){
        $user_detail = getSessionUserDetail();
        
        $data = $this->Device_model->get_device_list(array("user_id"=>$user_detail->user_id));
        echo json_encode($data);
        exit;
    }
    public function add_feedback(){

        if ($this->input->post()) {
           
             $this->form_validation->set_rules("feedback", "Feedback", "trim|required");
              $this->form_validation->set_rules("suggestion", "Suggestion", "trim|required");
             //print_r($this->session->userdata('admin_userdata')['userdata'][0]->user_id);die;
            if ($this->form_validation->run()) {
                $data_to_store = array(
                    "feedback" => rq("feedback"),
                    "suggestion" => rq("suggestion"),
                    "user_id"=>$this->session->userdata('admin_userdata')['userdata'][0]->user_id
                );
                if(!$this->Feedback_model->add_feedback($data_to_store)){
                    echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => "Something unexpected happened!"));
                exit;
                }else{
                    echo json_encode(array("status" => 1, "msg_type" => "success", "msg" => "Thanks For your Feedback we will show this on dashboard if it get Selected!"));
                exit;
                }
            }else {
                echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => validation_errors()));
                exit;
            }
        }
    }
}

