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
<title>77修改個人資料</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function checkForm(){
	if(document.formJoin.m_passwd.value!="" || document.formJoin.m_passwdrecheck.value!=""){
		if(!check_passwd(document.formJoin.m_passwd.value,document.formJoin.m_passwdrecheck.value)){
			document.formJoin.m_passwd.focus();
			return false;
		}
	}	
	if(document.formJoin.m_name.value==""){
		alert("姓名不得空白!");
		document.formJoin.m_name.focus();
		return false;
	}
	if(document.formJoin.m_birthday.value==""){
		alert("生日不得空白!");
		document.formJoin.m_birthday.focus();
		return false;
	}
	if(document.formJoin.m_email.value==""){
		alert("電子郵件不得空白!");
		document.formJoin.m_email.focus();
		return false;
	}
	if(document.formJoin.m_url.value==""){
		alert("身分證字號不得空白!");
		document.formJoin.m_url.focus();
		return false;
	}
	if(document.formJoin.m_phone.value==""){
		alert("電話不得空白!");
		document.formJoin.m_phone.focus();
		return false;
	}
	if(document.formJoin.m_address.value==""){
		alert("住址不得空白!");
		document.formJoin.m_address.focus();
		return false;
	}
	if(!checkmail(document.formJoin.m_email)){
		document.formJoin.m_email.focus();
		return false;
	}
	if(!checkbirthday(document.formJoin.m_birthday)){
		document.formJoin.m_birthday.focus();
		return false;
	}
	if(!checkurl(document.formJoin.m_url)){
		document.formJoin.m_url.focus();
		return false;
	}
	return confirm('確定送出嗎？');
}
function check_passwd(pw1,pw2){
	if(pw1==''){
		alert("密碼不可以空白!");
		return false;
	}
	for(var idx=0;idx<pw1.length;idx++){
		if(pw1.charAt(idx) == ' ' || pw1.charAt(idx) == '\"'){
			alert("密碼不可以含有空白或雙引號 !\n");
			return false;
		}
		if(pw1.length<5 || pw1.length>12){
			alert( "密碼長度只能5到12個字母 !\n" );
			return false;
		}
		if(pw1!= pw2){
			alert("密碼二次輸入不一樣,請重新輸入 !\n");
			return false;
		}
	}
	return true;
}
function checkmail(myEmail) {
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(filter.test(myEmail.value)){
		return true;
	}
	alert("電子郵件格式不正確");
	return false;
}
function checkbirthday(mybirthday) {
	var filter  = /^[0-9]{4}[-][0-9]{2}[-][0-9]{2}/;
	if(filter.test(mybirthday.value)){
		return true;}
	alert("日期格式為 YYYY-MM-DD");
	return false;
}
function checkurl(myurl) {
	var filter  = /^[A-Z]{1}[0-9]{9}/;
	if(filter.test(myurl.value)){
		return true;}
	alert("身分證格式錯誤!");
	return false;
}
</script>
</head>
<body>
<table width="50%" border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="80" class="tdbline"><img src="images/77.png"><p align="right"><a href="member_center.php">首頁</a> | <a href="A02.php">會員中心</a> | <a href="?logout=true">登出</a></p></td>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr valign="top">
		<form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
         <td width="200" class="tdrline"><div class="boxtl"></div>
            <div class="boxtr"></div>
            <div class="categorybox">
            </div>
            <div class="boxtl"></div>
            <div class="boxtr"></div>
            <div class="categorybox">
              <p class="title" align="center">會員中心<p><span class="smalltext">　會員帳號：<?php echo $row_RecMember["m_username"];?></span></p>
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
			<p class="heading">帳號資料</p>
            <p><strong>會員帳號</strong>：<?php echo $row_RecMember["m_username"];?></p>
            <p><strong>會員密碼</strong>：
            <input name="m_passwd" type="password" class="normalinput" id="m_passwd">
    		<input name="m_passwdo" type="hidden" id="m_passwdo" value="<?php echo $row_RecMember["m_passwd"];?>"></p>
            <p><strong>確認密碼</strong> ：
            <input name="m_passwdrecheck" type="password" class="normalinput" id="m_passwdrecheck"><br>
            <span class="smalltext">若不修改密碼，請不要填寫。若要修改，請輸入密碼</span><span class="smalltext">二次。<br>若修改密碼，系統會自動登出，請用新密碼登入。</span></p>
            <hr size="2" />
            <p class="heading">個人資料</p>
            <p><strong>真實姓名</strong>：
            <input name="m_name" type="text" class="normalinput" id="m_name" value="<?php echo $row_RecMember["m_name"];?>">
            <font color="#FF0000">*</font></p>
            <p><strong>性　　別</strong>：
            <input name="m_sex" type="radio" value="女" <?php if($row_RecMember["m_sex"]=="女") echo "checked";?>>女
    		<input name="m_sex" type="radio" value="男" <?php if($row_RecMember["m_sex"]=="男") echo "checked";?>>男
            <font color="#FF0000">*</font></p>
            <p><strong>生　　日</strong>：
            <input name="m_birthday" type="text" class="normalinput" id="m_birthday" value="<?php echo $row_RecMember["m_birthday"];?>">
            <font color="#FF0000">*</font><br><span class="smalltext">為西元格式(YYYY-MM-DD)。</span></p>
            <p><strong>電子郵件</strong>：
            <input name="m_email" type="text" class="normalinput" id="m_email" value="<?php echo $row_RecMember["m_email"];?>">
            <font color="#FF0000">*</font><br><span class="smalltext">請確定此電子郵件為可使用狀態，以方便未來系統使用，如補寄會員密碼信。</span></p>
			<p><strong>身分證字號</strong>：
            <input name="m_url" type="text" class="normalinput" id="m_url" value="<?php echo $row_RecMember["m_url"];?>"></p>
            <p><strong>電　　話</strong>：
            <input name="m_phone" type="text" class="normalinput" id="m_phone" value="<?php echo $row_RecMember["m_phone"];?>"></p>
            <p><strong>住　　址</strong>：
            <input name="m_address" type="text" class="normalinput" id="m_address" value="<?php echo $row_RecMember["m_address"];?>" size="40"> </p>
            </div>
          <hr size="2" />
          <p align="center">
            <input name="m_id" type="hidden" id="m_id" value="<?php echo $row_RecMember["m_id"];?>">
            <input name="action" type="hidden" id="action" value="update">
            <input type="submit" name="Submit2" value="修改資料">
            <input type="reset" name="Submit3" value="重設資料">
          </p>
      </table></td>
  </tr>
  <tr>
    <td height="30" align="center" background="images/album_r2_c1.jpg" class="trademark">© 2020 77vagetables shop.</td>
  </tr>
</table>
</body>
</html>
