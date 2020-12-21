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
<title href="#">奉贤区</title>
<!-- Bootstrap -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../css/bootstrap-4.0.0.css" rel="stylesheet">
</head>
<body background="../image/background.jpg" style="background-size: 100%;background-attachment:fixed;background-repeat:no-repeat;background-position:center;">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container" style="font-family: '微软雅黑'"> <a class="navbar-brand justify-content-start" href="奉贤区.php" style="font-size: 50px">奉贤区</a>
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
<div id="top" style="width: 100%;height: 400px;overflow:hidden"> <img src="../image/奉贤区.jpg" alt="head" style="width: 100%;-webkit-filter: blur(4px);filter: blur(4px);">
  <div style="position: absolute; top: 200px; left: 150px; font-family: '华文细黑'; font-size: 5em; color:azure">奉贤区</div>
</div>
<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/碧海金沙水上乐园/碧海金沙水上乐园.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">碧海金沙水上乐园</h1>
          <p class="card-text">上海碧海金沙水上乐园，即上海碧海金沙水上乐园有限公司——“碧海金沙”，坐落于上海市奉贤区海湾旅游区，
                    位于上海市南端，面向杭州湾，西毗上海化学工业区，东接临港开发区。拥有水域面积65万平方米，8万平方米的沙滩面积。
                    碧海金沙是中国最大的人造沙滩海滨浴场，也是上海唯一一处碧波荡漾的蓝色海域。乐园内设有各类游艺项目：
                    大海畅泳、水上乐园、水上自行车、水上休闲船、怀旧电影、儿童乐园。充沛的阳光、清凉的海水，凉爽的海风，金色的沙滩绝对是你今夏休闲渡假的好去处。</p>
          <a href="../../景点介绍/碧海金沙水上乐园_2.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
      </div>
    </div>
  </div>
</div>
<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/古华园/古华.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">古华园</h1>
          <p class="card-text">1982年10月，县人民政府向市报请在南桥镇分期建设一个面积为12.2万平方米的公园。 上海市基本建设委员会于1983年3月批准建设公园的计划任务书。次年7月第一期工程用地经市城市 规划建筑管理局核准后，征用江海乡曙光村、南星村土地共5.01万平方米。公园总体规划设计 由同济大学建筑系园林教研室司马铨、陈久昆负责，由无锡市园林古典建筑公司、浙江省上虞县盖北建设公司等施工。</p>
          <a href="../../景点介绍/古华园_8.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
      </div>
    </div>
  </div>
</div>
	<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/海湾森林公园/海湾森林公园.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">海湾森林公园</h1>
          <p class="card-text"> 上海海湾国家森林公园位于上海市奉贤区海湾镇五四农场境内，距上海市中心60公里，
                    上海海湾国家森林公园，东接临港新城，南临杭州湾的海湾森林公园。占地面积15983.5亩。
                    其中：开园面积4500亩，水域面积85公顷。分为三大旅游板块：游乐活动区
                    （包括游乐园、森林卡丁车、森林跑马、森林烧烤等）、水上活动区（农家菜、游船码头、梅林春晓、天喔茶庄等）、
                    文化观赏区主要包括：昆仑石屋、影蛟盆景苑、越窑青瓷馆、
                    四海陶艺馆、雅兴楼书画馆、旺家根雕馆、家具馆、美术馆、汝窑馆、恐龙馆、上公府等。</p>
          <a href="../../景点介绍/海湾森林公园_9.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
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