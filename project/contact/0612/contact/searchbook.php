<?php
//session_start();
require_once("../include/auth.php");
require_once('../include/gpsvars.php');
require_once('../include/configure.php');
require_once('../include/db_func.php');
$db_conn = connect2db($dbhost, $dbuser, $dbpwd, $dbname);
$sqlcmd = "SELECT * FROM manage WHERE account='$LoginID' AND valid='Y'";  //再次確認是否有這個人且還存在
$rs = querydb($sqlcmd, $db_conn);
if (count($rs) <= 0) die ('Unknown or invalid user!');
$PageTitle = '書籍查詢';
require_once ('../include/cssheader.php');

if (isset($action) && $action=='recover' && isset($id)) {
    // Recover this item
    $sqlcmd = "SELECT * FROM book WHERE id='$id' AND valid='N'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
        $sqlcmd = "UPDATE book SET valid='Y' WHERE id='$id'";
        $result = updatedb($sqlcmd, $db_conn);
    }
}
if (isset($action) && $action=='delete' && isset($id)) {
    // Invalid this item
    $sqlcmd = "SELECT * FROM book WHERE id='$id' AND valid='Y'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
        $sqlcmd = "UPDATE book SET valid='N' WHERE id='$id'";
        $result = updatedb($sqlcmd, $db_conn);
    }
}

$sqlcmd = "SELECT * FROM book WHERE valid='Y' ORDER BY date DESC";
$Contacts = querydb($sqlcmd, $db_conn);

$Warming = '';
$bookname = '';
$author = '';
$isbn = '';
$Id = '';
if(isset($_POST['bookname']) || isset($_POST['author']) || isset($_POST['isbn']) || isset($_POST['Id'])){
	if(!empty($_POST['bookname'])){
		$bookname = $_POST['bookname'];
		$sqlcmd = "SELECT * FROM book WHERE valid='Y' AND name='$bookname' ORDER BY date DESC";
		$arrSearch = querydb($sqlcmd, $db_conn);
		if (count($arrSearch) <= 0) {
			$bookname = '';
			$Warming = '搜尋字錯誤';
		}
	}else if(!empty($_POST['author'])){
		$author = $_POST['author'];
		$sqlcmd = "SELECT * FROM book WHERE valid='Y' AND author='$author' ORDER BY date DESC";
		$arrSearch = querydb($sqlcmd, $db_conn);
		if (count($arrSearch) <= 0) {
			$author = '';
			$Warming = '搜尋字錯誤';	
		}
	}else if(!empty($_POST['isbn'])){
		$isbn = $_POST['isbn'];
		$sqlcmd = "SELECT * FROM book WHERE valid='Y' AND ISBN='$isbn' ORDER BY date DESC";
		$arrSearch = querydb($sqlcmd, $db_conn);
		if (count($arrSearch) <= 0) {
			$isbn = '';
			$Warming = '搜尋字錯誤';	
		}
	}else if(!empty($_POST['Id'])){
		$Id = $_POST['Id'];
		$sqlcmd = "SELECT * FROM book WHERE valid='Y' AND ID='$Id' ORDER BY date DESC";
		$arrSearch = querydb($sqlcmd, $db_conn);
		if (count($arrSearch) <= 0) {
			$Id = '';
			$Warming = '搜尋字錯誤';	
		}
	}
}else{
	$Warming = '請輸入搜尋字';
}
?>
<HEAD>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<title>書籍查詢</title>
</HEAD>
<body>

<div style="text-align:center;margin-top:5px;font-size:40px;font-weight:bold;">
藍天租書店</div>
<div style="text-align:center;margin-top:10px;font-size:30px;">
書籍查詢
</div>

<table align="center">
<tr>
	<form method="POST" action="">
	<td>書名：</td>
	<td><input type="text" name="bookname" size="15" value="<?php echo $bookname; ?>"></td>
	<td>作者：</td>
	<td><input type="text" name="author" size="15" value="<?php echo $author; ?>"></td>
</tr>
<tr>	
	
	<td>ISBN：</td>
	<td><input type="text" name="isbn" size="15" value="<?php echo $isbn; ?>"></td>
	<td>書編號：</td>
	<td><input type="text" name="Id" size="15" value="<?php echo $Id; ?>"></td>
