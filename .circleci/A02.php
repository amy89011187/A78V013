<?php
function GetSQLValueString($theValue, $theType) {
  switch ($theType) {
    case "string":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_MAGIC_QUOTES) : "";
      break;
    case "int":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
      break;
    case "email":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_EMAIL) : "";
      break;      
  }
  return $theValue;
}
require_once("connMysql.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: index.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: index.php");
}
//重新導向頁面
$redirectUrl="member_center.php";
//執行更新動作
if(isset($_POST["action"])&&($_POST["action"]=="update")){	
	$query_update = "UPDATE memberdata SET m_passwd=?, m_name=?, m_sex=?, m_birthday=?, m_email=?, m_url=?, m_phone=?, m_address=? WHERE m_id=?";
	$stmt = $db_link->prepare($query_update);
	//檢查是否有修改密碼
	$mpass = $_POST["m_passwdo"];
	if(($_POST["m_passwd"]!="")&&($_POST["m_passwd"]==$_POST["m_passwdrecheck"])){
		$mpass = password_hash($_POST["m_passwd"], PASSWORD_DEFAULT);
	}
	$stmt->bind_param("ssssssssi", 
		$mpass,
		GetSQLValueString($_POST["m_name"], 'string'),
		GetSQLValueString($_POST["m_sex"], 'string'),		
		GetSQLValueString($_POST["m_birthday"], 'string'),
		GetSQLValueString($_POST["m_email"], 'email'),
		GetSQLValueString($_POST["m_url"], 'url'),
		GetSQLValueString($_POST["m_phone"], 'string'),
		GetSQLValueString($_POST["m_address"], 'string'),		
		GetSQLValueString($_POST["m_id"], 'int'));
	$stmt->execute();
	$stmt->close();
	//若有修改密碼，則登出回到首頁。
	if(($_POST["m_passwd"]!="")&&($_POST["m_passwd"]==$_POST["m_passwdrecheck"])){
		unset($_SESSION["loginMember"]);
		unset($_SESSION["memberLevel"]);
		$redirectUrl="index.php";
	}		
	//重新導向
	header("Location: $redirectUrl");
}

//繫結登入會員資料
$query_RecMember = "SELECT * FROM memberdata WHERE m_username='{$_SESSION["loginMember"]}'";
$RecMember = $db_link->query($query_RecMember);	
$row_RecMember = $RecMember->fetch_assoc();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>77會員中心</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="50%" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="80" class="tdbline"><img src="images/77.png"><p align="right"><a href="member_center.php">首頁</a> | <a href="A02.php">會員中心</a> | <a href="?logout=true">登出</a></p></td>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr valign="top">
         <td width="200" class="tdrline"><div class="boxtl"></div>
            <div class="boxtr"></div>
            <div class="categorybox">
            </div>
            <div class="boxtl"></div>
            <div class="boxtr"></div>
            <div class="categorybox">
              <p class="title" align="center" >會員中心</p><p><span class="smalltext">　會員帳號：<?php echo $row_RecMember["m_username"];?></span></p>
              <ul>
                <li><a href="A03.php">我的基本資料</a></li>
				<p><a href="A05.php">修改資料</a></p>
				<li><a href="1.php">購買清單</a></li> 
                <p><a href="1.php">購物車</a></li></p>
				<p><a href="1.php">購買紀錄</a></li></p>
				<li><a href="1.php">優惠券</a></li>
				</ul>
				</div>
				<td><div class="subjectDiv"></div>
				<p align="center"><font color="#ff8000" size="5">歡迎光臨77vagetables</font></p>
				<hr>
				<p><h4>會員中心可以查看以下資訊：</h4>
				<p>　　　● 我的基本資料 ：查看個人資料</p>
				<p>　　　● 修改資料　　 ：修改個人資料</p>
				<p>　　　● 購買清單　　 ：已下單的清單</p>
				<p>　　　● 購物車　　　 ：放在購物車還未結帳的清單</p>
				<p>　　　● 購買紀錄　　 ：查看歷史購買紀錄。</p>
				<p>　　　● 優惠券　　　 ：個人優惠券，例如免運費等。</p>
      </table></td>
  </tr>
  <tr>
    <td height="30" align="center" background="images/album_r2_c1.jpg" class="trademark">© 2020 77vagetables shop.</td>
  </tr>
</table>
</body>
</html>
