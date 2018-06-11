<?php

/*
 * CREATED BY: ANSHUL PAREEK
 * CREATED DATE:
 * MODIFIED DATE:  
 */

function salt() {
    $ci = &get_instance();
    if ($ci->session->userdata("privatekey")) {
        return $ci->session->userdata("privatekey");
    } else {
        $key = str_shuffle("lkasjfiaurahsagirjslkvj25432lkjdsaflku");
        $ci->session->set_userdata("privatekey", $key);
        return $ci->session->userdata("privatekey");
    }
}

function secretIv() {
    $ci = &get_instance();
    if ($ci->session->userdata("secretiv")) {
        return $ci->session->userdata("secretiv");
    } else {
        $key = str_shuffle("lksauoiyulkjehfahf234324lkadsufoiagj");
        $ci->session->set_userdata("secretiv", $key);
        return $ci->session->userdata("secretiv");
    }
}

function encryptMyData($message, $salt = '', $secretIv = '') {
    if (!isset($message)) {
        return false;
    }
    $output = false;
    $secret_iv = '';
    $encrypt_method = "AES-256-CBC";
    if (isset($salt) && $salt != '') {
        $secret_key = $salt;
    } else {
        $secret_key = salt();
    }
    if (isset($secretIv) && $secretIv != '') {
        $secret_iv = $secretIv;
    } else {
        $secret_iv = secretIv();
    }
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    $output = openssl_encrypt($message, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);

    return $output;
//    return $encoded;
}

function decryptMyData($decrypt, $salt = '', $secretIv = '') {
    if ($decrypt === '' || !isset($decrypt)) {
        return false;
    }

    $output = false;
    $encrypt_method = "AES-256-CBC";

    if (isset($salt) && $salt != '') {
        $secret_key = $salt;
    } else {
        $secret_key = salt();
    }

    if (isset($secretIv) && $secretIv != '') {
        $secret_iv = $secretIv;
    } else {
        $secret_iv = secretIv();
    }
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_decrypt(base64_decode($decrypt), $encrypt_method, $key, 0, $iv);
//    echo $output.",";
    return $output;
}

function checkForDdos() {
    // Assuming session is already started
    $ci = $ci = &get_instance();
    $uri = md5($_SERVER['REQUEST_URI']);
    $exp = 1; // 3 seconds
    $hash = $uri . '|' . time();
    if (!$ci->session->userdata('ddos')) {
        $ci->session->set_userdata('ddos', $hash);
    }

    list($_uri, $_exp) = explode('|', $ci->session->userdata('ddos'));
    if ($_uri == $uri && time() - $_exp < $exp) {
        header('HTTP/1.1 503 Service Unavailable');
        die('HTTP/1.1 503 Service Unavailable!');
    }
// Save last request
    $ci->session->set_userdata('ddos', $hash);
}

function getSha256($string) {
    return hash("sha256", $string);
}

function getSha512($string) {
    return hash("sha512", $string);
}