</tr>
<tr>
	<td><input type="submit" name="Send" value="搜尋"></td>
	
	</form> 
	<td><input type ="button" onclick="self.location.href='insertbook.php'" value="新增書籍"></input></td>&nbsp;&nbsp;&nbsp;
	
	
</tr>
</table>

<table border="0" width="90%" align="center" cellspacing="0" cellpadding="2">
<tr>
  <td width="50%" align="center">
  <?php
		if(!empty($Warming))
			echo $Warming;
	?>
  </td>
  <td align="right" width="30%">
	<input type ="button" onclick="self.location.href='catagram.php'" value="回目錄"></input>
  </td>
</tr>
</table>

<div style="text-align:center;">
<table class="mistab" width="90%" align="center">
<tr>
  <th width="11%">處理</th>
  <th>書名</th>
  <th width="15%">作者</th>
  <th width="15%">ISBN</th>
  <th width="12%">出版日期</th>
  <th width="6%">金額</th>
  <th width="3%">編號</th>
  <th width="2%">借出</th>
</tr>
<?php
if(empty($bookname) && empty($author) && empty($isbn) && empty($Id)){  
foreach ($Contacts AS $item) {
  $id = $item['ID'];
  $Name = $item['name'];
  $Author = $item['author'];
  $ISBN = $item['ISBN'];
  $Date = $item['date'];
  $Money = $item['money'];
  $Valid = $item['valid'];
  $Rental = $item['rental'];
  $DspMsg = "'確定刪除項目?'";
  $PassArg = "'searchbook.php?action=delete&id=$id'";
?>
<tr align="center">
  <td>
<?php
  if ($Valid=='N') {
?>
  <a href="searchbook.php?action=recover&id=<?php echo $id; ?>">
    回復
    </a></td>
  <td><STRIKE><?php echo $Name ?></STRIKE></td>
<?php } else { ?>
  <a href="javascript:confirmation(<?php echo $DspMsg ?>, <?php echo $PassArg ?>)">
  作廢</a>&nbsp;
  <a href="bookmod.php?id=<?php echo $id; ?>">
  修改</a>
  </td>
  <td align="left"><?php echo $Name ?></td>   
<?php } ?>
  <td><?php echo $Author ?></td>  
  <td><?php echo $ISBN ?></td>
  <td><?php echo $Date ?></td>
  <td><?php echo $Money ?></td> 
  <td><?php echo $id ?></td>
  <td><?php echo $Rental ?></td>
</tr>
<?php
} //foreach over
}else{
foreach ($arrSearch AS $item) {
  $id = $item['ID'];
  $Name = $item['name'];
  $Author = $item['author'];
  $ISBN = $item['ISBN'];
  $Date = $item['date'];
  $Money = $item['money'];
  $Valid = $item['valid'];
  $Rental = $item['rental'];
  $DspMsg = "'確定刪除項目?'";
  $PassArg = "'searchbook.php?action=delete&id=$id'";
?>
<tr align="center">
  <td>
<?php
  if ($Valid=='N') {
?>
  <a href="searchbook.php?action=recover&id=<?php echo $id; ?>">
    回復
    </a></td>
  <td><STRIKE><?php echo $Name ?></STRIKE></td>
<?php } else { ?>
  <a href="javascript:confirmation(<?php echo $DspMsg ?>, <?php echo $PassArg ?>)">
  作廢</a>&nbsp;
  <a href="contactmod.php?id=<?php echo $id; ?>">
  修改</a>
  </td>
  <td align="left"><?php echo $Name ?></td>   
<?php } ?>
  <td><?php echo $Author ?></td>  
  <td><?php echo $ISBN ?></td>
  <td><?php echo $Date ?></td>
  <td><?php echo $Money ?></td>
  <td><?php echo $id ?></td>
  <td><?php echo $Rental ?></td>
</tr>
<?php
} //foreach over
}
?>
</table>

</div>
</body>
<script Language="javascript">
<!--
function confirmation(DspMsg, PassArg) {
var name = confirm(DspMsg)
    if (name == true) {
      location=PassArg;
    }
}
-->
</script>
</html>
