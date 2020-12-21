<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=20;
$link=connect();
setcookie('member[name]','testUser');
 if(!(is_login($link)))
 {
    header("Location:tohome2.php");
 }
$user=$_COOKIE['member']['name'];
$query="select * from member where name='$user'";
$result=execute($link,$query);
$data=mysqli_fetch_assoc($result);
$memberID=$data['id'];
if(isset($_POST['comment'])&&$_POST['comment']!="")
{
    $query="insert into content(module_id,text,time,member_id,likes) values('{$moduleID}','{$_POST['comment']}',now(),'{$memberID}',0)";
    execute($link,$query);
    unset($_POST['comment']);
    header("location:新江湾湿地公园_20.php?view='comment'");
}
opHeader();
if(!isset($_GET['view']))
{
   
    opIntro();
}
else
{
    $view=$_GET['view'];
    $view=str_replace('\'','',$view);
   //var_dump($view);
    if($view=='intro')
    {
        opIntro();
        $p=<<<A
        <p id='intro'></p>
A;
        echo $p;
    }
    else
    {
        opComment($link);
    }
}
?>


<?php
function opComment($link)
{  
    //修改，每个页面唯一
    $moduleID=20;
    $CommentInput=<<<A
    <div style="height:350px;"></div>
    <div class="js">
    <br/><br/>
    <div style="width:100%;text-align:center;">
        <form name="comment" method="post" action="">
            <input type="text" name="comment"style="width:540px;height:80px;"><br/>
            <input type="submit" value="submit" name="submit" style="margin-left:490px">
        </form>
    </div>
</div>
A;
echo $CommentInput;
//href要改成本网址
$sortBox=<<<A
<div class="js">
<br/>
<div  style="font-size:15px;margin:0px auto 0px 550px;">
    <a style="color:#00CCFF;text-decoration: 0;"href=新江湾湿地公园_20.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=新江湾湿地公园_20.php?sortWay="likes"&view="comment">按赞数</a>
</div>
<br/>
</div>
A;
echo $sortBox;
if(!isset($_GET['sortWay']))
{
    $sortWay="time";
}
else
{
    $sortWay=$_GET['sortWay'];
    $sortWay=str_replace('"','',$sortWay);
}
if($sortWay=="time")
{
    $request="select *  from content where module_id={$moduleID} order by id DESC";
}
else
{
    $request="select * from content where module_id={$moduleID} order by likes DESC";
}
$result =execute($link,$request);
while ($data=mysqli_fetch_assoc($result))
{
    $myUser=$_COOKIE['member']['name'];
    $query="select * from member where name='$myUser'";
    $rslt=execute($link,$query);
    $arr=mysqli_fetch_assoc($rslt);
    $likesID=$data['likes_id'];
    $idStr=strval($arr['id']);
    $thumbId="thumbIcon".$data['id'];
    $query="select * from member where id='{$data['member_id']}'";
    $rslt=execute($link,$query);
    $arr=mysqli_fetch_assoc($rslt);
    $user=$arr['name'];
    //下面html中要修改URL为当前页面网址
    $BoxWithDel=<<<A
    <div class="js">
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="新江湾湿地公园_20.php">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-thumbs-up" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16v-1c.563 0 .901-.272 1.066-.56a.865.865 0 0 0 .121-.416c0-.12-.035-.165-.04-.17l-.354-.354.353-.354c.202-.201.407-.511.505-.804.104-.312.043-.441-.005-.488l-.353-.354.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315L12.793 9l.353-.354c.353-.352.373-.713.267-1.02-.122-.35-.396-.593-.571-.652-.653-.217-1.447-.224-2.11-.164a8.907 8.907 0 0 0-1.094.171l-.014.003-.003.001a.5.5 0 0 1-.595-.643 8.34 8.34 0 0 0 .145-4.726c-.03-.111-.128-.215-.288-.255l-.262-.065c-.306-.077-.642.156-.667.518-.075 1.082-.239 2.15-.482 2.85-.174.502-.603 1.268-1.238 1.977-.637.712-1.519 1.41-2.614 1.708-.394.108-.62.396-.62.65v4.002c0 .26.22.515.553.55 1.293.137 1.936.53 2.491.868l.04.025c.27.164.495.296.776.393.277.095.63.163 1.14.163h3.5v1H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
    </svg>
    </a>
    <span> {$data['likes']} </span>
    </div>
    <div class="userName">
    {$user}
    </div>
    <div class="commentText">
    {$data['text']}
    </div>
    <div class="commentMes" >
    <span style="color:gray";font-size:10px;>{$data['time']}&nbsp&nbsp&nbsp
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="新江湾湿地公园_20.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="新江湾湿地公园_20.php">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-hand-thumbs-up" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M6.956 1.745C7.021.81 7.908.087 8.864.325l.261.066c.463.116.874.456 1.012.965.22.816.533 2.511.062 4.51a9.84 9.84 0 0 1 .443-.051c.713-.065 1.669-.072 2.516.21.518.173.994.681 1.2 1.273.184.532.16 1.162-.234 1.733.058.119.103.242.138.363.077.27.113.567.113.856 0 .289-.036.586-.113.856-.039.135-.09.273-.16.404.169.387.107.819-.003 1.148a3.163 3.163 0 0 1-.488.901c.054.152.076.312.076.465 0 .305-.089.625-.253.912C13.1 15.522 12.437 16 11.5 16v-1c.563 0 .901-.272 1.066-.56a.865.865 0 0 0 .121-.416c0-.12-.035-.165-.04-.17l-.354-.354.353-.354c.202-.201.407-.511.505-.804.104-.312.043-.441-.005-.488l-.353-.354.353-.354c.043-.042.105-.14.154-.315.048-.167.075-.37.075-.581 0-.211-.027-.414-.075-.581-.05-.174-.111-.273-.154-.315L12.793 9l.353-.354c.353-.352.373-.713.267-1.02-.122-.35-.396-.593-.571-.652-.653-.217-1.447-.224-2.11-.164a8.907 8.907 0 0 0-1.094.171l-.014.003-.003.001a.5.5 0 0 1-.595-.643 8.34 8.34 0 0 0 .145-4.726c-.03-.111-.128-.215-.288-.255l-.262-.065c-.306-.077-.642.156-.667.518-.075 1.082-.239 2.15-.482 2.85-.174.502-.603 1.268-1.238 1.977-.637.712-1.519 1.41-2.614 1.708-.394.108-.62.396-.62.65v4.002c0 .26.22.515.553.55 1.293.137 1.936.53 2.491.868l.04.025c.27.164.495.296.776.393.277.095.63.163 1.14.163h3.5v1H8c-.605 0-1.07-.081-1.466-.218a4.82 4.82 0 0 1-.97-.484l-.048-.03c-.504-.307-.999-.609-2.068-.722C2.682 14.464 2 13.846 2 13V9c0-.85.685-1.432 1.357-1.615.849-.232 1.574-.787 2.132-1.41.56-.627.914-1.28 1.039-1.639.199-.575.356-1.539.428-2.59z"/>
    </svg>
    </a>
    <span> {$data['likes']} </span>
    </div>
    <div class="userName" >
    {$user}
    </div>
    <div class="commentText">
    {$data['text']}
    </div>
    <div class="commentMes" >
    <span style="color:gray";font-size:10px;>
    {$data['time']}&nbsp&nbsp&nbsp
    <span>
    </div>
    </div>
    </div>
A;
    if($user==$_COOKIE['member']['name'])
    {
        echo $BoxWithDel;
    }
    else
    echo $BoxWithNoDel;
    if(preg_match("~\b{$idStr}\b~",$likesID)&&$data['likes']>0)
    {
        $thumbColor=<<<A
        <script type="text/javascript">
        oThumb = document.getElementById('{$thumbId}');
        oThumb.className ="Approved";
        </script>
    A;
    }
    else
    {
        $thumbColor=<<<A
        <script type="text/javascript">
        oThumb = document.getElementById("{$thumbId}");
        oThumb.className ="Unapproved";
        </script>
    A;
    }
    echo $thumbColor;
}
$space= <<<A
<div class="js">
<br/><br/><br/>
</div>
A;
echo $space;
}
?>


