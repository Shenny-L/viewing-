<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
//moduleID要修改，不同页面的对应唯一
$moduleID=33;
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
    header("location:佘山_33.php?view='comment'");
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
    $moduleID=33;
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
    <a style="color:#00CCFF;text-decoration: 0;"href=佘山_33.php?sortWay="time"&view="comment">按时间</a>
    &nbsp|&nbsp
    <a style="color:#00CCFF;text-decoration: 0;"href=佘山_33.php?sortWay="likes"&view="comment">按赞数</a>
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
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="佘山_33.php">
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
        <a style="color:#0099FF; font-size:15px; text-decoration: 0;" href=comment_del.php?id={$data['id'] }&URL="佘山_33.php">删除</a>
    <span>
    </div>
    </div>
    </div>
A;
    $BoxWithNoDel=<<<A
    <div class="js" >
    <div class="commentBox">
    <div class="thumb">
    <a id={$thumbId} href=comment_like.php?id={$data['id'] }&URL="佘山_33.php">
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
                    佘山，位于中国上海市松江区，分西佘山跟东佘山。西佘山海拔100.8米（2004年前标定为99米）（一说97米），是上海境内第二高峰，上海陆地第一高峰；东佘山海拔72.4米。
                </p>
                <p>
                    佘山是大上海的后花园。佘山作为上海著名市郊风景区，现位于佘山国家森林公园，佘山上有著名的天主教朝圣地——佘山圣母大教堂。还有秀道者塔，佘山地震基准台，佘山月湖和佘山天文台。
                </p>
            </div>
            <div class="img">
                <img src="image/佘山/简介.jpg" alt="简介">
            </div>
        </div>
        <div class="main">
            <h3>主要景点</h3>
            <div class="scen1">
                <div class="img">
                    <img src="image/佘山/西佘山院.jpg" alt="西佘山院">
                </div>
                <div class="words">
                    <h4>西佘山院</h4>
                    <p>
                        西佘山园景区是云间九峰中环境最好、面积最大的景区。园内灿烂的人文景观和秀丽的自然风光吸引着众多的中外游客。山上古木参天，修篁蔽日。山顶浓荫深处一幢气势非凡的赭红色建筑，就是名闻遐迩的天主教“远东第一大教堂”。与之毗邻的那个穹庐形建筑，是中国最古老的天文观测台。而位于山腰的中山教堂、三圣亭、蜿蜒曲折的苦路和距今逾千年的修道者塔，又为该园坛添了几分神秘的色彩。而西南坡的茶园是唯一的“上海龙井”产地，堪称茶中精品。
                    </p>
                </div>
            </div>
            <div class="scen2">
                <div class="words">
                    <h4>佘山圣母大殿</h4>
                    <p>
                        佘山圣母大殿位于西佘山，是与法国罗德圣母大殿齐名的上海佘山天主教堂，也称远东圣母大殿。该堂于1871由法国传教士始建。1935年落成。它集多种建筑风格于一体，采用无木无钉无钢无梁的四无结构，堪称不对称的典范。从20世纪40年代起即为世界闻名的天主教圣地，也是国内天主教最主要的朝圣地。 佘山圣母大教堂于公元1871年由法国传教士始建，1925年翻造扩建，至1935年落成。 大殿东西长56米，南北宽25米，建筑面积为1400平方米。建筑平面呈拉丁式十字形。从殿基到十字架尖顶高为38米，其中到拱顶为17米，到硫璃瓦为22米。大殿设座位1000个，可容纳约1500名的教友。
                        这座罗马过渡风格的教堂，有“四无”之称，即无钉无木无钢无梁，堪称不对称的建筑典范。五彩花玻璃大小不一，神像各异。建筑造型南长北短，东宽西狭，内圆外尖，内石外砖。大殿集多种建筑风格于一体，其拱形、甬道为罗马式，廊柱为希腊式，尖顶为哥特式，橄榄形钟楼为以色列式，东端小圆顶为西班牙式，清水壁和斗角地砖为中国民族式，硫璃瓦则为中国宫廷式。殿内冬暖夏凉，采光极好。廊柱和斗拱之间的壁槽具有良好的吸潮功能和清洁功能。 1942年9月12日，罗马教宗庇护十二世册封佘山教堂为乙级宗座圣殿（minor
                        Basilica），这是远东第一座受到教宗敕封的圣殿。
                    </p>
                </div>
                <div class="img">
                    <img src="image/佘山/佘山圣母大殿.jpg" alt="佘山圣母大殿">
                </div>
            </div>
            <div class="scen3">
                <div class="words">
                    <h4>佘山天文台</h4>
                    <p>
                        佘山天文台坐落在上海西南郊的历史名山——佘山之巅（西佘山），1872年，天主教的巴黎耶稣会传教士在上海建立了徐家汇天文台，从事气象、天文和地磁等观测、预报。1900年，又在佘山新建一座天文台，安装了从法国购置的40厘米双筒折射望远镜，它在当时是亚洲最大的天文望远镜，在上一世纪伴随中国和西方的几代天文学家度过无数不眠之夜，拍摄了大量珍贵的天体照片。直到80年代上海天文台自行设计制造了更先进的1.56米天体测量望远镜，这架“世纪望远镜”才逐渐退出从科研第一线。 除了文物展览厅，上海天文博物馆内还有“佘山之巅”纪念石碑，佘山之巅基岩点，以及一处国际经度联测的基本点。
                    </p>
                </div>
                <div class="img">
                    <img src="image/佘山/佘山天文台.jpg" alt="佘山天文台">
                </div>
            </div>
            <div class="scen4">
                <div class="img">
                    <img src="image/佘山/佘山深坑酒店.jpg" alt="世茂深坑酒店">
                </div>
                <div class="words">
                    <h4>世茂深坑酒店</h4>
                    <p>
                        酒店总建筑面积为61087平方米，酒店建筑格局为地上2层、地平面下15层（其中水面以下两层），共拥有336间客房和套房，酒店利用所在深坑的环境特点，所有客房均设有观景露台，可欣赏峭壁瀑布。酒店设有攀岩、景观餐厅和850平方米宴会厅，在地平面以下设置有酒吧、SPA、室内游泳池和步行景观栈道等设施以及水下情景套房与水下餐厅。酒店旁还配有上海世茂精灵之城主题乐园。
                    </p>
                </div>
            </div>
            <div class="scen5">
                <div class="img">
                    <img src="image/佘山/月湖雕塑公园.jpg" alt="月湖雕塑公园">
                </div>
                <div class="words">
                    <h4>月湖雕塑公园</h4>
                    <p>
                        月湖雕塑公园依托山林和月湖资源，精心创设了寓意为春、夏、秋、冬四大主题的功能性区域景观，设置了游客服务中心、儿童智能活动广场、月圆园婚纱会馆、日月一号和秋月坊等公共休闲、娱乐场所。园区以“回归自然、享受艺术”为建设理念，是一座集现代雕塑、自然山水、景观艺术于一体的综合性艺术园区。月湖雕塑公园园区一期占地1300亩，其中月湖面积465亩，环湖腹地分为春夏秋冬四岸，来自世界各国现代艺术家所创作大型雕塑四十余件，融合于山水美景中，别具特色。月湖雕塑公园入口设计精致简约，正中央耸立的巨型雕塑“飞向永恒”也是一座具备功能性的现代化艺术造型日晷，为保加利亚籍旅意雕塑家吉沃吉·菲林所创作。两排直挺的加那利树精神抖擞地列队，热情地欢迎着每位游客，邀请您进入一个充满知性、感性的艺术桃花源。
                        月湖雕塑公园依托山林和月湖资源，精心创设了寓意为春、夏、秋、冬四大主题的功能性区域景观，设置了游客服务中心、儿童智能活动广场、月圆园婚纱会馆、日月一号和秋月坊等公共休闲、娱乐场所。
                    </p>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center" style="margin-top: 30px">
        <div class="container">
            <div class="row">
                <div class="col-12" style="color:white;">
                    <p>Copyright © MyWebsite. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
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
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="佘山_33.php?view='intro'">看介绍</a> 
    </div>
    <div class="anniu">
        <a style="text-decoration:0;color:rgba(0, 0, 0)" href="佘山_33.php?view='comment'">看评论</a>
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
    <link rel="stylesheet" href="css/佘山.css">
    <link href="css/bootstrap-4.0.0.css" rel="stylesheet">
    <script src="js/jquery-3.2.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap-4.0.0.js"></script>
    <script src="js/viewlist.js"></script>
    <title>佘山</title>

    <style>
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
            <a class="navbar-brand justify-content-start" href="#" style="font-size: 30px">佘山</a>
            <ul class="navbar-nav justify-content-end" style="font-size: 15px">
                <ul class="nav nav-tabs">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="border-top: 1px; border-color:RGB(248,249,250);">
                            其他景点</a>
                        <div class="dropdown-menu"> <a class="dropdown-item" href="上海欢乐谷_29.php">上海欢乐谷</a> <a class="dropdown-item" href="佘山_33.php">佘山</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../选区/选区1.php">区域</a>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </nav>
    <ul class="bt">
        <li>佘</li>
        <li class="clear">山</li>
    </ul>
    <ul class="bt-En">
        <li>Sheshan</li>
        <li class="clear">Mountain</li>
    </ul>
    </body>
A;
    echo $header;
    
    
}
?>


