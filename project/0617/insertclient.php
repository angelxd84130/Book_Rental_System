<?php
//session_start();
if (isset($_POST['Abort']) && !empty($_POST['Abort'])){
	header("Location: searchclient.php");
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
$PageTitle = '新增客戶';
require_once ('../include/cssheader.php');

if(!isset($Name)) $Name = '';
if(!isset($Email)) $Email = '';
if(!isset($Phone)) $Phone = '';
if(!isset($Address)) $Address = '';
if(!isset($Identity)) $Identity = '';
$Today = strftime("%Y-%m-%d");
$Money = 0;

if (isset($Confirm)) {   // 確認按鈕
    if (empty($Name)) $ErrMsg = '姓名不可為空白\n';
    //if (empty($Email)) $ErrMsg = '信箱不可為空白\n';
    if (empty($Phone)) $ErrMsg = '電話不可為空白\n';
	if (empty($Address)) $ErrMsg = '地址不可為空白\n';
	if (empty($Identity)) $ErrMsg = '身分證字號不可為空白\n';
	//if (empty($Money)) $ErrMsg = '金額不可為空白\n';
	
    if (empty($ErrMsg)) {
        
        $sqlcmd='INSERT INTO client (name,email,phone,address,identity,registereddate,money) VALUES ('
            . "'$Name','$Email','$Phone','$Address','$Identity','$Today','$Money')";
        $result = updatedb($sqlcmd, $db_conn);
        
        header("Location: searchclient.php");
		exit();
    }
	else echo "$ErrMsg";
}
$PageTitle = '新增客戶';
require_once ('../include/cssheader.php');
?>
<HEAD>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<title>新增客戶</title>
</HEAD>
<body>

<div style="text-align:center;margin-top:5px;font-size:40px;font-weight:bold;">
藍天租書店</div>
<div style="text-align:center;margin-top:10px;font-size:30px;">
新增客戶
</div>
<div style="text-align:center"><br/><br/>
<form action="" method="post" name="inputform">

<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
<tr height="30">
  <th width="40%">姓名</th>
  <td><input type="text" name="Name" value="<?php echo $Name ?>" size="10"></td>
</tr>
<tr height="30">
  <th width="40%">電話</th>
  <td><input type="text" name="Phone" value="<?php echo $Phone ?>" size="10"></td>
</tr>
<tr height="30">
  <th width="40%">身分證字號</th>
  <td><input type="text" name="Identity" value="<?php echo $Identity ?>" size="10"></td>
</tr>
<tr height="30">
  <th width="40%">信箱</th>
  <td><input type="text" name="Email" value="<?php echo $Email ?>" size="50"></td>
</tr>
<tr height="30">
  <th width="40%">地址</th>
  <td><input type="text" name="Address" value="<?php echo $Address ?>" size="50"></td>
</tr>
</table><br/>
<input type="submit" name="Confirm" value="存檔送出">&nbsp;
<input type="submit" name="Abort" value="放棄新增">
</form>
</div>

</body>

</html>