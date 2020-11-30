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
$PageTitle = '逾期通知';

$sqlcmd = "SELECT book.name,book.author,client.name AS 'cname',client.phone,rental.returnd FROM rental,client,book WHERE rental.bookID=book.ID AND rental.clientID=client.ID AND rental.valid='Y' ORDER BY rental.returnd DESC";
$Contacts = querydb($sqlcmd, $db_conn);
if (count($Contacts) <= 0) die ('no recoding!');

require_once ('../include/cssheader.php');
?>
<html>
<HEAD>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<title>逾期通知</title>
</HEAD>
<body>

<div style="text-align:center;margin-top:5px;font-size:40px;font-weight:bold;">
藍天租書店</div>
<div style="text-align:center;margin-top:10px;font-size:30px;">
逾期通知
</div>
<div style="text-align:center"><br/><br/>
<input type ="button" onclick="self.location.href='catagram.php'" value="回目錄"></input>
<table class="mistab" width="90%" align="center">
<tr>
	<th width="20%">書籍</th>
	<th width="5%">作者</th>
	<th width="5%">會員</th>
	<th width="8%">電話</th>
	<th width="2%">天數</th>
</tr>
<?php
foreach ($Contacts AS $item) {
  $Bookname = $item['name'];
  $Bookauthor = $item['author'];
  $Clientname = $item['cname'];
  $Clientphone = $item['phone'];
  $Duedate = $item['returnd'];
  $Today = strftime("%Y-%m-%d");
  $Duedate = strtotime($Today)-strtotime($Duedate);
  $Duedate = $Duedate/86400;
  if($Duedate>0){
?>
<tr align="center">

  <td><?php echo $Bookname ?></td>  
  <td><?php echo $Bookauthor ?></td>
  <td><?php echo $Clientname ?></td> 
  <td><?php echo $Clientphone ?></td>
  <td><?php echo $Duedate ?></td>
</tr>
<?php
}
}
?>

</table>
</div>

</body>

</html>