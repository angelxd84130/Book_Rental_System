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
$PageTitle = '客戶查詢';
require_once ('../include/cssheader.php');

if (isset($action) && $action=='recover' && isset($id)) {
    // Recover this item
    $sqlcmd = "SELECT * FROM client WHERE id='$id' AND valid='N'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
        $sqlcmd = "UPDATE client SET valid='Y' WHERE id='$id'";
        $result = updatedb($sqlcmd, $db_conn);
    }
}
if (isset($action) && $action=='delete' && isset($id)) {
    // Invalid this item
    $sqlcmd = "SELECT * FROM client WHERE id='$id' AND valid='Y'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
        $sqlcmd = "UPDATE client SET valid='N' WHERE id='$id'";
        $result = updatedb($sqlcmd, $db_conn);
    }
}

$sqlcmd = "SELECT * FROM client WHERE valid='Y'";
$Contacts = querydb($sqlcmd, $db_conn);

$Warming = '';
$identity = '';
if(isset($_POST['identity'])){
	$identity = $_POST['identity'];
	$sqlcmd = "SELECT * FROM client WHERE valid='Y' AND identity='$identity'";
	$arrSearch = querydb($sqlcmd, $db_conn);
	if (count($arrSearch) <= 0) {
		$identity = '';
		$Warming = '搜尋字錯誤';
		
	}
	if(empty($_POST['identity'])){
		$Warming = '';
	}
}
?>
<HEAD>
<meta HTTP-EQUIV="Content-Type" content="text/html; charset=utf8">
<meta HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<meta HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link rel="stylesheet" title="Default" href="../css/i4010.css" type="text/css" />
<title>客戶查詢</title>
</HEAD>
<body>

<div style="text-align:center;margin-top:5px;font-size:40px;font-weight:bold;">
藍天租書店</div>
<div style="text-align:center;margin-top:10px;font-size:30px;">
客戶查詢
</div>
<table align="center">
<tr>
	<form method="POST" action="">
	<td>身分證號碼：</td>
	<td><input type="text" name="identity" size="16" maxlength="10" value="<?php echo $identity; ?>"></td>
</tr>
<tr>
	<td><input type="submit" name="Send" value="搜尋">&nbsp;
	</form> 
	</td>
	<td><input type ="button" onclick="self.location.href='insertclient.php'" value="新增客戶"></input>&nbsp;&nbsp;&nbsp;
	<?php
		if(!empty($Warming))
			echo $Warming;
	?>
	</td>
</tr>
</table>
<table border="0" width="90%" align="center" cellspacing="0" cellpadding="2">
<tr>
  <td align="right" width="30%">
	<input type ="button" onclick="self.location.href='catagram.php'" value="回目錄"></input>
  </td>
</tr>
</table>

<?php
if(!empty($identity)){?>
<div style="text-align:center;">
<table class="mistab" width="90%" align="center">
<tr>
  <th width="15%">處理</th>
  <th width="15%">姓名</th>
  <th width="15%">ID</th>
  <th>地址</th>
  <th width="15%">電話</th>
  <th width="12%">金額</th>
</tr>
<?php
  foreach ($arrSearch AS $item) {
  $id = $item['ID'];
  $Name = $item['name'];
  $Identity = $item['identity'];
  $Address = $item['address'];
  $Phone = $item['phone'];
  $Money = $item['money'];
  $Valid = $item['valid'];
  $DspMsg = "'確定刪除項目?'";
  $PassArg = "'searchclient.php?action=delete&id=$id'";
?>
<tr align="center">
  <td>
<?php
  if ($Valid=='N') {
?>
  <a href="searchclient.php?action=recover&id=<?php echo $id; ?>">
    回復
    </a></td>
  <td><STRIKE><?php echo $Name ?></STRIKE></td>
<?php } else { ?>
  <a href="javascript:confirmation(<?php echo $DspMsg ?>, <?php echo $PassArg ?>)">
  作廢</a>&nbsp;
  <a href="contactmod.php?id=<?php echo $id; ?>">
  修改</a>
  </td>
  <td><?php echo $Name ?></td>   
<?php } ?>
  <td><?php echo $Identity ?></td>  
  <td align="left"><?php echo $Address ?></td>
  <td><?php echo $Phone ?></td> 
  <td><?php echo $Money ?></td>        
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