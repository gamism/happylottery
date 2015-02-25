<?php
include_once('common.php');
/**
 * Created by JetBrains PhpStorm.
 * User: steven
 * Date: 2012/1/4
 * Time: 上午 12:56
 * To change this template use File | Settings | File Templates.
 */

$action = $_REQUEST['action'];
$query = $_REQUEST['query'];
if($action){
    $con = mysql_connect($dbhost,$dbuser,$dbpass);
    if (!$con){
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db($dbname, $con);
    $sql='';
    switch($action){
        case 'gofetch':
            $sql =  gofetch($con,$query);
            mysql_query($sql);
            break;
        case 'add2GoldStack':
            $sql =  add2GoldStack($con,$query);
            mysql_query($sql);
            break;
        case 'showGoldStack':
            showGoldStack($query);
            break;
        case 'scratch':
            $sql = scratch($query);
            mysql_query($sql);
            break;
        case 'showData':
            showData($con);
            break;
        case 'delscratch':
            $sql = "delete from g_scratch where sn=".$query;
            mysql_query($sql);
            break;
        case 'selectscratch':
        selectscratch($con);
        break;
        case 'profit':
            profit($con,$query);
            break;
		case 'getStock':
			getStock($stable);
			break;
		case 'weeklyReport':
			weeklyReport();
				break;
        default:
            header('location:index.php');
            break;
    }
    mysql_close($con);
    unset($sql);
    unset($con);
}
else
{
    echo 'hello world';
    header('location:index.php');
}

function weeklyReport()
{
	$sql = "select * ,weekday(date) as theWeekDay, weekday(createTime) as theCreateDay from main03 ORDER BY `date` DESC limit 7";
	$result = mysql_query($sql) or die('weelyreport query failed');

	echo '[';
	while($item = mysql_fetch_array($result))
	{
		echo json_encode($item) . ',';
	}
	echo '{}]';
}

function getStock($stable)
{
	//select * from main02 ORDER BY `date` DESC limit 1
	$sql = "select * from ".$stable." ORDER BY `date` DESC limit 1";
	$result = mysql_query($sql) or die('MySQL query error');
	$item = mysql_fetch_array($result);
	echo $item["input5"];
}

function gofetch($con,$query){
    $query = explode(',',$query);
    $title = $query[0];
    $sn = $query[1];
    $sql = "update goldstack set fetchTime='".now()."' where scratchTitle='".$title."' and serialNumber='".$sn."'";
    return $sql;
}

function add2GoldStack($con,$query){
    $query = explode('-',$query);
    $scratchNumber = $query[0];
    $serial = explode(',',$query[1]);
    $length = count($serial);
    $sql = "insert into goldstack (scratchTitle,serialNumber,registerTime) values";
    for($i=0;$i<$length;$i++)
    {
        if($i!=$length-1)
            $sql .= "('".$scratchNumber."','".$serial[$i]."','".now()."'),";
        elseif($i=$length-1)
            $sql .= "('".$scratchNumber."','".$serial[$i]."','".now()."')";
        else
            $sql .= "('".$scratchNumber."','".$serial[$i]."','".now()."')";
    }
    return $sql;
}

function now(){
    return date('Y-m-d H:i:s');
}

function showGoldStack($query){

    $query = explode(",",$query);
    $year = $query[0];
    $month = $query[1];

    $sql = "select * from goldstack JOIN g_scratch ON goldstack.scratchTitle = g_scratch.sn order by price,title";
    $result = mysql_query($sql) or die('MySQL query error');

    echo '<p class="rCount_title"><span>金庫內容</span></p>';
    echo '<table border="0" width="100%" style="border: solid 1px #000000; text-align: center; margin-top: 10px;">';
    echo '<tr style="background:gray;color: white; text-align: center;"><td width="30%">彩券</td><td width="10%">序號</td><td width="50%">登錄日期<td width="10%">領用</td></tr>';
    while($item = mysql_fetch_array($result))
    {
        if(!strlen($item['fetchTime']))
        {
            echo "<tr>";
            echo '<td>'.$item['title'].'</td>';
            echo '<td>'.$item['serialNumber'].'</td>';
            echo '<td>'.$item['registerTime'].'</td>';
            echo '<td><input type="button" onclick="gofetch('.$item['scratchTitle'].','.$item['serialNumber'].')" value="領用"></td>';
        }
//            echo '<td><input type="checkbox" name="gofetch" value="'.$item['scratchTitle'].','.$item['serialNumber'].'" /></td>';
        echo "</tr>";
    }
//    echo '<tr><td colspan="4" align="right"><input type="button" value="全選" onclick="fetchAll();">&nbsp;<input type="button" value="領用">&nbsp;<input type="button" value="取消選取"></td></tr>';
    echo '</table>';

    $sql = "select * from goldstack JOIN g_scratch ON goldstack.scratchTitle = g_scratch.sn where year(fetchTime) = ".$year." and month(fetchTime) = ".$month."  order by price,title";
    $result = mysql_query($sql) or die('MySQL query error');

    echo '<p class="rCount_title"><span>'.$year.'年'.$month.'月彩券領用資料</span></p>';

    if(strlen($year))
        echo '<p align="left">年度<input type="text" name="showYear" size="10" value="'.$year.'"/>&nbsp;月份';
    else
        echo '<p align="left">年度<input type="text" name="showYear" size="10" value="'.date("Y").'"/>&nbsp;月份';

    echo '<select name="showMonth">';
    for($loopMonth=1;$loopMonth<=12;$loopMonth++)
    {
        if($loopMonth==$month)
            echo '<option selected>'.$loopMonth.'</option>';
        else
            echo '<option>'.$loopMonth.'</option>';
    }

    echo '</select>';
    echo '&nbsp;<input type="button" value="送出" onclick="showGoldStack('.date("Y").','.date("m").');" />';
    echo '</p>';

    echo '<table border="0" width="100%" style="border: solid 1px #000000; text-align: center; margin-top: 10px;">';
    echo '<tr style="background:gray;color: white; text-align: center;"><td width="20%">彩券</td><td width="10%">序號</td><td width="30%">登錄日期</td><td width="30%">領用日期</td><td width="10%">領用</td></tr>';
    while($item = mysql_fetch_array($result))
    {
        if(strlen($item['fetchTime']))
        {
            echo "<tr>";
            echo '<td>'.$item['title'].'</td>';
            echo '<td>'.$item['serialNumber'].'</td>';
            echo '<td>'.$item['registerTime'].'</td>';
            echo '<td>'.$item['fetchTime'].'</td>';
            echo '<td id="fetched">已領用</td>';
            echo "</tr>";
        }
    }
    //    echo '<tr><td colspan="4" align="right"><input type="button" value="全選" onclick="fetchAll();">&nbsp;<input type="button" value="領用">&nbsp;<input type="button" value="取消選取"></td></tr>';
    echo '</table>';
}

function profit($con,$query){

    $dateArray = explode(',',$query);
    $theYear = $dateArray['0'];
    $theMonth = $dateArray['1'];
    $sql = "select *,weekday(date) as theWeekDay from main where year(date)=$theYear and MONTH(date)=$theMonth order by date";
    $result = mysql_query($sql) or die('MySQL query error');
    $row = 0;
    $sum = 0;
    echo '<table border="0" width="100%" style="border: solid 1px #000000; text-align: center; margin-top: 10px;">';
    echo '<tr style="background:gray;color: white; text-align: center;"><td>日期</td><td>損益</td><td>日期</td><td>損益</td></tr>';
    echo "<tr>";
    while($item = mysql_fetch_array($result))
    {
        $subtotal = $item['input6']-$item['icash']+$item['commentMoney'];
        echo '<td>'.$item['date'].'</td>';
        if($item['theWeekDay']==5 || $item['theWeekDay']==6 )
        {
            echo '<td bgcolor="#add8e6">'.$subtotal.'</td>';
        }
        else
        {
            echo '<td>'.$subtotal.'</td>';
        }

        if($row>=1){
            echo "</tr>";
            echo "<tr>";
            $row = 0;
        }else{
            $row++;
        }
        $sum = $sum + $subtotal;
    }
    echo "</tr>";
    echo "<tr bgcolor='#ffc0cb'><td colspan='4'><table border='0' width='100%' style='text-align: center;'><td>損益結算：".$sum."</td></table></td></tr>";
    echo '</table>';
    mysql_free_result($result);
}

function selectscratch($con){
    echo '<select name="scratch" id="scratch">';
    mysql_select_db("scoutbbs_lottery", $con);
    $sql = "select sn,title,price from g_scratch order by price";
    $result = mysql_query($sql) or die('MySQL query error');
    $tmpMoney = 0;
    $tmpCount = 0;
    while($row = mysql_fetch_array($result))
    {
        if($tmpMoney != $row['price'])
        {
            $tmpMoney = $row['price'];
            $tmpCount =1;
        }
        else
        {
            $tmpCount =0;
        }

        if($tmpCount==1)
        {
            switch($row['price']){
                default:
                case '100':
                    echo '<optgroup style="background: #87cefa" label="'.$row['price'].'元">';
                    break;
                case '200':
                    echo '<optgroup style="background: #ffb6c1" label="'.$row['price'].'元">';
                    break;
                case '500':
                    echo '<optgroup style="background: #90ee90" label="'.$row['price'].'元">';
                    break;
                case '1000':
                    echo '<optgroup style="background: #d8bfd8" label="'.$row['price'].'元">';
                    break;
                case '2000':
                    echo '<optgroup style="background: #ff6347" label="'.$row['price'].'元">';
                    break;
            }
        }

        switch($row['price']){
            default:
            case '100':
                echo '<option value="'.$row['sn'].'" style="background: #87cefa">'.$row['title'].'('.$row['price'].')</option>';
                break;
            case '200':
                echo '<option value="'.$row['sn'].'" style="background: #ffb6c1">'.$row['title'].'('.$row['price'].')</option>';
                break;
            case '500':
                echo '<option value="'.$row['sn'].'" style="background: #90ee90">'.$row['title'].'('.$row['price'].')</option>';
                break;
            case '1000':
                echo '<option value="'.$row['sn'].'" style="background: #d8bfd8">'.$row['title'].'('.$row['price'].')</option>';
                break;
            case '2000':
                echo '<option value="'.$row['sn'].'" style="background: #ff6347">'.$row['title'].'('.$row['price'].')</option>';
                break;
        }
//        if($tmpCount==1 && $tmpOdd==1)
//            echo '</optgroup>';
    }
    echo '</select>';
    mysql_free_result($result);
}

function scratch($str){
    $str = explode(',',$str);
    $sql = "replace into g_scratch (title,price,publish,cessation,dueday) values ('$str[0]','$str[1]','$str[2]','$str[3]','$str[4]')";
    return $sql;
}

function showData($con){
        echo '<table border="0" width="100%" style="margin-top: 10px;">';
        echo "<tr style='background:gray;color: white; text-align: center;'>";
        echo "<td>彩券名稱</td><td>售價</td><td>上市日</td><td>下市日</td><td>兌獎截止日</td><td>備註</td>";
        echo  "</tr>";
        mysql_select_db("scoutbbs_lottery", $con);
        $sql = "select * from g_scratch order by price,dueday,publish,cessation";
        $result = mysql_query($sql) or die('MySQL query error');
        while($row = mysql_fetch_array($result))
        {
            if(warningDate($row['dueday'])<90) // 90 days stop exchange money
                echo "<tr bgcolor='pink'>";
            else
                echo "</tr>";
            echo "<td>".$row['title']."</td>";
            echo "<td>".$row['price']."</td>";
            echo "<td>".formatDate($row['publish'])."</td>";
            echo "<td>".formatDate($row['cessation'])."</td>";
            echo "<td>".formatDate($row['dueday'])."</td>";
            echo "<td><a href='#' onclick=\"delscratch('action=delscratch&query=".$row['sn']."')\">刪除</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    mysql_free_result($result);
}

function formatDate($str){
    $str = explode('-',$str);
    $str = substr($str[0],1) .'/'.$str[1].'/'.$str[2];
    return $str;
}

function warningDate($str){
    $str = explode('-',$str);
    $tmpDate = mktime(0,0,0,$str[1],$str[2],substr($str[0],1)+1911);
    return floor(($tmpDate-strtotime("now"))/(60 * 60 * 24));
}
?>