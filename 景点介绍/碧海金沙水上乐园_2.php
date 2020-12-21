<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=2;
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
    header("location:碧海金沙水上乐园_2.php?view='comment'");
    // echo '<script type="text/javascript">window.location.href="外滩_1.php#CommentArea";</script>';
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
    $moduleID=2;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=碧海金沙水上乐园_2.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=碧海金沙水上乐园_2.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="碧海金沙水上乐园_2.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="碧海金沙水上乐园_2.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="碧海金沙水上乐园_2.php">
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
                    上海碧海金沙水上乐园，即上海碧海金沙水上乐园有限公司——“碧海金沙”，坐落于上海市奉贤区海湾旅游区，
                    位于上海市南端，面向杭州湾，西毗上海化学工业区，东接临港开发区。拥有水域面积65万平方米，8万平方米的沙滩面积。
                    碧海金沙是中国最大的人造沙滩海滨浴场，也是上海唯一一处碧波荡漾的蓝色海域。乐园内设有各类游艺项目：
                    大海畅泳、水上乐园、水上自行车、水上休闲船、怀旧电影、儿童乐园。充沛的阳光、清凉的海水，凉爽的海风，金色的沙滩绝对是你今夏休闲渡假的好去处。
                </p>
            </div>
            <div class="img">
                <img src="image/碧海金沙水上乐园/碧海金沙概述图.jpg" alt="碧海金沙概述图">
            </div>
        </div>
        <div class="shatantese">
            <div class="img">
                <img src="image/碧海金沙水上乐园/沙滩特色.jpg" alt="沙滩特色">
            </div>
            <div class="words">
                <h3>沙滩特色</h3>
                <p>
                    碧海金沙最大的特点就是“人工”，海水，人工净化的，砂子，人工铺设的，就连海底的海床都是人工修的，还包括人工大堤，
                    为了把净化过的“碧海”与未净化的“黄海”分隔开来。碧海金沙的海，虽然没有三亚的海那么清澈，那么令人神往，
                    但在以颜色黄著称的长江地区来说，还是非常不错的，而且经过净化，海水的味道也淡了很多，没有那么苦咸。
                    碧海金沙的沙，号称是从海南运过来的，虽然没有亚龙湾的银沙那么白、那么细，但也令人眼前一亮，决不是一般黄沙那么粗糙，砂子的颗粒程度相当不错。
                </p>
            </div>
        </div>
        <div class="jingqutese">
            <div class="words">
                <h3>景区特色</h3>
                <p>
                    水上乐园从上往下大体分三层，葱郁的绿地、美丽的海、柔软细腻的沙。是夏天旅游的好去处，能够感受到一丝海的凉意。
                    没有时间去较远的海，那就来这里，不是最好但也不错了，建议住一夜，看看海景，听听海浪。
                </p>
                <p>
                    第一层为人造绿地，沿防汛墙向外延伸30多米，建造约4.5万平方米滨海绿化带。绿化带树木葱郁，植被丰茂，错落有致，充满热带风情；
                    木栅道蜿蜒于绿地与沙滩之间，两边郁郁葱葱的小树林、鲜艳夺目的鲜花丛、线条简洁的框架房、细腻柔软的海南沙，美丽景色尽收眼底。
                </p>
                <p>
                    第二层为人工沙滩，自然地从绿化带向大海延伸，露水沙滩大约有7.5万平方米。金色沙滩的沙粒实在太细了，
                    松软平坦；沙色实在太像金末了，金光闪闪；沙滩也实在太大了，成百上千穿着各色泳装的男女躺在那里，组成了一个美丽的彩棋盘。
                </p>
                <p>
                    第三层为蓝色海水，从沙滩到圈水顺堤约500多米，海水面积大约67万平方米，构成大型水上乐园。
                    水上乐园一眼望去，天空透明、澄蓝，片片白云像擦拭玉盘的丝绸；也许是阳光、也许是清澈，使得海水湛蓝湛蓝，
                    那种“蓝”才叫人们体味出“蓝”的本质。远方是难以想象的波平浪静，朦胧之中彩帆点点，
                    近处永远翻腾着海浪，蓝色的浪谷和白色的浪峰此消彼长，一次次涌来，渐渐把人们融入进这风情如画的境地。
                </p>
            </div>
            <div class="img">
                <img src="image/碧海金沙水上乐园/景点特色.jpg" alt="景区特色">
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="碧海金沙水上乐园_2.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="碧海金沙水上乐园_2.php?view='comment'">看评论</a>
    </div>
    <?php
    $command=<<<A
    <script type="text/javascript" >setTimeout("moveUp()",500); </script>
    A;
    if(!isset($_GET['view']))
    {
        echo $command;
    }
    else
    {
        $view=$_GET['view'];
        $view=str_replace('\'','',$view);
        if($view=='intro')
        {
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
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap-4.0.0.css" rel="stylesheet">
    <script src="js/jquery-3.2.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap-4.0.0.js"></script>
    <script src="js/viewlist.js"></script>
    <title>碧海金沙水上乐园</title>
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
            background-image: url(image/碧海金沙水上乐园/碧海金沙水上乐园.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin:0,auto;
        }
        #bg {
            height: 820px;
        }
        .bt1>li {
            float: left;
            position: relative;
            margin-left: 60px;
            margin-top: 60px;
            font-size: 70px;
            font-family: "微软雅黑";
        }
        .bt1>.clear {
            margin-left: 20px;
        }
        .bt2>li {
            float: left;
            position: relative;
            margin-left: -150px;
            margin-top: 160px;
            font-size: 70px;
            font-family: "微软雅黑";
        }
        .bt2>.clear1 {
            margin-left: -60px;
        }
        .bt2>.clear2 {
            margin-left: 20px;
        }
        .bt2>.clear3 {
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
        /* 简介 */
        .jianjie {
            overflow: hidden;
            padding-top: 40px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .jianjie .words {
            float: left;
            width: 550px;
            margin-top: 40px;
        }
        .jianjie .img {
            float: left;
            margin-left: 40px;
            
        }
        /* 沙滩特色  */
        .shatantese {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .shatantese .img {
            float: left;
            margin-top: 10px;
        }
        .shatantese .words {
            float: left;
            width: 600px;
            margin-left: 40px;
            margin-top: 30px;
        }
        /* 景区特色 */
        .jingqutese {
            overflow: hidden;
            padding-top: 40px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .jingqutese .words {
            float: left;
            width: 550px;
        }
        .jingqutese img {
            float: left;
            width: 40%;
            margin-left: 40px;
            margin-top: 110px;
        }
        .words h3 {
            line-height: 30px;
        }
        .words p {
            text-indent: 2em;
            line-height: 26px;
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">碧海金沙水上乐园</a>
            <ul class="navbar-nav justify-content-end" style="font-size: 15px">
                <ul class="nav nav-tabs">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="border-top: 1px; border-color:RGB(248,249,250);">
                            其他景点</a>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="碧海金沙水上乐园_2.php">碧海金沙水上乐园</a> <a class="dropdown-item" href="古华园_8.php">古华园</a> <a class="dropdown-item" href="海湾森林公园_9.php">海湾森林公园</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../选区/选区1.php">区域</a>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </nav>
    <ul class="bt1">
        <li>碧</li>
        <li class="clear">海</li>
        <li class="clear">金</li>
        <li class="clear">沙</li>
    </ul>
    <ul class="bt2">
        <li >水</li>
        <li class="clear1">上</li>
        <li class="clear2">乐</li>
        <li class="clear3">园</li>
    </ul>
    </body>
    </html>
A;
    echo $header;
}
?>


