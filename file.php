<?
date_default_timezone_set('PRC');
ob_start();
include('config.php');

if($_COOKIE['clogin']!='yes'){
?>
<script type="text/javascript">
	top.location='login.php';
</script>
<?
	exit();
}


if($_GET["action"]=='phpinfo'){
phpinfo();
	exit();
}
if($_GET["action"]=='logout'){
	setcookie("clogin",'',0,'/');
	setcookie("cuser",'',0,'/');
?>
<script type="text/javascript">
	top.location='login.php';
</script>
<?
	exit();
}


if($_GET[dir])$dir=$_GET[dir];else $dir=$defaultdir;
//if(substr($dir,0,3)!='../')$dir='../';
if(substr($dir,-1)!='/')$dir=$dir.'/';
//if(substr($dir,0,5)=='../..')$dir='../';
//echo $dir;


if($_POST["txtFileName"]!=''){
	?>
<meta http-equiv="refresh" content="0;URL=file.php?dir=<?=$dir?>">
	<?
	exit();
}

if($_POST["action"]=='mkdir'){
	$mkd=mkdir($dir.iconv('UTF-8','GBK',$_POST["dirname"]));
	if($mkd==true){
	//echo '<a href="file.php?dir='.$dir.urlencode(iconv('UTF-8','GBK',$_POST["dirname"])).'">aaaa</a>';
	?>
<meta http-equiv="refresh" content="0;URL=file.php?dir=<?=urlencode($dir.iconv('UTF-8','GBK',$_POST["dirname"]))?>">
	<?
/*
*/



	}
	exit();
}

if($_GET["action"]=='unzip'){
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
开发中
<?
exit();
}

if($_POST["action"]=='emptydir'){
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div style="float:left; margin:10px 0;">
<form action="" method="post" onsubmit="return confirm('确定删除此目录下所有文件？')"><table border="0" cellspacing="5" cellpadding="0" style="background:#f2f2f2;">
  <tr>
    <td><input type="hidden" name="action" value="emptydirdo" /><input type="submit" name="sss" id="sss" value="清空" /></td>
    <td><input type="hidden" name="action" value="emptydirdo" /><input type="button" name="s" id="s" value="取消" onclick="history.back(-1);" /></td>
  </tr>
</table>
</form>
</div>
<?
exit();
}
if($_POST["action"]=='emptydirdo'){
	$handle = opendir($dir) ;
    while (false !== ($file = readdir($handle))) {
		if($file!='..' && $file!='.'){
			if(unlink($dir.$file)){
				//echo 'Sucess to del file:"'.$dir.$file.'"! <br />';
			}else{
				//echo 'Fail to del file:"'.$dir.$file.'"! <br />';
			}
		}
	}
	echo '<meta http-equiv="refresh" content="0;URL=file.php?dir='.$dir.'">';
	exit();
}

