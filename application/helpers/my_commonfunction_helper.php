<?php

/*
 * CREATED BY: ANSHUL PAREEK
 * CREATED DATE:
 * LAST MODIFIED BY: ANUPAM PAGARIA
 * MODIFIED DATE:12,Jan-2018
 */

if (!function_exists("getDistrict")) {

    function getDistrict($filter = array()) {

        $ci = &get_instance();
        $table = "distmaster";
        $cols = " * ";
        $cond = " WHERE 1=1 ";
        $query = "SELECT ";
        $orderby = "";
        if (isset($filter)) {
            $fields = $ci->db->list_fields('distmaster');

            foreach ($fields as $field) {
                if (isset($filter[$field]) && array_key_exists($field, $filter)) {
                    $cond .= " and $field ='" . $filter[$field] . "' ";
                }
            }
            if ((array_key_exists("cols", $filter))) {
                if (is_array($filter['cols']) && count($filter['cols'])) {
                    $cols = rtrim(implode(",", $filter['cols']), ",");
                }
            }
            if (array_key_exists("orderasc", $filter) && is_array($filter['orderasc'])) {
                $orderby .= "order by " . rtrim(implode(",", $filter['orderasc']), ",") . " ASC ";
            }
            if (array_key_exists("orderdsc", $filter) && is_array($filter['orderdsc'])) {
                $orderby .= "order by " . rtrim(implode(",", $filter['orderdsc']), ",") . " DESC ";
            }
            $query = $query . $cols . " from $table $cond $orderby";
            $res = $ci->db->query($query);
//            echo $ci->db->last_query();die;
            if ($res->num_rows() > 0)
                return $data = $res->result();
            else
                return false;
        }
    }

}
if (!function_exists("__getUserRightLevel")) {

    function __getUserRightLevel() {
        $ci = &get_instance();
        $userRight = '';

        if (decryptMyData($ci->session->userdata('allowed_dist')) == "all") {
            $userRight = "sa"; //who have all district and project access (sa,fa etc.)
        } else if (decryptMyData($ci->session->userdata('allowed_project')) == "all") {
            $userRight = "level_1"; //who have all project rights of any district (district)
        } else if (is_numeric(decryptMyData($ci->session->userdata("allowed_project")))) {
            $userRight = "level_2";
        }

        return $userRight;
    }

}
#rolewise district
if (!function_exists("getDistrictRoleWise")) {

    function getDistrictRoleWise($filter = array()) {
        $ci = &get_instance();
        $retDist = array();
        $rightLevel = __getUserRightLevel();
        if ($rightLevel == 'sa') {
            $retDist = getDistrict($filter);
        } else if ($rightLevel == "level_1") {

            $distid = decryptMyData($ci->session->userdata("unitId"));
            $condArr = array_merge($filter, array("distid" => $distid));

            $retDist = getDistrict($condArr);
        } else if ($rightLevel == "level_2") {

            $distid = decryptMyData($ci->session->userdata("unitId"));
            $retDist = getDistByProjectId($distid);
        }
        return $retDist;
    }

}

