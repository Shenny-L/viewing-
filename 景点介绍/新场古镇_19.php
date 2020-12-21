<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=19;
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
    header("location:新场古镇_19.php?view='comment'");
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
    $moduleID=19;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=新场古镇_19.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=新场古镇_19.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="新场古镇_19.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="新场古镇_19.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="新场古镇_19.php">
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
                    新场古镇位于沪南公路南汇段的中间，位于浦东新区中南部，距上海市中心约36公里，
                    距浦东国际机场20公里，与迪士尼乐园仅8分钟车程。是原南汇地区的四大镇之一，
                    曾经有“金大团、银新场、铜周浦、铁惠南”的说法。2002年7月由原新场镇、坦直镇撤并而成，
                    镇域总面积53.46平方公里，有总人口10.9万人，其中户籍人口5.2万人。下辖13个行政村、7个居委会。
                    近几年，新场镇先后获得了“中国历史文化名镇”、“中国民间文化艺术之乡”、“国家卫生镇”等荣誉。
                </p>
                <p>
                    两区合并之后，新场处于大浦东中南部地区，既是南北中部产业带辐射范围，也是东西小城镇发展带覆盖范围，
                    区位优势明显。“四纵四横+轨道交通”的路网布局、迪士尼的带动辐射、张江工业园区的联动效应等，
                    为新场的发展带来了新的机遇。区委在“十三五”规划中，制定并明确了中部城镇带崛起的目标，
                    新场位列其中，并成为整个中部城镇带当中率先明确功能定位的一个镇，
                    即：围绕新场古镇的保护与开发，加快推进，推动整个区域经济发展。
                </p>
                <p>
                    新场地区原为下沙盐场的南场，是当时盐民用海水晒盐的场所。后来海滩慢慢长出去了，
                    这个盐场也逐渐成了盐民居住和交换商品的地方。在新场成镇之时，正值下沙盐场鼎盛时期，盐产量和盐灶之多，胜过浙西诸盐场。
                </p>
            </div>
            <div class="img">
                <img src="image/新场古镇/新场古镇1.jpg" alt="新场古镇">
            </div>
        </div>
        <div class="guzhenfengmao">
            <div class="img">
                <img src="image/新场古镇/古镇风貌.jpg" alt="古镇风貌">
            </div>
            <div class="words">
                <h3>古镇风貌</h3>
                <p>
                    传说在新场受恩桥石头湾沙中曾发现石笋，深不见底，所以过去新场镇又名“石笋里”。
                    所谓“石笋十景”即“石笋里”－－新场古镇十景。这十景，其中有书楼、寺庙、渔舟塘、古桥等等。
                    因年代久远，几经变迁，有的照原貌翻新修复，有的只有其名不存其貌，有的正在规划重建之中，
                    千年古迹十分珍贵。新场古镇十景为：溪湾石笋、书楼秋爽、雷音晓钟、横塘晚棹、仙洞丹霞、
                    海眼原泉、高阁晴云、上方烟雨、千秋夜月、南山雪霁。新场镇旧时多石拱桥，是江南水乡特色之一。
                    新场有“十三牌坊”、“九环龙”之称。著名而遗存的石拱桥有：洪福桥、千秋桥、
                    白虎桥、扬辉桥、玉皇阁桥、永宁桥、盛家桥。新场镇仍保留着古镇风貌，遗存着部分古景古迹。
                </p>
            </div>
        </div>
        <div class="zhuyaojingdian">
            <h3>古镇主要景点</h3>
            <div class="chenlieguan">
                <div class="words">
                    <h4>文化陈列馆</h4>
                    <p>
                        新场历史文化陈列馆（新场大街367号），建筑面积1500平方米，初建于清光绪年间，已有100多年的历史了。
                        解放前，这里是新场最大的一个典当行——信隆典当。新中国成立后，曾做过镇文化宫、工人俱乐部，2006年经过修缮后，
                        由上海市历史博物馆设计布展成“新场历史文化陈列馆”。陈列馆内共设有沧海桑田、煮海熬波、名人荟萃、生态古镇4个展厅。
                    </p>
                    <p>
                        有的资料反映，它是浦东新区清代的优秀建筑，原为嘉乐堂，至今保存完好，三进格局，建筑面积976.2平方米，
                        大厅雕梁画栋，厅的八扇门正面雕八骏图，背面雕水浒人物，前后仪门完整，是座小家碧玉式的典型院落；
                        信隆典当，三进，前一进为平房，后二进为楼房，建筑面积约700平方米；潘氏北宅，
                        原有七进，现存五进，第二进客堂泥金漆廊柱犹在，第三进为二层楼，尚存五扇蠡壳窗，重檐、通转游廊。
                    </p>
                </div>
            </div>
            <div class="sanshierpinfang">
                <div class="words">
                    <h4>三世二品坊</h4>
                    <p>
                        明太常寺卿朱国盛为其家三代都有二品官而在新场镇中市的街口建“三世二品坊”一座，三世二品石牌楼，
                        气势宏伟，高耸挺拔。石雕也很精美，上刻“九列名卿”。中进士的历代有二十几人。 
                        这座石坊在世间屹立了三百多年后于1974年被拆除，看到的石坊是在拆除30年后又重现仿建的。
                    </p>
                    <p>
                        三世二品坊是明朝万历年间的太常寺卿朱国盛建造的，因为他的祖父和父亲都被封赠为山东右布政使，列二品，太常寺卿也是二品的官阶，
                        一家三代都是官居二品的朝廷要员，这可是光宗耀祖的事情，所以朱国盛在家乡新场建造了这座三世二品坊。
                    </p>
                </div>
                <div class="img">
                    <img src="image/新场古镇/三世二品坊.jpg" alt="三世二品坊">
                </div>
            </div>
            <div class="jiangnandiyilou">
                <div class="words">
                    <h4>江南第一楼</h4>
                    <p>
                        “江南第一楼” 地处新场的闹市地段，倚洪福桥而造，是一座三层木质结构的古建筑：
                        第一层是普通茶馆，始建于清朝同治末年；第二层是书场兼高档茶馆，
                        时常有来自各地的民间说书、评弹艺人在此演出，每每说书名家到来，车舟汇聚、听客云集。
                        第三层则是“栈房”，供旅客歇息借宿。
                    </p>
                </div>
                <div class="img">
                    <img src="image/新场古镇/江南第一楼.jpg" alt="江南第一楼">
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="新场古镇_19.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="新场古镇_19.php?view='comment'">看评论</a>
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
    <title>新场古镇</title>
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
            background-image: url(image/新场古镇/新场古镇.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #bg {
            height: 820px;
        }
        .bt>li {
            float: left;
            position: relative;
            margin-left: 250px;
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
            width: 550px;
        }
        .jianjie .img {
            float: left;
            margin-left: 40px;
            margin-top: 100px;
        }
        /* 古镇风貌  */
        .guzhenfengmao {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .guzhenfengmao .img {
            float: left;
            margin-top: 10px;
        }
        .guzhenfengmao .words {
            float: left;
            width: 600px;
            margin-left: 40px;
        }
        /* 古镇主要景点 */
        .zhuyaojingdian {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .chenlieguan,.sanshierpinfang,.jiangnandiyilou {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 文化陈列馆 */
        .chenlieguan .words {
            float: left;
            width: 1050px;
        }
        /* 三世二品坊 */
        .sanshierpinfang .words {
            float: left;
            width: 550px;
            margin-top: 80px;
        }
        .sanshierpinfang .img {
            float: left;
            margin-left: 100px;
            margin-top: 20px;
        }
        /* 江南第一楼 */
        .jiangnandiyilou .words {
            float: left;
            width: 550px;
            margin-top: 80px;
        }
        .jiangnandiyilou .img {
            float: left;
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">新场古镇</a>
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
        <li>新</li>
        <li class="clear">场</li>
        <li class="clear">古</li>
        <li class="clear">镇</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


