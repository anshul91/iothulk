<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * CREATED BY: ANSHUL PAREEK
 * CREATED DATE:
 * MODIFIED DATE:  
 */

class Device_model extends CI_Model {

   
    public $tbl_users = "tbl_users";
    public $tbl_api_key = "tbl_api_key";
    public $tbl_device = "tbl_device";
    public $tbl_device_reading = 'tbl_device_reading';

     /**
     * Validate the login's data with the database
     * @param array $filter
     
     * @return array
     */
    public function get_device_list($filter = array()) {
        $columns = array('device_id', 'title', 'sub_title', 'signal_type', 'device_type',
            'description', 'short_desc', 'min_val', 'max_val', 'sensor_name', 't1.created',
            't1.modified', 't1.user_id', 'purpose', 'max_request','device_code');
//        $remCols = array('userid', 'allowed_dist', "allowed_project", "role_id"); //columns to be removed from datatable
        $requestData = rq();
        $cols = implode(",", $columns);
        $cond = ' Where 1=1 ';
//        prd($filter);
        $fields = $this->db->list_fields($this->tbl_device);
        if (sizeof($filter) > 0 && is_array($filter)) {
            foreach ($fields as $field) {
                if (isset($filter[$field]) && array_key_exists($field, $filter)) {
                    $cond .=" and $field=" . $this->db->escape($filter[$field]);
                }
            }
        }
        $sql = "SELECT $cols ";
        $sql.=" from $this->tbl_device as t1 left join $this->tbl_users as t2 on t1.user_id=t2.user_id " .
                " $cond ";
        $query = $this->db->query($sql);
        $totalData = $query->num_rows();
        $totalFiltered = $totalData;
        if (!empty($requestData['search']['value'])) {
            $sql.=" AND ( title LIKE '" . $requestData['search']['value'] . "%' ";
            $sql.=" OR sub_title LIKE '" . $requestData['search']['value'] . "%' )";
        }
        $sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['length'] . " OFFSET " . $requestData['start'] . "   ";
        $resArr = $this->db->query($sql);
//        echo lastQuery();die;
        $cnt = $requestData['start'] ? $requestData['start'] + 1 : 1;
        $data = array();
        $resData = $resArr->result_array();
        if (count($resData) > 0) {
            foreach ($resData as $rk => $row) {  // preparing an array
                $nestedData = array();
                $nestedData[] = $cnt++;
                $device_code = '<i class=""></i><input type="text" readonly="true" class="btn btn-primary btn-sm" data-toggle="tooltip" title="" onclick="copy_clipboard(this.id)" id="device_code" value="'.$row['device_code'].'" data-original-title="Click to Copy!">';

                $nestedData[] = isset($row['title']) && !empty($row['title']) ? $row['title']: "N/A";
                $nestedData[] = isset($row['sub_title'])?$row['sub_title']:"";
                
                $nestedData[] = isset($row['device_code'])?$device_code:"";


                $nestedData[] = isset($row['signal_type']) && $row['signal_type']==1 ?'<span class="btn btn-info btn-sm"><i class="fa fa-signal"></i> Digital</span>':'<span class="btn btn-info btn-sm"><i class="fa fa-signal"></i> Analog</span>' ;

                $nestedData[] = isset($row['device_type']) && $row['device_type']==1 ?'<span class="btn btn-danger btn-sm"><i class="fa fa-thermometer"></i> SensorReading</span>':'<span class="btn btn-danger btn-sm"><i class="fa fa-crosshairs"></i> GPS</span>' ;

                $nestedData[] = isset($row['sensor_name']) ? $row['sensor_name'] : "";

                $nestedData[] = isset($row['min_val']) ? $row['min_val'] : "";
                $nestedData[] = isset($row['max_val']) ? $row['max_val'] : "";
                $nestedData[] = isset($row['purpose']) ? $row['purpose'] : "";
                $nestedData[] = isset($row['max_request']) ? $row['max_request'] : "";
                $nestedData[] = isset($row['created']) ? date("d-M-Y H:i:s",strtotime($row['created'])) : "";
                $device_id = encryptMyData($row['device_id']);
               
                $update_btn = "<button class='btn btn-success btn-xs' id='btn_update' name='btn_update' onclick=open_update_device_popup('".$device_id."')  data-toggle='modal' data-target='#myModal'>".$this->lang->line('icon_pencil').'</button>';
                
                $del_btn = " <button onclick=delete_device('".$device_id."') name='btn_delete' class='btn btn-danger btn-xs'>".$this->lang->line('icon_trash').'</button>';
                $nestedData[] = $update_btn.$del_btn;
                
                $data[] = $nestedData;
            }
        }
        $data = array(
            "draw" => intval($requestData['draw']) > 0 ? $requestData['draw'] : 0, // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        array_walk_recursive($data, array($this->security, 'xss_clean'));
        return $data;
    }

public function add_device_detail(array $data) {
        $data_to_store = $this->security->xss_clean($data);
        $ret = $this->db->insert($this->tbl_device, $data_to_store);
//        echo $this->db->last_query();
//        die;
        if ($ret) {
            //actionTrail("User Add action performed", User_lang::ACTION_TRAIL_SUCCESS);
            return true;
        } else {
            //actionTrail("User Add Action Performed", User_lang::ACTION_TRAIL_ERROR);
            return false;
        }
    }
public function update_device_detail($device_id,array $data) {
        $data_to_store = $this->security->xss_clean($data);
        
        $ret = $this->db->update($this->tbl_device, $data_to_store,array("device_id"=>$device_id));
//        echo $this->db->last_query();
//        die;
        if ($ret) {
            //actionTrail("User Add action performed", User_lang::ACTION_TRAIL_SUCCESS);
            return true;
        } else {
            //actionTrail("User Add Action Performed", User_lang::ACTION_TRAIL_ERROR);
            return false;
        }
    }

    public function delete_device($device_id){
         return $this->db->delete($this->tbl_device, array("device_id" => $device_id)) ? true:false;
    }
      /*
        =====================================================================================
                            CREATING REST API FUNCTIONS FOR DEVICES MODULE
        ===================================================================================== 
    */

    public function store_device_reading( array $data){
        
        $data_to_store = $this->security->xss_clean($data);
        $ret = $this->db->insert($this->tbl_device_reading,$data_to_store);
        return $ret ? true : false;
    
    }
    public function store_gps_device_reading( array $data){
        $data_to_store = $this->security->xss_clean($data);
        $ret = $this->db->insert($this->tbl_device_reading,$data_to_store);
        return $ret ? true : false;
    }
}
