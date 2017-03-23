<?php
    @ob_start();
    @session_start();
    $dblink = mysqli_connect('localhost','root','root','dbname');
    $dblink->query("set names utf8");
    global $dblink;

    date_default_timezone_set('Asia/Bangkok');
    header_remove();
    header("Content-type:application/json");
?>