<?php
function opIntro()
{
    $intro=<<<A
    <div id="bg"></div>
    <div class="js">
        <div class="jianjie">
            <div class="words">
                <h3>发展历史</h3>
                <p>
                    江湾地区沿江滩地状况结束于清朝。雍正十年（1732年），宝山县令胡济仁沿浦江保留各通江小河口，
                    从蕴藻浜南岸至虬江路筑衣周塘（堤），衣周塘当时抵挡了江潮侵袭，
                    但浦江河口一带水情复杂多变，多年后塘堤时常塌陷，于是天然河网交错，沟浜密布，湿地连片。
                </p>
                <p>
                    上世纪30年代，江湾建造了军用机场，1994年停用。1997年转为民用地。长年的军事禁地，
                    少有人烟，林灌、森林、湿地等等生态环境复出，重新串起了接近“原生态”的脉络。
                    而钢筋水泥开始触须般的延伸整个上海，这里突兀地成了自然孤岛，鸟儿的天堂。
                </p>
            </div>
            <div class="img">
                <img src="image/新江湾湿地公园/概述.jpg" alt="概述图">
            </div>
            <div class="words clear">
                <p>
                    未来4大示范居住区。政府利用江湾机场这块宝贵的土地资源进行规划、投资，
                    开发后将建成以21世纪知识型、生态型住宅区和花园城区为方向的新江湾。江湾是一个千年古镇，
                    9000亩的旺地，黄浦江活水从旁贯通。它历史人文底蕴深厚，宋代抗金名将韩世忠曾屯兵于此，
                    佛教名刹玉佛寺原址也位于此地，朱自清、叶圣陶、夏衍曾在此创办立达学院。
                    因此，江湾在历史上商贾如云，是商家的必争 之地，是闻名遐迩的“铜江湾”。
                </p>
                <p>
                    上海东北角的新江湾城湿地公园，是世界上最大、功能最全、难度最高、施工质量最好的永久性极限运动公园。
                    整个公园的占地面积达到12000平方米，远远超过了9000平方米的美国极限运动公园。生态价值市区一块大型“绿肺”。
                </p>
                <p>
                    公园内拥有世界上长度最长、高度最高的“U”台及深度达到5米的“U”池，
                    这些设施在世界上都是绝无仅有的，是众多极限运动参与者想要征服的极限平台。
                </p>
                <p>
                    1986后，江湾长期处于封闭状态，致使这里的生物群落少有人为干扰，各物种组成都接近“原生态”。
                    后来，上海市自然博物馆各学科专家组对新江湾城进行了生态和物种调查。
                </p>
            </div>
        </div>
        <div class="ziranziyuan">
            <div class="img">
                <img src="image/新江湾湿地公园/自然资源.jpg" alt="自然资源">
            </div>
            <div class="words">
                <h3>自然资源</h3>
                <p>
                    调查发现，在原江湾机场上，林灌型、森林型、湿地型、农田型的生态环境纷纷复出，
                    现有36种鸟类在此间雀跃、翱翔，占到上海地区夏季鸟类种数的88%以上，超过了佘山地区；
                    其中包括3种国家二类保护鸟类、12种中日候鸟保护协定中的珍贵鸟种；还有上海新发现的小鸦鹃、
                    市区罕见的“雀中猛禽”伯劳；在3块绿地的水体中，
                    还发现了7种鱼类，其中棒花鱼、食纹鱼均为市区罕见，灰巴蜗牛、背角天齿蚌也早在市区绝迹。
                </p>
                <p>
                    如此良好的生态环境立即引起华东师大、复旦大学等高校众多专家的关注，他们纷纷指出：
                    “这是上海市区惟一一块自然生态‘绿宝石’！”对于其蕴含的价值，华东师范大学陆健健教授曾指出，
                    保持原生态和保持水面率对于提高商品房的价格是有利的，一块绿色生态园区的存在，
                    不仅提高了江湾地区绿化覆盖率，并且由于江湾绿地中有相当一块原生湿地的存在，
                    使得这块绿地兼有肺与肾的功能，能够改善小气候，形成绿岛效应。
                    这些都将使新江湾城更适于人的居住，也会相应提高周边房地产的价值。
                </p>
                <p>
                    公园内拥有世界上长度最长、高度最高的“U”台及深度达到5米的“U”池，
                    这些设施在世界上都是绝无仅有的，是众多极限运动参与者想要征服的极限平台。
                </p>
            </div>
        </div>
        <div class="zhuyaojingdian">
            <h3>主要景点</h3>
            <div class="zhuyaojingdian1">
                <div class="img">
                    <img src="image/新江湾湿地公园/主要景点1.jpg" alt="主要景点1">
                </div>
                <div class="words">
                    <p>
                        这里是上海市区唯一一块自然生态“绿宝石”，被列入上海未来4大示范居住区。政府利用江湾机场这块宝贵的土地资源进行规划、 投资，
                        开发后将建成以21世纪知识型、生态型住宅区和花园城区为方向的新江湾。江湾是一个千年古镇，9000亩的旺地，黄浦江活水从旁贯通。
                        它历史人文底蕴深厚，宋代抗金名将韩世忠曾屯兵于此，佛教名刹玉佛寺原址也位于此地，朱自清、叶圣陶、夏衍曾在此创办立达学院。
                        因此，江湾在历史上商贾如云，是商家的必争 之地，是闻名遐迩的“铜江湾”。 
                        上海东北角的新江湾城湿地公园，是世界上最大、功能最全、难度最高、施工质量最好的永久性极限运动公园。
                    </p>
                </div>
            </div>
            <div class="zhuyaojingdian2">
                <div class="img">
                    <img src="image/新江湾湿地公园/主要景点2.jpg" alt="主要景点2">
                </div>
                <div class="words">
                    <p>
                        整个公园的占地面积达到12000平方米，远远超过了9000平方米的美国极限运动公园。生态价值市区一块大型“绿肺”1986后，
                        江湾长期处于封闭状态，致使这里的生物群落少有人为干扰，各物种组成都接近“原生态”。
                        后来，上海市自然博物馆各学科专家组对新江湾城进行了生态和物种调查。调查发现，在原江湾机场上，
                        林灌型、森林型、湿地型、农田型的生态环境纷纷复出，现有36种鸟类在此间雀跃、翱翔，占到上海地区夏季鸟类种数的88%以上，
                        超过了佘山地区；其中包括3种国家二类保护鸟类、12种中日候鸟保护协定中的珍贵鸟种；还有上海新发现的小鸦鹃、市区罕见的“雀中猛禽”伯劳；
                        在3块绿地的水体中，还发现了7种鱼类，其中棒花鱼、食纹鱼均为市区罕见，灰巴蜗牛、背角天齿蚌也早在市区绝迹。
                    </p>
                </div>
            </div>
            <div class="zhuyaojingdian3">
                <div class="words">
                    <p>
                        如此良好的生态环境立即引起华东师大、复旦大学等高校众多专家的关注，他们纷纷指出：
                        “这是上海市区惟一一块自然生态‘绿宝石’！”对于其蕴含的价值，华东师范大学陆健健教授曾指出，
                        保持原生态和保持水面率对于提高商品房的价格是有利的，一块绿色生态园区的存在，不仅提高了江湾地区绿化覆盖率，
                        并且由于江湾绿地中有相当一块原生湿地的存在，使得这块绿地兼有肺与肾的功能，能够改善小气候，形成绿岛效应。
                        这些都将使新江湾城更适于人的居住，也会相应提高周边房地产的价值。
                    </p>
                </div>
                <div class="img">
                    <img src="image/新江湾湿地公园/主要景点3.jpg" alt="主要景点3">
                </div>
            </div>
            <div class="zhuyaojingdian4">
                <div class="words">
                    <p>
                        新江湾城湿地公园，是世界上最大、功能最全、难度最高、施工质量最好的永久性极限运动公园。
                        整个公园的占地面积达到12000平方米，远远超过了9000平方米的美国极限运动公园。
                        公园内拥有世界上长度最长、高度最高的“U”台及深度达到5米的“U”池，
                        这些设施在世界上都是绝无仅有的，是众多极限运动参与者想要征服的极限平台。
                    </p>
                </div>
            </div>
        </div>
    </div>
    A;
    echo $intro;

}
?>



