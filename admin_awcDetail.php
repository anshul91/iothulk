<?php

if (!defined('BASEPATH') || !isset($_SERVER['HTTP_REFERER']))
    exit('No direct script access allowed');
/*
 * Created by : Anshul pareek
 * Last modified by: Anshul pareek
 * Last modified date: 06-jul-17 
 */

class Admin_awcDetail extends CI_Controller {
    /*
     * Responsable for auto load the model
     * @return void
     */

    public $role, $unitid;
    public $tbl_awc_master = "awc_master";
    public $rptAccessArr = array("0", "1", "3", "5", "6", "7", "8");

    public function __construct() {
        parent::__construct();
        $this->load->model('awcDetail_model');
        $this->load->model('empDetail_model');
        $this->lang->load("awcDetail", $this->session->userdata("site_lang"));
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }

        $this->role = decryptMyData($this->session->userdata('role'));
        $this->unitid = decryptMyData($this->session->userdata('unitId'));
    }

    public function getDistrictRolewise() {
        if ($this->role == 0 || $this->role == 1) {
            return getDistrict(array("orderasc" => array("distnamee"), "cols" => array("distid", "distnamee")));
        } else if ($this->role == 3 || $this->role == 5) {
            if ($this->role == 3) {
                return getDistrict(array("distid" => $this->unitid, "orderasc" => array("distnamee"), "cols" => array("distid", "distnamee")));
            } else {
                return $this->empDetail_model->getDistrictMasterByProjectCode($this->unitid);
            }
        }
    }

    public function getRptDistrictRolewise() {
        if ($this->role == 0 || $this->role == 1 || $this->role == 6) {
            return getDistrict(array("orderasc" => array("distnamee"), "cols" => array("distid", "distnamee")));
        } else if ($this->role == 3 || $this->role == 5 || $this->role == 7) {
            if ($this->role == 3 || $this->role == 7) {
                return getDistrict(array("distid" => $this->unitid, "orderasc" => array("distnamee"), "cols" => array("distid", "distnamee")));
            } else {
                return $this->empDetail_model->getDistrictMasterByProjectCode($this->unitid);
            }
        }
    }

    public function index() {
        $role = $this->role;
        if (canAccess(array("0", "1", "3", "5"))) {
            $data['districtList'] = $this->getDistrictRolewise();
            $data['role'] = $role;
            $data['main_content'] = 'admin/awcDetail/awcDetail_view';
            $this->load->view('includes/template', $data);
        }
    }

    public function viewCrecheDetail() {
        $role = $this->role;
        if (canAccess(array("0", "1", "3", "5"))) {
            $data['districtList'] = $this->getDistrictRolewise();
            $data['role'] = $role;
            $data['main_content'] = 'admin/awcDetail/awcCreche_view';
            $this->load->view('includes/template', $data);
        }
    }

    public function getProjectByDistId() {
        $distid = rqs("distid");
        $options = "";
        $selected = "";
        $projectData = array();
        
        if ($this->input->post("selectedval", true))
            $selected = decryptMyData($this->input->post("selectedval", true));
        if (rq('all')) {
            $options .="<option value='" . encryptMyData("all") . "'>--All--</option>";
        }
        if ($distid === ''|| $distid==0)
            exit($options);
        if ($this->role == '0' || $this->role == '1' || $this->role == '3') {

            if ($distid != 'all') {
                $projectData = getProject(array("distid" => $distid, 'cols' => array("projectname", "projectcode"), "orderasc" => array("projectname")));
            }
        } else {
            $projectData = getProject(array("projectcode" => $this->unitid, 'cols' => array("projectname", "projectcode"), "orderasc" => array("projectname")));
        }
        if (count($projectData) > 0)
            foreach ($projectData as $val) {
                if (isset($selected) && $selected !== '' && $selected === $val->projectcode)
                    $options .= "<option value='" . encryptMyData($val->projectcode) . "' selected>" . $val->projectname . " (" . $val->projectcode . ")" . "</option>";
                else
                    $options .= "<option value='" . encryptMyData($val->projectcode) . "'>" . $val->projectname . " (" . $val->projectcode . ")" . "</option>";
            }
        echo ($options);
        exit;
    }

    public function getRptProjectByDistId() {
        $distid = decryptMyData($this->input->post("distid", true));
        $options = "";
        $selected = "";
        if ($this->input->post("selectedval", true))
            $selected = decryptMyData($this->input->post("selectedval", true));

        if ($distid === '')
            return false;
        if ($this->role == '0' || $this->role == '1' || $this->role == '3' || $this->role == '6' || $this->role == '7') {
            if ($this->input->post("all")) {
                $options.="<option value='" . encryptMyData('all') . "'>--All--</option>";
            }
            $projectData = getProject(array("distid" => $distid, 'cols' => array("projectname", "projectcode"), "orderasc" => array("projectname")));
        } else {
            $projectData = getProject(array("projectcode" => $this->unitid, 'cols' => array("projectname", "projectcode"), "orderasc" => array("projectname")));
        }

//        $options = '<option value="">--select Project--</option>';
        foreach ($projectData as $val) {
            if (isset($selected) && $selected !== '' && $selected === $val->projectcode)
                $options .= "<option value='" . encryptMyData($val->projectcode) . "' selected>" . $val->projectname . "</option>";
            else
                $options .= "<option value='" . encryptMyData($val->projectcode) . "'>" . $val->projectname . "</option>";
        }
        echo ($options);
        exit;
    }

    public function getSectorList() {
        $projectcode = $this->input->post("projectcode", true);
        $selected = '';
        $options = "";
        if ($projectcode === '')
            return false;
        if ($this->input->post("selectedval", true))
            $selected = decryptMyData($this->input->post("selectedval", true));

        if (rqs('projectcode') == 'all') {

            $options.="<option value='" . encryptMyData('all') . "'>--All--</option>";
            echo ($options);
            exit;
        } else if ($this->input->post("all")) {
            $options.="<option value='" . encryptMyData('all') . "'>--All--</option>";
        }
        $decprojectcode = decryptMyData($projectcode);
        $sectorData = getSector(array("projectcode" => $decprojectcode, "delflag" => Null, 'cols' => array("sectorid", "secnamee", "sec_code"), "orderasc" => array("secnamee")));

//        $options = '<option value="">--Select Sector--</option>';

        foreach ($sectorData as $val) {
            if (isset($selected) && $selected !== '' && $selected == $val->sectorid)
                $options .= "<option value='" . encryptMyData($val->sectorid) . "' selected>" . $val->secnamee . "</option>";
            else
                $options .= "<option value='" . encryptMyData($val->sectorid) . "'>" . $val->secnamee . " (" . getFormattedSectorCode($val->sec_code) . ")" . "</option>";
        }
        echo ($options);
        exit;
    }

    public function getRevenueVillageByProjectId() {
        $decprojectcode = decryptMyData($this->input->post("projectcode"));
        $selected = '';
        if ($decprojectcode === '')
            return false;
        if ($this->input->post("selectedval", true))
            $selected = decryptMyData($this->input->post("selectedval", true));

        $revvillagedata = $this->awcDetail_model->getRevenueVillageData(array("projectcode" => $decprojectcode, "delflag" => null,
            'cols' => array("revvillageid", "revvillname")));
//        prd($revvillagedata);
        $options = '<option value="">--Select Revenue Village--</option>';
        foreach ($revvillagedata as $val) {

            if ($selected === $val->revvillageid)
                $options .= "<option value='" . encryptMyData($val->revvillageid) . "' selected>" . $val->revvillname . "</option>";
            else
                $options .= "<option value='" . encryptMyData($val->revvillageid) . "'>" . $val->revvillname . "</option>";
        }
        echo ($options);
        exit;
    }

    public function getGramPanchayatByProjectId() {
        $decprojectcode = decryptMyData($this->input->post("projectcode"));
        if ($decprojectcode === '')
            return false;
        $selected = '';
        if ($this->input->post("selectedval", true))
            $selected = decryptMyData($this->input->post("selectedval", true));
        $panchvillagedata = $this->awcDetail_model->getVillagePanchayatData(array("projectcode" => $decprojectcode, "delflag" => null,
            'cols' => array("villpanchid", "panchname")));

        $options = '<option value="">-- Select Village Panchayat --</option>';
        foreach ($panchvillagedata as $val) {
            if ($selected === $val->villpanchid)
                $options .= "<option selected value='" . encryptMyData($val->villpanchid) . "'>" . $val->panchname . "</option>";
            else
                $options .= "<option value='" . encryptMyData($val->villpanchid) . "'>" . $val->panchname . "</option>";
        }
        echo ($options);
        exit;
    }

    public function getPanchayatSamitiByProjectId() {
        $decCDPO_id = decryptMyData($this->input->post("cdpo_id"));
        if ($decCDPO_id === '')
            return false;
        $selected = '';
        if ($this->input->post("selectedval", true))
            $selected = decryptMyData($this->input->post("selectedval", true));
        $panchvillagedata = $this->awcDetail_model->getPanchayatSamitiData(array("cdpo_id" => $decCDPO_id, "delflag" => null,
            'cols' => array("panchsamitiid", "panchsamname")));

        $options = '<option value="">-- Select Panchayat Samiti --</option>';
        foreach ($panchvillagedata as $val) {
            if ($selected === $val->panchsamitiid)
                $options .= "<option value='" . encryptMyData($val->panchsamitiid) . "' selected>" . $val->panchsamname . "</option>";
            else
                $options .= "<option value='" . encryptMyData($val->panchsamitiid) . "' >" . $val->panchsamname . "</option>";
        }
        echo ($options);
        exit;
    }

    public function getUrbanLocalBodyByProjectId() {
        $decprojectcode = decryptMyData($this->input->post("projectcode"));
        if ($decprojectcode === '')
            return false;
        $selected = '';
        if ($this->input->post("selectedval", true))
            $selected = decryptMyData($this->input->post("selectedval", true));

        $urbanlocaldata = $this->awcDetail_model->getLocalBodyMasterData(array("projectcode" => $decprojectcode, "delflag" => null,
            'cols' => array("localbodyid", "localbodyname")));

        $options = '<option value="">-- Select Local Body --</option>';
        foreach ($urbanlocaldata as $val) {
            if ($selected === $val->localbodyid)
                $options .= "<option value='" . encryptMyData($val->localbodyid) . "' selected>" . $val->localbodyname . "</option>";
            else
                $options .= "<option value='" . encryptMyData($val->localbodyid) . "' >" . $val->localbodyname . "</option>";
        }
        echo ($options);
        exit;
    }

    public function getWardMasterByLocalBodyId() {

        $declocalbodyid = decryptMyData($this->input->post("localbodyid"));
        $selected = '';
        if ($this->input->post('selectedval'))
            $selected = decryptMyData($this->input->post('selectedval'));
        if ($declocalbodyid === '')
            return false;

        $urbanlocaldata = $this->awcDetail_model->getWardMasterData(array("localbodyid" => $declocalbodyid, "delflag" => null,
            'cols' => array("wardid", "wardname")));
        $options = '';
        $options = '<option value="">-- Select Ward --</option>';

        foreach ($urbanlocaldata as $val) {

            if (isset($selected) && $selected == $val->wardid) {
                $options .= "<option value='" . encryptMyData($val->wardid) . "' selected>" . $val->wardname . "</option>";
            } else
                $options .= "<option value='" . encryptMyData($val->wardid) . "'>" . $val->wardname . "</option>";
        }
        echo ($options);
        exit;
    }

    public function awcDetailDt() {

        if (rqs('sectorid') != '') {
            $sectorid = decryptMyData($this->input->post('sectorid', true));
            $output = $this->awcDetail_model->get_awcList(array("sectorid" => $sectorid));
        } else {
            $output['data'] = array();
        }
        //output to json format
        echo json_encode($output);
    }

    public function addAWCDetail() {
        if (canAccess(array("0", "1", "3", "5"))) {

            if ($this->input->post() && $this->input->server("REQUEST_METHOD") === 'POST') {
                $sectorid = decryptMyData($this->input->post('sectorid', true));
                $projectcode = decryptMyData($this->input->post('projectnamecode', true));
                if ($this->awcDetail_model->checkIsDataFreeze($sectorid) === 'freeze') {
                    setFlashMessage(AWCDetail::ERROR_AWC_SECTOR_FREEZE, AWCDetail::ERROR);
                    redirect('awcDetail');
                    exit;
                }
                //check if other project is freeze
                $projectcount = $this->awcDetail_model->checkIsOthProjectFreeze($projectcode);
                if ($projectcount > 0) {
                    setFlashMessage(AWCDetail::ERROR_AWC_PROJECT_FREEZE, AWCDetail::ERROR);
                    redirect('awcDetail');
                    exit;
                }
                $sactionarr = $this->awcDetail_model->checkSanctionedAWC($sectorid);
//                prd($sactionarr);
                if (isset($sactionarr['currentawccnt']) && isset($sactionarr['sanctionawccnt']) && $sactionarr['currentawccnt'] >= $sactionarr['sanctionawccnt']) {
                    setFlashMessage(AWCDetail::ERROR_AWC_SANCTION_LIMIT_EXCEED . "Current: " . $sactionarr['currentawccnt'] . " = Sanctioned: " . $sactionarr['sanctionawccnt'], AWCDetail::ERROR);
//                    redirect('awcDetail');
                    echo ($sactionarr['sanctionawccnt']) . " " . ($sactionarr['currentawccnt']);
                    exit;
                }
                $ruralurbanval = $this->input->post('ruralurban', true);

                $this->form_validation->set_rules('sectorid', 'Sector Name', 'trim|required|xss_filter');
                $this->form_validation->set_rules('awcnamee', 'Anganwari English Name', 'trim|required|xss_filter');
                $this->form_validation->set_rules('awcnameh', 'Anganwari Hindi Name', 'trim|required|xss_filter');
                $this->form_validation->set_rules('awc_code', 'Anganwari center', 'trim|required|integer|exact_length[2]|xss_filter');
                $this->form_validation->set_rules('is_creche', 'Is Creche Available', 'trim|integer|exact_length[1]|xss_filter');
                $this->form_validation->set_rules('awcadd', 'Anganwari Kendra Address', 'trim|required|xss_filter');
                $this->form_validation->set_rules('a_const_code', 'Assembly', 'trim|xss_filter');
                $this->form_validation->set_rules('pcode', 'Parliament', 'trim|xss_filter');
                $this->form_validation->set_rules('awctype', 'awctype', 'trim|required|xss_filter');
                $this->form_validation->set_rules('ruralurban', 'ruralurban', 'trim|required|integer|xss_filter');
                if ($ruralurbanval == 0) {
                    $this->form_validation->set_rules('revvillageid', 'Revenue Village', 'trim|required|xss_filter');
                    $this->form_validation->set_rules('revvillcode', 'Revenue Village Name', 'trim|required|xss_filter');
                    $this->form_validation->set_rules('villpanchid', 'Gram panchayat', 'trim|required|xss_filter');
                    $this->form_validation->set_rules('panchsamitiid', 'Panchayat Samiti', 'trim|required|xss_filter');
                } else {
                    $this->form_validation->set_rules('localbodyid', 'Local Body', 'trim|required|xss_filter');
                    $this->form_validation->set_rules('wardid', 'Ward', 'trim|required|xss_filter');
                }
                $this->form_validation->set_rules('operational', 'Operational', 'trim|required|numeric|xss_filter');
                $this->form_validation->set_rules('awbuilding', 'Building', 'trim|xss_filter');
                $this->form_validation->set_rules('gpslatloc', 'GPS LATITUDE', 'trim|numeric|xss_filter');
                $this->form_validation->set_rules('gpslongloc', 'GPS Longitude', 'trim|numeric|xss_filter');
                $this->form_validation->set_rules('otherFacilities', 'Other Facilities', 'trim|xss_filter');

                if ($this->input->post("otherfacilities") && in_array('3', $this->input->post("otherfacilities"))) {
                    $this->form_validation->set_rules('toiletfunctioning', 'Toilet Functioning', 'trim|required|xss_filter');
                }

                $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><a class="close" data-dismiss="alert" aria-label="close" href="#">×</a><strong>', '</strong></div>');
                if ($this->form_validation->run()) {

                    $awcfields = $this->db->list_fields($this->tbl_awc_master);

                    $data_to_store = array();

                    $encFields = array("sectorid", "revvillageid", "villpanchid", "panchsamitiid", "pcode", "a_const_code", "wardid", "localbodyid");
                    $ruralfield = array("revvillageid", "revvillcode", "villpanchid", "panchsamitiid");
                    $urbanfield = array("localbodyid", "wardid");
                    foreach ($awcfields as $field) {
                        if (array_key_exists($field, $this->input->post())) {

//                            else {
                            if ($ruralurbanval == '0' && in_array($field, $urbanfield)) {
                                continue;
                            } else if ($ruralurbanval != '0' && in_array($field, $ruralfield)) {

                                continue;
                            }
                            if (in_array($field, $encFields)) {
                                $data_to_store[$field] = rqs($field);
                            } else {
//                                echo $field;
                                $data_to_store[$field] = $this->input->post($field, true);
                            }
//                            }
                        }
                    }
                    $data_to_store['projectcode'] = decryptMyData($this->input->post("projectnamecode"));
                    $data_to_store['verifieddate'] = date("Y-m-d");
                    $data_to_store['verifiedby'] = decryptMyData($this->session->userdata('userId'));
                    //getting new id from database last pawcid
                    $data_to_store['pawcid'] = $this->awcDetail_model->getNextAwcId();
                    /* adding toilet functioning to other facilities */
                    $otherfacilities = implode(",", $data_to_store['otherfacilities']);
                    if ($this->input->post("otherfacilities") && in_array('3', $this->input->post("otherfacilities"))) {
                        $otherfacilities .= "," . rq('toiletfunctioning');
                    }
                    $data_to_store['otherfacilities'] = $otherfacilities;
                    //if the insert has returned true then we show the flash message
                    if ($this->awcDetail_model->add_awcDetail($data_to_store)) {
                        setFlashMessage(AWCDetail::MSG_AWC_CREATED);
                    } else {
                        setFlashMessage(AWCDetail::MSG_AWC_ERROR_IN_CREATION, AWCDetail::ERROR);
                        redirect('awcDetail/awcDetail_desc');
                        exit;
                    }
                    redirect('awcDetail');
                }//validation run
            }
            $data['distList'] = $this->getDistrictRolewise();

            $data['vidhansabhaList'] = $this->awcDetail_model->getAssemblyData(array("cols" => array("a_const_code", "a_const_name")));
            $data['parliamentList'] = $this->awcDetail_model->getParliamentData(array("cols" => array("pcode", "pname2")));
            $data['main_content'] = 'admin/awcDetail/awcDetail_desc';
            $this->load->view('includes/template', $data);
        }
    }

    public function updateAwcDetail($pawcid = '') {

        if ($pawcid === '' || !isset($pawcid)) {
            redirect('awcDetail');
            exit;
        }

        $decpawcid = decryptMyData($pawcid);

//        if (canAccess(array("0", "1", "3"))) {
        if ($this->input->post() && $this->input->server("REQUEST_METHOD") === 'POST') {
            $sectorid = decryptMyData($this->input->post('sectorid', true));
            $projectcode = decryptMyData($this->input->post('projectnamecode', true));
            if ($this->awcDetail_model->checkIsDataFreeze($sectorid) === 'freeze') {
                setFlashMessage(AWCDetail::ERROR_AWC_SECTOR_FREEZE, AWCDetail::ERROR);
                redirect('awcDetail');
                exit;
            }
            //check if other project is freeze
            $projectcount = $this->awcDetail_model->checkIsOthProjectFreeze($projectcode);
            if ($projectcount > 0) {
                setFlashMessage(AWCDetail::ERROR_AWC_PROJECT_FREEZE, AWCDetail::ERROR);
                redirect('awcDetail');
                exit;
            }
            $sactionarr = $this->awcDetail_model->checkSanctionedAWC($sectorid);
//                prd($sactionarr);
            if (isset($sactionarr['currentawccnt']) && isset($sactionarr['sanctionawccnt']) && $sactionarr['currentawccnt'] >= $sactionarr['sanctionawccnt']) {
                setFlashMessage(AWCDetail::ERROR_AWC_SANCTION_LIMIT_EXCEED, AWCDetail::ERROR);
                redirect('awcDetail');
                exit;
            }
            $ruralurbanval = $this->input->post('ruralurban', true);

            $this->form_validation->set_rules('awcnamee', 'Anganwari English Name', 'trim|required|xss_filter');
            $this->form_validation->set_rules('awcnameh', 'Anganwari Hindi Name', 'trim|required|xss_filter');
            $this->form_validation->set_rules('awc_code', 'Anganwari center', 'trim|required|integer|exact_length[2]|xss_filter');
            $this->form_validation->set_rules('awcadd', 'Anganwari Kendra Address', 'trim|required|xss_filter');
            $this->form_validation->set_rules('a_const_code', 'Assembly', 'trim|xss_filter');
            $this->form_validation->set_rules('pcode', 'Parliament', 'trim|xss_filter');
            $this->form_validation->set_rules('awctype', 'awctype', 'trim|required|xss_filter');
            $this->form_validation->set_rules('ruralurban', 'ruralurban', 'trim|required|integer|xss_filter');
            $this->form_validation->set_rules('is_creche', 'Is Creche Available', 'trim|integer|exact_length[1]|xss_filter');
            if ($ruralurbanval == 0) {
                $this->form_validation->set_rules('revvillageid', 'Revenue Village', 'trim|required|xss_filter');
                $this->form_validation->set_rules('revvillcode', 'Revenue Village Name', 'trim|required|xss_filter');
                $this->form_validation->set_rules('villpanchid', 'Gram panchayat', 'trim|required|xss_filter');
                $this->form_validation->set_rules('panchsamitiid', 'Panchayat Samiti', 'trim|required|xss_filter');
            } else {
                $this->form_validation->set_rules('localbodyid', 'Local Body', 'trim|required|xss_filter');
                $this->form_validation->set_rules('wardid', 'Ward', 'trim|required|xss_filter');
            }
            $this->form_validation->set_rules('operational', 'Operational', 'trim|required|numeric|xss_filter');
            $this->form_validation->set_rules('awbuilding', 'Building', 'trim|xss_filter');
            $this->form_validation->set_rules('gpslatloc', 'GPS LATITUDE', 'trim|numeric|xss_filter');
            $this->form_validation->set_rules('gpslongloc', 'GPS Longitude', 'trim|numeric|xss_filter');
            $this->form_validation->set_rules('otherFacilities', 'Other Facilities', 'trim|xss_filter');

            if ($this->input->post("otherfacilities") && in_array('3', $this->input->post("otherfacilities"))) {
                $this->form_validation->set_rules('toiletfunctioning', 'Toilet Functioning', 'trim|required|xss_filter');
            }

            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissable"><a class="close" data-dismiss="alert" aria-label="close" href="#">×</a><strong>', '</strong></div>');
            if ($this->form_validation->run()) {

                $awcfields = $this->db->list_fields($this->tbl_awc_master);

                $data_to_store = array();

                $encFields = array("sectorid", "revvillageid", "villpanchid", "panchsamitiid", "pcode", "a_const_code", "wardid", "localbodyid");
                $ruralfield = array("revvillageid", "revvillcode", "villpanchid", "panchsamitiid");
                $urbanfield = array("localbodyid", "wardid");
                foreach ($awcfields as $field) {
                    if (array_key_exists($field, $this->input->post())) {
//                            else {
                        if ($ruralurbanval == 0 && in_array($field, $urbanfield)) {
                            continue;
                        } else if ($ruralurbanval != 0 && in_array($field, $ruralfield)) {
                            continue;
                        }
                        if (in_array($field, $encFields))
                            $data_to_store[$field] = rqs($field);
                        else
                            $data_to_store[$field] = $this->input->post($field, true);
//                            }
                    }
                }
                $data_to_store['is_creche'] = rq('is_creche') && rq('is_creche') == 1 ? rq('is_creche') : 0;

                $data_to_store['verifieddate'] = date("Y-m-d");
                $data_to_store['verifiedby'] = decryptMyData($this->session->userdata('userId'));
                //getting new id from database last pawcid
                $data_to_store['pawcid'] = $this->awcDetail_model->getNextAwcId();
                /* adding toilet functioning to other facilities */
                $otherfacilities = implode(",", $data_to_store['otherfacilities']);
                if ($this->input->post("otherfacilities") && in_array('3', $this->input->post("otherfacilities"))) {

                    $otherfacilities .= "," . rq('toiletfunctioning');
                }
                $data_to_store['otherfacilities'] = $otherfacilities;

                //if the insert has returned true then we show the flash message
                if ($this->awcDetail_model->update_awcDetail($decpawcid, $data_to_store)) {
                    setFlashMessage(AWCDetail::MSG_AWC_UPDATED);
                } else {
                    setFlashMessage(AWCDetail::MSG_AWC_ERROR_IN_UPDATION, AWCDetail::ERROR);
                    redirect('awcDetail/awcDetail_update/' . $pawcid);
                    exit;
                }
                redirect('awcDetail');
            }//validation run
        }

        $data['awcData'] = $this->awcDetail_model->get_awcDataByPawcid($decpawcid);
//            pr($data);
        $data['localbodyList'] = $this->awcDetail_model->getLocalBodyMasterData();

        $data['distList'] = getDistrict();
        $data['pawcid'] = $pawcid;
        $data['revvillageList'] = $data['vidhansabhaList'] = $this->awcDetail_model->getAssemblyData(array("cols" => array("a_const_code", "a_const_name")));
        $data['parliamentList'] = $this->awcDetail_model->getParliamentData(array("cols" => array("pcode", "pname2")));
        $data['main_content'] = 'admin/awcDetail/awcDetail_update';
        $this->load->view('includes/template', $data);
//        }
    }

    public function mobileno_check($no) {
        if ((int) substr($no, 0, 1) < 7) {
            $this->form_validation->set_message('mobileno_check', 'Mobile No. Must be start with 7 or above!');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /*     * *********************************************** AWC Reporting starts Here******************************************* */

    public function awcRpt() {

        $role = $this->role;
        if (canAccess($this->rptAccessArr)) {
            $data['districtList'] = $this->getRptDistrictRolewise();
            $data['role'] = $role;
            $data['main_content'] = 'admin/awcDetail/awcRpt';
            $this->load->view('includes/template', $data);
        }
    }

    public function awcRptDt() {

        if ($this->input->post('sectorid') && $this->input->post('distid') && $this->input->post('projectcode')) {
            $sectorid = decryptMyData($this->input->post('sectorid', true));
            $distid = rqs('distid');
            $projectnamecode = rqs('projectcode');
            $output = $this->awcDetail_model->get_awcRptList(array("distid" => $distid, "projectcode" => $projectnamecode, "sectorid" => $sectorid));
        } else {
            $output = $this->awcDetail_model->get_awcRptList();
        }
        //output to json format
        echo json_encode($output);
    }

    /*     * *******************************OLD AWC REPORT **************************************************** */

    public function awcOldRpt() {

        $role = $this->role;
        if (canAccess($this->rptAccessArr)) {
            $data['districtList'] = $this->getRptDistrictRolewise();
            $data['role'] = $role;
            $data['main_content'] = 'admin/awcDetail/awcOldRpt';
            $this->load->view('includes/template', $data);
        }
    }

    public function awcOldRptDt() {
//        prd($this->session->all_userdata());
        if ($this->input->post('sectorid') && $this->input->post('distid') && $this->input->post('projectcode')) {
            $sectorid = decryptMyData($this->input->post('sectorid', true));
            $distid = rqs('distid');
            $projectnamecode = rqs('projectcode');
            $output = $this->awcDetail_model->get_awcOldRptList(array("distid" => $distid, "projectcode" => $projectnamecode, "sectorid" => $sectorid));
        } else {
            $output = $this->awcDetail_model->get_awcOldRptList();
        }
        //output to json format
        echo json_encode($output);
    }

    public function deleteAwc() {
        if (rqs('pawcid') && !is_numeric(rqs('pawcid')) && rqs('sectorid')) {
            return false;
        }
//        if (!$this->awcDetail_model->checkFreezeForDelete(rqs('pawcid'), rqs('sectorid'))) {
//            echo json_encode(array("status" => AWCDetail::ERROR, "msg" => AWCDetail::ERROR_AWC_FREEZE, "type" => "red"));
//            exit;
//        }
        if (rqs('pawcid')) {
            if ($this->awcDetail_model->deleteAwc(rqs('pawcid'))) {
                echo json_encode(array("status" => AWCDetail::SUCCESS, "msg" => AWCDetail::DELETED, "type" => "green"));
            } else {
                echo json_encode(array("status" => AWCDetail::ERROR, "msg" => AWCDetail::ERROR_MSG, "type" => "red"));
            }
        } else {
            return false;
        }
    }

    /* Creche Detail */

    public function crecheDetailDt() {
        if (rqs('sectorid') != '') {
            $sectorid = decryptMyData($this->input->post('sectorid', true));
            $output = $this->awcDetail_model->get_crecheList(array("sectorid" => $sectorid));
        } else {
            $output['data'] = array();
        }
        //output to json format
        echo json_encode($output);
    }

    public function updateCrechData() {
        
        $rowIds = array_map('decryptMyData', rq('pawcid'));
        
        $status = changeStatus($this->tbl_awc_master, 'is_creche', '1', 'pawcid', $rowIds) ? "success" : "error";
        echo json_encode(array("status" => $status));
        die;
    }

//edit
}
