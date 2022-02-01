<?php 
require_once("connMysql.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: index.php");
}
//檢查權限是否足夠
if($_SESSION["memberLevel"]=="member"){
	header("Location: member_center.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: index.php");
}
//刪除會員
if(isset($_GET["action"])&&($_GET["action"]=="delete")){
	$query_delMember = "DELETE FROM memberdata WHERE m_id=?";
	$stmt=$db_link->prepare($query_delMember);
	$stmt->bind_param("i", $_GET["id"]);
	$stmt->execute();
	$stmt->close();
	//重新導向回到主畫面
	header("Location: member_admin.php");
}
//選取管理員資料
$query_RecAdmin = "SELECT m_id, m_name, m_logintime FROM memberdata WHERE m_username=?";
$stmt=$db_link->prepare($query_RecAdmin);
$stmt->bind_param("s", $_SESSION["loginMember"]);
$stmt->execute();
$stmt->bind_result($mid, $mname, $mlogintime);
$stmt->fetch();
$stmt->close();
//選取所有一般會員資料
//預設每頁筆數
$pageRow_records = 5;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
$query_RecMember = "SELECT * FROM memberdata WHERE m_level<>'admin' ORDER BY m_jointime DESC";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecMember = $query_RecMember." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $resultMember 中
$RecMember = $db_link->query($query_limit_RecMember);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_resultMember 中
$all_RecMember = $db_link->query($query_RecMember);
//計算總筆數
$total_records = $all_RecMember->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>77會員列表</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function deletesure(){
    if (confirm('\n確定要刪除這個會員嗎?\n刪除後將無法恢復!\n')) return true;
    return false;
}
</script>
</head>

<body>
<table width="50%" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td class="tdbline"><img src="images/77.png" alt="77vagetables" width="170" height="70"><p align="right"><a href="?logout=true">登出系統</a></p></td>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        <p class="title">會員列表 </p>
          <table width="100%" border="0" cellpadding="2" cellspacing="1" bgcolor="#F0F0F0">
            <tr>
              
              <th width="20%" bgcolor="#f5d8a2"><p>姓名</p></th>
              <th width="20%" bgcolor="#f5d8a2"><p>帳號</p></th>
              <th width="20%" bgcolor="#f5d8a2"><p>加入時間</p></th>
              <th width="20%" bgcolor="#f5d8a2"><p>上次登入</p></th>
			  <th width="15%" bgcolor="#f5d8a2">管理動作</th>
              
            </tr>
			<?php while($row_RecMember=$RecMember->fetch_assoc()){ ?>
            <tr>
              
              <td width="20%" align="center" bgcolor="#f7f7f7"><p><?php echo $row_RecMember["m_name"];?></p></td>
              <td width="20%" align="center" bgcolor="#f7f7f7"><p><?php echo $row_RecMember["m_username"];?></p></td>
              <td width="20%" align="center" bgcolor="#f7f7f7"><p><?php echo $row_RecMember["m_jointime"];?></p></td>
              <td width="20%" align="center" bgcolor="#f7f7f7"><p><?php echo $row_RecMember["m_logintime"];?></p></td>
			  <td width="15%" align="center" bgcolor="#f7f7f7"><p><a href="member_adminupdate.php?id=<?php echo $row_RecMember["m_id"];?>">修改</a><br>
                <a href="?action=delete&id=<?php echo $row_RecMember["m_id"];?>" onClick="return deletesure();">刪除</a></p></td>
              
            </tr>
			<?php }?>
          </table>          
          <hr size="1" />
          <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
            <tr>
              <td valign="middle"><p>資料筆數：<?php echo $total_records;?></p></td>
              <td align="right"><p>
                  <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
                  <a href="?page=1">第一頁</a> | <a href="?page=<?php echo $num_pages-1;?>">上一頁</a> |
                <?php }?>
                  <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
                  <a href="?page=<?php echo $num_pages+1;?>">下一頁</a> | <a href="?page=<?php echo $total_pages;?>">最末頁</a>
                  <?php }?>
              </p></td>
            </tr>
          </table><p>&nbsp;</p>
          </td>
        
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
	$db_link->close();
?>