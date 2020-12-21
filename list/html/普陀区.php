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
<title href="#">普陀区</title>
<!-- Bootstrap -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../css/bootstrap-4.0.0.css" rel="stylesheet">
</head>
<body background="../image/background.jpg" style="background-size: 100%;background-attachment:fixed;background-repeat:no-repeat;background-position:center;">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container" style="font-family: '微软雅黑'"> <a class="navbar-brand justify-content-start" href="普陀区.php" style="font-size: 50px">普陀区</a>
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
<div id="top" style="width: 100%;height: 400px;overflow:hidden"> <img src="../image/普陀区.jpg" alt="head" style="width: 100%;-webkit-filter: blur(4px);filter: blur(4px);">
  <div style="position: absolute; top: 200px; left: 150px; font-family: '华文细黑'; font-size: 5em; color:azure">普陀区</div>
</div>
<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/长风公园/背景.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">长风公园</h1>
          <p class="card-text">长风公园，始建于1957年4月，曾先后被称为沪西公园、碧萝湖公园，1959年国庆节建成正式开放，并更名为长风公园。长风公园占地面积36.4万平方米，位于上海市中心城区西部，普陀区中南部。 长风公园是上海市大型的综合性山水公园，借鉴北京颐和园风格和苏州园林的造景手法，总体布局模拟自然，园景以湖为主，山水结合，主要景点是人工湖“银锄湖”，以及人造山“铁臂山”。 1998年，长风公园被中国建设部、中国公园协会选为中国百家名园之一。2002年创建成上海市“四星级公园”。2010年被评为国家AAAA级旅游风景区。</p>
          <a href="../../景点介绍/长风公园_35.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
      </div>
    </div>
  </div>
</div>
<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/长风海洋世界/背景.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">上海长风海洋世界</h1>
          <p class="card-text">上海长风海洋世界隶属于欧洲第一、全球第二的Merlin Entertainment集团旗下之全球最大水族馆连锁品牌——Sea Life，与杜莎夫人蜡像馆、乐高乐园同为国际品牌。 长风海洋世界坐落于风景优美的长风公园内，主体建筑位于银锄湖底13米处。是集大型海洋动物表演与水族馆鱼类展览为一体的综合海洋主题公园。 为全国青少年科普教育基地、上海市专题性科普场馆、上海市二期课改授课场馆，并被命名为国家AAAA级景区。</p>
          <a href="../../景点介绍/上海长风海洋世界_31.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
      </div>
    </div>
  </div>
</div>
	<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/玉佛寺/背景.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">玉佛寺</h1>
          <p class="card-text">玉佛寺位于上海市普陀区安远路170号，因寺内主要供奉玉佛而得名，因其属于禅宗临济法系，修习禅法，故又名玉佛禅寺。玉佛寺作为上海旅游的十大景点之一，它虽地处繁华的市区，却又闹中取静，被喻为闹市中的一片净土。玉佛寺创建人和首任住持是慧根法师。</p>
          <a href="../../景点介绍/玉佛寺_34.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
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