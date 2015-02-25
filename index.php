<?php
include_once('common.php');

$user = $_POST['user'];
$passwd = $_POST['passwd'];

if ($_SESSION['islogin'] != true) {

	if ($user == "playlins" && $passwd == "lins1234") {
		$_SESSION['islogin'] = true;
	}
	elseif ($passwd == "19831026")
	{
		$_SESSION['islogin'] = true;
		$_SESSION['SuperAdmin'] = true;
	}
	else
	{
		// $_SESSION['islogin']=false;
		header('location:login.html');
	}
}

	function genDate(){

		$thisday = date("Y/m/d");

		$thisday = strtotime($thisday);

		echo "<option>".date("Y/m/d",strtotime("-2 days",$thisday))."</option>";

		echo "<option>".date("Y/m/d",strtotime("-1 day",$thisday))."</option>";

		echo "<option selected>".date("Y/m/d",strtotime("now",$thisday))."</option>"; //Today

		echo "<option>".date("Y/m/d",strtotime("+1 day",$thisday))."</option>";

		echo "<option>".date("Y/m/d",strtotime("+2 day",$thisday))."</option>";

	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HappyLottery系統</title>

<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>

<script type="text/javascript" src="commonjs.js" ></script>

<script type="text/javascript">

    function saveChanges(){

		if(getById('pcsubtotal').innerHTML == '0元' && getById('sellAmount').innerHTML == '0元')
		{
			alert('什麼都沒有填入,所以送不出去');
			return false;
		}

        if(parseInt(getById('input4').value)<parseInt(getById('input5').value))
        {
            alert('刮刮樂總數應該不會大於庫存吧!請再次檢查內容!!');
	        getById('input5').value = '';
	        getById('input5').focus();
	        count();
	        return false;
        }

        <?php //for incash money array?>
        getById('money_array').value = "";
        getById('money_array').value += (getById('money_2000').value==0)?0+',':getById('money_2000').value +',';
        getById('money_array').value += (getById('money_1000').value==0)?0+',':getById('money_1000').value +',';
        getById('money_array').value += (getById('money_500').value==0)?0+',':getById('money_500').value +',';
        getById('money_array').value += (getById('money_100').value==0)?0+',':getById('money_100').value +',';
        getById('money_array').value += (getById('money_50').value==0)?0+',':getById('money_50').value +',';
        getById('money_array').value += (getById('money_10').value==0)?0+',':getById('money_10').value +',';
        getById('money_array').value += (getById('money_5').value==0)?0+',':getById('money_5').value +',';
        getById('money_array').value += (getById('money_1').value==0)?0:getById('money_1').value;

        var thisSubtotal;

        thisSubtotal = getById('input6').value-(document.getElementById("input1").value-document.getElementById("input3").value-document.getElementById("input2").value+((document.getElementById("input4").value-document.getElementById("input5").value)*100));
		
		if(getById('commentMoney').value != '') <?php//扣掉其他支出 ?>
			thisSubtotal = parseInt(thisSubtotal) + parseInt(getById('commentMoney').value);
			
		if(getById('noAuth').value != '') <?php//扣掉其他支出 ?>
			thisSubtotal = parseInt(thisSubtotal) + parseInt(getById('noAuth').value);

        if(getById('otherincome').value != '') <?php//扣掉其他收入 ?>
            thisSubtotal = parseInt(thisSubtotal) - parseInt(getById('otherincome').value);

	    /**********************************************************************/

        if(getById('comment').style.display.toString() == "none")
        {
	        
         }
	else <?php//getById('comment').style.display.toString() != "none"?>
	{
		if(thisSubtotal == 0) 
	        {
	        	<?php//沒少錢,損益正負零?>
	        	//document.form3.submit(); 
	        }
		
		<?php//直接結帳確認?>
//		if(getById('directCount').checked == true) 
		{
		    if(thisSubtotal>0)
		    {
			    if(confirm('多'+thisSubtotal+'元，確定直接結帳嗎??'))
			    {
			    	getById('lost').value = thisSubtotal;
			    	document.form3.submit();
			    }
			    else 
			    {
			    	alert("今天損益不是正負零,請輸入其他支出或收入內容");
				getById('subtotalWord').innerHTML = "";
				getById('commentWord').focus();
			    	return false;
			    }
		    }
		    else if(thisSubtotal<0)
		    {
			    if(confirm('少'+thisSubtotal+'元，確定直接結帳嗎??'))
			    {
			    	getById('lost').value = thisSubtotal;
			    	document.form3.submit();
			    }
			    else
			    {
			    	alert("今天損益不是正負零,請補上未授權,其他支出或其他收入金額");
					getById('subtotalWord').innerHTML = "";
					getById('commentWord').focus();
			    	return false;
			    }
		    }
		}
		/*
		else <?php//有其他支出或是收入?>
		{
		    
		    return false;
		}*/
		
		        /*if(getById('commentWord').value=='' && getById('otherincome').value == '' && getById('commentMoney').value == '') <?php//金額內容都為空 ?>
		        {
		                alert("今天損益不是正負零,請輸入其他支出或收入內容");
		                getById('commentWord').focus();
		                return false;
		        }*/

			if(getById('commentWord').value != '' && getById('otherincome').value == '' && getById('commentMoney').value == '' && getById('noAuth').value == '') <?php//金額為空 ?>
			{
				alert('有備註,但是未授權,其他支出或收入尚未填寫!!');
				return false;
			}

			if(getById('commentWord').value=='' && (getById('otherincome').value != '' || getById('commentMoney').value != ''|| getById('noAuth').value != '')) <?php//備註不可為空 ?>
			{
				alert('備註不可為空!!');
				getById('commentWord').focus();
				return false;
			}
			<?php
			//損益非零,該填入的內容也都有
			//如果損益為零,該填入的內容也都有了,就會進入送出?>
			/*if(thisSubtotal != 0 && getById('commentWord').value != '' && (getById('commentMoney').value != '' || getById('otherincome').value != ''|| getById('noAuth').value != ''))
			{
				if(thisSubtotal<0)
				{
					if(confirm('還少'+(-1)*thisSubtotal+'元,對嗎??'))
					{
						getById('lost').value = thisSubtotal;
					}
					else
					{
						return false;
					}
				}
				else if(thisSubtotal>0)
				{
					if(confirm('還多'+thisSubtotal+'元,對嗎??'))
					{
						getById('lost').value = thisSubtotal;
					}
					else
					{
						return false;
					}
				}
			}*/
		
	}

	    /*********************************************************************************/

		if(getById('commentWord').value != "")
        {
        	getById('commentWord').value = encodeURIComponent(getById('commentWord').value);
        }
        document.form3.submit();
    }

    function countCash(){

        var income=0;

        income += (getById('money_2000').value==0)?0:2000*getById('money_2000').value;
        income += (getById('money_1000').value==0)?0:1000*getById('money_1000').value;
        income += (getById('money_500').value==0)?0:500*getById('money_500').value;
        income += (getById('money_100').value==0)?0:100*getById('money_100').value;
        income += (getById('money_50').value==0)?0:50*getById('money_50').value;
        income += (getById('money_10').value==0)?0:10*getById('money_10').value;
        income += (getById('money_5').value==0)?0:5*getById('money_5').value;
        income += parseInt((getById('money_1').value==0)?0:getById('money_1').value);
        getById('cash').innerHTML=income+'元';
        getById('input6').value=income;
        count();
    }

	function count(){

        var pcsell,pcexchange,pcexchange1,amount,inventory;
        var cash = getById('input6').value;

		//電腦銷售
		if (document.getElementById("input1").value!="") {
            pcsell = document.getElementById("input1").value;
		}else{
			pcsell = 0;
		}

		//電腦兌獎
		if (document.getElementById("input3").value!="") {
			pcexchange = document.getElementById("input3").value;
		}else{
			pcexchange = 0;
		}

		//電腦刮刮樂兌獎
		if (document.getElementById("input2").value!="") {
			pcexchange1 = document.getElementById("input2").value;
		}else{
			pcexchange1 = 0;
		}

		//刮刮樂總數
		if (document.getElementById("input4").value!="") {
			amount = document.getElementById("input4").value;
		}else{
			amount = 0;
		}

		//刮刮樂庫存
		if (document.getElementById("input5").value!="") {
			inventory = document.getElementById("input5").value;
		}else{
			inventory = 0;
		}

		//電腦銷售金額
		var pcsubtotal=pcsell-pcexchange-pcexchange1;
		document.getElementById("pcsubtotal").innerHTML = pcsubtotal+"元"+"<input type='hidden' name='pcsubtotal' value='"+pcsubtotal+"'/>";

		//刮刮樂銷售
		var countdiff=(amount-inventory)*100;
		document.getElementById("sellAmount").innerHTML = countdiff+"元"+"<input type='hidden' name='countdiff' value='"+countdiff+"'/>";

		//現金
		var icash=pcsell-pcexchange-pcexchange1+countdiff;
		document.getElementById("incash").innerHTML = " 應收"+icash+"元"+"<input type='hidden' name='icash' value='"+icash+"'/>";

		//小計
		var subtotal=0,
		    noAuth = (getById('noAuth').value=="")?0:getById('noAuth').value;
        var commentMoney = (getById('commentMoney').value=="")?0:getById('commentMoney').value;
        var otherincome = (getById('otherincome').value=="")?0:getById('otherincome').value;
        subtotal = ((cash-icash) + (parseInt (commentMoney)+parseInt(noAuth)) - parseInt (otherincome));

		document.getElementById("subtotal").innerHTML = "";
		getById('subtotalWord').innerHTML = "";
		
		if(subtotal>=0)
        {
			document.getElementById("subtotal").innerHTML = "<font color='blue'>"+subtotal+"元"+"</font><input type='hidden' name='subtotal' value='"+subtotal+"'/>";
            if(subtotal>0 && getById('comment').style.display.toString() == "none")
            {
                getById('subtotalWord').innerHTML = '(<input type="checkbox" id="directCount">有多錢唷!!直接結帳)';
            }
		}
        else if(subtotal<0)
        {
			document.getElementById("subtotal").innerHTML = "<font color='green'>"+subtotal+"元"+"</font><input type='hidden' name='subtotal' value='"+subtotal+"'/>";
		    if(subtotal<0 && getById('comment').style.display.toString() == "none")
		    {
				    getById('subtotalWord').innerHTML = '(<input type="checkbox" id="directCount">直接認賠結帳??)';
		    }
		}
	}
	
	function copyto()
	{
		if(document.getElementById('commentMoney'))
		{
			if(document.getElementById('commentMoney').value != '')
			{
				document.getElementById('comment').style.display='';
			}
		}
	}

function getStock()
{
	$.get("query_ajax.php?action=getStock").success(function(xhr){
		$("#input4").val(parseInt(xhr));
		count();
	});
}
</script>
</head>
<body class="twoColElsLtHdr" onload="copyto();">
<div id="container">
<div id="header">
<h1>樂透彩每日記帳 - <?php print $storeName ?></h1>
  <!-- end #header --></div>
  <div id="sidebar1">
<?php if ($_SESSION[islogin]!=True) {?>
<form id="form1" name="form1" method="post" action="">

  <h3>帳號：

    <label>

      <input name="user" type="text" id="user" size="10" />

    </label>

  </h3>

  <h3>密碼：

    <input name="passwd" type="password" id="passwd" size="10" />

  </h3>

  <span id="submitButton">

    <label>

      <input type="submit" name="Submit" id="Submit" value="登　　　　　入" />

    </label>

  </span>

</form>

    </p>

	<h3>sidebar1 內容</h3>

<p>這個 Div 的背景顏色只會針對內容長度而顯示。如果您想要改用分隔行，而且 #mainContent Div 總是會比 #sidebar1 Div 包含較多內容，請將邊框放置於 #mainContent Div 的左邊。 </p>

    <p>Donec eu mi sed turpis feugiat feugiat. Integer turpis arcu, pellentesque  eget, cursus et, fermentum ut, sapien. </p>

    <?php }else{?>

        <script type="text/javascript">menu();</script>
		<?php
		if($_SESSION['SuperAdmin'] == true)
		{
			echo "<p><a href='list.php'>快速查帳</a></p>";
		}
		?>
	<p>

  <?php } ?>

  <!-- end #sidebar1 --></div>

  <div id="mainContent">

  <?php if ( $_SESSION[islogin]==true) {?>

<fieldset>

  <legend>日結</legend>

  <form id="form3" name="form3" method="post" action="query.php">

    <input type="hidden" id='money_array' name='money_array' value="" />

    <input type="hidden" id='lost' name='lost' value="0" />

    <input type="hidden" id='input6' name='input6' value="" />

    <table width="100%" border="0" cellpadding="10" id='myTable'>

      <tr>

        <th scope="col" style="text-align:justify;"><label for="regdate">登入日期</label></th>

        <th scope="col" align="left">：<select name="regdate" id="regdate">

		<?php echo genDate();?>

		</select></th>

      </tr>

      <tr>

        <th width="150" scope="col" style="text-align:justify;">零用金</th>

        <th width="306" scope="col" align="left">：<span class="color1">5000元&nbsp;(請先把五千拿起來)</span></th>

      </tr>

      <tr>

        <th scope="row" style="text-align:justify;">銷售總計</th>

        <td><label>：

          <input type="text" name="input1" id="input1" onchange="count()" />

          </label></td>

      </tr>

      <tr>

        <th scope="row" style="text-align:justify;">本日電腦型兌獎代墊獎金</th>

        <td><label>

          ：

              <input type="text" name="input2" id="input2" onchange="count()" />

        </label></td>

      </tr>

      <tr>

        <th scope="row" style="text-align:justify;">本日立即型兌獎代墊獎金</th>

        <td><label>

          ：

            <input type="text" name="input3" id="input3" onchange="count()" />

        </label></td>

      </tr>

      <tr>

        <th scope="row" style="text-align:justify;">電腦銷售小計</th>

        <td>：<font id="pcsubtotal" color="#0000FF">0元</font></td>

      </tr>

      <tr>

        <th scope="row" style="text-align:justify;">刮刮樂未售出庫存</th>

        <td><label>

          ：

            <input type="text" name="input4" id="input4" size="8" maxlength="20" onchange="count()" />&nbsp;<input type="button" onclick="getStock();" value="帶入最後一次資料"/>

        </label></td>

      </tr>

      <tr>

        <th scope="row" style="text-align:justify;">刮刮樂本日剩餘總數</th>

        <td>：          <input type="text" name="input5" maxlength="20" id="input5" onchange="count()"/></td>

      </tr>

      <tr>

        <th height="20" style="text-align:justify;" scope="row">刮刮樂本日銷售</th>

        <td>：<font id="sellAmount" color="#0000FF">0元</font></td>

      </tr>

      <tr>

        <th scope="row" style="text-align:justify;">現金統計</th>

        <td>

            <table id='countCash'>

                <tr style="display:none;">

                    <td align="right"><label for="money_2000">2,000元 X</label></td>

                    <td><input id='money_2000' size='12' onchange="countCash()"/></td>

                </tr>

                <tr>

                    <td align="right">1,000元 X</td>

                    <td><input id='money_1000' size='12' onchange="countCash()"/></td>

                </tr>

                <tr>

                    <td align="right">5,00元 X</td>

                    <td><input id='money_500' size='12' onchange="countCash()"/></td>

                </tr>

                <tr>

                    <td align="right">100元 X</td>

                    <td><input id='money_100' size='12' onchange="countCash()"/></td>

                </tr>

                <tr>

                    <td align="right">50元 X</td>

                    <td><input id='money_50' size='12' onchange="countCash()"/></td>

                </tr>

                <tr>

                    <td align="right">10元 X</td>

                    <td><input id='money_10' size='12' onchange="countCash()"/></td>

                </tr>

                <tr>

                    <td align="right">5元 X</td>

                    <td><input id='money_5' size='12' onchange="countCash()"/></td>

                </tr>

                <tr>

                    <td align="right">1元 X</td>

                    <td><input id='money_1' size='12' onchange="countCash()"/></td>

                </tr>

                <tr>

                    <td>總計</td>

                    <td><span id='cash'></span></td>

                    <td><span id='incash'></span></td>

                </tr>

            </table>

<!--          <font id="incash" color="#0000FF"></font>-->

        </td>

      </tr>

      <tr>

        <th height="20" style="text-align:justify;text-justify:" scope="row">本日銷售損益</th>

        <td>：<font id="subtotal" color="#0000FF">0元</font>&nbsp;&nbsp;<span id="subtotalWord"></span></td>

      </tr>

        <tr id='comment'>

            <td><label for="commentWord">備註</label></td>
            <td>
                <p><label for="noAuth">未授權：</label><input type="text" id="noAuth" name="noAuth" onchange="count()"/></p>
                <p><label for="commentMoney">其他支出：</label><input type="text" id="commentMoney" name="commentMoney" onchange="count()"/></p>
                <p><label for="otherincome">其他收入：</label><input type="text" id="otherincome" name="otherincome" onchange="count()"/></p>
                <p><textarea id="commentWord" name="commentWord" cols="30" rows="6"></textarea></p>
            </td>
        </tr>
    </table>
    <input name="送出" type="button" onClick="return saveChanges();" value="送                                        出" />
  </form>
</fieldset>
<?php }else{?>

<h1>主要內容 </h1>

    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent aliquam,  justo convallis luctus rutrum, erat nulla fermentum diam, at nonummy quam  ante ac quam. Maecenas urna purus, fermentum id, molestie in, commodo  porttitor, felis. Nam blandit quam ut lacus. </p>

    <p>Quisque ornare risus quis  ligula. Phasellus tristique purus a augue condimentum adipiscing. Aenean  sagittis. Etiam leo pede, rhoncus venenatis, tristique in, vulputate at,  odio. Donec et ipsum et sapien vehicula nonummy. Suspendisse potenti. Fusce  varius urna id quam. Sed neque mi, varius eget, tincidunt nec, suscipit id,  libero. In eget purus. Vestibulum ut nisl. Donec eu mi sed turpis feugiat  feugiat. Integer turpis arcu, pellentesque eget, cursus et, fermentum ut,  sapien. Fusce metus mi, eleifend sollicitudin, molestie id, varius et, nibh.  Donec nec libero.</p>

    <h2>H2 層級標題 </h2>

    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent aliquam,  justo convallis luctus rutrum, erat nulla fermentum diam, at nonummy quam  ante ac quam. Maecenas urna purus, fermentum id, molestie in, commodo  porttitor, felis. Nam blandit quam ut lacus. Quisque ornare risus quis  ligula. Phasellus tristique purus a augue condimentum adipiscing. Aenean  sagittis. Etiam leo pede, rhoncus venenatis, tristique in, vulputate at, odio.</p>

        <?php }?>

	<!-- end #mainContent --></div>

	<!-- 這個清除元素應該緊接在 #mainContent Div 之後，以便強制 #container Div 包含所有子浮動 --><br class="clearfloat" />

   <div id="footer">

<p>Copyright © Happy Lottery</p>

  <!-- end #footer --></div>

<!-- end #container --></div>
</body>

</html>