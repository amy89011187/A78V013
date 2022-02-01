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

if(isset($_POST["action"])&&($_POST["action"]=="join")){
	require_once("connMysql.php");
	//找尋帳號是否已經註冊
	$query_RecFindUser = "SELECT m_username FROM memberdata WHERE m_username='{$_POST["m_username"]}'";
	$RecFindUser=$db_link->query($query_RecFindUser);
	if ($RecFindUser->num_rows>0){
		header("Location: member_join.php?errMsg=1&username={$_POST["m_username"]}");
	}else{
	//若沒有執行新增的動作	
		$query_insert = "INSERT INTO memberdata (m_name, m_username, m_passwd, m_sex, m_birthday, m_email, m_url, m_phone, m_address, m_jointime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
		$stmt = $db_link->prepare($query_insert);
		$stmt->bind_param("sssssssss", 
			GetSQLValueString($_POST["m_name"], 'string'),
			GetSQLValueString($_POST["m_username"], 'string'),
			password_hash($_POST["m_passwd"], PASSWORD_DEFAULT),
			GetSQLValueString($_POST["m_sex"], 'string'),
			GetSQLValueString($_POST["m_birthday"], 'string'),
			GetSQLValueString($_POST["m_email"], 'email'),
			GetSQLValueString($_POST["m_url"], 'url'),
			GetSQLValueString($_POST["m_phone"], 'string'),
			GetSQLValueString($_POST["m_address"], 'string'));
		$stmt->execute();
		$stmt->close();
		$db_link->close();
		header("Location: member_join.php?loginStats=1");
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>77vagetables</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function checkForm(){
	if(document.formJoin.m_username.value==""){		
		alert("請填寫帳號!");
		document.formJoin.m_username.focus();
		return false;
	}else{
		uid=document.formJoin.m_username.value;
		if(uid.length<5 || uid.length>20){
			alert( "您的帳號長度只能5至20個字元!" );
			document.formJoin.m_username.focus();
			return false;}
		for(idx=0;idx<uid.length;idx++){
			if(!(( uid.charAt(idx)>='A'&&uid.charAt(idx)<='Z')||( uid.charAt(idx)>='a'&&uid.charAt(idx)<='z')||(uid.charAt(idx)>='0'&& uid.charAt(idx)<='9'))){
				alert( "您的帳號只能是數字,英文字母,其他的符號都不能使用!" );
				document.formJoin.m_username.focus();
				return false;}
		}
	}
	if(!check_passwd(document.formJoin.m_passwd.value,document.formJoin.m_passwdrecheck.value)){
		document.formJoin.m_passwd.focus();
		return false;}	
	if(document.formJoin.m_name.value==""){
		alert("姓名不得空白!");
		document.formJoin.m_name.focus();
		return false;}
	if(document.formJoin.m_birthday.value==""){
		alert("生日不得空白!");
		document.formJoin.m_birthday.focus();
		return false;}
	if(document.formJoin.m_email.value==""){
		alert("電子郵件不得空白!");
		document.formJoin.m_email.focus();
		return false;}
	if(document.formJoin.m_url.value==""){
		alert("身分證字號不得空白!");
		document.formJoin.m_url.focus();
		return false;}
	if(document.formJoin.m_phone.value==""){
		alert("電話不得空白!");
		document.formJoin.m_phone.focus();
		return false;}
	if(document.formJoin.m_address.value==""){
		alert("住址不得空白!");
		document.formJoin.m_address.focus();
		return false;}
	if(!checkmail(document.formJoin.m_email)){
		document.formJoin.m_email.focus();
		return false;}
	if(!checkbirthday(document.formJoin.m_birthday)){
		document.formJoin.m_birthday.focus();
		return false;}
	if(!checkurl(document.formJoin.m_url)){
		document.formJoin.m_url.focus();
		return false;}
	return confirm('確定送出嗎？');
}
function check_passwd(pw1,pw2){
	if(pw1==''){
		alert("密碼不可以空白!");
		return false;}
	for(var idx=0;idx<pw1.length;idx++){
		if(pw1.length<5 || pw1.length>12){
			alert( "密碼長度只能5到12個字母 !\n" );
			return false;}
		if(pw1!= pw2){
			alert("密碼二次輸入不一樣,請重新輸入 !\n");
			return false;}
	}
	return true;
}
function checkmail(myEmail) {
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(filter.test(myEmail.value)){
		return true;}
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
<?php if(isset($_GET["loginStats"]) && ($_GET["loginStats"]=="1")){?>
<script language="javascript">
alert('會員新增成功\n請用申請的帳號密碼登入。');
window.location.href='index.php';		  
</script>
<?php }?>
<table width="500" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr>
    <td class="tdbline"><img src="images/77.png" alt="77vagetables" width="200" height="70"></td>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="15">
      <tr valign="top">
        <form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
          <p class="title">填寫「會員」資料</p>
		  <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <div class="errDiv">帳號 <?php echo $_GET["username"];?> 已經有人使用！</div>
          <?php }?>
          
            <p class="heading">設定會員帳號及密碼</p>
            <p><strong>會員帳號　</strong>：
            <input name="m_username" type="text" class="normalinput" id="m_username">
            <br><span class="smalltext">請填入5~20個字元以內的小寫英文字母、數字。</span></p>
            <p><strong>會員密碼　</strong>：
            <input name="m_passwd" type="password" class="normalinput" id="m_passwd">
            <br><span class="smalltext">請填入5~12個字元以內的英文字母、數字、以及各種符號組合。</span></p>
            <p><strong>確認密碼　</strong>：
            <input name="m_passwdrecheck" type="password" class="normalinput" id="m_passwdrecheck">
            <br><span class="smalltext">再輸入一次密碼。</span></p>
            <hr size="2" />
            <p class="heading">會員個人基本資料</p>
            <p><strong>姓名　　　</strong>：
            <input name="m_name" type="text" class="normalinput" id="m_name">
            </font></p>
            <p><strong>性別　　　</strong>：
            <input name="m_sex" type="radio" value="女" checked>女
            <input name="m_sex" type="radio" value="男">男
            </font></p>
            <p><strong>生日　　　</strong>：
            <input name="m_birthday" type="text" class="normalinput" id="m_birthday">
            </font> <br><span class="smalltext">日期格式為YYYY-MM-DD。</span></p>
            <span class="smalltext"></span></p>
            <p><strong>電子郵件　</strong>：
            <input name="m_email" type="text" class="normalinput" id="m_email">
            </font><br><span class="smalltext">請確定此電子郵件為可使用狀態，以方便未來系統使用，如補寄會員密碼信。</span></p>
            <p><strong>身分證字號</strong>：
            <input name="m_url" type="text" class="normalinput" id="m_url">
            <p><strong>電話　　　</strong>：
            <input name="m_phone" type="text" class="normalinput" id="m_phone"></p>
            <p><strong>住址　　　</strong>：
            <input name="m_address" type="text" class="normalinput" id="m_address" size="40"></p>  
         
          
          <p align="center">
            <input name="action" type="hidden" id="action" value="join">
            <input type="submit" name="Submit2" value="送出申請">
            <input type="reset" name="Submit3" value="重設資料">
            <input type="button" name="Submit" value="回上一頁" onClick="window.history.back();">
          </p>
        </form></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" background="images/album_r2_c1.jpg" class="trademark">© 2020 77vagetables shop.</td>
  </tr>
</table>
</body>
</html>