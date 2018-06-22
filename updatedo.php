<?
@unlink('index.htm');
echo 'delete index.htm<br>';
@unlink('swfupload/images/TestImageNoText_65x29.png');
echo 'delete swfupload/images/TestImageNoText_65x29.png<br>';
echo 'updata to v0.80,2014-04-25';


if(file_exists('log.php') && file_exists('log_bak.php')){
@unlink('log.php');
echo rename('log_bak.php','log.php');
}

if(file_exists('index-.php') && file_exists('index.php')){
@unlink('index.php');
echo rename('index-.php','index.php');
}
if(file_exists('ajax-.php') && file_exists('ajax.php')){
@unlink('ajax.php');
echo rename('ajax-.php','ajax.php');
}
if(file_exists('swfupload/upload.php') && file_exists('upload-.php')){
@unlink('swfupload/upload.php');
echo rename('upload-.php','swfupload/upload.php');
}


?>