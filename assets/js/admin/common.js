/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var path_arr = window.location.pathname.split("/");
var BASE_URL = window.location.origin + "/" + path_arr[1] + "/" + path_arr[2] + "/" + path_arr[3] + "/";
var jq = $.noConflict();
function fancyAlert(msg, msgType) {
    var msgColor = "red";
    if (msg === '') {
        return false;
    }
    if (typeof (msgType) === "string" && msgType.toLowerCase() === "success") {
        msgType = "Success";
        msgColor = "green";
        icons = 'fa fa-smile-o';
    } else if (msgType.toLowerCase() === 'error') {
        msgType = "Error";
        msgColor = "red";
        icons = "fa fa-frown-o";
    } else if (msgType.toLowerCase() === "info") {
        msgType = "Info";
        msgColor = "info";
        icons = 'fa fa-info-circle';
    } else if (msgType.toLowerCase() == 'warning') {
        msgType = "Warning";
        msgColor = "orange";
        icons = 'fa fa-exclamation-triangle';
    }
    else {
        msgType = "error";
        msgColor = "red",
                icons = 'fa fa-exclamation-triangle';
    }
    jq.alert({
        theme: "modern",
        title: msgType,
        content: msg,
        draggable: true,
        icon: icons,
        type: msgColor,
        typeAnimated: true
    });
//    return;
}
