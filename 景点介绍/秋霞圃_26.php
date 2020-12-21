<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=26;
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
    header("location:秋霞圃_26.php?view='comment'");
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
    $moduleID=26;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=秋霞圃_26.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=秋霞圃_26.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="秋霞圃_26.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="秋霞圃_26.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="秋霞圃_26.php">
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
                    秋霞圃是中国江南著名的古典园林，位于上海嘉定区嘉定镇东大街，东邻秋霞公寓，西毗陆俨少艺术院，南连东大街，北依启良路。秋霞圃是一座具有独特风格的明代园林，由三座私家园林明代龚氏园、沈氏园、金氏园和邑庙（城隍庙）合并而成，全园面积45.36亩。该园分为四个景区：桃花潭景区（原龚氏园）、凝霞阁景区（原沈氏园）、清镜塘景区（原金氏园）及邑庙景区。秋霞圃布局精致、环境幽雅，小巧玲珑，景物与色彩的变化都不大，好像笼罩着一层淡淡的秋意，让人充满着诗情画意的遐想。
                </p>
                <p>
                    秋霞圃与松江醉白池、上海豫园、南翔古漪园、青浦曲水园并称为上海五大古典园林，园内建筑大多建于明代，而邑庙则可以上溯至宋代，如果按其中的邑庙部分的始建时间推算，可称为五大园林中最古老的园林。
                </p>
            </div>
            <div class="img">
                <img src="image/佘山/简介.jpg" alt="简介">
            </div>
        </div>
        <div class="main">
            <h3>秋霞圃四区</h3>
            <div class="scen1">
                <div class="img">
                    <img src="image/秋霞圃/桃花潭.jpg" alt="桃花潭">
                </div>
                <div class="words">
                    <h4>桃花潭景区</h4>
                    <p>
                        桃花潭景区在园之西南，占地8亩，约5400平方米，东临宾藻风香室，西靠归家弄，南以院墙为界，北至清镜塘。景区以桃花潭为中心，南北两山隔潭相望，山石亭台互为衬景。南有晚香居、霁霞阁、池上草堂、仪慰厅，西有丛桂轩，北有即山亭、碧光亭、延绿轩、碧梧轩、观水亭，它们或筑于山上，或构于潭边。远近高低、前后左右，主次分明，疏密相宜。桃花潭南北两山对峙，南山峭壁耸崎，北山浑厚见长。沿潭茂林修竹，断岸滴泉，临水曲径，低栏板桥。可谓山具丘壑之美，水揽幽邃之胜。虽由人作，宛自天开。是典型的中国自然山水园林。
                    </p>
                </div>
            </div>
            <div class="scen2">
                <div class="words">
                    <h4>凝霞阁景区</h4>
                    <p>
                        凝霞阁景区在园之东部，原为沈氏园旧址，西邻桃花潭景区，东止于园墙，南联邑庙景区，北依清镜塘，占地4亩，约2700平方米。景区建筑密集，以太湖石堆砌的大屏山为中心，北有凝霞阁，南有聊淹堂、游骋堂、彤轩、亦是轩，东有扶疏堂、环翠轩、觅句廊，西有屏山堂、数雨斋、闲研斋、依依小榭等。区内多院组合，院廊相连，曲折深邃。院墙多置漏窗，院内孤植树木和丛植花草，步移景异，若隐若现。
                    </p>
                </div>
                <div class="img">
                    <img src="image/秋霞圃/凝霞阁.jpg" alt="凝霞阁">
                </div>
            </div>
            <div class="scen3">
                <div class="words">
                    <h4>邑庙景区</h4>
                    <p>
                        邑庙景区在位于园东南，北连凝霞阁景区，西北邻桃花潭景区，南及东南为职工生活区，东西两面为围墙，面积4亩，约2700平方米。殿建筑宏伟、高大，结构独特，系上海地区保存最为完整的邑庙。
                    </p>
                    <p>
                        城隍庙大殿于明清两代因火灾和兵祸而屡毁屡建，今大殿及工字廊、寝宫均系清光绪八年重建。清代末年，殿前尚有井亭、头门、仪门、打唱台，天井内置铁鼎，抱厦内有石制“千砍”、“水盂”等，解放前多已无存。1983年大殿按原样修复，其南北长50.66米，东西宽23.54米，高5.24米，面积1192.54平方米。大殿重檐覆顶，檐口饰钉帽，屋脊上塑盘龙吐水戏珠图，两端塑动物及八仙。殿北有工字廊与寝宫相连，宫内置大床及家具，陈设华丽。殿西有月门，门额“逸趣”、“神韵”，由王仁元书；殿东侧有石板路通凝霞阁景区。殿前月台三面有石围栏，十八根望柱头上镌有形态不同的石狮。
                    </p>
                </div>
                <div class="img">
                    <img src="image/秋霞圃/邑庙景区.jpg" alt="邑庙">
                </div>
            </div>
            <div class="scen4">
                <div class="img">
                    <img src="image/秋霞圃/清镜塘.jpg" alt="清镜塘">
                </div>
                <div class="words">
                    <h4>清镜塘景区</h4>
                    <p>
                        清镜塘景区在园之北部，为金氏园遗址，北、东、西三面为园墙，南与桃花潭、凝霞阁两景区相邻，面积20亩，约1.35万平方米。清镜塘横卧于南面，塘北与东有三隐堂、柳云居、秋水轩、清轩，西有青松岭、岁寒亭、补亭。柳云居前遍植垂柳，绿云叠翠。青松岭上青松、红枫、白玉兰、腊梅布局有致。景区以清镜塘贯穿东西，植物景观为主体，疏朗开阔。亭榭、林木、花径、溪塘、山丘、护岸或敞或蔽、或大或小、或明或暗，变化无穷，具有浓郁的村野气息，与建筑紧凑的凝霞阁景区形成强烈的反差，一疏一密，各具其趣。
                    </p>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center" style="margin-top: 30px">
        <div class="container">
            <div class="row">
                <div class="col-12" style="color:white;">
                    <p>Copyright © MyWebsite. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="秋霞圃_26.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="秋霞圃_26.php?view='comment'">看评论</a>
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
    <link rel="stylesheet" href="css/秋霞圃.css">
    <link href="css/bootstrap-4.0.0.css" rel="stylesheet">
    <script src="js/jquery-3.2.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap-4.0.0.js"></script>
    <script src="js/viewlist.js"></script>
    <title>秋霞圃</title>
    <style>
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">秋霞圃</a>
            <ul class="navbar-nav justify-content-end" style="font-size: 15px">
                <ul class="nav nav-tabs">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="border-top: 1px; border-color:RGB(248,249,250);">
                            其他景点</a>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="汇龙潭_23.php">汇龙潭</a> <a class="dropdown-item" href="秋霞圃_26.php">秋霞圃</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../选区/选区1.php">区域</a>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </nav>
    <ul class="bt">
        <li>秋</li>
        <li class="clear">霞</li>
        <li class="clear">圃</li>
    </ul>
    <ul class="bt-En">
        <li>Qiuxia</li>
        <li class="clear">Garden</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


