<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=21;
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
    header("location:中国航海博物馆_21.php?view='comment'");
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
    $moduleID=21;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=中国航海博物馆_21.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=中国航海博物馆_21.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="中国航海博物馆_21.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="中国航海博物馆_21.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="中国航海博物馆_21.php">
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
                    中国航海博物馆（China Maritime Museum），全称“上海中国航海博物馆”，
                    是中国首个经国务院批准设立的国家级航海博物馆，由交通运输部和上海市人民政府在上海市共建。
                    博物馆位于浦东新区南汇新城，占地面积24830平方米，建筑总面积46434平方米。2006年1月20日工奠基，
                    2010年7月5日全面建成开放。博物馆室内展览面积21000平方米。馆内以“航海”为主题，“博物”为基础，
                    分设航海历史、船舶、航海与港口、海事与海上安全、海员、军事航海六大展馆，渔船与捕鱼、航海体育与休闲两个专题展区，
                    并建有天象馆、4D影院、儿童活动中心，涵盖文物收藏、学术研究、社会教育、陈列展示等功能。
                </p>
            </div>
            <div class="img">
                <img src="image/中国航海博物馆/中国航海博物馆1.jpg" alt="简介">
            </div>
        </div>
        <div class="zhantingchenlie">
            <h3>展厅陈列</h3>
            <div class="hanghailishi">
                <div class="words">
                    <h4>航海历史馆</h4>
                    <p>
                        航海历史馆是中国航海博物馆的重点展馆。该馆以时间为主线分为古代、近代、现代三个展区。将浮力渡水、独木舟、木船、帆、桨、橹、舵、
                        指南针等造船和航海技术随时间主线并行展开，让观众更深入了解中国航海技术的发明与演变过程。
                    </p>
                </div>
                <div class="img">
                    <img src="image/中国航海博物馆/航海历史馆.jpg" alt="航海历史馆">
                </div>
            </div>
            <div class="chuanbo">
                <div class="words">
                    <h4>船舶馆</h4>
                    <p>
                        船舶馆分为船舶结构与设备、船舶制造两个部分，通过对船舶结构、设备及建造的分解、介绍与展示，
                        呈现给观众一幅清晰、透明的船舶图纸。同时，将互动、环境模拟、观众参与和文物、实物相结合。
                        展示船舶结构时，制作了1:6大型万吨级货轮高仿真剖面模型，船长约25米，高度贯穿两层展示空间。
                    </p>
                </div>
                <div class="img">
                    <img src="image/中国航海博物馆/船舶馆.jpg" alt="船舶馆">
                </div>
            </div>
            <div class="hanghaiyugangkou">
                <div class="words">
                    <h4>航海与港口馆</h4>
                    <p>
                        航海与港口馆主要展示了海洋环境、从古至今各类保障船舶航行的仪器、仪表等技术资料，
                        包括地文航海、天文航海、无线电航海等。同时，展示了大量反映港口与航道的文物和实物。 
                    </p>
                </div>
                <div class="img">
                    <img src="image/中国航海博物馆/航海与港口馆.jpg" alt="航海与港口馆">
                </div>
            </div>
            <div class="haishiyuhaishanganquan">
                <div class="words">
                    <h4>海事与海上安全馆</h4>
                    <p>
                        海事与海上安全馆由海事和海上安全两大独立展区组成。海事馆通过实物及辅助图文展示海事沿革与海事监管执法。
                        海上安全馆通过实物、模型、多媒体、电子地图等展示海上救助、海上打捞以及预防海盗专题内容。
                    </p>
                </div>
                <div class="img">
                    <img src="image/中国航海博物馆/海事与海上安全馆.jpg" alt="海事与海上安全馆">
                </div>
            </div>
            <div class="haiyuan">
                <div class="words">
                    <h4>海员馆</h4>
                    <p>
                        海员馆主要展示与海员工作、生活紧密相关的实物、文献。位于展馆中心的航海模拟器以大型集装箱船驾驶室为模拟器原型，
                        生动展示现代化船舶驾驶工作的特点，使观众通过主动操纵船舶，体验一回当船长的感觉。
                    </p>
                </div>
                <div class="img">
                    <img src="image/中国航海博物馆/海员馆.jpg" alt="海员馆">
                </div>
            </div>
            <div class="junshihanghai">
                <div class="words">
                    <h4>军事航海馆</h4>
                    <p>
                        军事航海馆分为中国人民海军建设和军舰知识两大展示内容，重点展示了各类军舰模型、
                        海军军旗及海军军装，高仿真复原了潜艇指挥舱。室外陈展了舰载火炮等实物。
                    </p>
                </div>
                <div class="img">
                    <img src="image/中国航海博物馆/军事航海馆.jpg" alt="军事航海馆">
                </div>
            </div>
        </div>
        <div class="wenwu">
            <h3>馆藏文物</h3>
            <div class="zongshu">
                <div class="words">
                    <br />
                    <h4>综述</h4>
                    <p>
                        中国海事馆以“海事”为主线，以“博物”为基础，系统展示了中国海事事业发展的昨天、今天和明天。
                        该展馆建筑面积约2000平方米，实物展品约260件、各类照片和影像资料约130件、
                        各类文献资料约70件、各类模型约30个、各类场景和沙盘约10个。在众多展品中，不乏珍品，
                        如民国时期的船员证件、船舶国籍证书、船舶所有权证书及1875年东海岸海图等重要珍贵资料。
                    </p>
                </div>
            </div>
            <div class="bafenyi">
                <div class="img">
                    <img src="image/中国航海博物馆/八分仪.jpg" alt="八分仪">
                </div>
                <div class="words">
                    <h4>八分仪</h4>
                    <p>
                        八分仪（英文名：Octant）为英国早期产品，其生产日期分别为1772年、1830年。
                    </p>
                    <p>
                        八分仪于1731年问世；1757年以八分仪为模子，发明了六分仪，并逐渐被其所替代，国内现存八分仪较少。
                    </p>
                </div>
            </div>
            <div class="tianwenzhong">
                <div class="img">
                    <img src="image/中国航海博物馆/天文钟.jpg" alt="天文钟">
                </div>
                <div class="words">
                    <h4>天文钟</h4>
                    <p>
                        天文钟是一种特别设计的、能用多种形式来表达天体时空运行的仪器，
                        它是把动力机械和许多传动机械组合在一个整体里，利用几组齿轮系把机轮的运动变慢，使它经常保持一个恒定的速度，
                        和天体运动一致。天文钟既能表示天象，又能计时，与人类航海有着密切联系。
                    </p>
                    <p>
                        其中一款天文钟为英国汤姆森品牌，产于20世纪初的英国圣奥尔本斯，该天文钟设计精巧，保存完好，尤其别致的72分钟计时设计。
                    </p>
                </div>
            </div>
            <div class="chuanmo">
                <div class="img">
                    <img src="image/中国航海博物馆/船模.jpg" alt="船模">
                </div>
                <div class="words">
                    <h4>船模</h4>
                    <p>
                        中海博全馆共有约420艘船舶，除了专设的船模专区，更分散在全馆各展馆，涵盖古今中外各类典型或著名民用船舶、军舰等。
                        所有展示模型原形船只均在中国航海史和造船史上占有重要地位，少数为世界著名船舶。
                        船模均从国内外著名船模制作专家或工作室按照中海博要求定制，堪称件件都是独一无二的艺术珍品。
                    </p>
                    <p>
                        船模按照一定比例缩小的船的形态，从微型比例如1/6000到可以载人大比例都可见。中海博船模集艺术与功能为一体，是博物馆不可缺少的元素。
                    </p>
                </div>
            </div>
            <div class="jinhumuduo">
                <div class="img">
                    <img src="image/中国航海博物馆/金湖木舵.jpg" alt="金湖木舵">
                </div>
                <div class="words">
                    <h4>金湖木舵</h4>
                    <p>
                        金湖木舵，2005年发现于长江口牛皮礁水域。舵上保留有首次发现的勒肚孔；此外，舵上还带有完美的吊舵孔
                        （通过操作穿孔而过的绳索以升降舵），这是至今考古发现的古舵中最为完整的吊舵孔。
                    </p>
                    <p>
                        此木舵是迄今发现的完整性最好的不平衡木质海船舵，带有独特的舵结构连接形式和完整厚实的舵叶。
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="中国航海博物馆_21.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="中国航海博物馆_21.php?view='comment'">看评论</a>
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
    <title>中国航海博物馆</title>
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
            background-image: url(image/中国航海博物馆/中国航海博物馆.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #bg {
            height: 820px;
        }
        .bt>li {
            float: left;
            position: relative;
            margin-left: 40px;
            margin-top: 60px;
            font-size: 70px;
            font-family: "微软雅黑";
        }
        .bt>.clear {
            margin-left: 10px;
        }
        .bt-En>li {
            float: left;
            position: relative;
            margin-left: -300px;
            margin-top: 160px;
            font-size: 40px;
            font-family: "微软雅黑";
        }
        .bt-En>.clear1 {
            margin-left: -180px;
        }
        .bt-En>.clear2 {
            margin-left: 0px;
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
        }
        .jianjie .img {
            float: left;
            margin-left: 40px;
        }
        /* 展厅陈列 */
        .zhantingchenlie {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .hanghailishi,.chuanbo,.hanghaiyugangkou
        ,.haishiyuhaishanganquan,.haiyuan
        ,.junshihanghai {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 航海历史馆 */
        .hanghailishi .words {
            float: left;
            width: 550px;
            margin-top: 60px;
            margin-left: 60px;
        }
        .hanghailishi .img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 船舶馆 */
        .chuanbo .words {
            float: left;
            width: 550px;
            margin-top: 50px;
            margin-left: 60px;
        }
        .chuanbo .img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 航海与港口馆 */
        .hanghaiyugangkou .words {
            float: left;
            width: 550px;
            margin-top: 70px;
            margin-left: 60px;
        }
        .hanghaiyugangkou .img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 海事与海上安全馆 */
        .haishiyuhaishanganquan .words {
            float: left;
            width: 550px;
            margin-top: 70px;
            margin-left: 60px;
        }
        .haishiyuhaishanganquan .img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 海员馆 */
        .haiyuan .words {
            float: left;
            width: 550px;
            margin-top: 50px;
            margin-left: 60px;
        }
        .haiyuan .img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 军事航海馆 */
        .junshihanghai .words {
            float: left;
            width: 550px;
            margin-top: 60px;
            margin-left: 60px;
        }
        .junshihanghai .img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 馆藏文物 */
        .wenwu {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .bafenyi,.tianwenzhong
        ,.chuanmo,.jinhumuduo {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 八分仪 */
        .bafenyi .img {
            float: left;
            margin-top: 10px;
        }
        .bafenyi .words {
            float: left;
            width: 550px;
            margin-left: 40px;
            margin-top: 60px;
        }
        /* 天文钟 */
        .tianwenzhong .img {
            float: left;
            margin-top: 10px;
        }
        .tianwenzhong .words {
            float: left;
            width: 550px;
            margin-left: 40px;
            margin-top: 60px;
        }
        /* 船模 */
        .chuanmo .img {
            float: left;
            margin-top: 10px;
        }
        .chuanmo .words {
            float: left;
            width: 550px;
            margin-left: 40px;
            margin-top: 50px;
        }
        /* 金湖木舵 */
        .jinhumuduo .img {
            float: left;
            margin-top: 10px;
        }
        .jinhumuduo .words {
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">中国航海博物馆</a>
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
        <li>中</li>
        <li class="clear">国</li>
        <li class="clear">航</li>
        <li class="clear">海</li>
        <li class="clear">博</li>
        <li class="clear">物</li>
        <li class="clear">馆</li>
    </ul>
    <ul class="bt-En">
        <li>China</li>
        <li class="clear1">Maritime</li>
        <li class="clear2">Museum</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


