<?php
//session_start();
if (isset($_POST['Abort']) && !empty($_POST['Abort'])){
	header("Location: rental.php");
	exit();
	
	}
require_once("../include/auth.php");
require_once('../include/gpsvars.php');
require_once('../include/configure.php');
require_once('../include/db_func.php');
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$sqlcmd = "SELECT * FROM manage WHERE account='$LoginID' AND valid='Y'";  //再次確認是否有這個人且還存在
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die ('Unknown or invalid user!');
foreach($rs AS $item){
	$ManageID = $item['ID'];
}

$PageTitle = '新增租借';
require_once ('../include/cssheader.php');

$Today = strftime("%Y-%m-%d");
$Return = date("Y-m-d", strtotime($Today."+3 day")); //3日後歸還
if(!isset($BookID)) $BookID = '';
if(!isset($ClientIdentity)) $ClientIdentity = '';

if (isset($Confirm)) {   // 確認按鈕
    if (empty($BookID)) $ErrMsg = '書代碼不可為空白\n';
    if (empty($ClientIdentity)) $ErrMsg = '身分證字號不可為空白\n';
    	
	$sqlcmd = "SELECT money,rental FROM book WHERE ID='$BookID' AND valid='Y'"; //select money;
	$rs = querydb($sqlcmd, $db_conn);
	if (count($rs) <= 0) die ('查詢錯誤!');
	foreach($rs AS $item){
		$Money = $item['money'];
		$R = $item['rental'];
	}
	if($R=='Y') die ('此書已被借走');
	$Money = $Money/10;
	echo "$Money"."<br/>";
	$sqlcmd = "SELECT ID FROM client WHERE identity='$ClientIdentity' AND valid='Y'"; //select clientID;
	$rs = querydb($sqlcmd, $db_conn);
	if (count($rs) <= 0) die ('Unknown this man!');
	foreach($rs AS $item){
		$ClientID = $item['ID'];
	}
	echo "ClientID"."<br/>";
    if (empty($ErrMsg)) {
        
        $sqlcmd='INSERT INTO rental (date,bookID,clientID,manageID,money,returnd) VALUES ('
            . "'$Today','$BookID','$ClientID','$ManageID','$Money','$Return')";
        $result = updatedb($sqlcmd, $db_conn);
        $sqlcmd="UPDATE book SET rental='Y' WHERE id='$BookID'";
        $result = updatedb($sqlcmd, $db_conn);
        header("Location: rental.php");
		exit();
    }
	else echo "$ErrMsg";
}
$PageTitle = '新增租借';
require_once ('../include/cssheader.php');
?>
<HEAD>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<title>新增租借</title>
</HEAD>
<body>

<div style="text-align:center;margin-top:5px;font-size:40px;font-weight:bold;">
藍天租書店</div>
<div style="text-align:center;margin-top:10px;font-size:30px;">
新增租借
</div>
<div style="text-align:center"><br/><br/>
<form action="" method="post" name="inputform">

<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
<tr height="30">
  <th width="40%">書編號</th>
  <td><input type="text" name="BookID" value="<?php echo $BookID ?>" size="10"></td>
</tr>
<tr height="30">
  <th width="40%">客戶身分證字號</th>
  <td><input type="text" name="ClientIdentity" value="<?php echo $ClientIdentity ?>" size="10"></td>
</tr>

</table><br/>
<input type="submit" name="Confirm" value="存檔送出">&nbsp;
<input type="submit" name="Abort" value="放棄新增">
</form>
</div>

</body>

</html>