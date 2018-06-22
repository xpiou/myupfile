<?
header('Content-Type:text/html;charset=GB2312');
header("Cache-Control: no-cache, must-revalidate");

include('config.php');

if($_GET['action']=='dorename'){
	$f=explode('|',$_GET['state']);
	$extf=explode('.',$f[0]);
	$extf=$extf[count($extf)-1];
	$renamefaila=explode(',',$renamefail);
	foreach($renamefaila as $k){
		if(strtolower($extf)==$k){
			echo $_GET['id'].'{p}fail';
			exit();
		}
	}
	rename($f[1],$f[0]);
	echo $_GET['id'].'{p}'.$f[0];

}

if($_GET['action']=='doedit'){
		if($_GET['backup']=='true'){
			//@unlink($_GET['state'].'.bak');
			$filename=substr($_GET['state'],strripos($_GET['state'],'/')+1);
			$filename1=substr($filename,0,strripos($filename,'.'));
			$filename_ext=substr($filename,strripos($filename,'.'));
			$filepath=substr($_GET['state'],0,strripos($_GET['state'],'/')+1);
			$filepath=str_replace('/','_~_',$filepath);
			//$filepath=str_replace('..','_~~_',$filepath);
			copy($_GET['state'],'./bak/'.$filepath.$filename1.'_'.date("Y_m_d_H_i_s").'_'.$filename_ext);
		}
		$handle = fopen($_GET['state'],'w');
		if($_GET['id']=='GB2312')	$cont=iconv('UTF-8','gb2312',urldecode($_POST['cont']));
		else $cont=urldecode($_POST['cont']);
		$cont=str_replace('{^connect^}','+',$cont);
		$cont=str_replace('{^dot^}',"'",$cont);
		$cont=str_replace('{^dots^}','"',$cont);
		$cont=str_replace('{^split^}',"\\",$cont);
		$cont=str_replace('{^and^}',"&",$cont);
		$cont=str_replace('{^poi^}',"·",$cont);
		$cont=str_replace('{^del^}',"—",$cont);
		$cont=str_replace('{^percent^}',"%",$cont);
		$ru=fwrite($handle,$cont);
		if($ru>=0)echo 'Save sucess('.date("m-d H:i:s").')';else echo 'Save fail';
		fclose($handle);
}

if($_POST['action']=='showupimg'){
	//include('cp_upload.php');
	$n='asfd';
	echo 'showupimg'.'|'.$n;
}

if($_GET['action']=='updatecache'){
		updatecache();
}


function GetRndFileName($sExt){
	return date("YmdHis").rand(1,999).".".$sExt;
}

?>