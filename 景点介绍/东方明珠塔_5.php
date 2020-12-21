<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=5;
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
    header("location:东方明珠塔_5.php?view='comment'");
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
    $moduleID=5;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=东方明珠塔_5.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=东方明珠塔_5.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="东方明珠塔_5.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="东方明珠塔_5.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="东方明珠塔_5.php">
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
                <h3>简介</h3>
                <p>
                    东方明珠广播电视塔（The Oriental Pearl Radio & TV Tower）是上海的标志性文化景观之一，位于浦东新区陆家嘴，
                    塔高约468米。该建筑于1991年7月兴建，1995年5月投入使用，承担上海6套无线电视发射业务，地区覆盖半径80公里。
                </p>
                <p>
                    东方明珠广播电视塔是国家首批AAAAA级旅游景区。塔内有太空舱、
                    旋转餐厅、上海城市历史发展陈列馆等景观和设施，1995年被列入上海十大新景观之一。
                </p>
                <p>
                    2020年1月6日，入选2019上海新十大地标建筑。
                </p>
            </div>
            <div class="img">
                <img src="image/东方明珠/东方明珠1.jpg" alt="简介">
            </div>
        </div>
        <div class="jingguan">
            <h3>景观设施</h3>
            <div class="guanguangceng">
                <div class="words">
                    <h4>观光层</h4>
                    <p>
                        直径50米的下球体室外观光廊标高90米。263米的上球体观光层直径45米，是东方明珠广播电视塔的主观光层。
                    </p>
                    <p>
                        259米的悬空观光廊全长150米，宽2.1米，通过原第二球体观光平台的临边改造而成。
                        该观光廊由24个可活动收放的“花瓣”状钢化透明夹胶玻璃组成，单元建筑面积17.29平方米。
                    </p>
                    <p>
                        350米处的太空舱直径为16米，以未来主义的风格展现了太空场景的科幻魅力，是电视塔最高的观光层。
                    </p>
                </div>
                <div class="img">
                    <img src="image/东方明珠/观光层.jpg" alt="观光层">
                </div>
            </div>
            <div class="xuanzhuancanting">
                <div class="words">
                    <h4>旋转餐厅</h4>
                    <p>
                        东方明珠广播电视塔的空中旋转餐厅坐落于东方明珠塔267米上球体，营业面积为1500平方米，可同时容纳350位游客用餐。
                    </p>
                </div>
                <div class="img">
                    <img src="image/东方明珠/旋转餐厅.jpg" alt="旋转餐厅">
                </div>
            </div>
            <div class="chenlieguan">
                <div class="words">
                    <h4>陈列馆</h4>
                    <p>
                        2001年5月，6000平方米的上海城市历史发展陈列馆在东方明珠广播电视塔的塔座开馆。 
                    </p>
                    <p>
                        陈列馆通过城厢风貌、开埠掠影、十里洋场、海上旧踪、建筑博览、车马春秋6个展馆的80多个景点、数百件珍贵历史文物、
                        上百幢按比例缩小的华美建筑、117个与真人般大小的蜡像、近千个小蜡像、小泥人，反映了上海的发展过程。
                    </p>
                </div>
                <div class="img">
                    <img src="image/东方明珠/陈列馆.jpg" alt="陈列馆">
                </div>
            </div>
        </div>
        <div class="huodong">
            <h3>主要活动</h3>
            <div class="denggao">
                <div class="img">
                    <img src="image/东方明珠/元旦登高.jpg" alt="元旦登高">
                </div>
                <div class="words">
                    <h4>元旦登高</h4>
                    <p>
                        东方明珠广播电视塔的元旦登高活动，自1996年起举办。活动以“新年步步高、节节向上攀”的美好寓意成为上海每年元旦传统的全民健身运动项目。
                    </p>
                </div>
            </div>
            <div class="diqiuyixiaoshi">
                <div class="img">
                    <img src="image/东方明珠/地球一小时.jpg" alt="地球一小时">
                </div>
                <div class="words">
                    <h4>地球一小时</h4>
                    <p>
                        从2009年起，东方明珠广播电视塔加入了“地球一小时”的活动中，以此来响应世界自然基金会的节能减排号召。
                    </p>
                </div>
            </div>
            <div class="gongyi">
                <div class="words">
                    <h4>公益活动</h4>
                    <p>
                        每年3月8日，东方明珠广播电视塔点亮粉红色灯光，呼吁全社会关爱妇女；
                        每年4月2日的世界自闭症日，东方明珠广播电视塔都会亮起蓝色灯光，以这种形式呼唤社会关注自闭症患者。
                    </p>
                </div>
            </div>
            <div class="wenhuahuodong">
                <div class="img">
                    <img src="image/东方明珠/文化活动.jpg" alt="文化活动">
                </div>
                <div class="words">
                    <h4>文化活动</h4>
                    <p>
                        东方明珠在4米大堂设置了的文化长廊，经常举办各类书画艺术、摄影图片展览，
                        如东方飞羽——野生鸟类摄影艺术作品展、可可西里保护藏羚羊图片展、上海市少年儿童百米长卷主题书画展等。
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="东方明珠塔_5.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="东方明珠塔_5.php?view='comment'">看评论</a>
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
    <title>东方明珠塔</title>
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
            background-image: url(image/东方明珠/东方明珠.jpg);
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
            margin-left: 20px;
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
            margin-top: 100px;
        }
        .jianjie .img {
            float: left;
            margin-left: 60px;
        }
        /* 景观设施 */
        .jingguan {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .guanguangceng,.xuanzhuancanting
        ,.chenlieguan {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 观光层 */
        .guanguangceng .words {
            float: left;
            width: 550px;
            margin-top: 30px;
        }
        .guanguangceng img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 旋转餐厅 */
        .xuanzhuancanting .words {
            float: left;
            width: 550px;
            margin-top: 50px;
        }
        .xuanzhuancanting img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 陈列馆 */
        .chenlieguan .words {
            float: left;
            width: 550px;
            margin-top: 50px;
        }
        .chenlieguan img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 主要活动 */
        .huodong {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .denggao,.diqiuyixiaoshi
        ,.gongyi,.wenhuahuodong {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 元旦登高 */
        .denggao .img {
            float: left;
            margin-top: 10px;
        }
        .denggao .words {
            float: left;
            width: 550px;
            margin-left: 40px;
            margin-top: 80px;
        }
        /* 地球一小时 */
        .diqiuyixiaoshi .img {
            float: left;
            margin-top: 10px;
        }
        .diqiuyixiaoshi .words {
            float: left;
            width: 550px;
            margin-left: 40px;
            margin-top: 80px;
        }
        /* 公益活动 */
        .gongyi .words {
            float: left;
            width: 1050px;
        }
        /* 文化活动 */
        .wenhuahuodong .img {
            float: left;
            margin-top: 10px;
        }
        .wenhuahuodong .words {
            float: left;
            width: 550px;
            margin-left: 40px;
            margin-top: 80px;
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">东方明珠塔</a>
            <ul class="navbar-nav justify-content-end" style="font-size: 15px">
                <ul class="nav nav-tabs">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="border-top: 1px; border-color:RGB(248,249,250);">
                            其他景点</a>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="滨江森林公园_3.php">滨江森林公园</a> <a class="dropdown-item" href="东方明珠塔_5.php">东方明珠塔</a> <a class="dropdown-item" href="上海迪士尼乐园_14.php">上海迪士尼乐园</a> <a class="dropdown-item" href="新场古镇_19.php">新场古镇</a> <a class="dropdown-item" href="中国航海博物馆_21.php">中国航海博物馆</a><a class="dropdown-item" href="中华艺术宫_13.php">中华艺术宫</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../选区/选区1.php">区域</a>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </nav>
    <ul class="bt">
        <li>东</li>
        <li class="clear">方</li>
        <li class="clear">明</li>
        <li class="clear">珠</li>
        <li class="clear">塔</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


