<?php

include_once('common.php');
if(!$_SESSION['islogin']) header('location:logout.php');

$str = ($_GET['str']);

$con = mysql_connect($dbhost,$dbuser,$dbpass);
if (!$con){
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($dbname, $con);

if(!strlen($str))
{
    $str = date("Y").'-'.date("m").'-'.date("d");
}

$sql="select date,input1,input2,input3,input4,input5,input6,pcsubtotal,subtotal,icash,createTime,commentWord,commentMoney,otherincome,moneyArray,noAuth from ".$stable." where date = '".$str."'";
$result = mysql_query($sql) or die('MySQL query error');
$str1 = "";
while($item = mysql_fetch_array($result))
{
    $str1 = $item['date'].','.$item['input1'].','.$item['input2'].','.$item['input3'].','.$item['input4'].','.$item['input5'].','.$item['input6'].','.$item['pcsubtotal'].','.$item['subtotal'].','.$item['icash'].','.(($item['input4']-$item['input5'])*100).','.$item['moneyArray'].','.$item['commentWord'].','.$item['commentMoney'].','.$item['otherincome'].','.$item['noAuth'];
}
	$str = explode(",",$str1);

function printWeek($str)
{
	$datearr = explode("-", $str);
	$year = $datearr[0];
	$month = $datearr[1];
	$day = $datearr[2];
	$dayofweek = getdate(mktime(0, 0, 0, $month, $day, $year));
	$weekname = getWeekDayName($dayofweek['wday']);
	echo "(" . $weekname . ")";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $str[0]?>日報表 - <?php echo $storeName?></title>
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="commonjs.js" ></script>
<style type="text/css">
<!--
.color1 {	color: #00F;
}
.dd1 {
	font-size: 22px;
}
td {
    font-size: 14px;
}
.dd2 {
	font-size: 16px;
    text-justify: distribute-all-lines;
    text-align: justify;
}
.lineHight1 {
    font-size: 18px;
	padding-bottom: 10px;
}
.spe1 {
	font-size: 26px;
}
.dd3 {
	font-size: 12pt;
}
-->
</style>
</head>



<body onload="print();window.close();">

<table width="750" border="0">

  <tr>

    <th width="264" align="left" valign="top" style="width:10cm; height:22.5cm; border-bottom:solid 2px black; font-size: 18px; text-align: center;" scope="col">日報表</th>

    <th width="10" valign="top" style="width:400px; border-left:solid 2px black; border-bottom:solid 2px black; font-size: 22px;" scope="col"><table width="100%" border="0" cellpadding="10">

      <tr>

        <th class="dd2" style="text-align:justify; text-justify:distribute-all-lines; font-size: 18px;" scope="col">登入日期</th>

        <th class="dd3" align="left" scope="col">：<?php echo $str[0]; ?> <?php printWeek($str[0]); ?></th>

      </tr>

      <tr style="display:none;">

        <th width="141" class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="col">零用金</th>

        <th width="213" colspan="2" align="left" scope="col">：$ 5,000元</th>

      </tr>

      <tr>

        <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">銷售總計</th>

        <td colspan="2" style="border: 1px solid black;" align="left"><label>：<span class="dd1">$ <font color="blue"><?php echo number_format($str[1]); ?><font id="sellAmount5">元</font></span></label></td>

      </tr>

      <tr>

        <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">本日電腦型兌獎代墊獎金</th>

        <td colspan="2" align="left"><label class="dd1"> ：$ <?php echo number_format($str[2]); ?><font id="sellAmount4">元</font></label></td>

      </tr>

      <tr>

        <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">本日立即型兌獎代墊獎金</th>

        <td colspan="2" align="left"><label class="dd1"> ：$ <?php echo number_format($str[3]); ?><font id="sellAmount3">元</font></label></td>

      </tr>

      <tr>

        <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">電腦銷售小計</th>

        <td colspan="2" align="left">：<span class="dd1">$ <font id="sellAmount2" color="red"><?php echo number_format($str[7]); ?>元</font></span></td>

      </tr>

      <tr>

        <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">刮刮樂未售出庫存</th>

        <td colspan="2" style="border: 1px solid black;" align="left"><label class="dd1"> ：<?php echo $str[4]; ?></label>張</td>

      </tr>

      <tr>

        <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">刮刮樂本日剩餘總數</th>

        <td colspan="2" style="border: 1px solid black;" align="left" class="dd1">：<?php echo  $str[5]; ?>張</td>

      </tr>

      <tr>

        <th height="20" class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">刮刮樂本日銷售</th>

        <td colspan="2" style="border: 1px solid black;" align="left">：<span class="dd1">$ <font id="sellAmount" color="red"><?php echo number_format($str[10]) ?>元</font></span></td>

      </tr>

      <tr>

        <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">應收現金</th>

        <td colspan="2" align="left">：<font color="#0000FF" class="dd1" id="sellAmount7">$ <?php echo number_format($str[9]); ?>元</font></td>

      </tr>

      <tr>

        <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">現金</th>

        <td colspan="2" style="border: 1px solid black;" align="left">：<font color="#0000FF" class="dd1" id="sellAmount6">$ <?php echo number_format($str[6]); ?>元</font></td>

      </tr>
      
      	<?php if($str[22]!=0) {?>

        <tr>
            <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">未授權</th>
            <td colspan="2" align="left">：<font color="#0000FF" class="dd1" id="sellAmount6">$ <?php echo number_format($str[22]*1);?>元</td>
        </tr>

        <?php } ?>

        <?php if($str[20]!=0) {?>

        <tr>
            <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">其他支出</th>
            <td colspan="2" align="left">：<font color="#0000FF" class="dd1" id="sellAmount6">$ <?php echo number_format($str[20]*1);?>元</td>
        </tr>

        <?php } ?>

        <?php if($str[21]!=0) {?>

        <tr>
            <th class="dd2" style="text-align:justify;text-justify:distribute-all-lines;" scope="row">其他收入</th>
            <td colspan="2" align="left">：<font color="#0000FF" class="dd1" id="sellAmount6">$ <?php echo number_format($str[21]*1);?>元</td>
        </tr>

        <?php } ?>

      <tr>

        <th height="20" class="dd2">銷售損益</th>

        <td colspan="2" align="left">：<span class="dd1">$ <font id="subtotal">
		<?php 
			echo number_format($str[8]); 
		?>
        元</font></span></td>

      </tr>

    </table>

      <table width="364" border="0">

          <?php if($str [11]!=0) {?>

  <tr>

      <th width="84" class="lineHight1" scope="row"><div align="right">2,000元</div></th>

      <td width="17" class="lineHight1">X</td>

      <td width="87" class="lineHight1"><?php echo $str[11];?></td>

      <td width="23" class="lineHight1"> ＝</td>

      <td width="131" class="lineHight1"><?php echo number_format($str[11]*2000);?>元</td>

  </tr>

      <?php } ?>

    <?php if($str [12]!=0) {?>

    <tr>

    <th width="84" class="lineHight1" scope="row"><div align="right">1,000元</div></th>

    <td width="17" class="lineHight1">X</td>

        <td width="87" class="lineHight1"><?php echo $str[12];?></td>

        <td width="23" class="lineHight1"> ＝</td>

        <td width="131" class="lineHight1"><?php echo number_format($str[12]*1000);?>元</td>

  </tr>

    <?php } ?>

    <?php if($str [13]!=0) {?>

  <tr>

    <th class="lineHight1" scope="row"><div align="right">500元</div></th>

    <td class="lineHight1">X</td>

      <td width="87" class="lineHight1"><?php echo $str[13];?></td>

      <td width="23" class="lineHight1"> ＝</td>

      <td width="131" class="lineHight1"><?php echo number_format($str[13]*500);?>元</td>

  </tr>

    <?php } ?>

    <?php if($str [14]!=0) {?>

    <tr>

    <th class="lineHight1" scope="row"><div align="right">100元</div></th>

    <td class="lineHight1">X</td>

        <td width="87" class="lineHight1"><?php echo $str[14];?></td>

        <td width="23" class="lineHight1"> ＝</td>

        <td width="131" class="lineHight1"><?php echo number_format($str[14]*100);?>元</td>

  </tr>

    <?php } ?>

    <?php if($str [15]!=0) {?>

  <tr>

    <th class="lineHight1" scope="row"><div align="right">50元</div></th>

    <td class="lineHight1">X</td>

      <td width="87" class="lineHight1"><?php echo $str[15];?></td>

      <td width="23" class="lineHight1"> ＝</td>

      <td width="131" class="lineHight1"><?php echo number_format($str[15]*50);?>元</td>

  </tr>

    <?php } ?>

    <?php if($str [16]!=0) {?>

  <tr>

    <th class="lineHight1" scope="row"><div align="right">10元</div></th>

    <td class="lineHight1">X</td>

      <td width="87" class="lineHight1"><?php echo $str[16];?></td>

      <td width="23" class="lineHight1"> ＝</td>

      <td width="131" class="lineHight1"><?php echo number_format($str[16]*10);?>元</td>

  </tr>

    <?php } ?>

    <?php if($str [17]!=0) {?>

  <tr>

    <th class="lineHight1" scope="row"><div align="right">5元</div></th>

    <td class="lineHight1">X</td>

      <td width="87" class="lineHight1"><?php echo $str[17];?></td>

      <td width="23" class="lineHight1"> ＝</td>

      <td width="131" class="lineHight1"><?php echo number_format($str[17]*5);?>元</td>

  </tr>

    <?php } ?>

    <?php if($str [18]!=0) {?>

  <tr>

    <th class="lineHight1" scope="row"><div align="right">1元</div></th>

    <td class="lineHight1">X</td>

      <td width="87" class="lineHight1"><?php echo $str[18];?></td>

      <td width="23" class="lineHight1"> ＝</td>

      <td width="131" class="lineHight1"><?php echo number_format($str[18]*1);?>元</td>

  </tr>

    <?php } ?>

    <tr>

    <th colspan="3" scope="row"><div class="lineHight1" style="text-align:center;text-justify:auto;">合計</div></th>

    <td>＝</td>

    <td><span class="lineHight1"><?php echo number_format($str[6]); ?>元</span></td>

  </tr>

</table></th>

  </tr>
<?php if(strlen($str[19])) {?>
  <tr>
    <th colspan="2" align="left" valign="top" scope="row">備註</th>
  </tr>
  <tr>
  <td style="text-align:left; width:100%; font-size: 14pt;">
      <?php echo nl2br(urldecode($str[19])); ?>
  <td>
  </tr>
<?php }?>

</table>
</body>

</html>