if (!function_exists("getProject")) {

    function getProject($filter = array()) {
        $ci = &get_instance();
        $fields = $ci->db->list_fields('projectmaster');
        $table = "projectmaster";
        $cols = " * ";
        $cond = " WHERE 1=1 ";
        $query = "SELECT ";
        $orderby = "";
        if (isset($filter)) {
            foreach ($fields as $field) {
                if (isset($filter[$field]) && array_key_exists($field, $filter)) {
                    $cond .= " and $field=" . $ci->db->escape($filter[$field]);
                }
            }
            if ((array_key_exists("cols", $filter))) {
                if (is_array($filter['cols'])) {
                    $cols = rtrim(implode(",", $filter['cols']), ",");
                }
            }
            if (array_key_exists("orderasc", $filter) && is_array($filter['orderasc'])) {
                $orderby .= "order by " . rtrim(implode(",", $filter['orderasc']), ",") . " ASC ";
            }
            if (array_key_exists("orderdsc", $filter) && is_array($filter['orderdsc'])) {
                $orderby .= "order by " . rtrim(implode(",", $filter['orderdsc']), ",") . " DESC ";
            }
            $query = $query . $cols . " from $table $cond $orderby";

            $res = $ci->db->query($query);
//            echo $ci->db->last_query();
//            die;
            return $res->num_rows() > 0 ? $res->result() : false;
        }
    }

}
#get project data according user roles defined in user table
#@ arg: projectOrDistId = pass project or dist id
if (!function_exists("getProjectRoleWiseDropdown")) {

    function getProjectRoleWiseDropdown($projectOrDistid, $filter = array(), $selectedVal = '', $all = '') {
        $ci = &get_instance();
        $projectData = array();
        $condArr = array();
        $options = "";
        $rightLevel = trim(__getUserRightLevel());

        if ($all != '' && ($rightLevel == 'sa' || $rightLevel == 'level_1')) {
            $options .= "<option value='" . encryptMyData("all") . "'>--All--</option>";
        }

        if ($rightLevel == 'sa') {
            if ($projectOrDistid != 'all') {
                $condArr = array_merge($filter, array("distid" => $projectOrDistid));
                $projectData = getProject($condArr);
            }#dist level user can see only his district projects
        } else if ($rightLevel == "level_1") {
            $distid = decryptMyData($ci->session->userdata("unitId"));
            $condArr = array_merge($filter, array("distid" => $distid));

            $projectData = getProject($condArr);
        } else if ($rightLevel == "level_2") {
            $projectcode = decryptMyData($ci->session->userdata("unitId"));
            $condArr = array_merge($filter, array("projectcode" => $projectcode));          
            $projectData = getProject($condArr);
        }
        
        if (count($projectData) > 0) {
            foreach ($projectData as $val) {
                if (isset($selectedVal) && $selectedVal !== '' && $selectedVal === $val->projectcode)
                    $options .= "<option value='" . encryptMyData($val->projectcode) . "' selected>" . $val->projectname . " (" . $val->projectcode . ")" . "</option>";
                else
                    $options .= "<option value='" . encryptMyData($val->projectcode) . "'>" . $val->projectname . " (" . $val->projectcode . ")" . "</option>";
            }
        }
        return $options;
    }

}
if (!function_exists("getProjectByDistId")) {

    function getDistByProjectId($projectcode) {
        $ci = &get_instance();
        $query = "Select d.distid,d.distnamee from "
                . "distmaster d inner join projectmaster p on p.distid=d.distid where projectcode=?"
                . "order by distnamee";
        $res = $ci->db->query($query, array($projectcode));

//        echo $ci->db->last_query();
//        die;
        return $data = $res->num_rows() > 0 ? $res->result() : false;
    }

}
if (!function_exists("getSector")) {

    function getSector($filter = array()) {
        $ci = &get_instance();
        $fields = $ci->db->list_fields('secmaster');
        $table = "secmaster";
        $cols = " * ";
        $cond = " WHERE 1=1 ";
        $query = "SELECT ";
        $orderby = '';
        if (isset($filter)) {
            foreach ($fields as $field) {
                if (isset($filter[$field]) && array_key_exists($field, $filter)) {
                    $cond .= " and $field=" . $ci->db->escape($filter[$field]);
                }
            }
            if ((array_key_exists("cols", $filter))) {
                if (is_array($filter['cols'])) {
                    $cols = rtrim(implode(",", $filter['cols']), ",");
                }
            }
            if (array_key_exists("orderasc", $filter) && is_array($filter['orderasc'])) {
                $orderby .= "order by " . rtrim(implode(",", $filter['orderasc']), ",") . " ASC ";
            }
            if (array_key_exists("orderdsc", $filter) && is_array($filter['orderdsc'])) {
                $orderby .= "order by " . rtrim(implode(",", $filter['orderdsc']), ",") . " DESC ";
            }
            $query = $query . $cols . " from $table $cond $orderby";
            $res = $ci->db->query($query);
            return $data = $res->result();
        }
    }

}
if (!function_exists("getMenuData")) {

    function getMenuData($filter = array()) {
        $ci = &get_instance();
        $fields = $ci->db->list_fields('menu');

        $table = "menu";
        $cols = " * ";
        $cond = " WHERE 1=1 ";
        $query = "SELECT ";
        $orderby = '';
        if (isset($filter)) {
            foreach ($fields as $field) {
                if (isset($filter[$field]) && array_key_exists($field, $filter)) {
                    $cond .= " and $field=" . $ci->db->escape($filter[$field]);
                }
            }
            if ((array_key_exists("cols", $filter))) {
                if (is_array($filter['cols'])) {
                    $cols = rtrim(implode(",", $filter['cols']), ",");
                }
            }
            if (array_key_exists("orderasc", $filter) && is_array($filter['orderasc'])) {
                $orderby .= "order by " . rtrim(implode(",", $filter['orderasc']), ",") . " ASC ";
            }
            if (array_key_exists("orderdsc", $filter) && is_array($filter['orderdsc'])) {
                $orderby .= "order by " . rtrim(implode(",", $filter['orderdsc']), ",") . " DESC ";
            }
            $query = $query . $cols . " from $table $cond $orderby";
            $res = $ci->db->query($query);
            return $data = $res->result();
        }
    }

}
if (!function_exists("prd")) {

    function prd($arr) {
        echo "<pre><strong>PRD:</strong><br/>";
        print_r($arr);
        die;
    }

}
if (!function_exists("pr")) {

    function pr($arr) {
        echo "<pre><strong>PR:</strong><br/>";
        print_r($arr);
        echo "</pre>";
    }

}
if (!function_exists("prsessd")) {

    function prsessd() {
        $ci = & get_instance();
        echo "<pre><strong>PRINT-SESSION:</strong><br/>";
        print_r($ci->session->all_userdata());
        echo "</pre>";
        die;
    }

}
if (!function_exists("prsess")) {

    function prsess() {
        $ci = & get_instance();
        echo "<pre><strong>PRINT-SESSION:</strong><br/>";
        print_r($ci->session->all_userdata());
        echo "</pre>";
    }

}
if (!function_exists("rq")) {

    function rq($param = '') {
        $ci = & get_instance();
        if (trim($param) !== '' && isset($param)) {
            return $ci->input->post($param, TRUE);
        } else {
            return $ci->input->post(null, TRUE);
        }
    }

}
if (!function_exists("rqs")) {

    function rqs($param = '') {
        $ci = & get_instance();
        if (trim($param) !== '' && isset($param)) {
            return decryptMyData($ci->input->post($param, true));
        }
    }

}
/*
 *  Message type: success/warning/error/info
 *  */
