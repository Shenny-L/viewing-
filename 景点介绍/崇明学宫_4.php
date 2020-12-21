<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=4;
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
    header("location:崇明学宫_4.php?view='comment'");
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
    $moduleID=4;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=崇明学宫_4.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=崇明学宫_4.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="崇明学宫_4.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="崇明学宫_4.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="崇明学宫_4.php">
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
                    崇明学宫是现今上海仅存的三座学宫之一。现址又称崇明博物馆，为市级文物保护单位。
                    坐落于上海市崇明南门码头。学宫基地原东长144.2米，西长148.3米，南北各阔80米，
                    有殿、宫、堂、厅、祠、阁等建筑群，建筑艺术精湛。清代曾10次重修。民国以降，
                    崇明学宫曾被小学和其他单位使用。经过近年来的三次整修，逐渐恢复了当年的格局，
                    占地面积扩大至23.21亩，成为上海地区面积最大的孔庙。学宫主要建筑有东西牌坊、
                    棂星门、泮池、登云桥、东西官厅、戟门、乡贤祠、名宦祠、忠义孝悌祠、大成殿、东西庑殿、尊经阁、崇圣祠等明清建筑群，
                    气势恢宏，规模壮观，徜徉其间，令人流连忘返。目前，古建筑明伦堂、仪门等修复完毕。
                </p>
            </div>
            <div class="img">
                <img src="image/崇明学宫/崇明学宫1.jpg" alt="崇明宫学">
            </div>
        </div>
        <div class="lishi">
            <div class="img">
                <img src="image/崇明学宫/崇明学宫2.jpg" alt="崇明宫学">
            </div>
            <div class="words">
                <h3>历史</h3>
                <p>
                    元泰定四年（1327年）始建庙学合一的建筑群。自宋、元至明代中叶，崇明诸沙饱受海潮侵袭，
                    州、县治城被迫五迁六建，学宫也随之屡建屡圮。今之崇明学宫为明天启二年（1622年）由知县唐世涵在城壕外东南隅重建，
                    其后又经历多次修缮。 民国初，学宫建有万仞宫墙、棂星门、泮池、登云阁、戟门、名宦祠、乡贤祠、忠义孝悌祠、
                    东庑西庑、大成殿、崇圣祠、拜亭、敬一亭；儒学有大门、仪门、土地祠、洒扫会所、明伦堂、瀛洲书院、文昌宫、魁星阁、
                    学海堂楼、教谕署、尊经阁、训导署、博文斋、约礼斋、斗级公所、训导署、博文斋、约礼斋、斗级公所等建筑。
                    1913年起，学宫内开办学校，崇圣祠、尊经阁等均作校舍。1942年，日寇强占学宫，将儒学署改作营房。学宫大成殿和东庑西庑毁于战火。
                    1946年后，学宫曾先后作校舍，古建筑被改成教室、宿舍和办公用房。1966年起，学宫又被县社队工业局、县科委等多家单位使用。
                    1981年5月，学宫被列为县级文物保护单位。1984年5月，又被列为上海市第三批文物保护单位。1997年，在原址修复大成殿、东西两庑建筑。2001年，修复明伦堂、仪门。
                </p>
            </div>
        </div>
        <div class="waiguan">
            <div class="words">
                <h3>外观陈设</h3>
                <p>
                    门前是二株有350年历史、三人合抱的银杏树，守门的是一对大石狮，气势恢宏。学宫最大的建筑是大成殿，
                    仿佛寺庙中的大雄宝殿。这是祭祀孔子的地方，东庑西庑是72高徒的宿舍。而今大成殿暂作古船陈列室，
                    大成殿东庑主要是崇明知名人士的照片和事迹，还有一些崇明出土的古代器物。西庑是黄丕漠艺术馆。
                    大成殿后的两幢建筑是崇明民俗陈列馆。另建有万仞宫墙、棂星门、登云桥、戟门、名宦祠、崇圣祠、尊经阁等，
                    为上海地区保存完好的明代建筑，门前5株18米左右高的银杏树已有377年的树龄，与树下2座结构奇特，
                    蔚为壮观的石木牌坊相互呼应，营造出一片浓郁的古意，使人顿生一股“念天地之悠悠”的苍然之感。
                    崇明县博物馆是崇明岛上一处重要的人文旅游景点和爱国主义教育场所，博物馆内列有“崇明岛的形成与发展”、“馆藏文物”、“自然博物”三个展览，
                    较为系统地介绍了崇明的悠久历史和文化遗产，学宫中最有特色的当数古船陈列室和崇明民俗陈列馆这两处地方。
                </p>
            </div>
            <div class="img">
                <img src="image/崇明学宫/外观陈设.jpg" alt="崇明宫学">
            </div>
            <div class="word">
                <h3>内容主题</h3>
                <p>
                    其主题鲜明，脉络清晰，内容翔实，展品丰富。布置在学宫大成殿及东西两庑内的“崇明岛史与古船”陈列，
                    由序厅和六个单元的内容组成。陈列运用了文物、模型、雕塑、沙盘、布景箱、图片和先进的视听手段、通俗简明的文字说明，
                    真实地反映了崇明岛的形成及其政治、经济、交通、水利、文化等各方面的发展和建设成就。其中，展出的两艘唐、宋古船，
                    是目前上海地区独一无二的珍贵文物，堪称镇馆之宝；我国四大船系之一的崇明沙船，更以其独特的功能蜚声海内外。
                </p>
                <p>
                    布置的尊经阁和崇圣祠内的“崇明民俗”陈列，则通过集镇、民间家庭居室、农耕、纺织等生动逼真的场景，
                    再现了崇明人民的辛勤劳动和淳朴生活，给人以身临其境的真切感受。其中，既有19世纪末、20世纪初崇明老街商业景致的生动展示，
                    又有崇明典型的传统民宅“四厅头宅沟”及其室内家居布置的逼真再现；另外，耕织部分还向人们展出了近30件功能各异的常用生产工具。
                    此外，崇明县博物馆内还有“黄丕谟艺术馆”等固定展览。崇明区博物馆热忱欢迎社会各界人士前来参观。
                </p>
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="崇明学宫_4.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="崇明学宫_4.php?view='comment'">看评论</a>
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
    <title>崇明学宫</title>
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
            background-image: url(image/崇明学宫/崇明学宫.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #bg {
            height: 820px;
        }
        .bt>li {
            float: left;
            position: relative;
            margin-left: 150px;
            margin-top: 60px;
            font-size: 70px;
            font-family: "微软雅黑";
        }
        .bt>.clear {
            margin-left: 30px;
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
            width: 550px;
        }
        .jianjie img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 历史  */
        .lishi {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .lishi img {
            float: left;
            width: 400px;
            margin-top: 40px;
        }
        .lishi .words {
            float: left;
            width: 600px;
            margin-left: 40px;
        }
        /* 外观陈设 */
        .waiguan {
            overflow: hidden;
            padding-top: 40px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .waiguan .words {
            float: left;
            width: 550px;
        }
        .waiguan img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 40px;
        }
        .waiguan .word {
            float: left;
            width: 900px;
            margin-top: 20px;
        }
        .words,.word h3 {
            line-height: 30px;
        }
        .words,.word p {
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">崇明学宫</a>
            <ul class="navbar-nav justify-content-end" style="font-size: 15px">
                <ul class="nav nav-tabs">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="border-top: 1px; border-color:RGB(248,249,250);">
                            其他景点</a>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="崇明学宫_4.php">崇明学宫</a> <a class="dropdown-item" href="东滩鸟类自然保护区_6.php">东滩鸟类自然保护区</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../选区/选区1.php">区域</a>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </nav>
    <ul class="bt">
        <li>崇</li>
        <li class="clear">明</li>
        <li class="clear">学</li>
        <li class="clear">宫</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


