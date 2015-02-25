<?php
/**
 * Created by JetBrains PhpStorm.
 * User: steven
 * Date: 2012/1/15
 * Time: 下午 7:45
 * To change this template use File | Settings | File Templates.
 */
?>
<?php include_once('common.php')?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>損益表</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="commonjs.js" ></script>
    <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
    <script type="text/javascript">
        function profit(){

            var d = new Date();
            var thisYear = d.getFullYear();
            var thisMonth = parseInt(d.getMonth())+1;

            if(getById('getYear').value!='' && getById('getMonth').value!='')
            {
                thisYear = getById('getYear').value;
                thisMonth = getById('getMonth').value;
            }

            $.ajax({
                url: 'query_ajax.php',
                data: 'action=profit&query='+thisYear+','+thisMonth,
                type: 'POST',
                success: function(data){
                    $('#profit').html(data);
                }
            });
        }

        $(document).ready(function(){
            profit();
        });
    </script>
</head>
<body class="twoColElsLtHdr">
<div id="container">
    <div id="header">
        <h1>樂透彩每日記帳</h1>
    </div>
    <?php if($_SESSION[islogin]) { ?>
    <div id="sidebar1">
        <script type="text/javascript">menu();</script>
    </div>
    <div id="mainContent">
        <fieldset>
            <legend>損益表</legend>
            <form id="form1" name="form1" method="post" action="">
         <p>
             <script type="text/javascript">
                 var d  = new Date();
                 var thisMonth = parseInt(d.getMonth())+1;
             document.write('<label for="getYear">年份<input type="text" name="getYear" value="'+d.getFullYear()+'" id="getYear" /></label>');
             document.write('<label  for="getMonth">月份<input name="getMonth" type="text" value="'+thisMonth+'" id="getMonth"/></label>');
             </script>
             <input name="" type="button" value="查        詢" onclick="profit();" />
            </form>
            <p id='profit'></p>
        </fieldset>
    </div>
    <?php } else { ?>
    <div id="sidebar1">
        <script type="text/javascript">menu();</script>
    </div>
    <div id="mainContent">
        <fieldset>
            <legend>HelloWorld</legend>
            <p>This is work!</p>
        </fieldset>
    </div>
    <?php } ?>
    <br class="clearfloat" />
    <div id="footer">
        <p>頁尾</p>
    </div>
</div>
</body>
</html>