if($_GET["action"]=='down'){
$filename =$dir.$_GET["file"]; 
//文件的类型
Header("Content-type: application/octet-stream"); 
//下载显示的名字
header('Content-Disposition: attachment; filename="'.$_GET["file"].'"'); 
readfile("$filename");

	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="Stylesheet" href="context/themes/base/style.css" type="text/css" />
<link rel="Stylesheet" href="context/themes/base/jquery-ui.css" type="text/css" />
<script src="scripts/jquery.js" type="text/javascript"></script>
<script src="scripts/jquery-ui.js" type="text/javascript"></script>
<script src="scripts/jquery.contextMenu.js" type="text/javascript"></script>
<script src="scripts/jquery.cleverTabs.js" type="text/javascript"></script>
<script src="scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<script type="text/javascript">
function opentab(lid,lurl,prop){
	var tabs = parent.tabs;
		tabs.add({
			url: lurl,
			label: lid,
			prop:prop
		});
}
function killtab(){
	var tabs = parent.tabs;
		var tab = tabs.getCurrentTab();
		tab.kill();

}

var proMaxHeight = 30;
var proMaxWidth = 30;
  
function rename(fid){
document.getElementById('j'+fid).style.display='';
document.getElementById('b'+fid).style.display='';
document.getElementById('f'+fid).style.display='none';
document.getElementById('j'+fid).focus();
}

function cleanrename(fid){//取消重命名
document.getElementById('j'+fid).style.display='none';
document.getElementById('b'+fid).style.display='none';
document.getElementById('f'+fid).style.display='';
document.getElementById('f'+fid).href='<?=$dir?>'+text[1];
document.getElementById('f'+fid).innerHTML=text[1];
}

function dorename(fb,id,oldn){
//alert('dorename'+id+'-----'+fb+'|'+oldn);
ajax('dorename',id,fb+'|'+oldn,'get');
}

function ajax(action,id,state,meth){
	if(action=='dorename'){
		document.getElementById('s'+id).innerHTML='Saving...';
		document.getElementById('s'+id).style.display='';
	}
	doAjaxRequest(action,id,state,meth);
}

function doAjaxRequest(action,id,state,meth) {
       var url = "ajax.php?action="+action+"&id="+id+"&state="+state;
	   var cont='';
       var params = "cont="+cont;
	   //alert(url);
	   /*
       var myAjax = new Ajax.Request(
       url,
       {
		parameters:params,
       method:meth,
       onComplete:showResponse
       }
       );
	   */
	   var myAjaxa=$.ajax({
		   url:url,
		   type:meth,
		   data:"cont="+cont,
		   complete:function (){showResponse(myAjaxa.responseText)}
	   });
}


function showResponse(request) {
	//alert(request);
	text=request.split("{p}");
	
	if(text.length==1){
		//alert(text[0]);
		document.getElementById('state').innerHTML=text[0];
		//document.getElementById('state').style.display='';
	}else{
		if(text[1]=='fail'){
			document.getElementById('s'+text[0]).innerHTML='文件扩展名非法';
			document.getElementById('s'+text[0]).style.display='';
			document.getElementById('j'+text[0]).focus();
		}else{
			var filename=text[1].split('/');
			filename=filename[filename.length-1];
			document.getElementById('j'+text[0]).value=filename;

			document.getElementById('s'+text[0]).style.display='none';
			document.getElementById('j'+text[0]).style.display='none';
			document.getElementById('b'+text[0]).style.display='none';
			document.getElementById('f'+text[0]).style.display='';
			document.getElementById('f'+text[0]).href=text[1];
			document.getElementById('f'+text[0]).innerHTML=filename;
		}
	}

}


function proDownImage(ImgD){
      var image=new Image();
      image.src=ImgD.src;
	  var a=ImgD.name;

      if(image.width>0 && image.height>0){
      var rate = (proMaxWidth/image.width < proMaxHeight/image.height)?proMaxWidth/image.width:proMaxHeight/image.height;
    if(rate <= 1){
     ImgD.width = image.width*rate;
     ImgD.height =image.height*rate;
    }
    else {
	  ImgD.width = image.width;
	  ImgD.height =image.height;
		  }
      }
}
</script>

</head>

<body>
<?

if($_FILES['file']!=''){
	if ($_FILES["file"]["error"] > 0)
	  {
	  echo "Error: " . $_FILES["file"]["error"] . "<br />";
	  }
	else
	  {
	  echo "Upload: " . $_FILES["file"]["name"] . "<br />";
	  echo "Type: " . $_FILES["file"]["type"] . "<br />";
	  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
	  echo "Stored in: " . $_FILES["file"]["tmp_name"];
	  move_uploaded_file($_FILES["file"]["tmp_name"],$dir."/" . $_FILES["file"]["name"]);
	  }
	  

}


if($dir && $_GET['file'] && $_GET['del']==1){//删除文件或目录
		if(is_dir($dir.$_GET['file'])==true){
			$d=@rmdir($dir.$_GET['file']);
		}else{
			$d=@unlink($dir.$_GET['file']);
		}
		if($d==true){
			echo '<meta http-equiv="refresh" content="0;URL=file.php?dir='.urlencode($dir).'&page='.$_GET[page].'">';
		}else{
			echo '删除失败! 5秒后<a href="file.php?dir='.urlencode($dir).'&page='.$_GET[page].'">返回</a><meta http-equiv="refresh" content="5;URL=file.php?dir='.urlencode($dir).'&page='.$_GET[page].'">';
		}
		exit();
}

	$i=0;
	$u=0;
	$y=0;
	$totalsize=0;
	$listnum=30;
	$page=$_GET[page];
	if(!$_GET[page])$page=1;
	$handle = opendir($dir) ;
    while (false !== ($file = readdir($handle))) {
		$isf=false;
		$ist=true;
		if($_GET["action"]=='sear' && $_GET["searname"]!=''){
			//$page=1;
			$isf=true;
			$searname=explode(' ',$_GET["searname"]);//分隔多个搜索关键词
			foreach($searname as $k){
				if(strpos($file,$k)>-1)$ist=1;
			}
		}

		if($file!='..' && $file!='.'){
			if($ist>-1 || $isf==false){
				$f[$y]=iconv('GBK','UTF-8',$file);
				if(is_dir($dir.$file)){
					$di[$u]['fname']=iconv('GBK','UTF-8',$file);
					$di[$u][ftime]=filemtime($dir.$file);
					$di[$u][fsize]=filesize($dir.$file);
					$u++;
				}else{
					$t[$i]['fname']=iconv('GBK','UTF-8',$file);
					$t[$i][ftime]=filemtime($dir.$file);
					$t[$i][fsize]=filesize($dir.$file);
					$totalsize=$t[$i][fsize]+$totalsize;
					$i++;
				}
				$y++;
			}
		}
	}

	if(count($di)==1 && count($t)==0 && $_GET["action"]=='sear' && $_GET["searname"]!=''){
		header("Location: file.php?dir=".$dir.$di[0]['fname']);
		exit();
	}


	if(count($f)>0)$f=array_reverse($f);
	$totalpage=ceil($y/$listnum);
	//print_r($di);
	//print_r($t);
	if(count($di)>0){
		$di=multi_array_sort($di,'fname',SORT_ASC);
	}
	if(count($t)>0){
		$t=multi_array_sort($t,'fname',SORT_ASC);
	}

	if(count($t)>0 && count($di)>0){
		$t=array_merge($di,$t);
	}elseif(count($t)==0){
		$t=$di;
	}

//访问记录
$dirlog=file_get_contents('log.php');
$dirloga=explode('|',$dirlog);
foreach($dirloga as $key => $k){
	if($k==$dir || $key>30 || $k==''){
		//$dirloga[$key]='';
		unset($dirloga[$key]);
	}
}
$handle2 = fopen('log.php', 'w');
$cont=$dir.'|'.implode('|',$dirloga);
fwrite($handle2, $cont);

?>
<div style="background:#f2f2f2;">
	<form method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="path" value="<?=$dir?>" />
	<input type="file" name="file" />
	<input type="submit" name="submit" />
	</form>
</div>

<div style="background:#f2f2f2;margin:10px 0 0 0;">
post_max_size:<?=ini_get("post_max_size")?>,&nbsp;upload_max_filesize:<?=ini_get("upload_max_filesize")?>,&nbsp;max_file_uploads:<?=ini_get("max_file_uploads")?>,&nbsp;<a href="?action=phpinfo" target="blank">phpinfo</a>
<br />
<input type="button" onclick="$('#webup').toggle('1000')" value="webuploader上传" /><br />
<iframe id="webup" src="./webup.php?dir=../../<?=urlencode($dir)?>" frameborder="0" style="width:500px;height:500px;display:none;"></iframe>
</div>


<?
$indir=$_SERVER['PHP_SELF'];
$indir=substr($indir,0,strrpos($indir,'/'));
$indir=substr($indir,1);
$indira=explode('/',$indir);
$dirdeep='';
foreach($indira as $h){
	$dirdeep.='../';
}
//echo $dirdeep;

$dira=explode('/',$dir);
if(count($dira)>1){
	array_pop($dira);
	array_pop($dira);
	$updir=implode('/',$dira);
}
 ?>
<br /><a href="file.php?dir=<?=urlencode($updir)?>"><strong>[返回上级]</strong></a>&nbsp;&nbsp;<a href="javascript:void(0);" onmouseover="document.getElementById('dirlog').style.display='';"><strong>[历史访问]</strong></a>&nbsp;&nbsp;<strong>当前目录：</strong><br />
对中文路径和中文文件名的文件处理有问题<br />
<?
$dira=explode('/',$dir.'/');
//array_pop($dira);
foreach($dira as $j){
	if($j=='..'){
		array_pop($indira);
		array_shift($dira);
	}
}
$indir=implode('/',$indira);
$ndir=implode('/',$dira);

$cdir=$indir.'/'.$ndir;
$cdir=explode('/',$cdir);
foreach($cdir as $k){
	if($k!=''){
		$cdirt.=urlencode($k).'/';
		$ccdir.=' / <a href="file.php?dir='.$dirdeep.$cdirt.'">'.iconv('GBK','UTF-8',$k).'</a>';
	}
}
echo 'http://<a href="file.php?dir='.$dirdeep.'">'.$_SERVER['HTTP_HOST'].'</a>'.$ccdir;?>
<div style="clear:both;"></div>
<div style="position:absolute;background:#ffffd5;margin:-1px 0 0 67px;*margin:-1px 0 0 -577px;border:1px solid #6da6d1;overflow:hidden;display:none;z-index:999;letter-spacing:1px;" id="dirlog">
<div style="margin:5px;">
<?
foreach($dirloga as $k){
	echo '<a href="file.php?dir='.urlencode($k).'">'.iconv('GBK','UTF-8',$k).'</a><br>';
}
?>
<br><a href="javascript:void(0);" onclick="document.getElementById('dirlog').style.display='none';">关闭</a>
</div>
</div>

<div style="float:left; margin:10px 0;">
<form action="" method="post"><table border="0" cellspacing="5" cellpadding="0" style="background:#f2f2f2;">
  <tr>
    <td width="80"><strong>建立目录：</strong></td>
	<td><input type="text" name="dirname" style="width:100px;" /><input type="hidden" name="action" value="mkdir" /></td><td><input type="submit" name="ss" value="提交" /></td>
  </tr>
</table>
</form>
</div>

<div style="float:left; margin:10px;">
<form action="" method="get"><table border="0" cellspacing="5" cellpadding="0" style="background:#f2f2f2;">
  <tr>
    <td width="140" style="text-align:right;"><strong>查找目录或文件：</strong><br>(Ctrl+Enter新标签打开)&nbsp;</td>
	<td><input type="text" name="searname" id="searname"<? if(!empty($_GET['searname'])) echo ' value="'.$_GET['searname'].'"'; ?> style="width:100px;" onkeydown="javascript:if (event.ctrlKey && event.keyCode == 13){opentab(document.getElementById('searname').value,'file.php?searname='+document.getElementById('searname').value+'&dir='+document.getElementById('dirs').value+'&page=1&action=sear')}" /><input type="hidden" name="dir" id="dirs" <? if(!empty($_GET['dir'])) echo ' value="'.$_GET['dir'].'"';?> /><input type="hidden" name="page" value="1" /><input type="hidden" name="action" value="sear" /></td><td><input type="submit" name="sss" id="sss" value="查找" /></td>
  </tr>
</table>
</form>
</div>

<div style="float:left; margin:10px 0;">
<form action="" method="post" onsubmit="return confirm('确定删除此目录下所有文件？')"><table border="0" cellspacing="5" cellpadding="0" style="background:#f2f2f2;">
  <tr>
    <td><input type="hidden" name="action" value="emptydir" /><input type="submit" name="sss" id="sss" value="清空" /></td>
  </tr>
</table>
</form>
</div>

<div style="clear:both;"></div>
    <div id="toppage"></div>
	
<table width="800" border="0" cellspacing="0" cellpadding="2" class="ftable">
	<tr style="background:#d1e0ed;">
	<td></td>
	<td>文件名</td>
	<td>大小</td>
	<td></td>
	<td>修改时间</td>
	<td>操作</td>
	
	</tr>
	<?
	$totalsize=0;
	$tj=($page)*$listnum;
	if(count($f)<$tj)$tj=count($f);
	for($j=($page-1)*$listnum;$j<$tj;$j++){
		$extf=explode('.',$t[$j]['fname']);
		if(count($extf)==1)$extf='';else $extf=strtolower($extf[count($extf)-1]);
		$isdir=0;
		$image_size = '';

		?>
  <tr onmouseover="this.style.background='#fcbe04';" onmouseout="this.style.background='#ffffff';">
    <td align="center">
	<?
	$isimg=0;
	$imgfilea=explode(',',$imgfile);
	foreach($imgfilea as $k){
		if(strtolower($extf)==$k){
			$isimg=1;
			break;		
		}
	}
	
	if($isimg==1){ //图片
			$image_size = getimagesize($dir.iconv('UTF-8','GBK',$t[$j]['fname']));
			$image_size = '宽:'.$image_size[0].'&nbsp;高:'.$image_size[1];
		if($t[$j][fsize]>$thumbsize){?>
			<a href="<?=iconv('GBK','UTF-8',$dir).$t[$j]['fname']?>" target="_blank"><img src="./phpThumb/phpThumb.php?src=../<?=urlencode($dir.iconv('UTF-8','GBK',$t[$j]['fname']))?>&w=200&h=200&q=60&hash=abc" border="0" name="<?=$t[$j]['fname']?>" /></a>
			
	  <? }else{?>
			<a href="<?=iconv('GBK','UTF-8',$dir).$t[$j]['fname']?>" target="_blank"><img src="<?=iconv('GBK','UTF-8',$dir).$t[$j]['fname']?>" border="0" onload="proDownImage(this);" width="30" height="30" name="<?=$t[$j]['fname']?>" /></a>
		<? }?>
	<? }elseif($extf=='avi' || $extf=='mpg' || $extf=='mpeg' || $extf=='wmv' || $extf=='mid' || $extf=='wma' || $extf=='mp3' || $extf=='wav' || $extf=='mp4'){ //视频?>
	<link rel="stylesheet" href="hivideo/assets/hivideo.css" />
    <script type="text/javascript" src="hivideo/hivideo.js"></script>
	<video ishivideo="true" autoplay="true" isrotate="false" autoHide="true" style="width:320px;height:320px;">
            <source src="<?=$dir.iconv('GBK','UTF-8',$t[$j]['fname'])?>" type="video/mp4">
        </video>
	
	
	
		<object classid="clsid:6BF52A52-394A-11D3-B153-00C04F79FAA6" style="width:100px; height:100px;" onMouseOver="javascript:this.style.width='200px';this.style.height='200px';" onMouseOut="javascript:this.style.width='100px';this.style.height='100px';">
		<param value="<?=$dir.$t[$j]['fname']?>" name="URL" />
		<param value="1" name="rate" />
		<param value="0" name="balance" />
		<param value="0" name="currentPosition" />
		<param value="" name="defaultFrame" />
		<param value="1" name="playCount" />
		<param value="0" name="autoStart" />
		<param value="0" name="currentMarker" />
		<param value="-1" name="invokeURLs" />
		<param value="" name="baseURL" />
		<param value="50" name="volume" />
		<param value="0" name="mute" />
		<param value="full" name="uiMode" />
		<param value="0" name="stretchToFit" />
		<param value="0" name="windowlessVideo" />
		<param value="-1" name="enabled" />
		<param value="-1" name="enableContextMenu" />
		<param value="0" name="fullScreen" />
		<param value="" name="SAMIStyle" />
		<param value="" name="SAMILang" />
		<param value="" name="SAMIFilename" />
		<param value="" name="captioningID" />
		<param value="0" name="enableErrorDialogs" />
		</object>
	<? }elseif($extf=='swf'){  //swf ?>
		<? if($t[$j][fsize]>300000){?>
			 
		<? }else{?>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="100" height="100">
	  <param name="movie" value="<?=$dir.$t[$j]['fname']?>" />
	  <param name="quality" value="high" /><param name="SCALE" value="exactfit" />
	  <embed src="<?=$dir.$t[$j]['fname']?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="100" height="100" scale="exactfit"></embed>
	</object>
		<? }?>
	<? }elseif($extf=='flv'){ //flv视频?>
		<script type="text/javascript">
		AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','100','height','100','src','../module/jwplayer/player','allowfullscreen','true','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','../module/jwplayer/player','flashvars','file=../<?=$dir.$t[$j]['fname']?>&bufferlength=5&autostart=false'); //end AC code
		</script>
	<? }elseif(is_dir($dir.iconv('UTF-8','GBK',$t[$j]['fname']))){ $isdir=1;//目录
	;?>
<a href="file.php?dir=<?=urlencode($dir.iconv('UTF-8','GBK',$t[$j]['fname']))?>"><img src="folder.jpg" border="0" /></a>
	<? } ?>
	</td>
    <td style="height:30px;">
	<div id="s<?=$j?>" style=" display:none; font-weight:bold; background: #66FFFF; width:100px; text-align:center;"></div>
	<? if($isdir!=1){ ?>
		<div style="width:300px;"><a href="<?=iconv('GBK','UTF-8',$dir).$t[$j]['fname']?>" target="_blank" id="f<?=$j?>"><b><?=$t[$j]['fname']?></b></a></div>
	<?
	}else{
	
	?>
		<a href="file.php?dir=<?=urlencode($dir.iconv('UTF-8','GBK',$t[$j]['fname'])) ?>" id="f<?=$j?>"><b><?=$t[$j]['fname']?></b></a>
	<?
	}
	?>

	<input name="<?=$t[$j]['fname']?>" id="j<?=$j?>" type="text" value="<?=$t[$j]['fname']?>" style="border:1px #0099FF solid;width:80px; display:none;" onKeyPress="if(event.keyCode==13)dorename('<?=$dir?>'+document.getElementById('j'+<?=$j?>).value,'<?=$j?>','<?=$dir.$t[$j]['fname']?>');" />
	<input name="do" type="button" value="√" id="b<?=$j?>" style="background: #B0CBE1; height:22px;width:22px; border:1px #0099FF solid; display:none;" onClick="dorename('<?=$dir?>'+document.getElementById('j'+<?=$j?>).value,'<?=$j?>','<?=$dir.$t[$j]['fname']?>');" /></td>
	<td>
	<? if($isdir==0){?><?=round($t[$j][fsize]/1000)?>k<? } ?></td>
	<td><?=$image_size;?></td>
	<td><?=date('Y-m-j H:i:s',$t[$j][ftime])?></td>
    <td><a href='javascript:void(0);' onClick="rename('<?=$j?>')" >[重命名]</a>&nbsp;
<a href='file.php?dir=<?=urlencode($dir)?>&file=<?=urlencode(iconv('UTF-8','GBK',$t[$j]['fname']))?>&del=1&page=<?=$_GET[page]?>' onclick='if(confirm("确认删除此文件:<?=$t[$j]['fname']?>")){return true;}return false;' >[删除]</a>
<?
if($isdir==1){
?>
&nbsp;<a href="javascript:void(0);" onclick="opentab('<?=$t[$j]['fname']?>','file.php?dir=<?=urlencode($dir.$t[$j]['fname'])?>/')">[新标签]</a>
<?
}else{
?>
&nbsp;<a href='file.php?dir=<?=$dir?>&file=<?=urlencode(iconv('UTF-8','GBK',$t[$j]['fname']))?>&action=down&page=<?=$_GET[page]?>' target="_blank">[下载]</a>
<?}
$editfilea=explode(',',$editfile);
foreach($editfilea as $k){
	if(strtolower($extf)==$k){
	?>
		&nbsp;<a href="javascript:void(0);" onclick="opentab('<?=$t[$j]['fname']?>','filedo.php?dir=<?=urlencode($dir)?>&file=<?=urlencode(iconv('UTF-8','GBK',$t[$j]['fname']))?>','file')" >[编辑]</a>
	<?
	}
}


	
if($extf=='zip'){
?>
	&nbsp;<a href="javascript:void(0);" onclick="if(confirm('解压会直接覆盖原有文件！确认解压此文件？')){opentab('解压<?=$t[$j]['fname']?>-<?=$dir?>','file.php?action=unzip&dir=<?=urlencode($dir)?>&file=<?=urlencode($t[$j]['fname'])?>');}">[解压]</a>
<? } ?>

</td>
  </tr>
<?
//if(($j+1)%3==0)echo '</tr><tr>';
    }

?>
</table>
    <div id="bottompage" style="float:left;width:850px;"><span style="float:left;width:36px;height:16px;display:block;padding:2px;">页码：</span>
	<? for($u=0;$u<$totalpage;$u++){?>
	<a href="file.php?page=<?=$u+1;?>&searname=<?=$_GET['searname']?>&action=<?=$_GET['action']?>&dir=<?=urlencode($dir)?>" style="float:left;border:1px solid #cccccc;width:16px;height:16px;margin:2px;display:block;text-align:center;overflow:hidden;<? if($_GET[page]==$u+1 || ($_GET[page]=='' && $u==0))echo 'font-weight:bold;background:#dedede;'?>"><?=$u+1;?></a>
	<? }
	
function multi_array_sort($multi_array,$sort_key,$sort=SORT_DESC){
    if(is_array($multi_array)){
        foreach ($multi_array as $row_array){
            if(is_array($row_array)){
                $key_array[] = $row_array[$sort_key];
            }else{
                return -1;
            }
        }
		array_multisort($key_array,$sort,$multi_array);
		return $multi_array;
    }else{
        return -1;
    }
}
?>
<div style="clear:both;"></div>
</div>
<div style="float:left;">
<a href="#">[top]</a>
<a href="update.php" target="_blank">[update]</a>
</div>
<script type="text/javascript">
document.getElementById('toppage').innerHTML=document.getElementById('bottompage').innerHTML;
</script>
<!-- <script language=javascript src="scripts/prototype.js"></script> -->
</body>
</html>