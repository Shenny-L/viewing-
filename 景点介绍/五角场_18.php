<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=18;
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
    header("location:五角场_18.php?view='comment'");
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
    $moduleID=18;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=五角场_18.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=五角场_18.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="五角场_18.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="五角场_18.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="五角场_18.php">
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
                    五角场全称“江湾-五角场”，通称五角场，它是上海四大城市副中心之一，
                    南部地块为上海十大商业中心之一，因于上海市区东北角的邯郸路、四平路、黄兴路、翔殷路、
                    淞沪路五条发散型大道的交汇处而得名，与“徐家汇繁华商业副中心”，“浦东花木高尚居住副中心”，
                    “普陀真如交通枢纽副中心”并列为“上海四大城市副中心区”之一。总体范围北至殷高路；
                    东至民京路、国京路、政立路、国和路一线；西至国定路、政立路、南北向规划道路；南至国定路、国和路；
                    规划占地面积3.11平方公里。而五角场板块范围则有所不同，为三门路、政立路以南；国和路以西；
                    内环线以北区域。随着现代化商务设施、交通、生态等的不断发展，
                    区域整体优势随之完全凸显，已发展成为北上海商圈乃至整个上海最繁华的地段之一。
                </p>
            </div>
            <div class="img">
                <img src="image/五角场/概述图.jpg" alt="概述图">
            </div>
        </div>
        <div class="jubo">
            <h3>巨擘汇集</h3>
            <div class="heshenghui">
                
                <div class="words">
                    <h4>上海合生国际广场</h4>
                    <p>
                        五角场真正的王者上海合生国际广场将2016年后展现于世人面前，它是合生创展集团商业地产总部的扛鼎力作，
                        总建筑面积超过36万平方米，总投资逾70亿元，总设计理念以“水”为核心。该项目包括一个16万平方米的高端大型购物中心，
                        一座34层180米高的超5A甲级办公塔楼，以及高逾百余米的23层并委托凯悦管理的五星级酒店豪华酒店。
                        它也拥有上海稀有的3D IMAX影院、多主题真冰溜冰场，浪漫水景餐饮，名品主力百货，让人们尽情体验高品质的现代都市生活。
                        集一线品牌之美誉，构筑起上海合生国际广场的核心竞争力，缔造五角场核心的顶级繁华。
                    </p>
                </div>
                <div class="img">
                    <img src="image/五角场/合生汇.jpg" alt="合生汇">
                </div>
            </div>
            <div class="dongfangshangsha">
                <div class="words">
                    <h4>东方商厦</h4>
                    <p>
                        东方商厦杨浦店位于金岛新蓝天“姐妹楼”大厦的多层裙房内，共有地上4层和地下1层共5个楼面。
                        东方商厦原名为华联商厦（杨浦店），在2005年1月1日翻牌为东方商厦（杨浦店），经营业态和品牌都作了较大扩展和调整，
                        经营品牌也从原有的近200个扩大到了370多个，改扩建后的经营面积从原有的1.2万平方米扩大到了3万平方米，实现了老楼和新楼衔接。
                        它是一家以服饰经营为主题包含餐饮、娱乐休闲和超市的现代化购物场所。东方商厦坚持以高档商品为主的礼品化经营方向，
                        坚持“礼品的世界、礼仪的氛围和礼貌的服务”的经营宗旨，积极倡导“以顾客为中心”的全方位服务理念，其针对的客户群年龄与层次都比较高。
                        东方商厦以更新、更强的姿态屹立于五角场商圈，成为大都市东北角的时尚购物新地标。
                    </p>
                </div>
                <div class="img">
                    <img src="image/五角场/东方商厦.jpg" alt="东方商厦">
                </div>
            </div>
            <div class="suning">
                <div class="words">
                    <h4>苏宁电器广场</h4>
                    <p>
                        苏宁电器广场位于南政院信息化综合大楼的裙房内，该广场建筑共八层：地上五层、地下三层，
                        是继万达广场、百联又一城、东方商厦之后，五角场商圈新增添的商业地标。苏宁电器广场的建筑面积共35000平方米，
                        其中苏宁电器的面积超过1.2万平米，其余区域将引进工商银行、屈臣氏超市、时尚品牌专卖店、餐饮等业态。
                        苏宁电器广场以苏宁电器首家EXPO超级旗舰店为主力店，辅以中高档精品专卖店、影院、餐饮、超市等生活形态关联度较高的商业业态，
                        商场还大量引进3C配件、OA办公、卫浴用品、五金工具、日化用品、婴童用品等产品。形成丰富、浓郁的商业氛围，
                        以促进电器零售与其他业态的共同发展，带给消费者全方位的购物、休闲乐趣。五角场店SKU数达到5万个，商品出样总数达到10万个，堪称沪上之最。
                    </p>
                </div>
                <div class="img">
                    <img src="image/五角场/苏宁.jpg" alt="苏宁电器广场">
                </div>
            </div>
            <div class="wanda">
                <div class="words">
                    <h4>上海万达商业广场</h4>
                    <p>
                        上海万达商业广场总建筑面积约33万平方米，其中商业面积26万平方米，是上海市区迄今为止体量最大、
                        拥有业态最齐全、商业氛围最浓厚、商业设施最完备的商业项目。万达广场由中、美、日、澳四国著名设计公司联合设计。
                        广场平面呈“品”字形布置，划分为五幢大型业态的商用群楼和三幢甲级高层办公楼。
                    </p>
                    <p>
                        整个广场集中档购物，办公，餐饮及休闲娱乐为一体，汇聚沃尔玛购物中心、HOLA家居、香港新世界巴黎春天百货、
                        第一食品广场、万达IAMX影院、黄金珠宝城、宝大祥青少年购物中心、新华书城等八大主力业态，以及近百家各具特色的精品专卖、
                        餐饮休闲和华纳电影城等诸多高端品牌商业。另广场内还建有一约5000平米的演绎广场，以配合各种商业活动的开展。整个万达商业广场就犹如一个商业巨人，屹立于环岛一角。
                    </p>
                </div>
                <div class="img">
                    <img src="image/五角场/万达.jpg" alt="上海万达商业广场">
                </div>
            </div>
            <div class="bailian">
                <div class="words">
                    <h4>百联又一城购物中心</h4>
                    <p>
                        百联又一城购物中心总建筑面积12.6万平方米，坐拥地面9层与地下3层，集购物、餐饮、休闲、娱乐、健身等功能业态于一体，
                        坚持中高档经营定位，荟萃了2000 余种国际、国内的精选品牌，丰富的商品和特色鲜明的功能业态使广大消费者近悦远来。
                        各类精品百货，迷你电影院，滑冰场以及小吃广场均落户在此。其目标消费主要群体为中等偏上能力消费能力客户群，年龄主要集中在25—40岁之间的中产阶级。
                        开业以来，百联又一城购物中心正日渐成为越来越多的杨浦乃至上海市北部地区消费者购物休闲的重要场所，成为五角场商圈最具影响力的企业之一。
                    </p>
                </div>
                <div class="img">
                    <img src="image/五角场/百联.jpg" alt="百联又一城购物中心">
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="五角场_18.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="五角场_18.php?view='comment'">看评论</a>
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
    <title>五角场</title>
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
            background-image: url(image/五角场/五角场.jpg);
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
            color: white;
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
        /* 巨擘汇集 */
        .jubo {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .heshenghui,.dongfangshangsha,.suning
        ,.wanda,.bailian {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 合生汇 */
        .heshenghui .words {
            float: left;
            width: 550px;
        }
        .heshenghui img {
            float: left;
            width: 400px;
            margin-left: 40px;
        }
        /* 东方商厦 */
        .dongfangshangsha .words {
            float: left;
            width: 550px;
        }
        .dongfangshangsha img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 苏宁 */
        .suning .words {
            float: left;
            width: 550px;
        }
        .suning img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 万达 */
        .wanda .words {
            float: left;
            width: 550px;
        }
        .wanda img {
            float: left;
            width: 400px;
            margin-left: 40px;
            margin-top: 30px;
        }
        /* 百联又一城 */
        .bailian .words {
            float: left;
            width: 550px;
            margin-top: 30px;
        }
        .bailian img {
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">五角场</a>
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
        <li>五</li>
        <li class="clear">角</li>
        <li class="clear">场</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


