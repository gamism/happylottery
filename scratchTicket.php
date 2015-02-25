<?php include_once('common.php')?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>刮刮樂管理</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="commonjs.js" ></script>
    <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
    <script type="text/javascript">
        function savechanges(){
            $.ajax({
                url: "query_ajax.php",
                type: "POST",
                data: gendata(),
                success: function(){showData();}
            });
            getById('title').value= '';
            getById('price').value= '';
            getById('publish').value= '';
            getById('cessation').value= '';
            getById('dueday').value = '';
        }

        function delscratch(str){
            if(confirm('確定刪除?'))
            {
                $.ajax({
                    url: "query_ajax.php",
                    type: "POST",
                    data: str,
                    success: function(){showData();},
                    error: function(){alert('請稍候再試');}
                });
            }
        }

        function gendata(){
            var str='';
            str += 'action=scratch&query=';
            str += getById('title').value+',';
            str += getById('price').value+',';
            str += getById('publish').value+',';
            str += getById('cessation').value+',';
            str += getById('dueday').value;
            return str;
        }

        function showData(){
            $.ajax({
                url: 'query_ajax.php',
                data: 'action=showData',
                type: 'POST',
                success: function(data){
                    $('#showData').html(data);
                }
            });
        }

        function copyto()
        {
            showData();
        }
    </script>
</head>
<body onload="copyto();" class="twoColElsLtHdr">
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
            <legend>刮刮樂管理</legend>
            <form name="scratchTicket">
                <table class="scratch" id="scratch">
                    <tr>
                        <td>項目</td>
                        <td>內容</td>
                        <td>範例</td>
                    </tr>
                    <tr>
                        <td id="item">彩券名稱</td>
                        <td id="content"><input type="text" id="title"></td>
                        <td>龍年行大運</td>
                    </tr>
                    <tr>
                        <td id="item">售價</td>
                        <td id="content"><input type="text" id="price"></td>
                        <td>100</td>
                    </tr>
                    <tr>
                        <td id="item">上市日</td>
                        <td id="content"><input type="text" id="publish"></td>
                        <td>101/1/2</td>
                    </tr>
                    <tr>
                        <td id="item">下市日</td>
                        <td id="content"><input type="text" id="cessation"></td>
                        <td>101/6/2</td>
                    </tr>
                    <tr>
                        <td id="item">兌獎截止日</td>
                        <td id="content"><input type="text" id="dueday"></td>
                        <td>101/10/2</td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right"><input type="button" size="20" value="送出" onclick="savechanges()" /></td>
                    </tr>
                </table>
            </form>
            <table width="100%">
                <tr>
                    <td bgcolor="pink" width="15%"></td>
                    <td width="35%">90天內兌獎截止</td>
                    <td width="15%"></td>
                    <td width="35%"></td>
                </tr>
            </table>
            <div id="showData" style="width: 100%"></div>
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