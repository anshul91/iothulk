<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * CREATED BY: ANSHUL PAREEK
 * CREATED DATE:
 * MODIFIED DATE:  
 */

class Feedback_model extends CI_Model {

   
    public $tbl_users = "tbl_users";
    public $tbl_api_key = "tbl_api_key";
    public $tbl_feedback_suggestion = "tbl_feedback_suggestion";
    public $tbl_device_reading = 'tbl_device_reading';

public function add_feedback(array $data) {
        $data_to_store = $this->security->xss_clean($data);
        $ret = $this->db->insert($this->tbl_feedback_suggestion, $data_to_store);
       // echo $this->db->last_query();
       // die;
        if ($ret) {
            //actionTrail("User Add action performed", User_lang::ACTION_TRAIL_SUCCESS);
            return true;
        } else {
            //actionTrail("User Add Action Performed", User_lang::ACTION_TRAIL_ERROR);
            return false;
        }
    }
}
