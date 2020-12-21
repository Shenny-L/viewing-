<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=8;
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
    header("location:古华园_8.php?view='comment'");
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
    $moduleID=8;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=古华园_8.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=古华园_8.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="古华园_8.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="古华园_8.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="古华园_8.php">
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
                    1982年10月，县人民政府向市报请在南桥镇分期建设一个面积为12.2万平方米的公园。
                    上海市基本建设委员会于1983年3月批准建设公园的计划任务书。次年7月第一期工程用地经市城市
                    规划建筑管理局核准后，征用江海乡曙光村、南星村土地共5.01万平方米。公园总体规划设计
                    由同济大学建筑系园林教研室司马铨、陈久昆负责，由无锡市园林古典建筑公司、浙江省上虞县盖北建设公司等施工。
                </p>
                <p>
                    1984年10月动工，次年完成挖、堆土2.85万立方米，砌湖石驳岸1128米、围墙733米、石板和弹石路600米，
                    从园外迁建古桥1座，新建拱桥、平桥、曲桥11座。1986年又完成南大门景区、秋水园及公园管理用房、餐厅、售品部。
                    同年10月1日初步建成开放，园名取奉贤县本属华亭县之意，定名古华园。公园开放后继续施工，先后建成兴园景区、儿童乐园、
                    三女祠、环秀桥、望海阁。至1992年6月，一期工程基本完成，总投资415.16万元，同年更名古华公园。
                </p>
            </div>
            <div class="img">
                <img src="image/古华园/古华园.jpg" alt="简介">
            </div>
        </div>
        <div class="jingqutese">
            <div class="words">
                <h3>景区特色</h3>
                <p>
                    古华公园构思独特，小桥流水间亭、台、楼、阁密布，花草树木相缀，形成了具有清明建筑风格特色，
                    给人以古朴典雅、清静、舒适、完整的优美形象，成为了沪郊旅游新干线一道亮丽的风景线。
                </p>
                <p>
                    古华公园构筑上采集了奉贤历史上众多典故。“三女祠”是仿真建筑，它依据的是历史上“吴越争需”，
                    越王勾践率部逼近苏州，吴王令其三女南逃，在行至奉贤南桥镇北二里处时，恐落入越兵之手，而就地悬梁自缢的史事。
                    历史不短的“南塘第一桥”、“兴园”等一大批历史建筑的迁入和再造，使古华公园展现出了一派古朴、典雅的历史文化风采。
                    人们在此寻游考古，自有别样的情趣。这些带有历史文化的深邃印迹的“主打”景点，也是公园吸引一批又一批游人观光，使众人流连忘返的“磁场”。
                </p>
            </div>
            <div class="img">
                <img src="image/古华园/景区特色.jpg" alt="景区特色">
            </div>
        </div>
        <div class="zhuyaojingdian">
            <h3>主要景点</h3>
            <div class="xihu">
                <div class="img">
                    <img src="image/古华园/西湖.jpg" alt="西湖">
                </div>
                <div class="words">
                    <h4>西湖</h4>
                    <p>
                        位于园中心，面积约8000平方米。湖边遍植垂柳，间种碧桃。湖中岛屿及湖四周共有桥11座，
                        均由花岗石构筑，桥名大多采用县境内原有的古桥名，如“启秀桥”、“接秀桥”、“香花桥”、
                        “环秀桥”、“福寿桥”等等。其中最大的为“南塘第一桥”，原位于南桥镇东街跨南桥塘(河)上，
                        原名乐善桥，清乾隆元年(1736年)改今名。现桥为同治元年(1862年)重建，长25.8米，宽3米,
                        拱跨7.6米，两边石阶各27级。湖南部五龙壁泉前，有题名水秀、水暖、水绿3亭成品字形立于湖中，
                        均为方形混合结构，小青瓦攒尖顶，面积共27平方米。壁泉侧有一座11曲平桥联接3亭。
                    </p>
                </div>
            </div>
            <div class="qiushuiyuan">
                <div class="img">
                    <img src="image/古华园/秋水园.jpg" alt="秋水园">
                </div>
                <div class="words">
                    <h4>秋水园</h4>
                    <p>
                        位于湖中岛上，占地约1300平方米，为仿清代张姓兄弟在县西庄行镇已废的秋水园而筑。
                        岛南北分别有南塘第一桥、香花桥、接秀桥与岸相联。园中主建筑超然堂为三开间，
                        砖木结构，单檐歇山顶，面积137平方米，堂额由陈从周书。堂中陈设大型木刻《古华名胜概图》
                        和孔子门生言偃的雕塑。超然堂及其南的伴月亭、东厢的听流亭、西厢的涵碧轩之间，
                        以回廊、粉墙围成庭院，有月洞门、漏窗与外相通。院中央有水池和湖石假山，
                        植两株金桂，还有一株年逾百龄的石榴。岛上其他地方植桂花、梧桐、竹、芭蕉。
                    </p>
                </div>
            </div>
            <div class="xingyuan">
                <div class="words">
                    <h4>兴园</h4>
                    <p>
                        位于东南隅，占地约2000平方米，系仿县西北邬桥已废同名古园而建。原古兴园始建于明代，
                        园主为贡生顾绂，乾隆年间(1736～1795年)重建后再废。园以池为中心，房舍皆近水而筑。
                        池东西狭长曲折，西北有河道与西湖相通。主建筑怡晚堂在池北偏东，三楹，单檐硬山顶，
                        面积106平方米。堂南临水，东、西、北三面有门。堂内陈设“福”、“寿”为题的大型木雕，
                        及“九龙戏珠”、“松鹤延年”、“八仙过海”、“金陵十二钗”为题材的木雕壁挂。池东的宝穑轩，两层，
                        面积72平方米。池南的养正书屋，面积55平方米。池西南有鸥舫，虽名舫而不临水。园中建筑均为混合结构，有游廊将各建筑联为一体。
                        此外，池西有扇亭(又名竹林青閟)、度鹤亭。池中有一小岛，上置湖石假山。景区内栽大量梅、竹。
                    </p>
                </div>
                <div class="img">
                    <img src="image/古华园/兴园.jpg" alt="兴园">
                </div>
            </div>
            <div class="sannvgang">
                <div class="words">
                    <h4>三女冈</h4>
                    <p>
                        蜿蜒于园北，东西长25米，高14米，有主次三峰。南桥镇北有丘名三女冈，
                        传说吴王夫差葬三女(也有说是葬三妃)于此，为古华亭县的一景，假山由此得名。
                        山上栽黑松,间植香樟、青枫、海桐、国槐、杨梅、火棘、桂花、茶花、杜鹃、中山柏、竹。
                        山巅建高13米的望海阁，为两层八角重檐攒尖顶建筑，由夏征农题额，面积85平方米。
                        西山麓有一座三合院式建筑，名三女祠，南北中轴布局，由大门、过廊、
                        正厅和耳房等组成。院门前置石鼓一对，有石阶10级。院外东侧有黄石叠砌的假山叠瀑。
                    </p>
                </div>
                <div class="img">
                    <img src="image/古华园/三女冈.jpg" alt="三女冈">
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="古华园_8.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="古华园_8.php?view='comment'">看评论</a>
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
    <title>古华园</title>
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
            background-image: url(image/古华园/古华.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #bg {
            height: 820px;
        }
        .bt>li {
            float: left;
            position: relative;
            margin-left: 360px;
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
            width: 600px;
            margin-top: 20px;
        }
        .jianjie .img {
            float: left;
            margin-left: 60px;
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
        .jingqutese .img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 主要景点 */
        .zhuyaojingdian {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .xihu,.qiushuiyuan
        ,.xingyuan,.sannvgang {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 西湖 */
        .xihu img {
            float: left;
            width: 400px;
        }
        .xihu .words {
            float: left;
            width: 600px;
            margin-left: 40px;
            margin-top: 10px;
        }
        /* 秋水园 */
        .qiushuiyuan img {
            float: left;
            width: 400px;
        }
        .qiushuiyuan .words {
            float: left;
            width: 600px;
            margin-left: 40px;
            margin-top: 10px;
        }
        /* 兴园 */
        .xingyuan .words {
            float: left;
            width: 550px;
            margin-top: 20px;
        }
        .xingyuan img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
            width: 400px;
        }
        /* 三女冈 */
        .sannvgang .words {
            float: left;
            width: 550px;
            margin-top: 60px;
        }
        .sannvgang img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
            width: 400px;
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">古华园</a>
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
    <ul class="bt">
        <li>古</li>
        <li class="clear">华</li>
        <li class="clear">园</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


