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
$PageTitle = '租借查詢';
require_once ('../include/cssheader.php');

if (isset($action) && $action=='recover' && isset($id)) {
    // Recover this item
    $sqlcmd = "SELECT * FROM rental WHERE id='$id' AND valid='N'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
        $sqlcmd = "UPDATE client SET valid='Y' WHERE id='$id'";
        $result = updatedb($sqlcmd, $db_conn);
    }
}
if (isset($action) && $action=='delete' && isset($id)) {
    // Invalid this item
    $sqlcmd = "SELECT * FROM rental WHERE id='$id' AND valid='Y'";
    $rs = querydb($sqlcmd, $db_conn);
    if (count($rs) > 0) {
        $sqlcmd = "UPDATE rental SET valid='N' WHERE id='$id'";
        $result = updatedb($sqlcmd, $db_conn);
    }
}

//找書
$sqlcmd = "SELECT * FROM book";
$rs = querydb($sqlcmd, $db_conn);
$arrBook = array();
$arrBookID = array();
if (count($rs) > 0) {
	foreach ($rs as $item) {
        $BookID = $item['ID'];
        $arrBook["$BookID"] = $item['name'];
		$arrBookID["$BookID"] = $item['barcode'];
    }
}

//找客戶
$sqlcmd = "SELECT * FROM client";
$rs = querydb($sqlcmd, $db_conn);
$arrClient = array();
if (count($rs) > 0) {
	foreach ($rs as $item) {
        $ClientID = $item['ID'];
        $arrClient["$ClientID"] = $item['name'];
    }
}
 

$sqlcmd = "SELECT * FROM rental WHERE valid='Y' ORDER BY ID DESC";
$Contacts = querydb($sqlcmd, $db_conn);

