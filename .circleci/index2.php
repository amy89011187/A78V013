<?php
require_once("connMysql.php");
session_start();
//檢查是否經過登入，若有登入則重新導向
if(isset($_SESSION["loginMember"]) && ($_SESSION["loginMember"]!="")){
	//若帳號等級為 member 則導向會員中心
	if($_SESSION["memberLevel"]=="member"){
		header("Location: member_center.php");
	//否則則導向管理中心
	}else{
		header("Location: member_admin.php");	
	}
}
//執行會員登入
if(isset($_POST["username"]) && isset($_POST["passwd"])){
	//繫結登入會員資料
	$query_RecLogin = "SELECT m_username, m_passwd, m_level FROM memberdata WHERE m_username=?";
	$stmt=$db_link->prepare($query_RecLogin);
	$stmt->bind_param("s", $_POST["username"]);
	$stmt->execute();
	//取出帳號密碼的值綁定結果
	$stmt->bind_result($username, $passwd, $level);	
	$stmt->fetch();
	$stmt->close();
	//比對密碼，若登入成功則呈現登入狀態
	if(password_verify($_POST["passwd"],$passwd)){
		//計算登入次數及更新登入時間
		$query_RecLoginUpdate = "UPDATE memberdata SET m_login=m_login+1, m_logintime=NOW() WHERE m_username=?";
		$stmt=$db_link->prepare($query_RecLoginUpdate);
	    $stmt->bind_param("s", $username);
	    $stmt->execute();	
	    $stmt->close();
		//設定登入者的名稱及等級
		$_SESSION["loginMember"]=$username;
		$_SESSION["memberLevel"]=$level;
		//使用Cookie記錄登入資料
		if(isset($_POST["rememberme"])&&($_POST["rememberme"]=="true")){
			setcookie("remUser", $_POST["username"], time()+365*24*60);
			setcookie("remPass", $_POST["passwd"], time()+365*24*60);
		}else{
			if(isset($_COOKIE["remUser"])){
				setcookie("remUser", $_POST["username"], time()-100);
				setcookie("remPass", $_POST["passwd"], time()-100);
			}
		}
		//若帳號等級為 member 則導向會員中心
		if($_SESSION["memberLevel"]=="member"){
			header("Location: member_center.php");
		//否則則導向管理中心
		}else{
			header("Location: member_admin.php");	
		}
	}else{
		header("Location: index.php?errMsg=1");
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>77vagetables</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>

<table width="500" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td class="tdbline"><img src="images/77.png" alt="77vagetables" width="200" height="70"></td>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="15" >
        <td width="500">
        <div class="boxtl"></div><div class="boxtr"></div>
		<div style="background-color:#ffac05;"><font size="5" face="華康儷中黑"><table style="margin-bottom:20px"><center>會　員　登　入</center></table></font></p></div>
		
<div class="A01"><table width="50%">
<div class="regbox"><?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <div class="errDiv"> 登入帳號或密碼錯誤！</div>
          <?php }?>
          <center><img src="images/77.png" alt="77vagetables" width="80" height="30"></center>
		  <p class="heading">登入</p>
          <form name="form1" method="post" action="">
            <p>帳號：              
              <input name="username" type="text" class="logintextbox" id="username" value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">
            </p>
            <p>密碼：
              <input name="passwd" type="password" class="logintextbox" id="passwd" value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">
            </p>
            <p>
              <input name="rememberme" type="checkbox" id="rememberme" value="true" checked style="margin-top:10px">
記住我的帳號密碼。</p>
            <p align="center">
              <input type="submit" name="button" id="button" style="margin-top:10px" value="登入">
            </p>
            </form>
          
          <hr noshade style="margin-top:20px">
		  <a style="float:left;" href="admin_passmail.php">忘記密碼</a><a href="member_join2.php">　　　　 加入商家</a><a style="float:right;" href="member_join.php">加入會員</a></p>
          
</div>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" background="images/album_r2_c1.jpg" class="trademark">© 2020 77vagetables shop.</td>
  </tr>
</table>
</body>
</html>
<?php
	$db_link->close();
?>