<?php
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
//繫結登入會員資料
$query_RecMember = "SELECT * FROM memberdata WHERE m_username = '{$_SESSION["loginMember"]}'";
$RecMember = $db_link->query($query_RecMember);	
$row_RecMember=$RecMember->fetch_assoc();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>77vagetables</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
          <center><a href="A02.php"><img src="images/3.png" alt="首頁" width="80%" ></a></center>
    </table></td>
  <tr>
    <td align="center" background="images/album_r2_c1.jpg" class="trademark">© 2020 77vagetables shop.</td>
  </tr>
</table>
</body>
</html>
<?php
	$db_link->close();
?>