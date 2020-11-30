<?php
header('Content-type: text/html; charset=utf-8');
session_start();
//$_SESSION['LoginID']='410206140';
if (!isset($_SESSION['LoginID']) || empty($_SESSION['LoginID'])) {
    die('您未登入');
}
?>