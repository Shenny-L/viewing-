<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=15;
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
    header("location:上海动物园_15.php?view='comment'");
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
    $moduleID=15;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=上海动物园_15.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=上海动物园_15.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="上海动物园_15.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="上海动物园_15.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="上海动物园_15.php">
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
                    上海动物园现有面积约74公顷，饲养展出各类稀有珍贵野生动物400余种6000多只（头）。
                    其中有世界闻名的有着〝国宝〞和〝活化石〞之称的大熊猫，以及金丝猴、华南虎、扬子鳄等我国特产珍稀野生动物，
                    还有世界各地的代表性动物如大猩猩、非洲狮、长颈鹿、袋鼠、南美貘等。 园内种植树木近600种、10万余株，
                    特别有10万平方米清新开阔的草坪，基本保持着50年前高尔夫球场地形。园内的每一处绿化造景都与动物的生态环境相结合。
                    尤其是在天鹅湖，湖区内外芦苇丛丛，绿树成荫，成群的鹈鹕、大雁等时常列队在蓝天盘旋，姿态优美而刚健。 
                    近年来，上海动物园以建成城市生态动物园为目标，逐步改造和新建视觉无障碍的生态化动物展区，使游客仿佛置身于大自然之中，尽情欣赏野趣之美。
                </p>
                <p>
                    自开园至今，从初期单纯的参观游览场所到现在具有娱乐休闲、动物知识普及、科学技术研究及野生动物保护的四大职能兼具的综合性公园，
                    上海动物园共计已接待近一亿六千万游客。优美的园林景观、精彩的野生动物世界、
                    生态化的野生动物展区、人与动物和谐共处，上海动物园将会给游客留下美好的回忆以及对大自然的热爱。
                </p>
            </div>
            <div class="img">
                <img src="image/上海动物园/logo.jpg" alt="logo">
            </div>
        </div>
        <div class="zhuyaojingdian">
            <h3>主要景点</h3>
            <div class="jieweihuhou">
                <div class="words">
                    <h4>节尾狐猴园</h4>
                    <p>
                        位于猴山北面，通过仿真塑山与猴山相连。园内种植草皮和各种灌木，供节尾狐猴嬉戏。
                        游客既可以站在节尾狐猴园一条空中廊道俯视观赏动物打闹，也可在动物园主干道边通过约一米高的钢化玻璃无障碍地平视参观狐猴的活动。
                    </p>
                </div>
                <div class="img">
                    <img src="image/上海动物园/节尾狐猴.jpg" alt="节尾狐猴园">
                </div>
            </div>
            <div class="mengshou">
                <div class="words">
                    <h4>猛兽生态园</h4>
                    <p>
                        2001年元旦前建成开放的无视线障碍的狮虎豹生态展区。猛兽生态园面积700㎡，由拆除部分旧豹舍的基础上改建而成，
                        因地制宜，充分利用原有条件，从人·动物·自然的生态关系出发，
                        依据展出动物的种类和生活习性，将整个园子依次分隔成三个不同的小生态园，分别饲养展出孟加拉虎、美洲虎和豹。
                    </p>
                    <p>
                        设计者在生态园内堆土造地形，选择一些胸径0.4~0.5米的香樟、悬铃木等大树支托环境。针对不同动物的习性，
                        配置小乔木和花灌木，并自然组群种植，在草地上布置枯树段，以满足猛兽的捕食、磨爪的要求，也可避免猛兽对大树的损坏。
                        另外还大量运用藤本植物如爬山虎、西番莲、南蛇藤、鸡血藤、紫藤等绿化。
                        生态园外围的参观面安装了大面积的钢化玻璃，游客参观时基本没有视觉障碍，提高观赏效果，有了一种可与动物亲近的感觉。
                    </p>
                </div>
                <div class="img">
                    <img src="image/上海动物园/猛兽.jpg" alt="猛兽生态园">
                </div>
            </div>
            <div class="heixingxing">
                <div class="words">
                    <h4>黑猩猩生态园</h4>
                    <p>
                        猩猩馆建于1977年，总建筑面积850平方米，共有6个室内展厅组合成二幢相连的建筑，主要饲养猩猩，黑猩猩，长臂猿等类人猿。
                    </p>
                    <p>
                        类人猿展览厅宽敞，每个室内展厅有43平方米，南向与东向都有长窗采光，通风良好，室内展厅局部为三层。
                        室内展厅有4间，合用二个敞开式室外活动场。室外活动场成园形半岛式，三面环水，游人在抬高后地坪上观察动物的日常活动。
                        每到天气晴朗的时候，黑猩猩便跑到室外活动场戏耍、打闹。黑猩猩是地球上除人类外智慧最高的动物，
                        动物园为黑猩猩提供了一些玩具，在丰富它们的日常活动之余，
                        也增添了游客的乐趣。1991年又建筑了大猩猩馆，建筑面积共515平方米，有二间室内展厅和2个室外活动场。
                    </p>
                </div>
                <div class="img">
                    <img src="image/上海动物园/黑猩猩.jpg" alt="黑猩猩生态园">
                </div>
            </div>
            <div class="yazhouxiang">
                <div class="words">
                    <h4>亚洲象生态园</h4>
                    <p>
                        上海动物园饲养有国内动物园少有的三代同堂的亚洲象家族。象展区包括象宫和象室外活动场两部分。
                        象宫建成于1955年，是上海动物园第一幢永久性的动物馆舍，总建筑面积1550平方米，主要包括500平方米室内活动场、
                        620平方米参观厅和100余平方米的4个门厅。室内活动场的东、西、南三面都是宽9米的参观厅，参观厅进出口的门厅东、西各有一个，
                        南面分左右二个，南面二门厅间外有紫藤棚架相连。整个建筑的门窗都用杉木拼雕成具有民族风格的图形装饰。
                    </p>
                    <p>
                        象室外活动场设在象房建筑的东北面，占地4000平方米，内设240平方米的浴池和大遮阴棚架，四周用干沟与游人隔离。
                        活动场地坪主要为略有起伏的泥地，并栽有树木，更适宜象泥浴、运动等活动。上海动物园的亚洲象一直是小朋友们的最喜爱的动物之一。
                    </p>
                </div>
                <div class="img">
                    <img src="image/上海动物园/亚洲象.jpg" alt="亚洲象生态园">
                </div>
            </div>
            <div class="shihushan">
                <div class="words">
                    <h4>狮虎山</h4>
                    <p>
                        分为东山、中山和西山三个各自独立的部分，展出东北虎、非洲狮和华南虎。
                    </p>
                    <p>
                        狮虎山的室外活动场是敞顶的，每座面积300平方米左右，与游人用水沟隔离。
                        游人通过垂直的混凝土墙从远处观察动物。其中一处用瓷砖烧制壁画做背景，
                        壁画长达60米，高约6米，上绘草原、疏林、流水、野生动物等，
                        使游客仿佛置身于原野之中。游客可在狮虎山欣赏猛虎矫健的身影，聆听虎啸之声。
                    </p>
                </div>
                <div class="img">
                    <img src="image/上海动物园/狮虎山.jpg" alt="狮虎山">
                </div>
            </div>
            <div class="xiongmaoling">
                <div class="words">
                    <h4>熊猫岭</h4>
                    <p>
                        展出大熊猫和小熊猫两种可爱动物，两种动物的展区中间由木香花架相连，北毛竹园，
                        水杉林，东南西三面有慈孝竹丛点缀，使熊猫岭掩映在翠竹树丛间，既反映出熊猫岭栖息地的生景，又提高了展出环境。
                    </p>
                    <p>
                        大熊猫馆为一扇形建筑，即参观廓，中间为室内展厅，南面为室外活动场。参观廓宽4米，室内展厅面积120平方米，
                        用玻璃分隔两部分。室内活动场采用仿木框架与双层防弹玻璃相接，玻璃上部外倾5°，减少了反光，拉近了人与动物间距离。
                        游客可以透过玻璃细心观察大熊猫的一举一动。大熊猫半圆形的室外活动场面积600平方米，用围墙隔离，
                        场内有树木、草地、山石、水池，游人可侧坐围墙顶端观看熊猫活动。大熊猫户外运动场做了栖架，满足了大熊猫爬树的爱好。
                        小熊猫展区也包括内外两个部分。小熊猫馆为园形建筑总面积82平方米，其中参观厅50平方米，室内展厅82平方米，
                        内设卧床，便于小熊猫进行“隐秘”活动。南面室外活动场为270平方米，内种植树木、草地，小熊猫可终日呆在几棵高树上晒太阳或休息，或者笑迎远道而来的游客。
                    </p>
                </div>
                <div class="img">
                    <img src="image/上海动物园/熊猫岭.jpg" alt="熊猫岭">
                </div>
            </div>
            <div class="beijixiong">
                <div class="words">
                    <h4>北极熊生态园</h4>
                    <p>
                        在模仿北极冰山的环境中，利用循环水给北极熊提供了一个大游泳池。在参观地坪做成一个下沉式广场。
                        游客透过厚达8厘米、重达2.6吨的有机玻璃可对北极熊的活动一览无遗；玻璃幕墙形成了一个宽10米的大展面，
                        参观大视角，让游客仿佛置身于北极境地。饲养的北极熊是捷克一家动物园赠送给上海动物园的。
                        每天早晨，北极熊便在水池中尽情玩耍，有时会将粗大的前肢放在玻璃上，让游客与这种世界上最大的陆生食肉动物面对面。
                    </p>
                </div>
                <div class="img">
                    <img src="image/上海动物园/北极熊.jpg" alt="北极熊生态园">
                </div>
            </div>
            <div class="tianehu">
                <div class="words">
                    <h4>天鹅湖</h4>
                    <p>
                        建于1954年的上海动物园天鹅湖面积近3.3公顷（近50亩），是由几个天然水塘开挖连成的，因首先饲养天鹅而得名，一座三孔桥南北横跨。
                        湖东一座琉璃绿瓦的四角亭，与西面鸳鸯榭遥遥相对，临水倚栏，观赏水禽、飞鸟，尤其每天鹈鹕在上空盘旋，令人迷离神往。
                    </p>
                    <p>
                        湖中五个小岛均栽植黑松、柳树、桑树、水杉、池杉等供游禽栖息、产卵，湖的四周密布荻草、花芦、倭竹、
                        紫穗槐、麻叶绣球、紫微等植物繁密昌盛。站在三孔桥上眺望远处，水杉、香樟、雪松、柳树形成了优美树线，
                        幽邃深远、野味无穷。冬夏春秋景色多变，耐人寻味。天鹅湖开阔、水清、千鸟栖息，静中有动，动中有静，
                        声、色、影、光溶为一体，不仅使人流连忘返，而且把野鸟留住。如作为迁徙鸟类的夜鹭在经过上海动物园天鹅湖时，
                        感觉这里环境特佳就决定留下来，如今已在此定居多年并繁殖后代。天鹅湖东南面为一鸳鸯生态展区，数十对鸳鸯在此形影不离，双宿双飞。
                    </p>
                </div>
                <div class="img">
                    <img src="image/上海动物园/天鹅湖.jpg" alt="天鹅湖">
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="上海动物园_15.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="上海动物园_15.php?view='comment'">看评论</a>
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
    <title>上海动物园</title>
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
            background-image: url(image/上海动物园/上海动物园.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #bg {
            height: 820px;
        }
        .bt>li {
            float: left;
            position: relative;
            margin-left: 60px;
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
            margin-top: 10px;
        }
        /* 主要景点 */
        .zhuyaojingdian {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .jieweihuhou,.mengshou,.heixingxing
        ,.yazhouxiang,.shihushan,.xiongmaoling
        ,.beijixiong,.tianehu {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 节尾狐猴 */
        .jieweihuhou .words {
            float: left;
            width: 550px;
            margin-top: 50px;
        }
        .jieweihuhou img {
            float: left;
            width: 400px;
            margin-left: 40px;
        }
        /* 猛兽生态园 */
        .mengshou .words {
            float: left;
            width: 550px;
        }
        .mengshou img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 黑猩猩生态园 */
        .heixingxing .words {
            float: left;
            width: 550px;
            margin-top: 10px;
        }
        .heixingxing img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 10px;
        }
        /* 亚洲象生态园 */
        .yazhouxiang .words {
            float: left;
            width: 550px;
            margin-top: 10px;
        }
        .yazhouxiang img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 狮虎山 */
        .shihushan .words {
            float: left;
            width: 550px;
            margin-top: 30px;
        }
        .shihushan img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 熊猫岭 */
        .xiongmaoling .words {
            float: left;
            width: 550px;
            margin-top: 30px;
        }
        .xiongmaoling img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 100px;
        }
        /* 北极熊生态园 */
        .beijixiong .words {
            float: left;
            width: 550px;
            margin-top: 30px;
        }
        .beijixiong img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 天鹅湖 */
        .tianehu .words {
            float: left;
            width: 550px;
            margin-top: 30px;
        }
        .tianehu img {
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">上海动物园</a>
            <ul class="navbar-nav justify-content-end" style="font-size: 15px">
                <ul class="nav nav-tabs">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="border-top: 1px; border-color:RGB(248,249,250);">
                            其他景点</a>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="上海动物园_15.php">上海动物园</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../选区/选区1.php">区域</a>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </nav>
    <ul class="bt">
        <li>上</li>
        <li class="clear">海</li>
        <li class="clear">动</li>
        <li class="clear">物</li>
        <li class="clear">园</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


