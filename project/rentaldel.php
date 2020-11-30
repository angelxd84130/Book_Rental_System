<?php
// 使用者點選放棄修改按鈕

// Authentication 認證
require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
// 確認參數是否正確
if (!isset($id)) die ("Parameter error!");
if (!isset($BookID)) die ("Parameter error!");
// 找出此用戶的群組
$sqlcmd = "SELECT * FROM manage WHERE account='$LoginID' AND valid='Y'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die ('Unknown or invalid user!');

// 處理使用者異動之資料

$PageTitle = '還書';
require_once ('../include/cssheader.php');

$sqlcmd="DELETE FROM rental WHERE ID='$id'";
        $result = updatedb($sqlcmd, $db_conn);
$sqlcmd="UPDATE book SET rental='N' WHERE ID='$BookID'";
        $result = updatedb($sqlcmd, $db_conn);		
        header("Location: rental.php");
        exit();
?>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<div align="center">

</div>
<?php 
require_once ('../include/footer.php');
?>