<?
$version='v0.80';
$user='admin';//管理账号
//echo md5('123789');
$password='62ea5a417f6f56ed4fa7625b65e8aea1';

$indir=$_SERVER['PHP_SELF'];
$indir=substr($indir,0,strrpos($indir,'/'));
$indir=substr($indir,1);
$indira=explode('/',$indir);
$dirdeep='';
foreach($indira as $h){
	$dirdeep.='../';
}

$defaultdir=$dirdeep;//默认管理目录，此处计算后设为站点根目录，可改为'../../'等形式

$renamefail='php,php3,asp,aspx,js,jsp,exe';//不允许重命名的文件类型,防止重命为可执行程序在服务器上运行,必须为小写
$editfile='txt,php,js,css,html,htm,aspx,ascx,cs,bak,htaccess,md,gitignore';//允许在线编辑的文件类型
$imgfile='jpg,jpeg,gif,png,bmp';//图片类型的文件，会显示小图，显示图片尺寸
$thumbsize=100000;//处理图片文件的大小限制，此大小以下的文件才会显示缩略图，以减小下载时间，单位：字节
?>