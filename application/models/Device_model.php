<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * CREATED BY: ANSHUL PAREEK
 * CREATED DATE:
 * MODIFIED DATE:  
 */

class Device_model extends CI_Model {

    /**
     * Validate the login's data with the database
     * @param string $user_name
     * @param string $password
     * @return void
     */
    public $tbl_users = "tbl_users";
    public $tbl_api_key = "tbl_api_key";
    public $tbl_api_key = "tbl_device";
    public function signup($data) {
        $data_to_store = $this->security->xss_clean($data);
        $ret = $this->db->insert($this->tbl_users, $data_to_store);
        if ($ret){
             $insert_id = $this->db->insert_id();
             $api_data = array("user_id"=>$insert_id,"api_key"=>time().rand(),"updated"=>date("Ymd"));
             if($this->db->insert($this->tbl_api_key,$api_data))
                return true;
            else
                return false;
        }
        else
            return false;
    }


    public function deviceListDt($filter = array()) {
        $columns = array(`device_id`, `title`, `sub_title`, `signal_type`, `device_type`, `description`, `short_desc`, `min_val`, `max_val`, `sensor_name`, `created`, `modified`, `created_by`, `purpose`, `max_request`);
//        $remCols = array('userid', 'allowed_dist', "allowed_project", "role_id"); //columns to be removed from datatable
        $requestData = rq();
        $cols = implode(",", $columns);
        $cond = ' Where 1=1 ';
//        prd($filter);
        $fields = $this->db->list_fields($this->tbl_users);
        if (sizeof($filter) > 0 && is_array($filter)) {
            foreach ($fields as $field) {
                if (isset($filter[$field]) && array_key_exists($field, $filter)) {
                    $cond .=" and $field=" . $this->db->escape($filter[$field]);
                }
            }
        }
        $sql = "SELECT $cols ";
        $sql.=" from $this->tbl_users " .
                " $cond ";
        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        if (!empty($requestData['search']['value'])) {
            $sql.=" AND ( username ILIKE '" . $requestData['search']['value'] . "%' ";
            $sql.=" OR cast(usercontactno as text) ILIKE '" . $requestData['search']['value'] . "%' )";
        }
        $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['length'] . " OFFSET " . $requestData['start'] . "   ";
        $resArr = $this->db->query($sql);

        $cnt = $requestData['start'] ? $requestData['start'] + 1 : 1;
        $data = array();
        foreach ($resArr->result_array()as $rk => $row) {  // preparing an array
            $nestedData = array();
            $nestedData[] = $cnt++;

            foreach ($row as $k => $v) {
                if (in_array(trim($k), $columns) && !in_array($k, $remCols)) {
                    if (isset($v) && !empty($v)) {
                        $nestedData[] = $v;
                    } else {
                        $nestedData[] = "<span style='color:red;'>Not Available.</span>";
                    }
                }
            }
            $nestedData[] = isset($row['allowed_dist']) && !empty($row['allowed_dist']) && is_numeric($row['allowed_dist']) ? getDistrict(array("distid" => $row['allowed_dist']))[0]->distnamee : "<span style='color:green;'>" . strtoupper($row['allowed_dist']) . "</span>";
            $nestedData[] = isset($row['allowed_project']) && !empty($row['allowed_project']) && is_numeric($row['allowed_project']) ? getProject(array("projectcode" => $row['allowed_project']))[0]->projectname : "<span style='color:green'>" . strtoupper($row['allowed_project']) . "</span>";
            $nestedData[] = isset($row['role_id']) ? getTableData($this->tbl_roles, array("group_id" => $row['role_id']))[0]->name : '<span style="color:red">Not Available</span>';
//            prd($row['group_id']);
            $encUserId = encryptMyData($row['userid']);
            $delFxn = "deleteUser('" . $encUserId . "','" . $this->security->get_csrf_token_name() .
                    "','" . $this->security->get_csrf_hash() . "')";
            $nestedData[] = " <button class='btn btn-success btn-xs' id='showUpdatePopup' name='showUpdatePopup'"
                    . " onclick=showUpdatePopup('" . $encUserId . "','" . $this->security->get_csrf_token_name() .
                    "','" . $this->security->get_csrf_hash() . "')>" . User_lang::BUTTON_UPDATE . "</button> "
                    . "<button class='btn btn-danger btn-xs' onclick=$delFxn>" . User_lang::BUTTON_DELETE . "</button> ";

            $data[] = $nestedData;
        }

        $data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        array_walk_recursive($data, array($this->security, 'xss_clean'));
        return $data;
    }

    public function addUser(array $data) {
        $data_to_store = $this->security->xss_clean($data);
        $ret = $this->db->insert($this->tbl_users, $data_to_store);
//        echo $this->db->last_query();
//        die;
        if ($ret) {
            actionTrail("User Add action performed", User_lang::ACTION_TRAIL_SUCCESS);
            return true;
        } else {
            actionTrail("User Add Action Performed", User_lang::ACTION_TRAIL_ERROR);
            return false;
        }
    }

