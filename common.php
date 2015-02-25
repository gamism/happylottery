<?php
/**
 * Created by JetBrains PhpStorm.
 * User: steven
 * Date: 2011/12/28
 * Time: 下午 11:30
 * To change this template use File | Settings | File Templates.
 */

session_start();

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "h19831026";
$dbname = "lottery";

$con = mysql_connect($dbhost,$dbuser,$dbpass);
if (!$con){
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($dbname, $con);

if($_POST["storeid"])
{
	$sid = $_POST["storeid"];
	$_SESSION['storeid'] = $sid;
}
else
{
	$sid = $_SESSION['storeid'];
}

//if(!$_SESSION['islogin']) header('location:logout.php');

$sql = "select * from store where sid='".$_SESSION['storeid']."'";
$result = mysql_query($sql) or die('MySQL query error');
$store = mysql_fetch_array($result);

$storeName = $store["stitle"];

if($sid == "1")
{
	$stable = 'main';
}
else
{
	$stable = 'main0'.$sid;
}

date_default_timezone_set("Asia/Taipei");
?>
