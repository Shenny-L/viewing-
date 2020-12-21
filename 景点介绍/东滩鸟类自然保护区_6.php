<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=6;
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
    header("location:东滩鸟类自然保护区_6.php?view='comment'");
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
    $moduleID=6;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=东滩鸟类自然保护区_6.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=东滩鸟类自然保护区_6.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="东滩鸟类自然保护区_6.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="东滩鸟类自然保护区_6.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="东滩鸟类自然保护区_6.php">
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
                    上海崇明东滩鸟类国家级自然保护区位于低位冲积岛屿——崇明岛东端的崇明东滩的核心部分，面积约32,600公顷，
                    约占上海市湿地总面积的7.8，主要保护对象为水鸟和湿地生态系统。在长江泥沙的淤积作用下，
                    形成了大片淡水到微咸水的沼泽地、潮沟和潮间带滩涂。区内有众多的农田、鱼塘、蟹塘和芦苇塘，
                    沼生植被繁茂，底栖动物丰富，是亚太地区春秋季节候鸟迁徙极好的停歇地和驿站，也是候鸟的重要越冬地，
                    是世界为数不多的野生鸟类集居、栖息地之一。据有关资料表明，东滩有116种鸟，占中国鸟类总数的十分之一，
                    尤其是国家二级保护动物小天鹅在东滩越冬数量曾达3000～3500只。还有来自澳大利亚、新西兰、日本等国过境栖息候鸟，总数达二三百万。
                </p>
                <p>
                    保护区主要由团结沙外滩、东旺沙外滩、北八滧外滩、潮间带滩涂湿地和河口水域组成，1998年经上海市人民政府批准建立，
                    划分为核心区、缓冲区和实验区，其中核心区165.92平方公里、缓冲区10.7平方公里、试验区64.93平方公里。
                </p>
            </div>
            <div class="img">
                <img src="image/东滩鸟类自然保护区/标志.jpg" alt="简介">
            </div>
        </div>
        <div class="shuiwenqihou">
            <div class="img">
                <img src="image/东滩鸟类自然保护区/水文气候.jpg" alt="水文气候">
            </div>
            <div class="words">
                <h3>水文气候</h3>
                <p>
                    上海崇明东滩鸟类国家级自然保护区气候温和湿润，阳光充足，雨量充沛，拥有丰富的生物资源，四季分明，
                    年均气温15.3℃，年降水量1022mm，无霜期达229天，既是鸟类良好的觅食、栖息场所，又是广大公众休闲、
                    旅游和观鸟的好去处；同时也是生物学、地学、生态学、水产等学科教学实习场所对公众及中小学生进行
                    生态环境保护教育、野生动植物保护教育和生物多样性保护教育的重要基地。
                </p>
            </div>
        </div>
        <div class="shengwuziyuan">
            <h3>生物资源</h3>
            <div class="niaolei">
                <div class="words">
                    <h4>鸟类</h4>
                    <p>
                        崇明东滩记录的鸟类有290种，其中鹤类、鹭类、雁鸭类、鸻鹬类和鸥类是主要水鸟类群。
                        已观察到的国家重点保护的一、二级鸟类共39种，占崇明东滩鸟类群落组成的15.06%，
                        其中列入国家一级保护的鸟类4种，分别为东方白鹳（Ciconia boyciana）、黑鹳（Ciconia nigra）、
                        白尾海雕（Haliaeetus albicilla）和白头鹤（Grus monacha）；列入国家二级保护的鸟类35种，
                        如黑脸琵鹭（Platalea minor）、小青脚鹬（Tringa guttifer）、小天鹅（Cygnus columbianus）、
                        鸳鸯（Aix galericulata）等。列入《中国濒危动物红皮书》的鸟类有20种。除此之外，
                        保护区还记录中日候鸟及其栖息地保护协定的物种156种，中澳候鸟保护协定的物种54种。这些物种资源属于濒危鸟类就占鸟类总数的15%，
                        有的则极其稀有（如黑脸琵鹭，种群数量极少，全球仅1500余只），大部分为洲际迁徙候鸟。
                    </p>
                </div>
                <div class="img">
                    <img src="image/东滩鸟类自然保护区/鸟类.jpg" alt="鸟类">
                </div>
            </div>
            <div class="fuyouzhiwu">
                <div class="words">
                    <h4>浮游植物</h4>
                    <p>
                        截至2012年，在崇明东滩鸟类自然保护区水域，调查鉴定出浮游植物4门31属59种。其中硅藻22属49种，
                        甲藻3属4种，蓝藻4属4种，绿藻2属2种。硅藻的数量最多，其种类数占总种数的83.5%，数量占总数量的99.57%；
                        甲藻占总种数的6.78%，其数量占总数量的0.13%；蓝藻占总种数的6.78%，其数量占总数量的0.01%；绿藻占总种数的3.39%，其数量占总数量的0.26%。
                    </p>
                </div>
            </div>
            <div class="fuyoudongwu">
                <div class="words">
                    <h4>浮游动物</h4>
                    <p>据夏季（2000年6～7月）对崇明东滩鸟类自然保护区水域的调查，共鉴定出浮游动物19种，其中以甲壳动物占绝对优势。
                        其主要种类以低盐近岸生态类为主，其次为半咸水河口生态类型，也有少量的广温广盐生态类型的种类。
                        低盐近岸生态类型，有虫肢歪水蚤、真刺唇角水蚤、长额刺糠虾和中华节糠虾、腹针胸刺水蚤等；半咸水河口生态类型，
                        有华哲水蚤、火腿许水蚤和江湖独眼钩虾；少量的广温广盐生态类型如精致真刺水蚤、中华哲水蚤、微刺哲水蚤等。
                    </p>
                </div>
            </div>
            <div class="diqidongwu">
                <div class="words">
                    <h4>底栖动物</h4>
                    <p>截至2012年，东滩有底栖动物70多种。主要种类可分3类：软体动物、甲壳动物和环节动物。
                        软体动物中的彩虹明樱蛤（俗称海瓜子），泥螺、溢蛏是三大美味海产品，有很高的经济价值。
                        这些海产品主要分布在崇明东滩的V区，已经形成较大产量。甲壳动物中方蟹科的蟹类数量巨大，为鸟类提供丰富的食物。
                    </p>
                </div>
            </div>
            <div class="yulei">
                <div class="words">
                    <h4>鱼类</h4>
                    <p>截至2012年，在保护区水域已知分布有鱼类94种，为长江口鱼类（记载为117种）的80.34%。
                        这些鱼类隶属14目34科，其中鲤科鱼类最多有24种，占25.53%；银鱼科8种，占8.51%；鳀科6种，占6.38%；
                        鰕虎鱼科和鰕科均为5种，各占5.32%；鲱科、舌鳎科各4种，各占4.26%；其余各科的种类比较少，仅1～2种。
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="东滩鸟类自然保护区_6.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="东滩鸟类自然保护区_6.php?view='comment'">看评论</a>
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
    <title>东滩鸟类自然保护区</title>
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
            background-image: url(image/东滩鸟类自然保护区/东滩鸟类自然保护区.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #bg {
            height: 820px;
        }
        .bt>li {
            float: left;
            position: relative;
            margin-left: 140px;
            margin-top: 100px;
            font-size: 70px;
            font-family: "微软雅黑";
        }
        .bt>.clear {
            margin-left: 10px;
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
            margin-top: 20px;
        }
        .jianjie img {
            width: 400px;
            float: left;
            margin-left: 60px;
        }
        /* 水文气候  */
        .shuiwenqihou {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .shuiwenqihou img {
            float: left;
            width: 400px;
            margin-top: 10px;
        }
        .shuiwenqihou .words {
            float: left;
            width: 550px;
            margin-left: 40px;
            margin-top: 50px;
        }
        /* 生物资源 */
        .shengwuziyuan {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .fuyouzhiwu,.fuyoudongwu,.diqidongwu
        ,.yulei,.niaolei {
            overflow: hidden;
            padding-top: 30px;
        }
        .fuyouzhiwu,.fuyoudongwu
        ,.diqidongwu,.yulei {
            width: 800px;
            margin-bottom: 30px;
        }
        /* 鸟类 */
        .niaolei .words {
            float: left;
            width: 550px;
        }
        .niaolei img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 50px;
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">东滩鸟类自然保护区</a>
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
        <li>东</li>
        <li class="clear">滩</li>
        <li class="clear">鸟</li>
        <li class="clear">类</li>
        <li class="clear">自</li>
        <li class="clear">然</li>
        <li class="clear">保</li>
        <li class="clear">护</li>
        <li class="clear">区</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


