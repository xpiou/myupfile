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

?>
<div style="background:#f2f2f2;">
	<form method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="path" value="<?=$dir?>" />
	<input type="file" name="file" />
	<input type="submit" name="submit" />
	</form>
</div>

    <style>
        #progress{
            width: 300px;
            height: 20px;
            background-color:#f7f7f7;
            box-shadow:inset 0 1px 2px rgba(0,0,0,0.1);
            border-radius:4px;
            background-image:linear-gradient(to bottom,#f5f5f5,#f9f9f9);
			display:none;
        }

        #finish{
            background-color: #149bdf;
            background-image:linear-gradient(45deg,rgba(255,255,255,0.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,0.15) 50%,rgba(255,255,255,0.15) 75%,transparent 75%,transparent);
            background-size:40px 40px;
            height: 100%;
        }
        form{
            margin-top: 0px;
        }
    </style>
<div style="background:#f2f2f2;margin:10px 0 0 0;">
post_max_size:<?=ini_get("post_max_size")?>,&nbsp;upload_max_filesize:<?=ini_get("upload_max_filesize")?>,&nbsp;max_file_uploads:<?=ini_get("max_file_uploads")?>,&nbsp;<a href="?action=phpinfo" target="blank">phpinfo</a>
<div id="progress">
    <div style="position:fixed;line-height:200%;" ><font id="progtext">0</font> <font id="filesize">1212</font></div>
    <div id="finish" style="width: 0%;" progress="0"></div>
</div>
<form action="./upload.php">
    <input type="hidden" name="uppath" id="uppath" value="<?=$dir?>">
    <input type="file" name="file" id="file">
    <input type="button" value="停止" id="stop" style="display:none;">
</form>
</div>

<script>
    var fileForm = document.getElementById("file");
    var uppath = document.getElementById("uppath").value;
    var stopBtn = document.getElementById('stop');
    var upload = new Upload();
	//alert(uppath);

    fileForm.onchange = function(){
	var file_blob = file.slice(0,10);
	
	
        //upload.addFileAndSend(this);
    }
    fileForm.onclick = function(){
        //alert('dd');
    }

    stopBtn.onclick = function(){
        this.value = "停止中";
        upload.stop();
        this.value = "已停止";
    }

    function Upload(){
        var xhr = new XMLHttpRequest();
        var form_data = new FormData();
        const LENGTH = 1024 * 1024;
        var start = 0;
        var end = start + LENGTH;
        var blob;
        var blob_num = 1;
        var is_stop = 0
        //对外方法，传入文件对象
        this.addFileAndSend = function(that){
		
        xhr = new XMLHttpRequest();
        form_data = new FormData();
		start = 0;
         end = start + LENGTH;
         blob='';
         blob_num = 1;
         is_stop = 0
		
		
            var file = that.files[0];
            blob = cutFile(file);
            sendFile(blob,file);
            blob_num  += 1;
        }
        //停止文件上传
        this.stop = function(){
            xhr.abort();
            is_stop = 1;
        }
        //切割文件
        function cutFile(file){
            var file_blob = file.slice(start,end);
            start = end;
            end = start + LENGTH;
            return file_blob;
        };
        //发送文件
        function sendFile(blob,file){
            var total_blob_num = Math.ceil(file.size / LENGTH);
            form_data.append('file',blob);
            form_data.append('blob_num',blob_num);
            form_data.append('total_blob_num',total_blob_num);
            form_data.append('file_name',file.name);
            form_data.append('uppath',uppath);
			
			document.getElementById('progress').style.display='block';
			document.getElementById('filesize').innerHTML=Math.round(file.size/1024/1024*100)/100+'MB';
			document.getElementById('stop').style.display='';

            xhr.open('POST','./upload.php',false);
            xhr.onreadystatechange  = function () {
				rtext=xhr.responseText;
                var progress;
                var progress2;
                var progressObj = document.getElementById('finish');
                if(total_blob_num == 1){
                    progress = '100%';
                    progress2 = '100';
					
					
                }else{
                    progress = Math.min(100,(blob_num/total_blob_num)* 100 ) +'%';
                    progress2 = Math.min(100,(blob_num/total_blob_num)* 100 );
                }
				if(progress2=='100'){
					document.getElementById('stop').style.display='none';
					//document.getElementById("file").value='';
				}
                progressObj.style.width = progress;
				//alert('aa '+rtext);
				document.getElementById("progtext").innerHTML= Math.round(progress2*100)/100 +'%';
                var t = setTimeout(function(){
                    if(start < file.size && is_stop === 0){
                        blob = cutFile(file);
                        sendFile(blob,file);
                        blob_num  += 1;
                    }else{
                        setTimeout(t);
                    }
                },1000);
            }
            
			xhr.send(form_data);
			
        }
    }

</script>

</body>
</html>