    public function updateUser($userid, array $data) {
        $data_to_store = $this->security->xss_clean($data);
        $ret = $this->db->update($this->tbl_users, $data_to_store, array("userid" => $userid));
        //echo $this->db->last_query();
        //die;
        if ($ret) {
            actionTrail("User Update action performed", User_lang::ACTION_TRAIL_SUCCESS);
            return true;
        } else {
            actionTrail("User Update Action Performed", User_lang::ACTION_TRAIL_ERROR);
            return false;
        }
    }

    public function generateUserId() {
        $res = $this->db->query("select max(userid) as userid from $this->tbl_users");
        if ($res->num_rows() > 0) {
            $result = $res->result();
            return $result[0]->userid + 1;
        } else {
            return 1;
        }
    }

    public function addUserPermissions($userid, array $data) {

        if ($this->deleteDuplicatePermission($userid)) {
            $this->db->insert_batch($this->tbl_user_role_permission, $data);
//            prd($this->db->last_query());
            if ($this->db->affected_rows() < 1) {
                actionTrail("Add User Permission details Insert operation performed", "Unsuccessful!");
                return false;
            } else {
                actionTrail("Add User Permission details Insert Operation Performed", "Successful!");
                return true;
            }
        } else {
            return false;
        }
    }

    public function deleteDuplicatePermission($userid) {
        $flag = true;
        $this->db->select("*");
        $count = $this->db->where(array("userid" => $userid))->get($this->tbl_user_role_permission)->num_rows();
        if ($count > 0)
            $flag = $this->db->delete($this->tbl_user_role_permission, array("userid" => $userid));
//         echo $this->db->last_query();        
        return $flag;
    }

    public function checkDuplicateUser($userid, $username) {
        $query = "Select count(*) from $this->tbl_roles where userid!=? and username=?";
        if ($res = $this->db->query($query, array($userid, $username))) {
            $data = $res->result();
            return ($data[0]->count > 0) ? false : true;
        }
    }

    public function getPermissions($userId) {
        $query = "select t1.permission_user_id, t1.group_id, t1.page_id, t1.read, t1.creat, t1.update, "
                . "t1.delete,t2.ciroutes,t2.text from $this->tbl_user_role_permission as t1 left join "
                . "$this->tbl_menu as t2 on t1.page_id = t2.menuid where t1.userid=?";
        $res = $this->db->query($query, array($userId));
//        echo $this->db->last_query();die;   
        return $res->num_rows() > 0 ? $res->result_array() : false;
    }

    public function getAllVacantPosts($filter = array()) {
        $cond = "";
        if (isset($filter['distId']) && $filter['distId'] != "") {
            $cond .= " and d.distid='" . $filter['distId'] . "' ";
        }

        $sql = "select "
                . "(select COUNT(*)  from AWC_Master ab inner join SecMaster sb on sb.SectorID=ab.SectorID inner join ProjectMaster pb on pb.ProjectCode=sb.ProjectCode where ab.DelFlag is null and sb.DelFlag is NULL and ab.SectorID is not null and d.distid=pb.distid and ab.PAWCID not in (select AWCID from Employee_Master ec inner join AWC_Master ac on ac.PAWCID=ec.AWCID inner  join SecMaster sc on sc.SectorID=ac.SectorID inner join ProjectMaster pc on pc.ProjectCode=sc.ProjectCode where ec.DesignationId=1 and pc.distid=d.distid and ec.DelFlag is null and ac.DelFlag is null and sc.DelFlag is NULL)) as AWWVacant, "
                . " (select COUNT(*) from AWC_Master af inner join SecMaster sf on sf.SectorID=af.SectorID inner join ProjectMaster pf on pf.ProjectCode=sf.ProjectCode where af.DelFlag is null and sf.DelFlag is NULL and af.SectorID is not null and pf.distid=d.distid and af.PAWCID not in (select AWCID from Employee_Master eg inner join AWC_Master ag on ag.PAWCID=eg.AWCID inner  join SecMaster sg on sg.SectorID=ag.SectorID inner join ProjectMaster pg on pg.ProjectCode=sg.ProjectCode where eg.DesignationId=2 and pg.distid=d.distid and eg.DelFlag is null and ag.DelFlag is null and sg.DelFlag is NULL)) as AWHVacant,"
                . " (select COUNT(*) from AWC_Master aj inner join SecMaster sj on sj.SectorID=aj.SectorID inner join ProjectMaster pj on pj.ProjectCode=sj.ProjectCode where aj.DelFlag is null and sj.DelFlag is NULL and aj.SectorID is not null and pj.distid=d.distid and aj.PAWCID not in (select AWCID from Employee_Master ek inner join AWC_Master ak on ak.PAWCID=ek.AWCID inner  join SecMaster sk on sk.SectorID=ak.SectorID inner join ProjectMaster pk on pk.ProjectCode=sk.ProjectCode where ek.DesignationId=3 and pk.distid=d.distid and ek.DelFlag is null and ak.DelFlag is null and sk.DelFlag is NULL)) as AshaVacant,"
                . " (select COUNT(*) from AWC_Master an inner join SecMaster sn on sn.SectorID=an.SectorID inner join ProjectMaster pn on pn.ProjectCode=sn.ProjectCode where an.DelFlag is null and sn.DelFlag is NULL and an.SectorID is not null and pn.distid=d.distid and an.PAWCID not in (select AWCID from Employee_Master eo inner join AWC_Master ao on ao.PAWCID=eo.AWCID  inner  join SecMaster so on so.SectorID=ao.SectorID inner join ProjectMaster po on po.ProjectCode=so.ProjectCode where eo.DesignationId=4 and eo.DelFlag is null and po.distid=d.distid and ao.DelFlag is null and so.DelFlag is NULL)) as MiniAWWVacant "
                . "  from distmaster d where 1=1 ";
        $totRes = $this->db->query($sql . $cond);
//         echo $this->db->last_query();die;
        return $res = $totRes->num_rows() > 0 ? $totRes->result() : false;
    }

