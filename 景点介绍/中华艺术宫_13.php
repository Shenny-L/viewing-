<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=13;
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
    header("location:中华艺术宫_13.php?view='comment'");
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
    $moduleID=13;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=中华艺术宫_13.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=中华艺术宫_13.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="中华艺术宫_13.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="中华艺术宫_13.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="中华艺术宫_13.php">
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
                    中华艺术宫由中国2010年上海世博会中国国家馆改建而成，于2012年10月1日开馆，总建筑面积16.68万平米，展示面积近7万平米，拥有35个展厅。
                </p>
                <p>
                    公共教育空间近2万平米，配套衍生服务经营总面积达3000平方米。其主体建筑位于浦东新区上南路205号，毗邻地铁7号线和8号线，交通便利。
                </p>
                <p>
                    中华艺术宫是集公益性、学术性于一身的近现代艺术博物馆，以收藏保管、学术研究、陈列展示、普及教育和对外交流为基本职能，坚持立足上海、携手全国、面向世界。自开馆试展后，
                    参照国际艺术博物馆运行的经验，逐步建立了政府主导下理事会决策、学术委员会审核、基金会支持的“三会一体”运营架构。
                </p>
                <p>
                    以打造整洁、美丽、友好、诚实、知性的艺术博物馆的目标，中华艺术宫以上海国有艺术单位的收藏为基础，
                    常年陈列反映中国近现代美术的起源与发展脉络的艺术珍品；联手全国美术界，收藏和展示代表中国艺术创作最高水平的艺术作品；
                    联手世界著名艺术博物馆合作展示各国近现代艺术精品，成为中国近现代经典艺术传播、东西方文化交流展示的中心。
                    同时，馆内还设有艺术剧场、艺术教育长廊等艺术教育传播区域，
                    引进了与馆内整体文化形象相吻合的餐饮、图书、艺术品等配套衍生服务，积极打造 “艺术服务综合体”的文化服务概念。
                </p>
                <p>
                    中华艺术宫秉持艺术服务人民的立馆之本，始终把观众需求作为第一信号，坚持公益性的基本价值取向，集社会各方之力，
                    加强文化生产，强化公共服务，努力成为公众享受经典艺术、提升艺术美育的高雅殿堂。
                </p>
            </div>
            <div class="img">
                <img src="image/中华艺术宫/中华艺术宫1.jpg" alt="简介">
            </div>
        </div>
        <div class="guanbiao">
            <div class="img">
                <img src="image/中华艺术宫/馆标.jpg" alt="馆标">
            </div>
            <div class="words">
                <h3>馆标</h3>
                <p>
                    中华艺术宫的馆标基本延用中国2010年上海世博会中国国家馆馆标的主要元素和设计理念，包含了繁体的“华”字，
                    同时勾勒出世博会中国馆“东方之冠”的形象外观，东方红更是体现了炎黄子孙对祖国的热爱！
                </p>
            </div>
        </div>
        <div class="kaimuzhanlan">
            <div class="words">
                <h3>开幕展览</h3>
                <p>
                    中华艺术宫开馆展览将由《海上生明月——中国近现代美术的起源》、《来自世界的祝贺——国际美术珍品展》、
                    《锦绣中华——行进中的新世纪中国美术》、《上海历史文脉美术创作工程作品展》以及“名家馆”5个部分组成。
                    “名家馆”首批将推出贺天健、滑田友、关良、谢稚柳、程十发、吴冠中等七位艺术大师的300余件作品，
                    并在2楼展厅联合陈列其他著名艺术家的160余件艺术作品：在开幕季展览上，中华艺术宫从11月开始推出
                    《米勒、库尔贝与法国自然主义——法国奥赛博物馆馆藏珍品展》、《不息的变动——上海美专一百周年纪念展》等系列展。
                </p>
            </div>
        </div>
        <div class="mubiaodingwei">
            <h3>目标定位</h3>
            <div class="xueshudingwei">
                <div class="words">
                    <p>
                        根据《国际博物馆协会章程》、《全国重点美术馆评估办法》的相关规定，中华艺术宫以近现代艺术为侧重，
                        履行收藏保管、陈列展示、学术研究、普及教育、对外交流的基本职能，是对公众免费开放的公益性艺术博物馆。
                    </p>
                </div>
            </div>
            <div class="yunyingyuanze">
                <div class="words">
                    <p>
                        坚持公益性质，坚持社会效益，不以营利为目的，不租借专业展厅。
                        严格学术标准，恪守博物馆伦理，所有的收藏业务及展览项目须经学术委员会讨论确定。
                    </p>
                </div>
            </div>
            <div class="yunyingmubiao">
                <br />
                <p>
                    将通过三年努力，达到“国际知名，亚洲一流，国内领先”的目标。包括：
                </p>
                <br />
                <p>
                    1、丰富馆藏。设立“中华艺术宫、上海当代艺术博物馆基金会”，开辟募捐渠道，鼓励社会捐赠。
                    如今，中华艺术宫已接受吴冠中家属、滑田友家属、贺慕群、贺友直、美国著名艺术家康道斯的捐赠，
                    为建立“名家馆”打下良好基础。基金会的募集已正式启动，已收到第一笔资金捐赠。
                    通过收购、捐赠，中华艺术宫将在如今1.4万件馆藏品的基础上，不断完善中国近现代美术史系列等藏品体系；
                </p>
                <br />
                <p>
                    2、公共教育。建立学生专场活动机制，免费举办专业美术讲座、艺术教育师资培训、美术爱好者临摹等活动，提高公民艺术素养。
                    每年各类教育活动不少于400场，受益人数100万人，占总参观人数不少于1/3。招募培训专业志愿者，开馆时将有首批800位志愿者协助工作。
                </p>
                <br />
                <p>
                    3、展览策划。建立国内外、老中青相结合的开放式、项目制、柔性化的“策展人”机制。
                    以项目为任务编组，聘请海内外一流专业人士担任策展人。加强自主策划，提高展览质量，每年自主策划的展览应占展览总数65%以上。
                </p>
                <br />
                <p>
                    4、数字博物馆。两馆将为观众提供二维码接入、在线互动参与、艺术信息数据查询等服务。还将利用展厅播放艺术电影。
                </p>
                <br />
                <p>
                    5、观众服务。制订针对不同群体的服务策略，两馆每年观众人次总数不低于300万人次。有关交通服务信息将在9月份的发布会上正式发布。
                </p>
                <br />
                <p>
                    6、学术研究。依托藏品，加强学术研究，积极承担国家级、省部级的研究课题，
                    定期推出常设陈列展，出版学术专著，举办学术活动。常设陈列展应占所有展示面积30%以上。
                </p>
                <br />
                <p>
                    此外，中华艺术宫将积极拓展衍生服务，提供具有品牌效应的餐饮、图书、剧场和其它衍生服务。
                    两馆还将阶段性邀请中国博协美术馆专业委员会和第三方社会资讯机构进行业内外绩效评估，定期向社会发布年报。
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="中华艺术宫_13.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="中华艺术宫_13.php?view='comment'">看评论</a>
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
    <title>中华艺术宫</title>
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
            background-image: url(image/中华艺术宫/中华艺术宫.jpg);
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
            margin-top: 60px;
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
            
        }
        .jianjie .img {
            float: left;
            margin-left: 40px;
            margin-top: 100px;
        }
        /* 馆标 */
        .guanbiao {
            overflow: hidden;
            padding-top: 40px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .guanbiao .img {
            float: left;
            margin-top: 10px;
        }
        .guanbiao .words {
            float: left;
            width: 600px;
            margin-left: 40px;
            margin-top: 60px;
        }
        /* 开幕展览 */
        .kaimuzhanlan {
            overflow: hidden;
            padding-top: 40px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        /* 目标定位 */
        .mubiaodingwei {
            overflow: hidden;
            padding-top: 40px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .mubiaodingwei h3 {
            margin-left: 75px;
        }
        /* 学术定位,运营原则,运营目标 */
        .xueshudingwei,.yunyingyuanze
        ,.yunyingmubiao .words {
            margin-top: 30px;
        }
        .xueshudingwei,.yunyingyuanze
        ,.yunyingmubiao {
            width: 900px;
            margin-left: 75px;
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">中华艺术宫</a>
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
        <li class="clear">华</li>
        <li class="clear">艺</li>
        <li class="clear">术</li>
        <li class="clear">宫</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


