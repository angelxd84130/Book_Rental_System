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
$PageTitle = '目錄';
require_once ('../include/cssheader.php');
?>
<HEAD>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<title>目錄</title>
</HEAD>
<body>

<div style="text-align:center;margin-top:5px;font-size:40px;font-weight:bold;">
藍天租書店</div>
<div style="text-align:center;margin-top:10px;font-size:30px;">
目錄
</div>
<div style="text-align:center"><br/><br/>
<input type="button" value="書籍查詢" style="width:120px;height:40px;font-size:20px;" onclick="self.location.href='searchbook.php'"></input><br/><br/>
<input type="button" value="客戶查詢" style="width:120px;height:40px;font-size:20px;" onclick="self.location.href='searchclient.php'"></input><br/><br/>
<input type="button" value="租借查詢" style="width:120px;height:40px;font-size:20px;" onclick="self.location.href='rental.php'"></input><br/><br/>
<input type="button" value="逾期通知" style="width:120px;height:40px;font-size:20px;" onclick="self.location.href='overdue.php'"></input><br/><br/>

</div>

</body>

</html>