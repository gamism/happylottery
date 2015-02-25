<?php include_once('common.php')?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>金庫管理</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="commonjs.js" ></script>
    <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
    <script type="text/javascript">
        function selectscratch(){
            $.ajax({
                url: 'query_ajax.php',
                data: 'action=selectscratch',
                type: 'POST',
                success: function(data){
                    $('#selectscratch').html(data);
                }
            });
        }

        function showGoldStack(year,month){
            var d = new Date();
            year = (year==undefined)?d.getFullYear():$('input[name="showYear"]').val();
            month = (month==undefined)?d.getMonth()+1:$('select[name="showMonth"]').val();
            $.ajax({
                url: 'query_ajax.php',
                data: 'action=showGoldStack&query='+year+','+month,
                type: 'POST',
                success: function(data){
                    $('#showGoldStack').html(data);
                }
            });
        }

        function add2GoldStack(scratchNumber,serialNumber){
            $.ajax({
                url: 'query_ajax.php',
                data: 'action=add2GoldStack&query='+scratchNumber+'-'+serialNumber,
//                data: 'action=add2GoldStack&query='+serialNumber,
                type: 'POST',
                success: function(){
                    getById('serialnumber').value = '';
                    showGoldStack();
                }
            });
        }

        function submitScratch(){
            var REG=/^[0-9]{1,6}?$/; //isNumber
            var str = '';
            var tmpstr = '';
            var tmpArray = '';
            var serial = getById('serialnumber').value;
            var count=0;
            var oddCount=0;

            serial = $.trim(serial);

            if(serial=='')
            {
                alert('請輸入內容,再按送出!!');
                return false;
            }

            tmpArray = serial.split("\n");
            str += '您送出的內容為:\n';
            str += getById('scratch').options[getById('scratch').selectedIndex].innerHTML+'\n';
            for(var i=0;i<tmpArray.length;i++)
            {
                if(tmpArray[i].match(REG)==null)
                    oddCount++;
                if(tmpArray[i]!='' && tmpArray[i].match(REG)){
                    tmpstr += tmpArray[i]+'\n';
                    count++;
                }
            }

            if(oddCount>=1)
            {
                alert('您輸入的內容,有'+oddCount+'本彩卷的序號,無法辨認!!');
                return false;
            }

            str += '總共'+count+'本彩券,序號如下:\n';
            str += tmpstr;

            if(confirm(str)){
                add2GoldStack(getById('scratch').value,tmpArray);
                return false;
            }
            else
                return false;
//            str += tmpArray.length+'<br/>';
//            for(var i=0;i<tmpArray.length;i++)
//            {
//                if(tmpArray[i]!='')
//                    str += tmpArray[i]+'<br/>';
//            }
//            getById('showScratchSerial').innerHTML += str;
        }

        function fetchAll()
        {
            alert("fetchALL");
            $('input[name="gofetch"]').each(function(){
                $(this).attr("check",true);
            });
        }

        function gofetch(title,sn)
        {
            if(confirm("確定領用?"))
            {
                $.ajax({
                    url: 'query_ajax.php',
                    data: 'action=gofetch&query='+title+','+sn,
                    type: 'POST',
                    success: function(data){
                        showGoldStack();
                    }
                });
            }
        }

        $(document).ready(function(){
            selectscratch();
            showGoldStack();
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
            <legend>金庫管理</legend>
            <table align="center">
                <tr align="right">
                    <td width="15%">100元</td>
                    <td bgcolor="#87cefa" width="5%">&nbsp;</td>
                    <td width="15%">200元</td>
                    <td bgcolor="#ffb6c1" width="5%">&nbsp;</td>
                    <td width="15%">500元</td>
                    <td bgcolor="#90ee90" width="5%">&nbsp;</td>
                    <td width="15%">1000元</td>
                    <td bgcolor="#d8bfd8" width="5%">&nbsp;</td>
                    <td width="15%">2000元</td>
                    <td bgcolor="#ff6347" width="5%">&nbsp;</td>
                </tr>
            </table>
            <table class="scratch">
            <tr>
             <td>彩券名稱</td>
             <td><span id="selectscratch"></span>
             </td>
            </tr>
            <tr><td><label for="serialnumber">彩券序號</label></td><td><textarea name="serialnumber" id="serialnumber" cols="30" rows="10" title="一行序號代表一本彩券,序號最多六位數(由0-9組成)"></textarea></td></tr>
            <tr><td colspan="2" align="right"><input type="button" value="送出" style="width: 90px;" onclick="submitScratch()"></td></tr>
            </table>
            <p id="showGoldStack"></p>
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
