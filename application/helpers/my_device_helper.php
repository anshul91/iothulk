<?php

/*
 * CREATED BY: ANSHUL PAREEK
 * CREATED DATE:
 * LAST MODIFIED BY: ANUPAM PAGARIA
 * MODIFIED DATE:12,Jan-2018
 */

if (!function_exists("getTotUserDevice")) {

    function getTotUserDevice($userid) {
    	
    	$data = getTableData('tbl_device',array('user_id'=>$userid,'cols'=>array('device_id')));
    	
    	return is_array($data) && count($data)>0 ? count($data) : 0;
	}

}