if (!function_exists("setFlashMessage")) {

    function setFlashMessage($msg, $type = 'success', $name = 'flash_message', $heading = "message") {

//        $msgtypearr = array(
//            "success" => "success",
//            "warning" => "warning",
//            "error" => "danger",
//            "info" => "info"
//        );
        $msgtypearr = array("success", "warning", "error", "info");
        $type = strtolower($type);
        $ci = & get_instance();
        $ci->load->library("session");

//        $flashmsg = "<div class='alert alert-" . $msgtypearr[$type] . " alert-dismissable'><strong>" .
//                ucfirst($type) . ": </strong><a href='#' class=\"close\" data-dismiss=\"alert\" "
//                . "aria-label=\"close\">&times;</a>" . ucfirst($msg) . "</div>";
        $flashmsg = "<script> fancyAlert('" . ucfirst($msg) . "','" . $type . "');</script>";
        $ci->session->set_flashdata($name, $flashmsg);
    }

}
/*
 *  Message type: success/warning/error/info
 *  */
if(!function_exists("setKeepFlashMessage")) {
    function setKeepFlashMessage($msg, $type = 'success', $name = 'flash_message', $heading = "message") {
        $msgtypearr = array(
            "success" => "success",
            "warning" => "warning",
            "error" => "danger",
            "info" => "info"
        );
        $type = strtolower($type);
        $ci = & get_instance();
        $ci->load->library("session");
        $flashmsg = "<div class='alert alert-" . $msgtypearr[$type] . " alert-dismissable'><strong>" . ucfirst($type) . ": </strong><a href='#' class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>" . ucfirst($msg) . "</div>";
        $ci->session->keep_flashdata($name, $flashmsg);
    }

}
if (!function_exists("fetchMenu")) {

    function fetchMenu($parent = '') {
        $ci = &get_instance();
        $items = array();
        if ($parent != '')
            $ci->db->where('parentid', $parent);
        else
            $ci->db->where("coalesce(parentid,null) is null");
//        $ci->db->where('delflag', "0");

        $ci->db->order_by('menuid', 'asc');
        $query = $ci->db->get('menu');
//        echo $ci->db->last_query();
//        die;
        return $result = $query->result_array();
    }

}
if (!function_exists("getFormattedProjectCode")) {

    function getFormattedProjectCode($projectcode) {
        if (!isset($projectcode))
            return false;
        return strlen($projectcode) == 5 ? "08" . $projectcode : "080" . $projectcode;
    }

}
if (!function_exists("getFormattedSectorCode")) {

    function getFormattedSectorCode($sectorcode) {

        if (!isset($sectorcode))
            return false;
        return strlen($sectorcode) == 2 ? $sectorcode : "0" . $sectorcode;
    }

}
if (!function_exists("getEduQualification")) {

    function getEduQualification($filter = array()) {
        $ci = &get_instance();
        $fields = $ci->db->list_fields('eduQual_master');
        $table = "eduQual_master";
        $cols = " * ";
        $cond = " WHERE 1=1 ";
        $query = "SELECT ";
        $orderby = "";
        if (isset($filter)) {
            foreach ($fields as $field) {
                if (isset($filter[$field]) && array_key_exists($field, $filter)) {
                    $cond .= " and $field=" . $ci->db->escape($filter[$field]);
                }
            }
            if ((array_key_exists("cols", $filter))) {
                if (is_array($filter['cols'])) {
                    $cols = rtrim(implode(",", $filter['cols']), ",");
                }
            }
            if (array_key_exists("orderasc", $filter) && is_array($filter['orderasc'])) {
                $orderby .= "order by " . rtrim(implode(",", $filter['orderasc']), ",") . " ASC ";
            }
            if (array_key_exists("orderdsc", $filter) && is_array($filter['orderdsc'])) {
                $orderby .= "order by " . rtrim(implode(",", $filter['orderdsc']), ",") . " DESC ";
            }
            $query = $query . $cols . " from $table $cond $orderby";
            $res = $ci->db->query($query);
            return $data = $res->result();
        }
    }

}
if (!function_exists("getEduQualificationDropdown")) {

    function getEduQualificationDropdown($filter = array(), $attr = '') {
        $dropdown = '';
        $param = '';
        if ($attr !== '' && !is_array($attr)) {
            $dropdown .= "<select  $attr>";
        } else if ($attr !== '' && is_array($attr)) {
            foreach ($attr as $k => $v)
                $param .= $k . " = " . $v . " ";
            $dropdown .= "<select $param>";
        }

        $data = getEduQualification($filter);
        $dropdown .= '<option value="">--Select Qualification--</option>';
        foreach ($data as $k => $v) {

            $dropdown .= "<option value='" . encryptMyData($v->eduqualid) . "'>" . $v->eduqual . "</option>";
        }
        if ($attr !== '')
            $dropdown .= "</select>";
        return $dropdown;
    }

}


