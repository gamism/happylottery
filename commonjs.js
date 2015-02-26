/**
 * Created by JetBrains PhpStorm.
 * User: steven
 * Date: 2011/12/25
 * Time: 下午 10:04
 * To change this template use File | Settings | File Templates.
 */
function dw(str){
    document.write(str);
}
function getById(obj){
    return document.getElementById(obj);
}
function menu(){
    document.write('<form id="form2" name="form2" method="post" action="logout.php">');
    dw('<p>管理員你好阿！</p>');
    dw('<p><input type="submit" name="button" id="button" value="登　　　　　出" /></p>');
    document.write('</form>');
    //document.write('<p><a href="index.php">輸入日報表</a></p>');
//    document.write('<p><a href="scratchTicket.php">刮刮樂管理</a></p>');
//    document.write('<p><a href="profit.php">損益表</a></p>');
//    document.write('<p><a href="rCount.php">金庫管理</a></p>');
//    document.write('<p>---尚未完成---</p>');
}
