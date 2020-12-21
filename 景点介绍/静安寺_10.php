<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=10;
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
    header("location:静安寺_10.php?view='comment'");
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
    $moduleID=10;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=静安寺_10.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=静安寺_10.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="静安寺_10.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="静安寺_10.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="静安寺_10.php">
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
                    静安寺，又称静安古寺，位于上海市静安区，其历史相传最早可追溯至三国孙吴赤乌十年（247年），初名沪渎重玄寺。
                    宋大中祥符元年（1008年），更名静安寺。南宋嘉定九年（1216年），寺从吴淞江畔迁入境内芦浦沸井浜边（今南京西路），
                    早于上海建城。静安寺总建筑面积达2.2万平方米，整个庙宇形成前寺后塔的格局，由大雄宝殿、天王殿、三圣殿三座主要建筑构成，
                    是上海最古老的佛寺。寺内藏有八大山人名画、文征明真迹《琵琶行》行草长卷。
                    静安区亦由静安寺而闻名于世。静安寺的建筑风格是仿明代以前的建筑风格，典型的代表就是斗拱的形制。
                </p>
            </div>
            <div class="img">
                <img src="image/南京路/jingansi.jpg" alt="静安寺">
            </div>
        </div>
        <div class="geju">
            <div class="img">
                <img src="image/静安寺/建筑格局.jpg" alt="建筑格局">
            </div>
            <div class="words">
                <h3>建筑格局</h3>
                <p>
                    静安寺建筑布局在严格的中轴线上。从南至北依次座落着山门钟楼鼓楼、大雄宝殿和法堂。静安寺山门朝南，与天王殿合一。
                    山门地面层铺砌优质花岗岩，半椭圆型拱门门券雕刻着宋代云纹花饰。钟楼鼓楼各侧东西，
                    钟楼底层是重新恢复的“天下第六泉”——涌泉，上悬精铸7.3吨的和平钟。鼓楼采用架空方式将地铁出入口覆盖起来，上置直径3.38米牛皮大鼓。
                </p>
            </div>
        </div>
        <div class="zhuyaojingdian">
            <h3>主要景点</h3>
            <div class="shanmen">
                <div class="words">
                    <h4>山门</h4>
                    <p>
                        静安寺山门与天王殿一体，上下两层结构。花岗石料贴面，柚木木作，黄色琉璃瓦屋顶，屋脊上“正法久住”四个字正见佛陀心意。
                        下层正中三扇柚木大门，上覆铜钉装饰，门洞一围石刻佛手、莲花、八吉祥等图案。
                        正门石刻对联“愿祈佛手双垂下”、“摩得人心一样平”，山门左右两侧各饰一只万年青石雕刻法轮。二层回廊汉白玉围栏，内为柚木。
                    </p>
                </div>
                <div class="img">
                    <img src="image/静安寺/山门.jpg" alt="山门">
                </div>
            </div>
            <div class="zhonglou">
                <div class="words">
                    <h4>钟楼</h4>
                    <p>
                        静安寺二层悬挂和平钟，钟高3.3米，口径2.1米，重7.3吨，用青铜浇铸而成，钟声浑厚，尾音绵长。
                        下层恢复“静安八景”之一“涌泉”，人称“天下第六泉”，与新打的156米深井地下泉水相溶，涌泉观赏井深6米，
                        井口为六边形，用整块金山石加工制成，整个涌泉井用666块花岗石砌成，一块井底石重达6000斤。
                    </p>
                </div>
                <div class="img">
                    <img src="image/静安寺/钟楼.jpg" alt="钟楼">
                </div>
            </div>
            <div class="guanyindian">
                <div class="words">
                    <h4>观音殿</h4>
                    <p>
                        静安寺观音殿位于东厢房正中，殿高20.6米，供奉由整颗香樟木雕刻的观音菩萨一尊。屋顶为黄色琉璃瓦，
                        与东厢房深灰色琉璃瓦对比明显，突出殿堂庄严。东厢房上下两层回廊结构，与山门、钟鼓楼、大殿及法堂连为一体。
                    </p>
                </div>
                <div class="img">
                    <img src="image/静安寺/观音殿.jpg" alt="观音殿">
                </div>
            </div>
            <div class="mounidian">
                <div class="words">
                    <h4>牟尼殿</h4>
                    <p>
                        牟尼殿位于西厢房正中，殿高20.6米，供奉白玉牟尼佛一尊。屋顶为黄色琉璃瓦，与西厢房深灰色琉璃瓦对比明显，
                        突出殿堂庄严。西厢房上下两层回廊结构，与山门、钟鼓楼、大殿及法堂连为一体。
                    </p>
                </div>
                <div class="img">
                    <img src="image/静安寺/牟尼殿.jpg" alt="牟尼殿">
                </div>
            </div>
            <div class="daxiongbaodian">
                <div class="words">
                    <h4>大雄宝殿</h4>
                    <p>
                        大雄宝殿殿高26米，庑殿重檐，内竖46根直径0.72至0.8米、精心加工的柚木柱子，建筑用木料达3000多立方米，
                        大雄宝殿以铜瓦为顶。殿内供奉一尊15吨纯银铸造的释迦牟尼佛像。大殿底层为千人讲经堂，地下为1000平方米的藏经库，
                        内将存放13万片石刻藏经，以保后世流传。大雄宝殿两旁是东西厢房，有两层雕梁廊道与整个寺院相连，
                        廊道边缘建有汉白玉莲花立柱和围栏。东厢房设观音殿，内供高6.2米千年香樟独木观音像，西厢房设牟尼殿，
                        内供高3.87米、重11吨，用整块缅甸白玉雕刻的释迦牟尼佛坐像。大雄宝殿殿前广场正中，是新落成的福慧宝鼎。
                        用白铜铸造的福慧宝鼎，重15.5吨、高10.23米，矗立在重36吨、
                        以整块万年青石雕刻而成的宝鼎基座上，宝鼎侧面铸有慧明大和尚亲自撰写的、详细记载这座古寺历史之传承的铭文。
                    </p>
                </div>
                <div class="img">
                    <img src="image/静安寺/大雄宝殿.jpg" alt="大雄宝殿">
                </div>
            </div>
            <div class="baota">
                <div class="words">
                    <h4>静安宝塔</h4>
                    <p>
                        静安宝塔，为7层平面方形，宝塔占地面积85平方米，建筑面积952平方米，塔刹为金刚宝座塔样式，青铜浇铸，
                        表面贴金。金佛殿座落于大雄宝殿后面的法堂最高层，仿宋代建筑风格，柚木铜顶架构，殿内将供奉一尊两吨重纯金释迦牟尼佛像。
                        法堂东、西顶端20米高处，建有知恩阁和报恩阁，与庙前的钟、鼓楼遥相呼应。
                    </p>
                </div>
                <div class="img">
                    <img src="image/静安寺/静安宝塔.jpg" alt="静安宝塔">
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="静安寺_10.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="静安寺_10.php?view='comment'">看评论</a>
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
    <title>静安寺</title>
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
            background-image: url(image/静安寺/静安寺.jpg);
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
            margin-top: 60px;
        }
        .jianjie .img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 建筑格局  */
        .geju {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .geju img {
            float: left;
            width: 400px;
            margin-top: 10px;
        }
        .geju .words {
            float: left;
            width: 600px;
            margin-left: 40px;
            margin-top: 60px;
        }
        /* 主要景点 */
        .zhuyaojingdian {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .shanmen,.zhonglou,.guanyindian
        ,.mounidian,.daxiongbaodian,.baota {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 山门 */
        .shanmen .words {
            float: left;
            width: 550px;
            margin-top: 50px;
        }
        .shanmen img {
            float: left;
            width: 400px;
            margin-left: 40px;
        }
        /* 钟楼 */
        .zhonglou .words {
            float: left;
            width: 550px;
            margin-top: 50px;
        }
        .zhonglou img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 观音殿 */
        .guanyindian .words {
            float: left;
            width: 550px;
            margin-top: 60px;
        }
        .guanyindian img {
            float: left;
            width: 400px;
            margin-left: 40px;
        }
        /* 牟尼殿 */
        .mounidian .words {
            float: left;
            width: 550px;
            margin-top: 50px;
        }
        .mounidian img {
            float: left;
            width: 400px;
            margin-left: 40px;
        }
        /* 大雄宝殿 */
        .daxiongbaodian .words {
            float: left;
            width: 550px;
        }
        .daxiongbaodian img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 静安宝塔 */
        .baota .words {
            float: left;
            width: 550px;
            margin-top: 50px;
        }
        .baota img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">静安寺</a>
            <ul class="navbar-nav justify-content-end" style="font-size: 15px">
                <ul class="nav nav-tabs">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="border-top: 1px; border-color:RGB(248,249,250);">
                            其他景点</a>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="静安寺_10.php">静安寺</a> <a class="dropdown-item" href="四行仓库_16.php">四行仓库</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../选区/选区1.php">区域</a>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </nav>
    <ul class="bt">
        <li>静</li>
        <li class="clear">安</li>
        <li class="clear">寺</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