if (!function_exists("actionTrail")) {

    function actionTrail($actperformed = '', $status = '') {
        $ci = &get_instance();
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $dir = $ci->router->directory;
        $clas = $ci->router->class;
        $method = $ci->router->method;
        $actArr = array();
        $actArr['actperformed'] = $ci->db->escape($actperformed);
        $actArr['status'] = $ci->db->escape($status);
        $actArr['acturl'] = $dir . "/" . $clas . "/" . $method;
        $actArr['actquery'] = $ci->db->escape($ci->db->last_query());
        $actArr['actdatetime'] = date("Y-m-d H:i:s");
        $actArr['entrydate'] = date("Y-m-d H:i:s");
        $actArr['userid'] = decryptMyData($ci->session->userdata("userId"));
        $actArr['ipaddress'] = $ci->db->escape($ip);
	$actArr['user_trail_id'] = $ci->session->userdata("login_id");

        $ci->db->insert("actiontrail", $actArr);
//        echo $ci->db->last_query();die;        
        if ($ci->db->affected_rows() > 0) {
            return true;
        } else {

            return false;
        }
    }

}

if (!function_exists("loginAttemptTrail")) {

    function loginAttemptTrail($actArr = array(),$status) {
        $ci = &get_instance();
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $actArr['status'] = $status;
        $actArr['ipaddress'] = $ci->db->escape($ip);
        $actArr['created'] = date("Y-m-d H:i:s");
        $ci->db->insert("tbl_login_attempts", $actArr);  
//        prd(lastQuery());
        if ($ci->db->affected_rows() > 0) {
            return true;
        } else {

            return false;
        }
    }

}
if (!function_exists("emailMask")) {

    function emailMask($emailId) {
        if (empty($emailId))
            return false;
        return $returnData = str_ireplace(array("@", "."), array("[at]", "[dot]"), $emailId, $count);
    }

}
if (!function_exists("mobileMask")) {

    function mobileMask($mobileno, $mask = '*') {
        if (empty($mobileno))
            return false;
        return $returnData = str_pad(substr($mobileno, -4), strlen($mobileno), $mask, STR_PAD_LEFT);
    }

}
if (!function_exists("bankAccountNoMask")) {

    function bankAccountNoMask($acno, $mask = '*') {
        if (empty($acno))
            return false;
        return $returnData = str_pad(substr($acno, -2), strlen($acno), $mask, STR_PAD_LEFT);
    }

}

