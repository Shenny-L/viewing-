<?php 
include_once '../../inc/config.inc.php';
include_once '../../inc/mysql.inc.php';
include_once '../../inc/tool.inc.php';
$link=connect();
if(!(is_login($link))){ 
  header("Location:tohome.php");
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title href="#">静安区</title>
<!-- Bootstrap -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../css/bootstrap-4.0.0.css" rel="stylesheet">
</head>
<body background="../image/background.jpg" style="background-size: 100%;background-attachment:fixed;background-repeat:no-repeat;background-position:center;">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container" style="font-family: '微软雅黑'"> <a class="navbar-brand justify-content-start" href="静安区.php" style="font-size: 50px">静安区</a>
    <ul class="navbar-nav justify-content-end" style="font-size: 25px" >
      <ul class="nav nav-tabs">
        <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="border-color:azure;border-top: 1px">其他区县</a>
        <div class="dropdown-menu"> <a class="dropdown-item" href="黄浦区.php">黄浦区</a> <a class="dropdown-item" href="徐汇区.php">徐汇区</a> <a class="dropdown-item" href="长宁区.php">长宁区</a> <a class="dropdown-item" href="静安区.php">静安区</a> <a class="dropdown-item" href="普陀区.php">普陀区</a> <a class="dropdown-item" href="虹口区.php">虹口区</a> <a class="dropdown-item" href="杨浦区.php">杨浦区</a> <a class="dropdown-item" href="闵行区.php">闵行区</a> <a class="dropdown-item" href="宝山区.php">宝山区</a> <a class="dropdown-item" href="嘉定区.php">嘉定区</a> <a class="dropdown-item" href="金山区.php">金山区</a> <a class="dropdown-item" href="松江区.php">松江区</a> <a class="dropdown-item" href="青浦区.php">青浦区</a> <a class="dropdown-item" href="奉贤区.php">奉贤区</a> <a class="dropdown-item" href="崇明岛.php">崇明岛</a> <a class="dropdown-item" href="浦东新区.php">浦东新区</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="../../选区/选区1.php">区域汇总</a> </div>
        </li>
      </ul>
    </ul>
  </div>
</nav>
<div id="top" style="width: 100%;height: 400px;overflow:hidden"> <img src="../image/静安区.jpg" alt="head" style="width: 100%;-webkit-filter: blur(4px);filter: blur(4px);">
  <div style="position: absolute; top: 200px; left: 150px; font-family: '华文细黑'; font-size: 5em; color:azure">静安区</div>
</div>
<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/静安寺/静安寺.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">静安寺</h1>
          <p class="card-text">静安寺，又称静安古寺，位于上海市静安区，其历史相传最早可追溯至三国孙吴赤乌十年（247年），初名沪渎重玄寺。
                    宋大中祥符元年（1008年），更名静安寺。南宋嘉定九年（1216年），寺从吴淞江畔迁入境内芦浦沸井浜边（今南京西路），
                    早于上海建城。静安寺总建筑面积达2.2万平方米，整个庙宇形成前寺后塔的格局，由大雄宝殿、天王殿、三圣殿三座主要建筑构成，
                    是上海最古老的佛寺。寺内藏有八大山人名画、文征明真迹《琵琶行》行草长卷。
                    静安区亦由静安寺而闻名于世。静安寺的建筑风格是仿明代以前的建筑风格，典型的代表就是斗拱的形制。</p>
          <a href="../../景点介绍/静安寺_10.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
      </div>
    </div>
  </div>
</div>
<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/四行仓库/四行仓库.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">四行仓库</h1>
          <p class="card-text"> 四行仓库是一座位于上海市原闸北区南部、苏州河北岸、西藏路桥西北角的仓库建筑。
                    1937年10月26日至11月1日，在这里发生的四行仓库保卫战重新振奋了因淞沪会战受挫而下降的中国军民的士气。
                    它的结束标志着中国抗日战争中的一场重大战役淞沪会战的结束。参加这场保卫战的中国士兵被称为“八百壮士”。</p>
          <a href="../../景点介绍/四行仓库_16.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
      </div>
    </div>
  </div>
</div>
<div style="position: fixed;top:80%;right:0%;">
  <input type="button" value="BACK" onclick="topFunction()" class="btn btn-secondary" style="font-size: 20px"/>
</div>
<footer class="text-center" style="margin-top: 30px">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <p>Copyright © MyWebsite. All rights reserved.</p>
      </div>
    </div>
  </div>
</footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="../js/jquery-3.2.1.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="../js/popper.min.js"></script> 
<script src="../js/bootstrap-4.0.0.js"></script> 
<script src="../js/viewlist.js"></script>
</body>
</html>