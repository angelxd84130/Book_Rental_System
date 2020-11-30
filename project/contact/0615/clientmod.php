<?php
// 使用者點選放棄修改按鈕
if (isset($_POST['Abort']) && !empty($_POST['Abort'])){
	header("Location: searchclient.php");
	exit();
	}
// Authentication 認證
require_once("../include/auth.php");
// 變數及函式處理，請注意其順序
require_once("../include/gpsvars.php");
require_once("../include/configure.php");
require_once("../include/db_func.php");
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
// 確認參數是否正確
if (!isset($id)) die ("Parameter error!");
// 找出此用戶的群組
$sqlcmd = "SELECT * FROM manage WHERE account='$LoginID' AND valid='Y'";
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die ('Unknown or invalid user!');


$name = '';
$identity = '';
$address = '';
$phone = '';
// 處理使用者異動之資料
if (isset($Confirm)) {   // 確認按鈕
    if (!isset($Name) || empty($Name)) $ErrMsg = '姓名不可為空白\n';
    if (!isset($Identity) || empty($Identity)) $ErrMsg = '身分證字號不可為空白\n';
    if (!isset($Address) || empty($Address)) $ErrMsg = '地址不可為空白\n';
    if (!isset($Phone) || empty($Phone)) $ErrMsg = '電話不可為空白\n';
	if (empty($ErrMsg)) {   // 資料經初步檢核沒問題
    // Demo for XSS
    //    $Name = xssfix($Name);
    //    $Phone = xssfix($Phone);
    // Demo for the reason to use addslashes
        if (!get_magic_quotes_gpc()) {
            $Name = addslashes($Name);
            $Identity = addslashes($Identity);
            $Address = addslashes($Address);
			$Phone = addslashes($Phone);
        }
		$name = $Name;
        $identity = $Identity;
        $address = $Address;
		$phone = $Phone;
        $sqlcmd="UPDATE client SET name='$Name',identity='$Identity',address='$Address', phone='$Phone' WHERE ID='$id'";
        $result = updatedb($sqlcmd, $db_conn);
        header("Location: searchclient.php");
        exit();
    }
}
if (!isset($Name)) {    
// 此處是在contactlist.php點選後進到這支程式，因此要由資料表將欲編輯的資料列調出
    $sqlcmd = "SELECT * FROM client WHERE ID='$id'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) <= 0) die('No data found');      // 找不到資料，正常應該不會發生
    $Name = $rs[0]['name'];
    $Identity = $rs[0]['identity'];
	$Address = $rs[0]['address'];
	$Phone = $rs[0]['phone'];
} else {    // 點選送出後，程式發現有錯誤
// Demo for stripslashes
    if (get_magic_quotes_gpc()) {
		$Name = addslashes($Name);
        $Identity = addslashes($Identity);
        $Address = addslashes($Address);
		$Phone = addslashes($Phone);
    }
}

$PageTitle = '客戶修改';
require_once ('../include/cssheader.php');
?>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<div align="center">
<form action="" method="post" name="inputform">
<input type="hidden" name="id" value="<?php echo $id ?>">
<b>修改客戶資料</b>
<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
<tr height="30">
  <th width="40%">姓名</th>
  <td><input type="text" name="Name" value="<?php echo $Name ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">身分證字號</th>
  <td><input type="text" name="Identity" maxlength="10" value="<?php echo $Identity ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">地址</th>
  <td><input type="text" name="Address" value="<?php echo $Address ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">電話</th>
  <td><input type="text" name="Phone" value="<?php echo $Phone ?>" size="50"></td>
</tr>
</table>
<input type="submit" name="Confirm" value="存檔送出">&nbsp;
<input type="submit" name="Abort" value="放棄修改">
</form>
</div>
<?php 
require_once ('../include/footer.php');
?>