if (!function_exists("mask")) {

    function mask($no, $mask = '*') {
        if (is_null($no) || empty($no))
            return false;
        return $returnData = str_pad(substr($no, -2), strlen($no), $mask, STR_PAD_LEFT);
    }

}

if (!function_exists("createBreadCrumb")) {

    function createBreadCrumb($ciroute) {
        $ci = &get_instance();
//        echo $ci->router->fetch_method();
        $res = $ci->db->query("select * from menu where ciroutes = ? ", array($ciroute));
//        echo $ci->db->last_query();
        $result = $res->result();
//        pr($result);
        if (count($result) > 0)
            return $route = $result[0]->text;
        else {

//            $pth = explode("/", $_SERVER['REQUEST_URI']);
//            
//            $cnt = count($pth);
//            $res = $ci->db->query("select * from menu where ciroutes = ? ", array($pth[$cnt - 2]));
//        echo $ci->db->last_query();
//            $result = $res->result();
////        pr($result);
//            if (count($result) > 0)
//                return $route = $result[0]->text;
        }
    }

}
if (!function_exists("getUrlMethod")) {

    function getUrlMethod() {
        $ci = &get_instance();
        return $ci->router->method;
    }

}

if (!function_exists("checkUnitId")) {

    function checkUnitId() { 
        $ci = &get_instance();
        $unitId = decryptMyData($ci->session->userdata('unitId'));
//        $projectCode = "";
        $distId = "";
        $sql = "select distid from projectmaster where projectcode=?";
        $query = $ci->db->query($sql, array($unitId));
        //prd($query->num_rows);
        if ($query->num_rows > 0) {
            $res = $query->result();
            $distId = $res[0]->distid;
//            $projectCode = $unitId;
        } else {
            $distId = $unitId;
        }
        $arr = array('distId' => $distId, 'projectId' => $unitId);
        return $arr;
    }

}


if (!function_exists("userTrail")) {

    function userTrail($userid, $username, $ssoId = null) {
        $ci = &get_instance();
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $actArr = array();
        $actArr['lastacttime'] = date("Y-m-d H:i:s");
        $actArr['logintime'] = date("Y-m-d H:i:s");
        $actArr['userid'] = $userid;

        $actArr['username'] = $username;
        $actArr['ipaddr'] = $ci->db->escape($ip);
        $actArr['ssoid'] = $ssoId;
        $macAddr = exec("getmac");
        $actArr['macaddr'] = isset($macAddr) ? $ci->db->escape($macAddr) : "";
        $ci->load->helper('url');
        $ci->load->library('user_agent');
        $data['browser'] = $ci->agent->browser();
        $data['browserVersion'] = $ci->agent->version();
        $data['platform'] = $ci->agent->platform();
        $data['full_user_agent_string'] = $_SERVER['HTTP_USER_AGENT'];
        $actArr['browserinfo'] = json_encode($data);
        $actArr['status'] = 0;
        $actArr['loginbrowser'] = $data['browser'] . "  " . $data['browserVersion'];
        $ci->db->insert("usertrail", $actArr);

        if ($ci->db->affected_rows() > 0) {
            return $ci->db->insert_id();
        } else {
            return false;
        }
    }

    if (!function_exists("getUserTrail")) {

        function getUserTrail($noRec = 10) {
            $ci = & get_instance();
            $today = date("Y-m-d 00:00:00");
            $query = "select * from usertrail where logintime>='$today' order by id desc limit " . $noRec;
            $res = $ci->db->query($query);
//        echo $ci->db->last_query();die;
            $result = $res->result();
            return count($result) > 0 ? $result : false;
        }

    }
}
if (!function_exists("startTime")) {

    function startTime() {

        $ci = & get_instance();
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $ci->config->set_item("start", $time);
    }

}

