<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_user extends CI_Controller {
    public $tbl_feedback_suggestion = 'tbl_feedback_suggestion';
    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->lang->load(array("button", "heading", "site_label"));

    }

    public function login() {
        if ($this->session->userdata("admin_userdata") !== null) {
            echo json_encode(array("status" => 1, "msg_type" => "success", "msg" => "Successfully Login!", "redirect_url" => site_url()));
            redirect(site_url());
            exit;
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules("email_id", "Email id", "trim|required|valid_email");
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            if ($this->form_validation->run()) {
                $data_to_store = array(
                    "email_id" => $this->input->post("email_id"),
                    "password" => $this->input->post("password")
                );
                if ($data = $this->User_model->validate($data_to_store)) {
                    $this->session->set_userdata(array("admin_userdata" => $data));

                    echo json_encode(array("status" => 1, "msg_type" => "success", "msg" => "Successfully Login!", "redirect_url" => site_url()));
                    exit;
                } else {
                    echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => "User does not exists with given credentials!"));
                    exit;
                }
            } else {
                echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => validation_errors()));
                exit;
            }
        }
        //$data['main_content'] = 'admin/login';
        $this->load->view('admin/login');
    }

    public function logout() {
        session_destroy();
        redirect('login');
    }

    public function signup() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('email_id', 'Email id', 'trim|required|valid_email|is_unique[tbl_users.email_id]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[15]|min_length[6]');
            $this->form_validation->set_rules('mobile_no', 'Mobile no.', 'trim|required|integer|exact_length[10]');
            if ($this->form_validation->run()) {
                $data_to_store = array(
                    "email_id" => $this->input->post("email_id"),
                    "username" => $this->input->post("email_id"),
                    "password" => $this->input->post("password"),
                    "mobile_no" => $this->input->post("mobile_no"),
                    "created" => date("Y-m-d H:i:s"),
                    "modified" => date("Y-m-d H:i:s")
                );
                if ($this->User_model->signup($data_to_store)) {

                    echo json_encode(array("status" => 1, "msg_type" => "success", "msg" => "Ohh well You've signup successfully!", "redirect_url" => site_url() . "login"));
                } else {
                    echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => "Sorry! Something unexpected happened please try after sometime!"));
                }
                exit;
            } else {
                echo json_encode(array("status" => 0, "msg_type" => "error", "msg" => validation_errors()));
                exit;
            }
        }
        $this->load->view('admin/signup');
    }

    public function index() {
        if ($this->session->userdata("admin_userdata") === NULL) {
            redirect("login");
            exit;
        }
        $user_detail_obj = getSessionUserDetail();
        $tot_user_device = getTotUserDevice($user_detail_obj->user_id);
        $feedback = getTableData($this->tbl_feedback_suggestion,array('is_approved'=>1,array('feedback','user_id')));
        
        $data['feedback'] = $feedback;
        $data['tot_device'] = $tot_user_device;

        $data['main_content'] = 'admin/dashboard';
        
        $this->load->view('templates/adminTemplate', $data);
    }

}

?>