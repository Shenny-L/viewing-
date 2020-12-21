<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=12;
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
    header("location:南京路_12.php?view='comment'");
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
    $moduleID=12;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=南京路_12.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=南京路_12.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="南京路_12.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="南京路_12.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="南京路_12.php">
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
                <p>上海的南京路是上海开埠后最早建立的一条商业街。
                    它东起外滩、西迄延安西路，横跨静安、黄浦两区，全长5.5公里，
                    以西藏中路为界分为东西两段。1945年，国民政府从列强手上回收所有租界后将南京路改名南京东路，
                    静安寺路改名南京西路。故广义的南京路包括上海十大商业中心中的两个：
                    南京东路与南京西路——南京东路（包括南京路步行街）主要是平价商业区和旅游区；
                    而南京西路（包括静安寺地区）则是中国商铺租金最高也是全上海最奢华的时尚商业街区，
                    以奢侈品和高端个性消费为主。狭义的南京路即1945年以前的南京路则专指今天的南京东路。
                    南京西路则是和今天淮海中路并肩齐名的上海顶级商业街区。
                </p>
                <p>
                    老上海南京路的四大百货公司创亚洲百货业无数先河：最早在百货公司使用自动扶梯，
                    最早在百货公司使用空调系统，最早开具收据，最早服务人员着统一制服，
                    最早将百货公司与其他餐饮影院、赌场、杂耍场等业态融为一体等待。
                    传统与现代的交织为这条百年老街增添了别样的魅力。
                    这里是万商云集的宝地，是上海对外开放窗口 也是国内外购物者的天堂。
                    两侧商厦鳞次栉比，繁华异常。
                </p>
                <p>
                    中华人民共和国成立后南京路发生了巨大的变化，老介福商厦、华联商厦、广电大厦、轻工大厦、上海商城、锦沧文华大酒店等，
                    现代化的大型、高层建筑使南京路更加绮丽繁华。《霓虹灯下的哨兵》——“南京路上好八连”更是一道亮丽的风景线。
                </p>
            </div>
            <div class="img">
                <img src="image/南京路/nanjinglu1.jpg" alt="上海南京路">
            </div>
        </div>
        <div class="zhumingjianzhu">
            <h3>著名建筑</h3>
            <div class="bailemen">
                <div class="img">
                    <img src="image/南京路/bailemen.jpg" alt="百乐门">
                </div>
                <div class="words">
                    <h4>百乐门</h4>
                    <p>
                        1932年，中国商人顾联承投资七十万两白银，购买静安寺的土地营建Paramount Hall，
                        (今址为愚园路218号)，并以谐音取名“百乐门”，1933年开张。大名鼎鼎的远东第一乐府，
                        百乐门舞厅是上海著名的综合性娱乐场所。全称“百乐门大饭店舞厅”。
                        当时有资格在此消费的几乎都是顶尖名流，如蒋介石夫妇、孔家父子、戴笠、
                        远东教父杜月笙、喜剧大师卓别林、国人首富荣宗敬和远东首富沙逊及定居上海的香港首富何东
                        （今天澳门赌王何鸿燊的大伯，在当时号称握有半个香港的资产），
                        阮玲玉、胡蝶、张爱玲、黄金荣等等，百乐门至今仍享誉于整个中华世界。
                    </p>
                </div>
            </div>
            <div class="daguangmingjuyuan">
                <div class="words">
                    <h4>大光明剧院</h4>
                    <p>无可置疑的远东第一影剧院，创造亚洲和中国电影界无数第一 ：
                        亚洲第一个使用空调系统的电影院、亚洲第一个立体声电影院，播放了亚洲首部有声电影
                        （亚洲首部无声电影在19世纪后期上海的一个咖啡厅播放），播放了亚洲首部配音电影，
                        播放了中国首部宽银幕电影，中国第一个国际A类电影节（上海电影节）的第一届颁奖礼亦在此举行。
                        大光明还是当时好莱坞在全球指定的8家顶级首映电影院之一，为当时亚洲唯一。
                        这也导致了每有大片放映，总有大量日本富人专程从东京乘船甚至坐飞机来“魔都”——上海一睹为快。
                        此外当时亚洲最顶尖的交响乐队——工部局乐队长驻于此演出，几乎所有世界名曲的亚洲首演都是在此举行。
                    </p>
                </div>
                <div class="img">
                    <img src="image/南京路/daguangmingjuyuan.jpg" alt="大光明剧院">
                </div>
            </div>
            <div class="jingansi">
                <div class="words">
                    <h4>静安寺</h4>
                    <p>静安寺位于南京西路1686号，是上海市的著名古刹之一，原名重元寺、重云寺。
                        相传静安寺始建于三国孙吴赤乌年间，初名沪渎重玄寺。宋大中祥符元年去静安寺拜佛求经。
                    </p>
                    <p>
                        1008年，更名静安寺。南宋嘉定九年（1216年），寺从吴淞江畔迁入境内芦浦沸井浜边（今南京西路1686号），
                        至今已近780年，早于上海建城。清末，寺成现今规模。民国34年（1945年），书法家邓散木题额“静安古寺”，沿用迄今。
                        至20世纪30年代，静安寺商市已初具规模，今发展为上海重要商业中心之一。
                    </p>
                    <p>
                        1997年1月起，静安寺在恢复原貌的基础上，开始改建与扩建工程。除修复赤乌山门、兜率殿、大雄宝殿、圆通殿、
                        真言宗坛场、文物楼、功德堂、素斋部、僧寮等主要建筑外，还将兴建法堂、藏经楼、佛教图书馆，复修“静安八景”。
                    </p>
                </div>
                <div class="img">
                    <img src="image/南京路/jingansi.jpg" alt="静安寺">
                </div>
            </div>
            <div class="henglongguangchang">
                <div class="img">
                    <img src="image/南京路/henglongguangchang.jpg" alt="上海恒隆广场">
                </div>
                <div class="words">
                    <h4>恒隆广场</h4>
                    <p>分为恒隆广场一期和恒隆广场二期，陕西北路1266号与西康路之间。
                        地上66层，288米，地下3层，裙房5层，其中相当部分是办公楼，商场面积为5.5万平方米。
                        购物中心于2001年6月20日开始营业、7月14日正式开幕。有5层楼面，面积为5.5万平方米。
                    </p>
                </div>
            </div>
            <div class="kaisiling">
                <div class="img">
                    <img src="image/南京路/kaisiling.jpg" alt="凯司令">
                </div>
                <div class="words">
                    <h4>凯司令</h4>
                    <p>
                        凯司令西点房始建于民国17年（1928年）。凯司令最初是由三位上海西点厨师花费八根金条合伙开办的，
                        三位合伙人中包括当时上海最有名的西点师凌阿毛、天津赵士林西饼店的领班。由于一位下野的军阀协助三人选定了店面，
                        为了感谢这位军阀而取名「凯司令」，具有“感谢司令、凯旋常胜”之意。凯司令最初只是一间酒吧，
                        进过有序的经营逐渐发展成为一家集西点、西餐、咖啡店为一体的综合型西点公司。在创办之初，凯司令的立顿柠檬下午茶已十分有名，
                        随后由凌阿毛创新推出的栗子蛋糕，更使它享誉上海滩，栗子蛋糕也成为了凯司令的招牌西点，直至今日依然很受人们的欢迎。
                    </p>
                </div>
            </div>
            <div class="hepingfandian">
                <div class="words">
                    <h4>和平饭店</h4>
                    <p>上海和平饭店于1919年开业，1999年10月重新装修，2000年5月19日正式挂牌5星级。
                        共有两栋楼，一栋楼高11层为主楼，另一栋楼高6层为南楼，共有客房273套，其中标准间面积约30平方米。
                        地处外滩，南京路，环境很好，又与对面的东方明珠遥相呼应，屋顶花园可以360°遥望外滩夜色。
                    </p>
                    <p>
                        由于历史的原因，外滩的地皮楼房多为洋商所有，但中国官民的力量也努力打入。
                        华洋间的竞争很激烈，第一次世界大战后，中国作为战胜国，于 1934 年收回了德国总汇旧址（沙逊大厦旁边），
                        拆除后由中国银行取得建造权。国民党中国银行想炫耀一番，打算建造当时远东最高的三十四层银行大厦，
                        并由当时上海第一流的营造商陶桂记承包。当设计和一切准备工作就绪，正待破土动工兴建时，
                        沙逊出来蛮横的加以阻挠：这是英租界，在我的旁边造房子，高度不准超过大厦尖顶。于是国民党中国银行和跷脚沙逊打起官司来。
                        当时，按照丧权辱国的《天津条约》规定，凡涉及英国籍民的诉讼，中国官府一概无权裁决。
                        据说这桩“官司”一直打到伦敦，结果国民党中国银行败诉，当时以中国人的设计建造能力不足，会影响周围建筑的地基为由，
                        被隔壁沙逊大厦的犹太主人沙逊一脚踢去18层，硬是比77米的沙逊大厦低30厘米。
                    </p>
                    <p>
                        和平饭店建于1929年，原名华懋饭店，属芝加哥学派哥特式建筑，楼高77米，共十二层。
                        华懋饭店是由当时富甲一方的英籍犹太人沙逊建造的，外墙采用花岗岩石块砌成，由旋转厅门而入，
                        大堂地面用乳白色意大利大理石铺成，顶端古铜镂花吊灯，豪华典雅，有“远东第一楼”的美誉。
                    </p>
                </div>
                <div class="img">
                    <img src="image/外滩/Bund3.jpg" alt="和平饭店">
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="南京路_12.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="南京路_12.php?view='comment'">看评论</a>
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
    <title>南京路</title>
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
            background-image: url(image/南京路/nanjinglu.jpg);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        #bg {
            height: 820px;
        }
        .bt>li {
            float: left;
            position: relative;
            margin-left: 200px;
            margin-top: 80px;
            font-size: 70px;
            font-family: "微软雅黑";
        }
        .bt>.clear {
            margin-left: 20px;
        }
        .bt-En>li {
            float: left;
            position: relative;
            margin-left: -100px;
            margin-top: 180px;
            font-size: 40px;
            font-family: "微软雅黑";
        }
        .bt-En>.clear {
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
            padding-top: 50px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .jianjie .words {
            float: left;
            width: 510px;
        }
        .jianjie img {
            float: left;
            margin-left: 40px;
            margin-top: 100px;
        }
        /* 著名建筑 */
        .zhumingjianzhu {
            overflow: hidden;
            padding-top: 30px;
            padding-bottom: 30px;
            border-bottom: dashed;
        }
        .bailemen,.daguangmingjuyuan,.jingansi
        ,.henglongguangchang,.kaisiling,.hepingfandian {
            overflow: hidden;
            padding-top: 30px;
        }
        /* 百乐门 */
        .bailemen .img {
            float: left;
            margin-top: 10px;
        }
        .bailemen .words {
            float: left;
            width: 600px;
            margin-left: 40px;
        }
        /* 大光明剧院 */
        .daguangmingjuyuan .words {
            float: left;
            width: 550px;
        }
        .daguangmingjuyuan img {
            float: left;
            margin-left: 40px;
            margin-top: 20px;
        }
        /* 静安寺 */
        .jingansi .words {
            float: left;
            width: 550px;
            margin-top: 30px;
        }
        .jingansi img {
            float: left;
            margin-left: 70px;
            margin-top: 20px;
        }
        /* 恒隆广场 */
        .henglongguangchang .img {
            float: left;
            margin-left: 30px;

        }
        .henglongguangchang .words {
            float: left;
            width: 600px;
            margin-left: 50px;
            margin-top: 100px;
        }
        /* 凯司令 */
        .kaisiling .img {
            float: left;
            margin-top: 10px;

        }
        .kaisiling .words {
            float: left;
            width: 600px;
            margin-left: 40px;
            margin-top: 80px;
        }
        /* 和平饭店 */
        .hepingfandian .words {
            float: left;
            width: 550px;
        }
        .hepingfandian img {
            float: left;
            margin-left: 70px;
            margin-top: 100px;
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">南京路</a>
            <ul class="navbar-nav justify-content-end" style="font-size: 15px">
                <ul class="nav nav-tabs">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="border-top: 1px; border-color:RGB(248,249,250);">
                            其他景点</a>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="外滩_1.php">外滩</a> <a class="dropdown-item" href="南京路_12.php">南京路</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../选区/选区1.php">区域</a>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </nav>
    <ul class="bt">
        <li>南</li>
        <li class="clear">京</li>
        <li class="clear">路</li>
    </ul>
    <ul class="bt-En">
        <li>Nanjing</li>
        <li class="clear">Road</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


