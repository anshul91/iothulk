<?php

/*
 * CREATED BY: ANSHUL PAREEK
 * CREATED DATE:
 * MODIFIED DATE:  
 */
if (!function_exists("addCalendarJs")) {

    function addCalendarJs() {
        echo '<link href="' . CSS_URL . 'admin/jquery-ui.css" rel="stylesheet" type="text/css"/>
            <link href="' . CSS_URL . 'admin/calstyle.css" rel="stylesheet" type="text/css"/>
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>';
    }

}
if (!function_exists("addDays")) {

    function addDays($days, $date = '', $format = "Y-m-d H:i:s") {

        $ci = &get_instance();
        if (isset($date) && $date != '' && checkDateFormat($date)) {
            $date = $date;
        } else {
            $date = date($format);
        }
        return $retDate = date($format, strtotime("+" . $days . " days", strtotime($date)));
    }

}

if (!function_exists("addMonth")) {

    function addMonth($month, $date = '', $format = "Y-m-d H:i:s") {
//         prd($date);
        if ($month == '')
            return false;
        if (isset($date) && $date != '' && checkDateFormat($date)) {
            $date = $date;
        } else {

            $date = date('Y-m-d H:i:s');
        }

        return $retDate = date($format, strtotime("+" . $month . " months", strtotime($date)));
    }

}
if (!function_exists("addYear")) {

    function addYear($yearsToAdd, $date = '') {
        if ($yearsToAdd == '')
            return false;
        if (isset($date) && $date != '' && checkDateFormat($date)) {
            $date = $date;
        } else {
            $date = date('Y-m-d H:i:s');
        }
        return $retDate = date("Y-m-d H:i:s", strtotime("+" . $yearsToAdd . " years", strtotime($date)));
    }

}
if (!function_exists("subYear")) {

    function subYear($year, $date = '') {
        if ($year == '')
            return false;
        $ci = &get_instance();
        if (isset($date) && $date != '' && checkDateFormat($date)) {
            $date = $date;
        } else {
            $date = date('Y-m-d H:i:s');
        }
        return $retDate = date("Y-m-d H:i:s", strtotime("-" . $year . " years", strtotime($date)));
    }

}

if (!function_exists("subMonths")) {

    function subMonths($month, $date = '', $format = "Y-m-d H:i:s") {
        if ($month == '')
            return false;
        if (isset($date) && $date != '' && checkDateFormat($date)) {
            $date = $date;
        } else {
            $date = date($format);
        }

        return $retDate = date($format, strtotime("-" . $month . " months", strtotime($date)));
    }

}
#CHECK FOR DATE FORMAT WHICH IS YYYY-MM-DD(DEFAULT)
if (!function_exists("checkDateFormat")) {

    function checkDateFormat($date, $regex = '') {
        $regex = ($regex != '') ? $regex : "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
        if ($date !== '')
            return preg_match($regex, $date) ? true : false;
        else
            return false;
    }

}

if (!function_exists("monthNameToText")) {

    function monthNameToText($monthNum) {
        $dateObj = DateTime::createFromFormat('!m', $monthNum);
        return $dateObj->format('F');
    }

}

if (!function_exists("monthYear_Num_To_TextFormat")) {

    function monthYear_Num_To_TextFormat($monthYear) {
        $monthNum = substr($monthYear, 4, 6);
        $montText = monthNameToText($monthNum);
        $Year = substr($monthYear, 0, 4);
        return implode(', ', array($montText, $Year));
    }

}
if (!function_exists("calculate_DaysInMonth")) {

    function calculate_DaysInMonth($monthYear) {

        $mY = $monthYear;
        $monthNum = substr($mY, 4, 6);
        $Year = substr($mY, 0, 4);

        return cal_days_in_month(CAL_GREGORIAN, $monthNum, $Year);
    }

}
//Define a callback and pass the format of date 
if (!function_exists("valid_date")) {

    function valid_date($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        //Check for valid date in given format
        if ($d && $d->format($format) == $date) {
            return true;
        } else {
            $this->form_validation->set_message('valid_date', 'The %s date is not valid it should match this (' . $format . ') format');
            return false;
        }
    }

}
if (!function_exists("calculate_Age")) {

    function calculate_Age($dob) {
        if (!isset($dob))
            return false;
        $date1 = time();
        $date2 = strtotime($dob);
        $time_difference = $date1 - $date2;
        $seconds_per_year = 60 * 60 * 24 * 365;
        $years = round($time_difference / $seconds_per_year);
        return $years;
    }

}

if (!function_exists("getMonthName")) {

    function getMonthName($monthVal) {
        if (!isset($monthVal))
            return false;
        $monthArr = array(
            "01" => "Jan", "02" => "Feb", "03" => "Mar",
            "04" => "Apr", "05" => "May", "06" => "June",
            "07" => "Jul", "08" => "Aug", "09" => "Sep",
            "10" => "Oct", "11" => "Nov", "12" => "Dec"
        );
        return $monthArr[$monthVal];
    }

}
if (!function_exists("converToIndianDateFormat")) {

    function converToIndianDateFormat($date, $format = 'd-M-Y') {
        return date($format, strtotime($date));
    }

}
/*
 * @Usage: This function calculate age and return year month and days from given date
 */
if (!function_exists("calculateAge")) {

    function calculateAge($dob, $tillDate) {

//        echo $dob="1957-9-30";
//        echo $tillDate;
        $datetime1 = new DateTime($dob);
        $datetime2 = new DateTime($tillDate);
        $interval = $datetime1->diff($datetime2);

        return array(
            "y" => $interval->y,
            "m" => $interval->m,
            "d" => $interval->d,
            "h" => $interval->h,
            "i" => $interval->i
        );
    }

}

if (!function_exists('time_elapsed_string')) {

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full)
            $string = array_slice($string, 0, 1);
        
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

}
