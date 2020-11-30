<?php
//session_start();
if (isset($_POST['Abort']) && !empty($_POST['Abort'])){
	header("Location: searchbook.php");
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
$PageTitle = '新增書籍';
require_once ('../include/cssheader.php');

if(!isset($Bookname)) $Bookname = '';
if(!isset($Bookauthor)) $Bookauthor = '';
if(!isset($BookISBN)) $BookISBN = '';
if(!isset($Money)) $Money = '';
if(!isset($Bookclass)) $Bookclass = '';
if(!isset($Bookdate)) $Bookdate = '';
if(!isset($Barcode)) $Barcode = '';

if (isset($Confirm)) {   // 確認按鈕
    if (empty($Bookname)) $ErrMsg = '書名不可為空白\n';
    if (empty($Bookauthor)) $ErrMsg = '作者不可為空白\n';
    if (empty($BookISBN)) $ErrMsg = 'ISBN不可為空白\n';
	if (empty($Money)) $ErrMsg = '金額不可為空白\n';
	if (empty($Bookclass)) $ErrMsg = '分類不可為空白\n';
	if (empty($Bookdate)) $ErrMsg = '出版日期不可為空白\n';
	if (empty($Barcode)) $ErrMsg = '條碼不可為空白\n';
	
    if (empty($ErrMsg)) {
        
        $sqlcmd='INSERT INTO book (name,author,ISBN,money,classification,date,barcode) VALUES ('
            . "'$Bookname','$Bookauthor','$BookISBN','$Money','$Bookclass','$Bookdate','$Barcode')";
        $result = updatedb($sqlcmd, $db_conn);
        
        header("Location: searchbook.php");
		exit();
    }
	else echo "$ErrMsg";
}
$PageTitle = '新增書籍';
require_once ('../include/cssheader.php');
?>
<HEAD>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<title>新增書籍</title>
</HEAD>
<body>

<div style="text-align:center;margin-top:5px;font-size:40px;font-weight:bold;">
藍天租書店</div>
<div style="text-align:center;margin-top:10px;font-size:30px;">
新增書籍
</div>
<div style="text-align:center"><br/><br/>
<form action="" method="post" name="inputform">

<table border="1" width="60%" cellspacing="0" cellpadding="3" align="center">
<tr height="30">
  <th width="40%">書名</th>
  <td><input type="text" name="Bookname" value="<?php echo $Bookname ?>" size="50"></td>
</tr>
<tr height="30">
  <th width="40%">作者</th>
  <td><input type="text" name="Bookauthor" value="<?php echo $Bookauthor ?>" size="50"></td>
</tr>
<tr height="30">
  <th width="40%">ISBN</th>
  <td><input type="text" name="BookISBN" value="<?php echo $BookISBN ?>" size="30"></td>
</tr>
<tr height="30">
  <th width="40%">金額</th>
  <td><input type="text" name="Money" value="<?php echo $Money ?>" size="10"></td>
</tr>
<tr height="30">
  <th width="40%">分類</th>
  <td><input type="text" name="Bookclass" value="<?php echo $Bookclass ?>" size="10"></td>
</tr>
<tr height="30">
  <th width="40%">出版日期</th>
  <td><input type="text" name="Bookdate" value="<?php echo $Bookdate ?>" size="10">Y-M-D</td>
</tr>
<tr height="30">
  <th width="40%">條碼</th>
  <td><input type="text" name="Barcode" value="<?php echo $Barcode ?>" size="10"></td>
</tr>
</table><br/>
<input type="submit" name="Confirm" value="存檔送出">&nbsp;
<input type="submit" name="Abort" value="放棄新增">
</form>
</div>

</body>

</html>