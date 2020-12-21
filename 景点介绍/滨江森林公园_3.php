<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=3;
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
    header("location:滨江森林公园_3.php?view='comment'");
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
    $moduleID=3;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=滨江森林公园_3.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=滨江森林公园_3.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="滨江森林公园_3.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="滨江森林公园_3.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="滨江森林公园_3.php">
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
                    上海滨江森林公园位于浦东新区高桥镇高沙滩，于上世纪50年代采取吹泥成陆的办法围垦形成。
                    隔黄浦江与炮台山相对，隔长江与横沙生态岛、崇明东滩鸟类保护区、九段沙湿地保护区相望，是上海森林覆盖率最高的郊野森林公园。
                </p>
                <p>
                    这里保留了原生态的乡土植物和动植物的栖息地，适合游客享受休闲的乐趣。
                    公园设湿地植物观赏园、生态林保护区、滨江岸线观景区、蔷薇园、木兰园、杜鹃园、果园区等多个自然景点，
                    还安排了游览观光车、电动游船、水上步行球、休闲自行车、碰碰车、海盗船等游乐项目。
                    为市民和游客提供自然风光、湿地景观、休闲娱乐、科普教育和园林空间的自由开畅的城市郊野森林公园。
                </p>
            </div>
            <div class="img">
                <img src="image/滨江森林公园/森林公园1.jpg" alt="简介">
            </div>
        </div>
        <div class="zhuyaojingdian">
            <h3>主要景点</h3>
            <div class="binjianghuayuan">
                <div class="img">
                    <img src="image/滨江森林公园/滨江花园.jpg" alt="滨江花园">
                </div>
                <div class="words">
                    <h4>滨江花园</h4>
                    <p>
                        滨江花园全长2.5公里，北起泰同栈码头，南至东昌路码头，是集观光、绿化、
                        交通及服务设施为一体，着眼于城市生态环境和功能的沿江景观工程。
                    </p>
                    <p>
                        滨江花园由亲水平台、坡地绿化、景观道路及有50年历史的滨江花园
                        （原浦东公园）等组成，是二十一世纪上海东外滩。 滨江森林公园位于浦东新区最北端，
                        北临长江口，西临黄浦江，占据了上海独一无二的黄浦江、长江和东海“三水并流”的地理位置。如果江面能见度高，
                        不仅能观赏到“三水并流”的壮观场面，还能望到横沙、崇明、长兴三岛。而这条与公园融为一体的“滨江岸线”
                        是由浦东新区北部的海塘和黄浦江吴淞口东侧的防汛堤岸改建而成，沿途还设置了凭栏、木栈道和休憩亭廊等，
                        岸线上还建有三处“亲水平台”，有机连结了公园与江海。
                    </p>
                    <p>
                        在亲水平台上人们可凭栏临江。在亲水平台一侧逐渐升高的坡地上，花灌木镶嵌在翠绿的草坪丛中。
                        园内的湖泊、水榭亭、六角亭、小桥、假山、花廊、小径等在高大的乔木和茂密的花灌木融合下，
                        更具有古典园林特色，给游客创造了一种远离大都市的安逸、憩静的环境。
                    </p>
                </div>
            </div>
            <div class="yuanshimilin">
                <div class="img">
                    <img src="image/滨江森林公园/原始密林.jpg" alt="原始密林">
                </div>
                <div class="words">
                    <h4>原始密林</h4>
                    <p>
                        连绵不绝的绿色森林成为滨江湿地公园独特的景观。向公园腹地走20分钟便进入一片密林，
                        只见大片树身呈弯曲状的香樟树，多年累积的落叶厚厚地铺在野草坪上，
                        到处是不知名的各色野花。又步行了近20分钟，才走出了这片纵深的香樟林。
                        这片占公园总面积五分之一左右的密林生态保护区已经过了近半个世纪积累和保护。
                    </p>
                    <p>
                        出了密林，搭乘公园的电瓶车，在公园的东侧，随处可见成林的水杉、雪松，
                        林中的小路依然保持原有的石板路，上面早已覆盖了层层叠叠枯萎的水杉叶。
                        而果园区内还有山楂、枣树、柿树、杨梅、枇杷以及2000余株的橘树，到了秋天，市民就可以来此采摘果实了。
                    </p>
                    <p>
                        滨江森林公园一期工程由三岔港苗圃改建而成，公园所在的浦东新区高桥镇高沙滩，
                        是采取“吹泥成陆”的办法围垦形成土地。经过50多年的精心培育，苗圃内逐渐形成了面积大、
                        分布广和类型多样的近自然植被类型。位于公园“心脏区”的香樟林及公园东区的大片自然林带内的树木，
                        都已密不透风。公园在改建时，特地在密林浓荫中开出“林窗”，使阳光能照射进来。
                    </p>
                </div>
            </div>
            <div class="shengtaiyuan">
                <div class="words">
                    <h4>湿地生态园</h4>
                    <p>
                        森林公园内还拥有一块“宝藏”，这片“湿地生态观赏区”位于公园西侧，约占200多亩。
                        走在错落的水边石道上，在一片片水杉、枫香、广玉兰丛中，乌桕、枫杨等本地乡土乔木交错，
                        顺着起伏不定的地势，几条溪流从林中穿过，汇集到低处，
                        形成一个60余亩的浅湖。湖边鸢尾、水葱等植物生机盎然，时不时有小鸟掠过水面。
                    </p>
                    <p>
                        据介绍，这里的水生植物共有50多种，到了夏秋季节，水中的荷花、莲花绽放，岸边则是芦苇丛生。
                        自然湖岛、沼泽湿地、溪流、林地等原生自然景观，形成了“林溪间杂”的景观氛围和游憩空间。
                        不仅如此，由于保留了原先的自然水道，这里还有许多市区见不到的鱼类和两栖动物。
                    </p>
                </div>
                <div class="img">
                    <img src="image/滨江森林公园/湿地生态园.jpg" alt="湿地生态园">
                </div>
            </div>
            <div class="dujuanhuazhan">
                <div class="words">
                    <h4>杜鹃花展</h4>
                    <p>
                        杜鹃花，素有“花中西施”之称。 杜鹃花展以滨江森林公园的杜鹃花专园为主，
                        展出30多种4000多株杜鹃，游人还能欣赏到有20年树龄以上的珍稀杜鹃。杜鹃花专园内有杜鹃山，占地100余亩，
                        山上的杜鹃有毛鹃、高山杜鹃、夏杜鹃、春杜鹃等四大系列，20多个品种，姹紫嫣红，春意盎然。
                    </p>
                    <p>
                        花展在园内布置了三个景点：园门口有“迎客鹃”，由引进的近百盆独本杜鹃组成，
                        一条鲜花簇拥的景观主干道，把人们引到杜鹃山；一组风车景，由百花烘托杜鹃，
                        形成了特有的气氛；世博船，也由各类花装点成，海宝在世博船上迎来送往。
                        杜鹃花展期间，醉花草、金雀、报春花、虞美人、矮牵牛、四季海棠、
                        孔雀草等时令草花将布置成色块，成为满山满园的杜鹃之“绿叶”，观赏性极佳。
                    </p>
                </div>
                <div class="img">
                    <img src="image/滨江森林公园/杜鹃花展.jpg" alt="杜鹃花展">
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="滨江森林公园_3.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="滨江森林公园_3.php?view='comment'">看评论</a>
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
    <title>滨江森林公园</title>
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
            background-image: url(image/滨江森林公园/滨江森林公园.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #bg {
            height: 820px;
        }
        .bt>li {
            float: left;
            position: relative;
            margin-left: 100px;
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
            margin-top: 20px;
        }
        .jianjie .img {
            float: left;
            margin-left: 40px;
            margin-top: 30px;
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
        /* 主要景点 */
        .zhuyaojingdian {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .binjianghuayuan,.yuanshimilin
        ,.shengtaiyuan,.dujuanhuazhan {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 滨江花园 */
        .binjianghuayuan .img {
            float: left;
            margin-left: 50px;
        }
        .binjianghuayuan .words {
            float: left;
            width: 600px;
            margin-left: 40px;
            margin-top: 10px;
        }
        /* 原始密林 */
        .yuanshimilin .img {
            float: left;
            margin-top: 50px;
        }
        .yuanshimilin .words {
            float: left;
            width: 600px;
            margin-left: 40px;
            margin-top: 10px;
        }
        /* 湿地生态园 */
        .shengtaiyuan .words {
            float: left;
            width: 550px;
            margin-top: 10px;
        }
        .shengtaiyuan .img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 杜鹃花展 */
        .dujuanhuazhan .words {
            float: left;
            width: 550px;
            margin-top: 10px;
        }
        .dujuanhuazhan .img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">滨江森林公园</a>
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
        <li>滨</li>
        <li class="clear">江</li>
        <li class="clear">森</li>
        <li class="clear">林</li>
        <li class="clear">公</li>
        <li class="clear">园</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


