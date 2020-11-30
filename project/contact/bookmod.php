<?php
// 使用者點選放棄修改按鈕
if (isset($_POST['Abort']) && !empty($_POST['Abort'])){
	header("Location: searchbook.php");
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

// 處理使用者異動之資料
if (isset($Confirm)) {   // 確認按鈕
    if (!isset($Name) || empty($Name)) $ErrMsg = '書名不可為空白\n';
    if (!isset($Author) || empty($Author)) $ErrMsg = '作者不可為空白\n';
    if (!isset($ISBN) || empty($ISBN)) $ErrMsg = 'ISBN不可為空白\n';
    if (!isset($Money) || empty($Money)) $ErrMsg = '金額不可為空白\n';
	if (!isset($Classification) || empty($Classification)) $ErrMsg = '分類不可為空白\n';
	if (!isset($Date) || empty($Date)) $ErrMsg = '出版日期不可為空白\n';
	if (!isset($Barcode) || empty($Barcode)) $ErrMsg = '編號不可為空白\n';
	if (empty($ErrMsg)) {   // 資料經初步檢核沒問題
    // Demo for XSS
    //    $Name = xssfix($Name);
    //    $Phone = xssfix($Phone);
    // Demo for the reason to use addslashes
        if (!get_magic_quotes_gpc()) {
            $Name = addslashes($Name);
            $Author = addslashes($Author);
            $ISBN = addslashes($ISBN);
			$Money = addslashes($Money);
			$Classification = addslashes($Classification);
			$Date = addslashes($Date);
			$Barcode = addslashes($Barcode);
        }
        $sqlcmd="UPDATE book SET name='$Name',author='$Author',ISBN='$ISBN', Money='$Money', classification='$Classification',"
            . " date='$Date', barcode='$Barcode' WHERE id='$id'";
        $result = updatedb($sqlcmd, $db_conn);
        header("Location: searchbook.php");
        exit();
    }
}
if (!isset($Name)) {    
// 此處是在contactlist.php點選後進到這支程式，因此要由資料表將欲編輯的資料列調出
    $sqlcmd = "SELECT * FROM book WHERE id='$id'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) <= 0) die('No data found');      // 找不到資料，正常應該不會發生
    $Name = $rs[0]['name'];
    $Author = $rs[0]['author'];
	$ISBN = $rs[0]['ISBN'];
	$Money = $rs[0]['money'];
	$Classification = $rs[0]['classification'];
	$Date = $rs[0]['date'];
	$Barcode = $rs[0]['barcode'];
} else {    // 點選送出後，程式發現有錯誤
// Demo for stripslashes
    if (get_magic_quotes_gpc()) {
		$Name = addslashes($Name);
		$Author = addslashes($Author);
		$ISBN = addslashes($ISBN);
		$Money = addslashes($Money);
		$Classification = addslashes($Classification);
		$Date = addslashes($Date);
		$Barcode = addslashes($Barcode);
    }
}

$PageTitle = '書籍修改';
require_once ('../include/cssheader.php');
?>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<div align="center">
<form action="" method="post" name="inputform">
<input type="hidden" name="id" value="<?php echo $id ?>">
<b>修改書籍資料</b>
<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
<tr height="30">
  <th width="40%">書名</th>
  <td><input type="text" name="Name" value="<?php echo $Name ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">作者</th>
  <td><input type="text" name="Author" value="<?php echo $Author ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">ISBN</th>
  <td><input type="text" name="ISBN" value="<?php echo $ISBN ?>" size="20"></td>
</tr>
<tr height="30">
  <th width="40%">金額</th>
  <td><input type="text" name="Money" value="<?php echo $Money ?>" size="50"></td>
</tr>
<tr height="30">
  <th width="40%">分類</th>
  <td><input type="text" name="Classification" value="<?php echo $Classification ?>" size="50"></td>
</tr>
<tr height="30">
  <th width="40%">出版日期</th>
  <td><input type="text" name="Date" value="<?php echo $Date ?>" size="50"></td>
</tr>
<tr height="30">
  <th width="40%">書的編號</th>
  <td><input type="text" name="Barcode" value="<?php echo $Barcode ?>" size="50"></td>
</tr>
</table>
<input type="submit" name="Confirm" value="存檔送出">&nbsp;
<input type="submit" name="Abort" value="放棄修改">
</form>
</div>
<?php 
require_once ('../include/footer.php');
?>