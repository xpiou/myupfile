<?
date_default_timezone_set('PRC');
ob_start();
include('config.php');
if($_GET[dir])$dir=$_GET[dir];else $dir=$defaultdir;
//if(substr($dir,0,3)!='../')$dir='../';
if(substr($dir,-1)!='/')$dir=$dir.'/';
//if(substr($dir,0,5)=='../..')$dir='../';
//echo $dir;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理v</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="Stylesheet" href="context/themes/base/style.css" type="text/css" />
<link rel="Stylesheet" href="context/themes/base/jquery-ui.css" type="text/css" />
<script src="scripts/jquery.js" type="text/javascript"></script>
<script src="scripts/jquery-ui.js" type="text/javascript"></script>
<script src="scripts/jquery.contextMenu.js" type="text/javascript"></script>
<script src="scripts/jquery.cleverTabs.js" type="text/javascript"></script>
<script src="edit_area/edit_area_full.js" type="text/javascript"></script>
<script type="text/javascript">
function opentab(lid,lurl){
	var tabs = parent.tabs;
		tabs.add({
			url: lurl,
			label: lid
		});
}
function killtab(){
	var tabs = parent.tabs;
		var tab = tabs.getCurrentTab();
		tab.kill();

}


function edit(filecode,filename){
//alert(filecode);
		document.getElementById('state').innerHTML='Saving...';
		ajax('doedit',filecode,filename,'post');
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
	   if(action=='doedit'){
		   if(document.getElementById('backup').checked==true)url=url+"&backup=true";
		   cont=document.getElementById('cont').value;
		   cont=editAreaLoader.getValue("cont");
		   <?include('replace.php');?>
	   }
       var params = "cont="+cont;
       var myAjax = new Ajax.Request(
       url,
       {
		parameters:params,
       method:meth,
       onComplete:showResponse
       }
       );
}

function showResponse(request) {
	text=request.responseText.split("{p}");
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

// initialisation
editAreaLoader.init({
	id: "cont"	// id of the textarea to transform		
	,start_highlight: false	// if start with highlight
	,allow_resize: "y"
	,allow_toggle: true
	,word_wrap: true
	,toolbar: "search, go_to_line, fullscreen,|, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight,  word_wrap,|, help"
<? if (filesize($dir.$_GET['file'])>40000){?>			,display: "later"<? } ?>
	,language: "zh"
	,syntax: "php"
});
		
		
// callback functions
function my_save(id, content){
	alert("Here is the content of the EditArea '"+ id +"' as received by the save callback function:\n"+content);
}

function my_load(id){
	editAreaLoader.setValue(id, "The content is loaded from the load_callback function into EditArea");
}

function test_setSelectionRange(id){
	editAreaLoader.setSelectionRange(id, 100, 150);
}

function test_getSelectionRange(id){
	var sel =editAreaLoader.getSelectionRange(id);
	alert("start: "+sel["start"]+"\nend: "+sel["end"]); 
}

function test_setSelectedText(id){
	text= "[REPLACED SELECTION]"; 
	editAreaLoader.setSelectedText(id, text);
}

function test_getSelectedText(id){
	alert(editAreaLoader.getSelectedText(id)); 
}

function editAreaLoaded(id){
	if(id=="example_2")
	{
		open_file1();
		open_file2();
	}
}


function toogle_editable(id)
{
	editAreaLoader.execCommand(id, 'set_editable', !editAreaLoader.execCommand(id, 'is_editable'));
}

</script>
</head>
<body>
<?
	if($_POST['cont']){//文本编辑写文件
	
		if($_POST[backup]=='1'){
		echo 'ddd';
			//@unlink($_GET[state].'.bak');
			$filename=$_GET['file'];
			$filename1=substr($filename,0,strripos($filename,'.'));
			$filename_ext=substr($filename,strripos($filename,'.'));
			$filepath=$dir;
			$filepath=str_replace('/','_~_',$filepath);
			//$filepath=str_replace('..','_~~_',$filepath);
			copy($dir.$_GET['file'],'./bak/'.$filepath.$filename1.'_'.date("Y_m_d_H_i_s").'_'.$filename_ext);
		}
	
	
		$handle2 = fopen($dir.$_GET['file'], 'w');
		$cont=$_POST['cont'];
		if(get_magic_quotes_gpc()==1)$cont=stripslashes($_POST['cont']);
		fwrite($handle2, $cont);
		echo '<meta http-equiv="refresh" content="0;URL=filedo.php?dir='.$_GET[dir].'&file='.$_GET['file'].'">';
		exit();
	}
	//文本编辑
	$cont=file_get_contents($dir.$_GET['file']);
	
	if($_GET[ccode]==''){
		$ccode=mb_detect_encoding($cont, array('ASCII','GB2312','GBK','UTF-8'));
	}else{
		$ccode=$_GET[ccode];
	}
	//echo $ccode;
	if($ccode=='CP936' || $ccode=='UTF-8'){
		header("Content-type: text/html; charset=utf-8");
		$filecode='UTF-8';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<b>'.iconv('gb2312','UTF-8',$dir.$_GET['file']).'</b>';
	}else{
		$filecode='GB2312';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<b>'.$dir.$_GET['file'].'</b>';
	}
	echo '&nbsp;&nbsp;&nbsp;&nbsp;Filecharset:&nbsp;';
?>
<select name="filecode" onchange="self.location.href='filedo.php?ccode='+this.value+'&dir=<?=urlencode ($_GET[dir])?>&file=<?=urlencode($_GET[file])?>';">
               <option value="UTF-8" <? if($ccode=='CP936' || $ccode=='UTF-8')echo 'selected' ?>>UTF-8</option>
               <option value="gb2312"<? if($ccode!='CP936' && $ccode!='UTF-8')echo 'selected' ?>>GB2312</option>
               <option value="gbk">GBK</option>
             </select>
&nbsp;&nbsp;<span id="state"></span>
<form method=post action="filedo.php?dir=<?=$dir?>&file=<?=$_GET['file']?>">
<textarea style="width:500px;height:200px" name="cont" id="cont"><?=htmlspecialchars($cont,ENT_COMPAT,$filecode)?></textarea><br>
<script type="text/javascript">
if((document.body.clientHeight-110)>400){
	document.getElementById('cont').style.height=(document.body.clientHeight-310)+"px";
}
</script>
<label><input type="checkbox" name="backup" id="backup" value='1' checked />backup(每次保存都备份文件到目录"bak")</label><br /><input type=submit value="submit" title="File's name will be garbage If file's charset is UTF8 and file's name in Chinese">
<?
	if($ccode=='CP936' || $ccode=='UTF-8'){
		?>
		<input type=button onclick="edit('<?=$filecode;?>','<?=iconv('gb2312','UTF-8',$dir.$_GET['file'])?>');" value="Save">
		<?
	}else{
		?>
		<input type=button onclick="edit('<?=$filecode;?>','<?=$dir.$_GET['file']?>');" value="Save">
		<?
	}
?>
<a href="javascript:void(0);" onclick="window.location.reload();">[Refresh]</a>
<a href="javascript:void(0);" onclick="killtab()">[Close]</a>
<a href="filedo.php?dir=<?=urlencode($_GET['dir'])?>&file=<?=urlencode($_GET['file'])?>" target="_blank" title="Open in new window">[New window]</a>
</form>
<script language=javascript src="scripts/prototype.js"></script>
</body>
</html>