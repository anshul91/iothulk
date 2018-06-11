<?php

/*
 * CREATED BY: ANSHUL PAREEK
 * CREATED DATE:
 * MODIFIED DATE:  
 */
if (!function_exists("can_access")) {

    function can_access($url = null) {

        $ci = &get_instance();
        $permissionArr = $ci->session->userdata('permissions');
        if ($url == null) {
            $url_controller_name = $ci->uri->segment(SEGMENT_CONTROLLER);
        } else {
            $url_controller_name = $url;
        }


        if ($url_controller_name !== 'home') {
            if (is_array($permissionArr) && count($permissionArr) > 0) {
                $route_arr = array_column($permissionArr, "ciroutes");

                $col = array_search($url_controller_name, array_column($permissionArr, "ciroutes"));

                if ($col !== FALSE) {

//		echo $col."---HERE";
                    if ($permissionArr[$col]['read'] == 1) {
                        return true;
                    } else {
                        return false;
                    }
                } else if ($col === FALSE) {

//		echo "here--".$url_controller_name;die;
                    return false;
                }
            }
        }
        return true;
    }

}
if (!function_exists("can_create")) {

    function can_create($url = null) {

        $ci = &get_instance();
        $permissionArr = $ci->session->userdata('permissions');
        if ($url == null)
            $url_controller_name = $ci->uri->segment(SEGMENT_CONTROLLER);
        else {
            $url_controller_name = $url;
        }

        if (strtolower($url_controller_name) !== 'home') {
            $col = array_search($url_controller_name, array_column($permissionArr, "ciroutes"));

            if ($col !== FALSE) {
                return $permissionArr[$col]['creat'] == 1 ? true : false;
            } else {

                exit;
//                return false;
            }
        }
    }

}
if (!function_exists("can_update")) {

    function can_update($url = null) {

        $ci = &get_instance();
        $permissionArr = $ci->session->userdata('permissions');
        if ($url == null)
            $url_controller_name = $ci->uri->segment(SEGMENT_CONTROLLER);
        else {
            $url_controller_name = $url;
        }

        if (strtolower($url_controller_name) !== 'home') {

            $col = array_search($url_controller_name, array_column($permissionArr, "ciroutes"));
            if ($col !== FALSE) {
                return $permissionArr[$col]['update'] == 1 ? true : false;
            } else {

                return false;
            }
        }
        return true;
    }

}
if (!function_exists("can_delete")) {

    function can_delete($url = null) {

        $ci = &get_instance();
        $permissionArr = $ci->session->userdata('permissions');
        if ($url == null)
            $url_controller_name = $ci->uri->segment(SEGMENT_CONTROLLER);
        else {
            $url_controller_name = $url;
        }
        if (strtolower($url_controller_name) !== 'home') {

            $col = array_search($url_controller_name, array_column($permissionArr, "ciroutes"));
            if ($col !== FALSE) {

                return $permissionArr[$col]['delete'] == 1 ? true :
                        false;
            } else {

                return false;
            }
        }
        return true;
    }

}



if (!function_exists("canAccess")) {

    function canAccess(array $accessArr) {
        $ci = &get_instance();
        $role = decryptMyData($ci->session->userdata('role'));
        if ($role == '' || !isset($role)) {
            exit(setFlashMessage(Common::NO_ACCESS, Common::ERROR) . redirect("home"));
        }
        return !in_array($role, $accessArr) ? exit(setFlashMessage(Common::NO_ACCESS, Common::ERROR) . redirect("home")) : true;
    }

}

if (!function_exists("canAdd")) {

    function canAdd(array $accessArr) {
        $ci = &get_instance();
        $role = decryptMyData($ci->session->userdata('role'));
        return !in_array($role, $accessArr) ? false : true;
    }

}
if (!function_exists("canUpdate")) {

    function canUpdate(array $accessArr) {
        $ci = &get_instance();
        $role = decryptMyData($ci->session->userdata('role'));

        return !in_array($role, $accessArr) ? false : true;
    }

}
if (!function_exists("canDelete")) {

    function canDelete(array $accessArr = array()) {
        $ci = &get_instance();
        $role = decryptMyData($ci->session->userdata('role'));
        return !in_array($role, $accessArr) ? false : true;
    }

}