<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>

    <div style="position:fixed; bottom: 10%;left: 10px ">
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="新江湾湿地公园_20.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="新江湾湿地公园_20.php?view='comment'">看评论</a>
    </div>
    <?php
    if(!isset($_GET['view']))
    {
        $command=<<<A
      <script type="text/javascript" >setTimeout("moveUp()",500); </script>
A;
        echo $command;
    }
    else
    {
        $view=$_GET['view'];
        $view=str_replace('\'','',$view);
        if($view=='intro')
        {
            $command=<<<A
      <script type="text/javascript" >setTimeout("moveUp()",500); </script>
A;
            echo $command;
        }
    }
    ?>
    
    <p id="arrow" style="color:rgba(255, 255, 255);"> 
    <!-- 点此处继续浏览 -->
    </p>
    </div>
     <!-- <a name="CommentArea"></a>  -->
</body>
<script>
    window.onload = load()
</script>
</html>


<?php
function opHeader()
{
    $header=<<<A
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap-4.0.0.css" rel="stylesheet">
    <script src="js/jquery-3.2.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap-4.0.0.js"></script>
    <script src="js/viewlist.js"></script>
    <title>新江湾湿地公园</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        li {
            list-style: none;
        }
        body {
            background-size: 100%;
            background-image: url(image/新江湾湿地公园/新江湾湿地公园.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #bg {
            height: 820px;
        }
        .bt>li {
            float: left;
            position: relative;
            margin-left: 80px;
            margin-top: 80px;
            font-size: 70px;
            font-family: "微软雅黑";
        }
        .bt>.clear {
            margin-left: 25px;
        }
        .js {
            position: relative;
            width: 1200px;
            background-color: rgba(255,255,255,0.7);
            margin: 0 auto;
        }
        .js>div {
            width: 1050px;
            margin: 0 auto;
        }
        /* 简介 */
        .jianjie {
            overflow: hidden;
            padding-top: 40px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .jianjie .words {
            float: left;
            width: 600px;
            margin-top: 30px;
            
        }
        .jianjie img {
            float: left;
            width: 400px;
            margin-left: 40px;
        }
        .jianjie .clear {
            width: 1000px;
        }
        /* 自然资源  */
        .ziranziyuan {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .ziranziyuan img {
            float: left;
            width: 400px;
            margin-top: 60px;
        }
        .ziranziyuan .words {
            float: left;
            width: 600px;
            margin-left: 40px;
        }
        /* 主要景点 */
        .zhuyaojingdian {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .zhuyaojingdian1,.zhuyaojingdian2
        ,.zhuyaojingdian3,.zhuyaojingdian4 {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 第一段 */
        .zhuyaojingdian1 img {
            float: left;
            width: 400px; 
        }
        .zhuyaojingdian1 .words {
            float: left;
            width: 600px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 第二段 */
        .zhuyaojingdian2 img {
            float: left;
            width: 400px;
        }
        .zhuyaojingdian2 .words {
            float: left;
            width: 600px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 第三段 */
        .zhuyaojingdian3 .words {
            float: left;
            width: 550px;
            margin-top: 80px;
        }
        .zhuyaojingdian3 img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 第四段 */
        .zhuyaojingdian4 .words {
            float: left;
            width: 1050px;
        }
        .words h3 {
            line-height: 30px;
        }
        .words h4 {
            line-height: 30px;
        }
        .words p {
            text-indent: 2em;
            line-height: 26px;
        }
        .anniu {
            width: 60px;
            height: 60px;
            background-color: rgba(255,255,255,0.7);
            line-height: 60px;
            border-radius: 30px;
            margin-bottom: 10px;
            text-align: center;
        }
        .anniu:hover {
            background-color: rgba(255,255,255,0.9);
        }
     .js .commentBox{
    border-color: black;
    border-image: none;
    border-radius: 3px;
    border-style: solid;
    border-width: 1px;
    /*margin-bottom: 15px;*/
    padding:10px 15px 40px 15px;
    margin:0 auto;
    width:520px;
    text-align:center;
    position:relative;
    overflow: hidden;
    }
    .commentText{
    /* position:absolute;  */
    float:left;
    top:40px;
    margin-top:20px;
    }
    .commentText::before{
    display: inline-block;
    content: "";
    vertical-align: middle;
    }
    .commentMes{
    position:absolute;
    /* float:right; */
    right:10px; 
    bottom:10px;
    }
    .thumb{
    /* position:absolute; */
    float:right;
    right:10px;
    top:10px;
    }
    .userName{
    /* position:absolute; */
    border-color: #0099FF;
    border-image: none;
    border-radius: 3px;
    border-style: solid;
    border-width:2px;
    text-align: center;
    width:80px;
    float:left;
    left:10px;
    top:10px;
    }
    .Approved{
    color:#CC3333; 
    font-size:15px; 
    text-decoration: 0;
    }
    .Unapproved{
    color:#006666; 
    font-size:15px; 
    text-decoration: 0;
    }
    </style>
    </head>
    <body>
    <nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container" style="font-family: '微软雅黑'">
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">新江湾湿地公园</a>
            <ul class="navbar-nav justify-content-end" style="font-size: 15px">
                <ul class="nav nav-tabs">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="border-top: 1px; border-color:RGB(248,249,250);">
                            其他景点</a>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="五角场_18.php">五角场</a> <a class="dropdown-item" href="新江湾湿地公园_20.php">新江湾湿地公园</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../选区/选区1.php">区域</a>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </nav>
    <ul class="bt">
        <li>新</li>
        <li class="clear">江</li>
        <li class="clear">湾</li>
        <li class="clear">湿</li>
        <li class="clear">地</li>
        <li class="clear">公</li>
        <li class="clear">园</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


