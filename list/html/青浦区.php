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
<title href="#">青浦区</title>
<!-- Bootstrap -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../css/bootstrap-4.0.0.css" rel="stylesheet">
</head>
<body background="../image/background.jpg" style="background-size: 100%;background-attachment:fixed;background-repeat:no-repeat;background-position:center;">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container" style="font-family: '微软雅黑'"> <a class="navbar-brand justify-content-start" href="青浦区.php" style="font-size: 50px">青浦区</a>
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
<div id="top" style="width: 100%;height: 400px;overflow:hidden"> <img src="../image/青浦区.jpg" alt="head" style="width: 100%;-webkit-filter: blur(4px);filter: blur(4px);">
  <div style="position: absolute; top: 200px; left: 150px; font-family: '华文细黑'; font-size: 5em; color:azure">青浦区</div>
</div>
<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/曲水园/背景.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">曲水园</h1>
          <p class="card-text">上海曲水园位于上海青浦区公园路612号。初建于清乾隆十年（公元1745年）。据说为了建此园，曾向城中每个居民征募一文钱，故又有“一文园”之称。乾隆四十九年（公元1784年），拓地池筑堤累石，增建楼台，前后历四十余年，建成二十四景，三十余庙的园林。园内一石一水，一亭一阁，尽皆成趣。嘉庆三年（公元1798年）改园名为曲水园。因园在大盈浦旁，取王羲之《兰亭集序》中的“曲水流觞”之意。咸丰年间毁于炮火。光绪年间费时20年相继修复庙、园，增建放生池、花神堂。1927年增修假山，山上筑九峰一览亭，可登高远眺松郡九峰。</p>
          <a href="../../景点介绍/曲水园_27.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
      </div>
    </div>
  </div>
</div>
<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/上海大观园/背景.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">上海大观园</h1>
          <p class="card-text">上海大观园位于上海青浦区青商公路701号，坐落于淀山湖西侧，距离上海市区65公里，占地135亩，建筑面积约8000平方米。原称淀山湖风景游览区，1991年改称上海大观园，占地也扩大到1500亩，是上海市精神文明建设的重点单位，经过多次的整修扩建，是一个集观光、旅游、休闲的综合性游乐园。</p>
          <a href="../../景点介绍/上海大观园_28.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
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