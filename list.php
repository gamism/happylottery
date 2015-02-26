<?php include_once('common.php')?>

<?php
if (!$_SESSION['islogin']) header('location:logout.php');

$theYear = $_POST['getYear'];

$theMonth = $_POST['getMonth'];

?>

<!DOCTYPE html>
<html>
<head lang="zh-tw">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HappyLottery系統 - <?php echo $storeName?></title>
	<script src="js/jquery-1.10.2.js"></script>
	<link rel="stylesheet" href="css/uikit.min.css"/>
	<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript">
    function detail(str){
        str = decodeURIComponent(str);
        alert(str);
    }
</script>

</head>

<body class="uk-width-8-10 uk-container-center twoColElsLtHdr">

<div id="container2">

  <div id="header">

<h1>樂透彩每日記帳 - <?php echo $storeName?></h1>

  </div>

  <div id="mainContent">
	  <script>
		  $.get("query_ajax.php?action=weeklyReport").success(
			  function(xhr){
//				  console.log($.parseJSON(xhr));
			  }
		  )
	  </script>
	  <?php genMenu();?>
      <form id="form1" name="form1" method="post" action="">

        <?php if($theYear!="" and $theMonth!=""){?>

         <p>年份<input type="text" name="getYear" value="<?php echo $theYear ?>" id="getYear" />月份<input name="getMonth" type="text" value="<?php echo $theMonth ?>" /><input name="" type="submit" value="查        詢" />

         <?php }else{?>

         <p>年份<input type="text" name="getYear" value="<?php echo date('Y') ?>" id="getYear" />月份<input name="getMonth" type="text" value="<?php echo date('m') ?>" /><input name="" type="submit" value="查        詢" />

         <?php }?>

      </form>

      <form id="form2" name="form2" method="post" action="logout.php">

          <input type="submit" name="button" id="button" value="登　　　　　出" />

      </form>
	  <hr/>

	  <table width="100%" border="1">

    <tr>

      <th>登入日期</th>

<!--      <td>登入星期</th>-->

      <th>電腦銷售</th>

    <th>電腦兌獎</th>

    <th>電腦刮刮樂兌獎</th>

<!--    <td>電腦銷售小計</th>-->

    <th>刮刮樂總數</th>

    <th>刮刮樂庫存</th>

    <th>刮刮樂銷售小計</th>
	  
    <th>現金</th>

    <th>應收現金</th>

    <th>銷售損益</th>
    
    <th>備註</th>

    <th>建立時間</th>

  </tr>

  <?php

	if($theYear!="" and $theMonth!=""){

		$sql = "SELECT *, weekday(date) as theWeekDay, weekday(createTime) as theCreateDay FROM ".$stable." where year(date)=$theYear and MONTH(date)=$theMonth order by date";

	}else{

		$sql = "SELECT *, weekday(date) as theWeekDay, weekday(createTime) as theCreateDay FROM ".$stable." where year(date)=".date(Y)." and MONTH(date)=".date(m)." order by date";

	}

	$result = mysql_query($sql);

	$input1Total = 0;

	$pcSellTotal = 0;

	$scratchTotal = 0;

	while($row = mysql_fetch_array($result)) {
		if ($row['theWeekDay'] != $row['theCreateDay']) {
			echo "<tr style='background: #bc8f8f'>";
		} else {
			echo "<tr>";
		}

?>

	  <td><?php echo $row['date'] . "(" . getWeekDayName($row['theWeekDay']) . ")"; ?></td>

<!--	  <td>--><?php //echo getWeekDayName($row['theWeekDay']); ?><!--</td>-->

	  <td style="background: #90ee90;"><?php echo '$ ' . number_format($row['input1']); ?></td>

      <td><?php echo $row['input2']; ?></td>

      <td><?php echo $row['input3']; ?></td>

<!--      <td>--><?php //echo $row['pcsubtotal']; ?><!--</td>-->

      <td><?php echo $row['input4']; ?></td>

      <td><?php echo $row['input5']; ?></td>

      <td style="background: lightblue;"><?php echo '$ ' . number_format(($row['input4']-$row['input5'])*100); ?></td>

      <td><?php echo $row['input6']; ?></td>

      <td><?php echo $row['icash']; ?></td>

		
      <td><?php echo $row['subtotal'];	?></td>

      <td><?php 
	  	if(strlen($row['commentWord']))
			echo '<input type="button" value="備註" onclick="detail(\''.'其他收入:'.$row['otherincome'].'元\n'.'其他支出:'.$row['commentMoney'].'元\n'.addslashes($row['commentWord']).'\');" />';
		else
			echo '&nbsp;';
		?>
      </td>
      
      <td><?php echo $row['createTime'] ; ?></td>

    </tr>

  <?php
  		$input1Total += $row['input1'];
		$pcSellTotal += (($row['input4']-$row['input5'])*100);
		$scratchTotal += ($row['subtotal']);
	}

?>

</table>

  <p>電腦銷售統計：$ <?php echo number_format($input1Total);?>元</p>

  <p>刮刮樂銷售統計：$ <?php echo number_format($pcSellTotal);?>元</p>

  <p>電腦+刮刮樂：$ <?php echo number_format($input1Total+$pcSellTotal);?>元</p>

  <p>銷售損益統計：$ <?php echo number_format($scratchTotal);?>元</p>

<?php mysql_close($con);?>

<!-- end #mainContent --></div>

	<!-- 這個清除元素應該緊接在 #mainContent Div 之後，以便強制 #container Div 包含所有子浮動 --><br class="clearfloat" />

   <div id="footer">

<p>頁尾</p>

  <!-- end #footer --></div>

<!-- end #container --></div>

</html>