    public function getVacantPostsProjectWise($filter = array()) {
        $cond = "";
        if (isset($filter['distId']) && $filter['distId'] != "") {
            $cond .= " and p.distid='" . $filter['distId'] . "' ";
        }
        if (isset($filter['projectcode']) && $filter['projectcode'] != "") {
            $cond .= " and p.projectcode='" . $filter['projectcode'] . "' ";
        }

        $sql = "select p.projectname,p.projectcode,"
                . "(select COUNT(*) from AWC_Master aa left outer join ProjectMaster pa on pa.ProjectCode=aa.ProjectCode where aa.sectorid is not null and aa.DelFlag is NULL and pa.projectcode=p.projectcode) as mappedawc,"
                . "(select COUNT(*)  from AWC_Master ab inner join SecMaster sb on sb.SectorID=ab.SectorID inner join ProjectMaster pb on pb.ProjectCode=sb.ProjectCode where ab.DelFlag is null and sb.DelFlag is NULL and ab.SectorID is not null and p.projectcode=pb.projectcode and ab.PAWCID not in (select AWCID from Employee_Master ec inner join AWC_Master ac on ac.PAWCID=ec.AWCID inner  join SecMaster sc on sc.SectorID=ac.SectorID inner join ProjectMaster pc on pc.ProjectCode=sc.ProjectCode where ec.DesignationId=1 and pc.projectcode=p.projectcode and ec.DelFlag is null and ac.DelFlag is null and sc.DelFlag is NULL)) as AWWVacant,"
                . "(select COUNT(*) from AWC_Master af inner join SecMaster sf on sf.SectorID=af.SectorID inner join ProjectMaster pf on pf.ProjectCode=sf.ProjectCode where af.DelFlag is null and sf.DelFlag is NULL and af.SectorID is not null and pf.projectcode=p.projectcode and af.PAWCID not in (select AWCID from Employee_Master eg inner join AWC_Master ag on ag.PAWCID=eg.AWCID  inner  join SecMaster sg on sg.SectorID=ag.SectorID inner join ProjectMaster pg on pg.ProjectCode=sg.ProjectCode where eg.DesignationId=2 and pg.projectcode=p.projectcode and eg.DelFlag is null and ag.DelFlag is null and sg.DelFlag is NULL)) as AWHVacant,"
                . " (select COUNT(*) from AWC_Master aj inner join SecMaster sj on sj.SectorID=aj.SectorID inner join ProjectMaster pj on pj.ProjectCode=sj.ProjectCode where aj.DelFlag is null and sj.DelFlag is NULL and aj.SectorID is not null and pj.projectcode=p.projectcode and aj.PAWCID not in (select AWCID from Employee_Master ek inner join AWC_Master ak on ak.PAWCID=ek.AWCID  inner  join SecMaster sk on sk.SectorID=ak.SectorID inner join ProjectMaster pk on pk.ProjectCode=sk.ProjectCode where ek.DesignationId=3 and pk.projectcode=p.projectcode and ek.DelFlag is null and ak.DelFlag is null and sk.DelFlag is NULL)) as AshaVacant,"
                . " (select COUNT(*) from AWC_Master an inner join SecMaster sn on sn.SectorID=an.SectorID inner join ProjectMaster pn on pn.ProjectCode=sn.ProjectCode where an.DelFlag is null and sn.DelFlag is NULL and an.SectorID is not null and pn.projectcode=p.projectcode and an.PAWCID not in (select AWCID from Employee_Master eo inner join AWC_Master ao on ao.PAWCID=eo.AWCID  inner  join SecMaster so on so.SectorID=ao.SectorID inner join ProjectMaster po on po.ProjectCode=so.ProjectCode where eo.DesignationId=4 and eo.DelFlag is null and po.projectcode=p.projectcode and ao.DelFlag is null and so.DelFlag is NULL)) as MiniAWWVacant"
                . "  from projectmaster p where 1=1   ";

        $totRes = $this->db->query($sql . $cond);
//        echo $this->db->last_query();die;
        return $res = $totRes->num_rows() > 0 ? $totRes->result() : false;
    }

}
