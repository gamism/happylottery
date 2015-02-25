<?php 

include_once('common.php');
if(!$_SESSION['islogin']) header('location:logout.php');

$regdate = $_POST['regdate'];

$input1 = $_POST['input1'];

$input2 = $_POST['input2'];

$input3 = $_POST['input3'];

$input4 = $_POST['input4'];

$input5 = $_POST['input5'];

$input6 = $_POST['input6'];

$commentMoney = (strlen($_POST['commentMoney']))?$_POST['commentMoney']:0;
$noAuth = (strlen($_POST['noAuth']))?$_POST['noAuth']:0;
$otherincome = (strlen($_POST['otherincome']))?$_POST['otherincome']:0;
$commentWord = $_POST['commentWord'];

$money_arrary = $_REQUEST['money_array'];

$pcsubtotal = $input1-$input2-$input3;

$icash = $pcsubtotal+(($input4-$input5)*100);

$countdiff = ($input4-$input5)*100;

$subtotal = $input6-$icash+($commentMoney+$noAuth)-$otherincome;

$createTime = date("Y-m-d H:i:s");

	$con = mysql_connect($dbhost,$dbuser,$dbpass);
	if (!$con){
	  die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($dbname, $con);

	$sql="REPLACE INTO ".$stable." (date,input1,input2,input3,input4,input5,input6,pcsubtotal,subtotal,icash,createTime,commentWord,commentMoney,otherincome,moneyArray,noAuth) VALUES ('$regdate', '$input1', '$input2', '$input3', '$input4', '$input5', '$input6', '$pcsubtotal', '$subtotal', '$icash','$createTime','$commentWord','$commentMoney','$otherincome','$money_arrary','$noAuth')";

	mysql_query($sql);

	mysql_close($con);

//    $qstr="$regdate,$input1,$input2,$input3,$input4,$input5,$input6,$pcsubtotal,$subtotal,$icash,$countdiff,$money_arrary,$commentWord,$commentMoney";
    $qstr="$regdate";
    header("location:print.php?str=$qstr");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>無標題文件</title>
</head>

<body>

<?php //echo $sql; ?>

<br/>
<br/>
<br/>

<?php //echo $qstr; ?>

</body>

</html>
