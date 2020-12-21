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
<title href="#">崇明岛</title>
<!-- Bootstrap -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../css/bootstrap-4.0.0.css" rel="stylesheet">
</head>
<body background="../image/background.jpg" style="background-size: 100%;background-attachment:fixed;background-repeat:no-repeat;background-position:center;">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <div class="container" style="font-family: '微软雅黑'"> <a class="navbar-brand justify-content-start" href="崇明岛.html" style="font-size: 50px">崇明岛</a>
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
<div id="top" style="width: 100%;height: 400px;overflow:hidden"> <img src="../image/崇明岛.jpg" alt="head" style="width: 100%;-webkit-filter: blur(4px);filter: blur(4px);">
  <div style="position: absolute; top: 200px; left: 150px; font-family: '华文细黑'; font-size: 5em; color:azure">崇明岛</div>
</div>
<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/崇明学宫/崇明学宫.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">崇明学宫</h1>
          <p class="card-text">崇明学宫是现今上海仅存的三座学宫之一。现址又称崇明博物馆，为市级文物保护单位。
                    坐落于上海市崇明南门码头。学宫基地原东长144.2米，西长148.3米，南北各阔80米，
                    有殿、宫、堂、厅、祠、阁等建筑群，建筑艺术精湛。清代曾10次重修。民国以降，
                    崇明学宫曾被小学和其他单位使用。经过近年来的三次整修，逐渐恢复了当年的格局，
                    占地面积扩大至23.21亩，成为上海地区面积最大的孔庙。学宫主要建筑有东西牌坊、
                    棂星门、泮池、登云桥、东西官厅、戟门、乡贤祠、名宦祠、忠义孝悌祠、大成殿、东西庑殿、尊经阁、崇圣祠等明清建筑群，
                    气势恢宏，规模壮观，徜徉其间，令人流连忘返。目前，古建筑明伦堂、仪门等修复完毕。</p>
          <a href="../../景点介绍/崇明学宫_4.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
      </div>
    </div>
  </div>
</div>
<div class="container" style="margin: auto;margin-top: 30px;">
  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card" style="border: thin;background-color:black" onMouseMove="fucusOn(this)" onMouseOut="deFucusOn(this)" > <img src="../../景点介绍/image/东滩鸟类自然保护区/东滩鸟类自然保护区.jpg" alt="Card image cap" width="100%" class="card-img-top"  >
        <div class="card-body" style="position: absolute; color: aliceblue; display: none">
          <h1 class="card-title">东滩鸟类自然保护区</h1>
          <p class="card-text">上海崇明东滩鸟类国家级自然保护区位于低位冲积岛屿——崇明岛东端的崇明东滩的核心部分，面积约32,600公顷，
                    约占上海市湿地总面积的7.8，主要保护对象为水鸟和湿地生态系统。在长江泥沙的淤积作用下，
                    形成了大片淡水到微咸水的沼泽地、潮沟和潮间带滩涂。区内有众多的农田、鱼塘、蟹塘和芦苇塘，
                    沼生植被繁茂，底栖动物丰富，是亚太地区春秋季节候鸟迁徙极好的停歇地和驿站，也是候鸟的重要越冬地，
                    是世界为数不多的野生鸟类集居、栖息地之一。据有关资料表明，东滩有116种鸟，占中国鸟类总数的十分之一，
                    尤其是国家二级保护动物小天鹅在东滩越冬数量曾达3000～3500只。还有来自澳大利亚、新西兰、日本等国过境栖息候鸟，总数达二三百万。</p>
          <a href="../../景点介绍/东滩鸟类自然保护区_6.php" class="btn btn-primary" style="position:relative;">浏览详情</a> </div>
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