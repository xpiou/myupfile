<?
include('config.php');


?>
<!DOCTYPE html>
<html>
<head>
<title><?=$version?>管理2</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,minimal-ui">
<link rel="Stylesheet" href="context/themes/base/style.css" type="text/css" />
<link rel="Stylesheet" href="context/themes/base/jquery-ui.css" type="text/css" />
<script src="scripts/jquery.js" type="text/javascript"></script>
<script src="scripts/jquery-ui.js" type="text/javascript"></script>
<script src="scripts/jquery.contextMenu.js" type="text/javascript"></script>
<script src="scripts/jquery.cleverTabs.js" type="text/javascript"></script>
<script type="text/javascript">
	var tabs;
	var tmpCount = 0;

	$(function () {

		tabs = $('#tabs').cleverTabs();
		$(window).bind('resize', function () {
			tabs.resizePanelContainer();
		});


		tabs.add({
			url: 'file.php',
			label: 'Main'
		});
		var tab = tabs.getTabByUrl('file.php');
			//参数true为锁定，false或不提供值为解锁
			tab.setLock(true);



		$('input[type="button"]').button();

		$('#btnAddMore').click(function () {
			tabs.add({
				url: 'tmp.htm?' + tmpCount++,
				label: 'tab' + tmpCount
			});
		});
	});


</script>
</head>
<body>
    <div id="tabs" style="margin: 0px;height: 98%;">
 		<span style="position:absolute;width:100%;margin:10px 0 0 -15px;text-align:right;"><a href="file.php?action=logout">退出</a></span>
       <ul>
        </ul>
    </div>
</body>
</html>