if (!function_exists("pageLoadTime")) {

    function pageLoadTime() {
        $ci = & get_instance();
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $finish = $time;
        $total_time = round(($finish - $ci->config->item("start")), 4);
        $ci->config->set_item("tot_load_time", $total_time);
        return $total_time;
    }

}
if (!function_exists("ajaxStartTime")) {

    function ajaxStartTime() {
        $ci = & get_instance();
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $ci->config->set_item("ajax_start", $time);
    }

}

if (!function_exists("ajaxPageLoadTime")) {

    function ajaxPageLoadTime() {
        error_reporting(0);
        $ci = & get_instance();
        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $finish = $time;
        $ci->load->library('session');
        $total_time = round(($finish - $ci->config->item("ajax_start")), 4);
        $ci->session->set_userdata("ajaxtime", $total_time);
//        prd($ci->session->all_userdata());
        updateAjaxLoadTrail(decryptMyData($ci->session->userdata("userId")));
//        $ci->config->set_item("ajax_load_time", $total_time);
    }

}

if (!function_exists("updateUserVisitTrail")) {

    function updateUserVisitTrail($userid) {
        $ci = & get_instance();

        $dir = $ci->router->directory;
        $clas = $ci->router->class;
        $method = $ci->router->method;
        $data['pagecontroller'] = $dir . "/" . $clas . "/" . $method;
        $methodArr = explode("/", current_url());
        $urlMethod = end($methodArr);
        $trailData = getMenuData(array("ciroutes" => $urlMethod));
        if (count($trailData) > 0)
            $data['pagename'] = $trailData[0]->description;
        else
            $data['pagename'] = null;
        $data['pageurl'] = current_url();
        $data['userid'] = $userid;
        /* Added on 10may18 to store user trail id */
        $data['user_trail_id'] = $ci->session->userdata("login_id");
        
        $data['pageloadtime'] = $ci->config->item("tot_load_time");
        $data['created'] = date("Y-m-d H:i:s");

        if ($ci->db->insert("uservisittrail", $data)) {
            if ($ci->db->affected_rows() > 0) {
                $ci->session->set_userdata("time", time());
                $ci->session->set_userdata("lastVisitedPageId", @$ci->db->insert_id());
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}

if (!function_exists("updateAjaxLoadTrail")) {

    function updateAjaxLoadTrail($userid) {
        //Given below controller will not be stored as ajax logs because these are called in every second
//        display_error(0);
//        echo current_url();die;
        $ci = & get_instance();
        $escapeToInsControllerArr = array(
            "/user/logoutUserForcefullyAjx",
            "/user/updateUserActivityTime",
            "/user/getUserTrail",
        );
//        prd($ci->session->all_userdata());
        $pageController = $ci->router->directory . "/" . $ci->router->class . "/" . $ci->router->method;
        if (!in_array($pageController, $escapeToInsControllerArr)) {
            $data['pagecontroller'] = $pageController;

            if ($data['page'])
                $methodArr = explode("/", current_url());

            $urlMethod = end($methodArr);
            $trailData = getMenuData(array("ciroutes" => $urlMethod));

//            if (count($trailData) > 0)
//                $data['pagename'] = $trailData[0]->description;
//            else
//                $data['pagename'] = null;
            $data['pageurl'] = current_url();
            $data['userid'] = $userid;
            /* Added on 10may18 to store user trail id */
            $data['user_trail_id'] = $ci->session->userdata("login_id");
            
            $data['ajaxloadtime'] = $ci->session->userdata("ajaxtime");
            $data['created'] = date("Y-m-d H:i:s");
            $data['pageid'] = $ci->session->userdata("lastVisitedPageId");
            $ci->db->insert("ajaxtrail", $data);

//            echo $ci->db->last_query();
        }
    }

}

if (!function_exists("deleteLogs")) {

    function deleteLogs($deletedId, $type) {
        $ci = & get_instance();

        $data['deletedid'] = $deletedId;
        $data['deleteby'] = decryptMyData($ci->session->userdata("userid"));
        $data['type'] = $type;
        $data['datetime'] = date("Y-m-d H:i:s");
        return $ci->db->insert("deletedlogs", $data) ? true : false;
//        echo $ci->db->last_query();die;
    }

}
if (!function_exists("spanLabel")) {

    function spanLabel($content, array $attributes) {
        $retD = $attr = '';
        if (count($attributes) == 0) {
            $retD = "<span class='label label-info'>" . $content . "</span>";
        } else {
            foreach ($attributes as $k => $v)
                $attr .= "$k='" . "$v'";
            $retD = "<span $attr>" . $content . "</span>";
        }
//        echo "<pre>".$retD;die;
        return $retD;
    }

}
if (!function_exists("lastQuery")) {

    function lastQuery() {
        $ci = & get_instance();
        echo $ci->db->last_query();
    }

}

/* @Usage: return minority if category code length is 2
 * @param: categoryCode (sc/st etc)
 */
if (!function_exists("isCategoryMinority")) {

    function isCategoryMinority($categoryCode) {
        if (strlen($categoryCode) == 2) {
            return ' + Minority';
        } else {
            return '';
        }
    }

}

/*
 * @usage: Get Full Awc code by single awc code
 * @param1: pass pawcid
 */
if (!function_exists("getFullAwcCodeByPawcid")) {

    function getFullAwcCodeByPawcid($pawcid) {
        if (!is_numeric($pawcid)) {
            return false;
        }
        $CI = &get_instance();
        $query = "select t4.pdistcode as distcode,t2.projectcode,t3.sec_code,t1.awc_code"
                . " from awc_master as t1"
                . " left join projectmaster as t2 on t1.projectcode=t2.projectcode "
                . "left join secmaster t3 on t3.sectorid=t1.sectorid "
                . "left join distmaster as t4 on t4.distid=t2.distid where t1.pawcid='$pawcid'";
        $res = $CI->db->query($query);
        $data = $res->result();
        if (is_array($data) && count($data[0]) > 0) {
            return $retCode = "08"
//                    . "<span style='color:red'>" . $data[0]->distcode . "</span>"
                    . "<span style='color:green'>" . ($data[0]->projectcode) . "</span>"
                    . "<span style='color:red'>" . getFormattedSectorCode($data[0]->sec_code) . "</span>"
                    . "<span style='color:blue'>" . getFormattedSectorCode($data[0]->awc_code) . "</span>";
        } else {
            return false;
        }
//        prd($data);
    }

}

/* Getting all users data who logged in last 15 min. or less */
if (!function_exists("get_all_session_data")) {

    function get_all_session_data() {
        $CI = &get_instance();
        $qry = "select * from ci_sessions where last_activity <" . strtotime(date('Y-m-d H:i:s')) . " AND user_data!='' AND user_data like '%userId\";s:%'";

        $res = $CI->db->query($qry);
//            prd($res->result()[0]->user_data);
        $tot_active_user = $res->num_rows();

//        prd($CI->db->last_query());
        return $tot_active_user;
    }

}
/* * ************************ Show Tool tip************************** */
if (!function_exists("showTooltip")) {

    function showTooltip($text, $tooltip, $align = 'top') {
        $tooltipHref = '<a href="#" data-toggle="tooltip" data-placement="' . $align . '" title="' . $tooltip . '">' . $text . '</a>';

        return $tooltipHref;
    }

}
/* Adding pdf library method to abstract use of pdf library */
if (!function_exists("loadPdfLib")) {

    function loadPdfLib() {
        $CI = &get_instance();
        $CI->load->library('m_pdf');
    }

}
/* created by: Anshul Pareek
 * Modified By: Anshul Pareek
 * created on: 12,Jan-2018
 * Last modified: 12,Jan 2018
 * purpose: Change status of any table by using this function 
 * @param1: $tablename (string) name of the table
 * @param2: $updateColname : Colname which need to be updated like as col=>status  
 * @param3: $newStatus : New status like as 1 or zero which is going to be changed
 * @param4: $rowIds : this must contains array of ids which to be updated 
 */
if (!function_exists("changeStatus")) {

    function changeStatus($tablename, $updateColname, $newStatus, $condColName, array $rowIds) {
        if ($tablename == '' || $newStatus == '' || $rowIds == '' || $colname = '') {
            return false;
        }
        $CI = &get_instance();
        $sanitizeRowIds = array_map(array($CI->db, 'escape'), $rowIds);
        $ids = implode(',', $sanitizeRowIds);
        $qry = "update $tablename set $updateColname='$newStatus' where $condColName" . " in( " . $ids . ")";
        $flag = $CI->db->query($qry);
        if ($flag) {
            actionTrail("Status change action performed for $tablename", "successful!");
            return true;
        } else {
            actionTrail("Status change action performed for $tablename", "Unsuccessful!");
            return false;
        }
    }

}

if (!function_exists("getTableData")) {

    function getTableData($tablename = null, $filter = array()) {
        if ($tablename == null)
            return false;
        $ci = &get_instance();
        $table = $tablename;
        $cols = " * ";
        $cond = " WHERE 1=1 ";
        $query = "SELECT ";
        $orderby = "";
        $limit = "";
        $groupby = "";
        if (isset($filter)) {
            $fields = $ci->db->list_fields($tablename);
            foreach ($fields as $field) {
                if (isset($filter[$field]) && array_key_exists($field, $filter)) {
                    $cond .= " and $field='" . $filter[$field] . "' ";
                }
            }
            if ((array_key_exists("cols", $filter))) {
                if (is_array($filter['cols'])) {
                    $cols = rtrim(implode(",", $filter['cols']), ",");
                }
            }
            if (array_key_exists("orderasc", $filter) && is_array($filter['orderasc'])) {
                $orderby .= "ORDER BY " . rtrim(implode(",", $filter['orderasc']), ",") . " ASC ";
            }
            if (array_key_exists("orderdsc", $filter) && is_array($filter['orderdsc'])) {
                $orderby .= "ORDER BY " . rtrim(implode(",", $filter['orderdsc']), ",") . " DESC ";
            }
            if (array_key_exists("group_by", $filter) && is_array($filter['group_by'])) {
                $groupby .= "GROUP BY " . rtrim(implode(",", $filter['group_by']), ",");
            }
            if (array_key_exists("limit", $filter)) {
                $limit .= " LIMIT " . $filter['limit'];
            }
            $query = $query . $cols . " from $table $cond $groupby $orderby $limit";
            $res = $ci->db->query($query);
//            echo $ci->db->last_query();
//            die;
            if ($res->num_rows() > 0) {
                return $data = $res->result();
            } else {
                return false;
            }
        }
    }

}

function deleteData($tablename = null, $cond = null, $limit = '') {
    if ($tablename == null || $cond == null)
        return false;
    $ci = &get_instance();
    $finalCond = '';
    $qry = "Delete from $tablename where 1=1 and ";
    if (is_array($cond)) {
        foreach ($cond as $k => $v) {
            $finalCond .= $k . "=" . $ci->db->escape($v);
        }
    } else {
        $finalCond = $cond;
    }
    if ($limit != '') {
        $limit .= " LIMIT 1";
    }
    $qry .= $finalCond . $limit;

    if ($ci->db->query($qry)) {
        actionTrail("Record Deletion action performed in table: $tablename", "success");
        return true;
    } else {
        actionTrail("Record Deletion action performed in table: $tablename", "unsuccessful");
    }
}

if (!function_exists("getDataFromCurl")) {

    function getDataFromCurl($url) {
        // create a new cURL resource
        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        // grab URL and pass it to the browser
        $result = curl_exec($ch);
        //prd($result);
        // close cURL resource, and free up system resources
        curl_close($ch);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

}

if (!function_exists('array_column_per')) {

    function array_column_per(array $input, $columnKey, $indexKey = null) {
        $array = array();
        foreach ($input as $value) {
            if (!array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }

            if (is_null($indexKey) && $value->$columnKey !== '') {
                $array[] = $value->$columnKey;
            } else {
                if (!array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }

}
if (!function_exists('array_column')) {

    function array_column(array $array, $columnKey, $indexKey = null) {
        $result = array();
        foreach ($array as $subArray) {
            if (!is_array($subArray)) {
                continue;
            } elseif (is_null($indexKey) && array_key_exists($columnKey, $subArray)) {
                $result[] = $subArray[$columnKey];
            } elseif (array_key_exists($indexKey, $subArray)) {
                if (is_null($columnKey)) {
                    $result[$subArray[$indexKey]] = $subArray;
                } elseif (array_key_exists($columnKey, $subArray)) {
                    $result[$subArray[$indexKey]] = $subArray[$columnKey];
                }
            }
        }
        return $result;
    }

}

if (!function_exists("addUIDValidateScript")) {

    function UAIDValidateScript() {
        echo '<script src="' . JS_URL . 'verhoeff.js" type="text/javascript"></script>';
    }

}

if (!function_exists("getSessionUserDetail")) {

    function getSessionUserDetail() {
        $ci = &get_instance();
        return $ci->session->userdata('admin_userdata')['userdata'][0];
    }

}