$Warming = '';
$bookname = '';
$identity = '';
$Day = '';
if(isset($_POST['bookname']) || isset($_POST['identity']) || isset($_POST['Day'])){
	if(!empty($_POST['bookname'])){
		$bookname = $_POST['bookname'];
		$sqlcmd = "SELECT * FROM book WHERE valid='Y' AND name='$bookname'";
		$arrSearch = querydb($sqlcmd, $db_conn);
		if (count($arrSearch) <= 0) {
			$bookname = '';
			$Warming = '搜尋字錯誤';
		}
	}else if(!empty($_POST['identity'])){
		$identity = $_POST['identity'];
		$sqlcmd = "SELECT * FROM client WHERE valid='Y' AND identity='$identity'";
		$arrSearch = querydb($sqlcmd, $db_conn);
		if (count($arrSearch) <= 0) {
			$identity = '';
			$Warming = '搜尋字錯誤';
		}
	}else if(!empty($_POST['Day'])){
		$Day = $_POST['Day'];
		$sqlcmd = "SELECT * FROM rental WHERE valid='Y' AND returnd='$Day'";
		$arrSearch = querydb($sqlcmd, $db_conn);
		if (count($arrSearch) <= 0) {
			$identity = '';
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
<title>租借查詢</title>
</HEAD>
<body>

<div style="text-align:center;margin-top:5px;font-size:40px;font-weight:bold;">
藍天租書店</div>
<div style="text-align:center;margin-top:10px;font-size:30px;">
租借查詢
</div>

<table align="center">
<tr>
	<form method="POST" action="">
	<td>書名：</td>
	<td><input type="text" name="bookname" size="15" value="<?php echo $bookname; ?>"></td>
	<td>客戶身分證號碼：</td>
	<td><input type="text" name="identity" size="10" maxlength="10" value="<?php echo $identity; ?>"></td>
	<td>日期：</td>
	<td><input type="text" name="Day" size="10" maxlength="10" value="<?php echo $Day; ?>"></td>
	<td>Y-M-D</td>
</tr>
<tr>
	<td><input type="submit" name="Send" value="搜尋">&nbsp;
	</form> 
	</td>
	<td><input type ="button" onclick="self.location.href='insertrental.php'" value="新增租借"></input>&nbsp;&nbsp;&nbsp;
	</td>
</tr>
</table>

<table border="0" width="90%" align="center" cellspacing="0" cellpadding="2">
<tr>
  <td width="50%" align="center">
	<?php
		if(!empty($Warming))
			echo $Warming;
	?>
  <td>
  <td align="right" width="30%">
	<input type ="button" onclick="self.location.href='catagram.php'" value="回目錄"></input>
  </td>
</tr>
</table>

<div style="text-align:center;">
<table class="mistab" width="90%" align="center">
<tr>
  <th width="15%">處理</th>
  <th>書名</th>
  <th width="3%">書ID</th>
  <th width="15%">人名</th>
  <th width="15%">歸還日期</th>
  <th width="12%">金額</th>
  <th width="12%">罰金</th>
</tr>
<?php
if(empty($bookname) && empty($identity) && empty($Day)){
foreach ($Contacts AS $item) {
  $id = $item['ID'];
  $BookID = $item['bookID'];
  $Bookname = '';
  $Barcode = '';
  $Duedate = '';
  if(isset($BookID)){
		$Bookname = $arrBook["$BookID"];
  }
  if(isset($BookID)){
		$Barcode = $arrBookID["$BookID"];
   }
  $ClientID = $item['clientID'];
  $Clientname = '';
  if(isset($ClientID)){
		$Clientname = $arrClient["$ClientID"];
	}
  $Returnd = $item['returnd'];
  $Money = $item['money'];
  $Valid = $item['valid'];
  $Duedate = $item['returnd'];
  $DspMsg = "'確定刪除項目?'";
  $PassArg = "'searchbook.php?action=delete&id=$id'";
  $Today = strftime("%Y-%m-%d");
  $Duedate = strtotime($Today)-strtotime($Duedate);
  $Due = 0;
  $Duedate = $Duedate/86400;
  if($Duedate>0) $Due = $Duedate * $Money;
?>
<tr align="center">
  <td>
<?php
  if ($Valid=='N') {
?>
  <a href="rental.php?action=recover&id=<?php echo $id; ?>">
    回復
    </a></td>
  <td><STRIKE><?php echo $Name ?></STRIKE></td>
<?php } else { ?>
  <a href="rentaldel.php?id=<?php echo $id;?>&BookID=<?php echo $BookID; ?>">
  作廢</a>&nbsp;
  <a href="return.php?id=<?php echo $id; ?>&BookID=<?php echo $BookID; ?>">
  還書</a>
  </td>
  <td align="left"><?php echo $Bookname ?></td>   
<?php } ?>
  <td><?php echo $BookID ?></td>  
  <td><?php echo $Clientname ?></td>
  <td><?php echo $Returnd ?></td> 
  <td><?php echo $Money ?></td> 
  <td><?php echo $Due ?></td>
</tr>
<?php
}//foreach over
}else{	//search rental
  foreach($arrSearch AS $items){
  if(!empty($bookname))
	$bookid = $items['ID'];

  if(!empty($identity))
	$clientid = $items['ID'];
	
  if(!empty($Day)){
	$Id = $items['ID'];
  }
	
  foreach ($Contacts AS $item) {
  $id = $item['ID'];
  $BookID = $item['bookID'];
  $ClientID = $item['clientID'];
  $Returnd = $item['returnd'];
  $Money = $item['money'];
  if(!empty($bookid)){  
	  if($bookid == $BookID){
		  $Bookname = '';
		  $Barcode = '';
		  if(isset($BookID)){
			$Bookname = $arrBook["$BookID"];
		  }
		  if(isset($BookID)){
			$Barcode = $arrBookID["$BookID"];	
		   }
		  
		  $Clientname = '';
		  if(isset($ClientID)){
			$Clientname = $arrClient["$ClientID"];
			}
		  $Returnd = $item['returnd'];
		  $Money = $item['money'];
		  $Valid = $item['valid'];
		  $DspMsg = "'確定刪除項目?'";
		  $PassArg = "'searchbook.php?action=delete&id=$id'";
	    }
    }

	if(!empty($identity)){
		if($clientid == $ClientID){
			$Bookname = '';
			$Barcode = '';
			if(isset($BookID)){
				$Bookname = $arrBook["$BookID"];
			}
			if(isset($BookID)){
				$Barcode = $arrBookID["$BookID"];
			}
		  
			$Clientname = '';
			if(isset($ClientID)){
				$Clientname = $arrClient["$ClientID"];
			}
			$Returnd = $item['returnd'];
			$Money = $item['money'];
			$Valid = $item['valid'];
			$DspMsg = "'確定刪除項目?'";
			$PassArg = "'searchbook.php?action=delete&id=$id'";
		}
	}
	
	if(!empty($Day)){
		if($Id == $id){
			$Bookname = '';
			$Barcode = '';
			if(isset($BookID)){
				$Bookname = $arrBook["$BookID"];
			}
			if(isset($BookID)){
				$Barcode = $arrBookID["$BookID"];
			}
		  
			$Clientname = '';
			if(isset($ClientID)){
				$Clientname = $arrClient["$ClientID"];
			}
			$Returnd = $item['returnd'];
			$Money = $item['money'];
			$Valid = $item['valid'];
			$DspMsg = "'確定刪除項目?'";
			$PassArg = "'searchbook.php?action=delete&id=$id'";
		}
	}
	$Duedate = $item['returnd'];
	$Today = strftime("%Y-%m-%d");
    $Duedate = strtotime($Today)-strtotime($Duedate);
    $Due = 0;
    $Duedate = $Duedate/86400;
    if($Duedate>0) $Due = $Duedate * $Money;
}
// foreach contact over
?>
<tr align="center">
  <td>
<?php
  if ($Valid=='N') {
?>
  <a href="rental.php?action=recover&id=<?php echo $id; ?>">
    回復
    </a></td>
  <td><STRIKE><?php echo $Name ?></STRIKE></td>
<?php } else { ?>
  <a href="rentaldel.php?id=<?php echo $id; ?>&BookID=<?php echo $BookID; ?>">
  作廢</a>&nbsp;
  <a href="contactmod.php?id=<?php echo $id; ?>&BookID=<?php echo $BookID; ?>">
  修改</a>
  </td>
  <td align="left"><?php echo $Bookname ?></td>   
<?php } ?>
  <td><?php echo $BookID ?></td>  
  <td><?php echo $Clientname ?></td>
  <td><?php echo $Returnd ?></td> 
  <td><?php echo $Money ?></td>   
  <td><?php echo $Due ?></td>  
</tr>
<?php
}// foreach arrSearch over
}// if over
?>
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
