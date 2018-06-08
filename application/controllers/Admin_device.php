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
        
    }
}