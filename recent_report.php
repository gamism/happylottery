<?php
include_once('common.php');
if (!$_SESSION['islogin']) header('location:logout.php');

$recent8 = [];
$recent8_info = [];

$store_info = query("select * from store");
while($stores = mysql_fetch_array($store_info))
{
	array_push($recent8, $stores);
	$s_info = query("select * from " . $stores["stable"] ." order by date desc LIMIT 3");
	$recent8_info[$stores["stable"]] = [];
	while($arr = mysql_fetch_array($s_info))
	{
		array_push($recent8_info[$stores["stable"]], $arr);
	}
	foreach($stores as $s)
	{
//		echo $s . " ";
	}
//	echo "<br/>";
};

$recent7 = query("select * from main, main02, main03 LIMIT 1");


$d = mysql_fetch_array($recent7);
?>

<!DOCTYPE html>
<html>
<head lang="zh-tw">
	<meta charset="UTF-8">
	<title>Recent 7 days Report</title>
	<link rel="stylesheet" href="css/uikit.min.css"/>
	<script src="js/jquery-1.10.2.js"></script>
	<script src="js/uikit.min.js"></script>
	<script src="js/accounting.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0/angular.min.js"></script>
	<script>
		var store = <?= json_encode($d)?>;
		var store2 = <?= json_encode($recent8)?>;
		var store2_info = <?= json_encode($recent8_info)?>;
//		console.info("store2 " ,store2);
//		console.info("store2_info " ,store2_info);

		function storeCtl($scope)
		{
			$scope.store = store2;
			$scope.store_info = store2_info;
			$scope.cur = function(s)
			{
				return accounting.formatMoney(s, '$ ', 0);
			}
		}
	</script>
</head>
<body>
<div class="uk-width-6-10 uk-container-center">
	<div class="uk-h1">Recent 7 day Report</div>
	<div class="uk-overflow-container" ng-app="" ng-controller="storeCtl">
		<div class="uk-grid">
			<div ng-repeat="d in store">
				<div>{{ d.stitle }}</div>
				<div>
					<div ng-repeat="d2 in store_info[d.stable]">
						{{ d2.date }} | {{ cur(d2.input1) }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>