<?php
include_once('common.php');
if (!$_SESSION['islogin']) header('location:logout.php');

$recent8 = [];
$recent8_info = [];

$store_info = query("select * from store");
while ($stores = mysql_fetch_array($store_info)) {
	array_push($recent8, $stores);
	$s_info = query("select * from " . $stores["stable"] . " order by date desc LIMIT 3");
	$recent8_info[$stores["stable"]] = [];
	while ($arr = mysql_fetch_array($s_info)) {
		array_push($recent8_info[$stores["stable"]], $arr);
	}
	foreach ($stores as $s) {
//		echo $s . " ";
	}
//	echo "<br/>";
};
?>

<!DOCTYPE html>
<html>
<head lang="zh-tw">
	<meta charset="UTF-8">
	<title>Recent 7 days Report</title>
	<link rel="stylesheet" href="css/uikit.min.css"/>
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/uikit.min.js"></script>
	<script src="js/accounting.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0/angular.min.js"></script>
	<script>
		var store2 = <?= json_encode($recent8)?>;
		var store2_info = <?= json_encode($recent8_info)?>;
		//		console.info("store2 " ,store2);
		//		console.info("store2_info " ,store2_info);
		function storeCtl($scope)
		{
			$scope.store = store2;
			$scope.store_info = store2_info;
			$scope.cur = function (s)
			{
				return accounting.formatMoney(s, '$ ', 0);
			}
			$scope.week = function (w)
			{
				var week = ["日", '一', '二', '三', '四', '五', '六'],
					d = new Date(w);
				return "(" + week[d.getDay()] + ")";
			}
		}
	</script>
</head>
<body class="twoColElsLtHdr">
<div id="container" style="width:80%;">
	<div id="header">
		<h1>樂透彩每日記帳 - <?php echo $storeName ?></h1>
	</div>
	<?php genMenu() ?>
	<form id="form2" name="form2" method="post" action="logout.php">
		<input type="submit" name="button" id="button" value="登　　　　　出"/>
	</form>
	<hr/>
	<div class="uk-width-1-1" ng-app="" ng-controller="storeCtl">
		<div class="uk-grid uk-width-1-1 uk-text-bold uk-text-large" ng-repeat="d in store">
			<div class="uk-width-1-6">
				<div class="uk-panel-box-primary uk-h1">
					{{ d.stitle }}
				</div>
			</div>
			<div class="uk-width-1-6">
				登入日期 : <br/>
				電腦銷售 : <br/>
				刮刮樂銷售小計 :
			</div>
			<div class="uk-width-4-6">
				<div class="uk-grid uk-grid-width-1-3">
					<div class="uk-text-left" ng-repeat="d2 in store_info[d.stable]">
						{{ d2.date }} {{week(d2.date)}}<br/> {{ cur(d2.input1) }} <br/> {{ cur((d2.input4 -
						d2.input5) *100) }}
						<div class="uk-grid-divider"></div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<div id="footer">
		<p>Copyright © Happy Lottery</p>
	</div>
</div>
</body>
</html>