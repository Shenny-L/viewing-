<?php 
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
if(isset($_POST['submit3'])){ 
  deletecookie();
  skip('../home/start.php','ok','退出成功！'); 
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Viewing 看见（选区）</title>
  <!-- Bootstrap -->
  <link href="css/bootstrap-4.0.0.css" rel="stylesheet">
</head>
<style type="text/css">
  body {
    margin: 0;
    background-image: url('1.jpeg');
    background-repeat: no-repeat;
    background-position: 0% 0%;
    background-size: cover;
    background-color: #22C3AA;
  }

  .a1 {
    color: rgb(0, 153, 255)
  }

  p.topmargin {
    color: black
  }
</style>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">主页</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link " href="#">景区<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">区县选择（快捷版）</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="../list/html/黄浦区.php">黄浦区</a>
              <a class="dropdown-item" href="../list/html/徐汇区.php">徐汇区</a>
              <a class="dropdown-item" href="../list/html/长宁区.php">长宁区</a>
              <a class="dropdown-item" href="../list/html/静安区.php">静安区</a>
              <a class="dropdown-item" href="../list/html/普陀区.php">普陀区</a>
              <a class="dropdown-item" href="../list/html/虹口区.php">虹口区</a>
              <a class="dropdown-item" href="../list/html/杨浦区.php">杨浦区</a>
              <a class="dropdown-item" href="../list/html/闵行区.php">闵行区</a>
              <a class="dropdown-item" href="../list/html/宝山区.php">宝山区</a>
              <a class="dropdown-item" href="../list/html/嘉定区.php">嘉定区</a>
              <a class="dropdown-item" href="../list/html/浦东新区.php">浦东新区</a>
              <a class="dropdown-item" href="../list/html/金山区.php">金山区</a>
              <a class="dropdown-item" href="../list/html/松江区.php">松江区</a>
              <a class="dropdown-item" href="../list/html/青浦区.php">青浦区</a>
              <a class="dropdown-item" href="../list/html/奉贤区.php">奉贤区</a>
              <a class="dropdown-item" href="../list/html/崇明岛.php">崇明区</a>
            </div>
          </li>
          <li class="nav-item active">
            <form method='post'>
              <input type="submit" value="退出账号" name="submit3">
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div id="top" style="width: 100%;height: 80px;overflow:hidden"> <img src="photo\shanghai.jpg" alt="head"
      style="width: 100%;-webkit-filter: blur(4px);filter: blur(4px);">
    <div style="position: absolute; top: 58px; left: 50px; font-family: '华文细黑'; font-size: 3em; color:azure">区域</div>
  </div>
  <div class="container" style="margin: auto;margin-top: 30px;">
    <div class="row">
      <div class="col-3">
        <div class="card" style="border: thin"> <img src="photo\宝山.jpeg" alt="Card image cap" width="100%"
            class="card-img-top">
          <div class="card-body">
            <h1 class="card-title">宝山</h1>
            <a href="../list/html/宝山区.php" class="btn btn-primary" style="position:relative;">查看详细</a>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="card" style="border: thin"> <img src="photo\嘉定.jpeg" alt="Card image cap" width="100%"
            class="card-img-top">
          <div class="card-body">
            <h1 class="card-title">嘉定</h1>
            <a href="../list/html/嘉定区.php" class="btn btn-primary" style="position:relative;">查看详细</a>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="card" style="border: thin"> <img src="photo\浦东.jpeg" alt="Card image cap" width="100%"
            class="card-img-top">
          <div class="card-body">
            <h1 class="card-title">浦东</h1>
            <a href="../list/html/浦东新区.php" class="btn btn-primary" style="position:relative;">查看详细</a>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="card" style="border: thin"> <img src="photo\金山.jpeg" alt="Card image cap" width="100%"
            class="card-img-top">
          <div class="card-body">
            <h1 class="card-title">金山</h1>
            <a href="../list/html/金山区.php" class="btn btn-primary" style="position:relative;">查看详细</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container" style="margin: auto;margin-top: 30px;">
    <div class="row">
      <div class="col-3">
        <div class="card" style="border: thin"> <img src="photo\松江.jpeg" alt="Card image cap" width="100%"
            class="card-img-top">
          <div class="card-body">
            <h1 class="card-title">松江</h1>
            <a href="../list/html/松江区.php" class="btn btn-primary" style="position:relative;">查看详细</a>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="card" style="border: thin"> <img src="photo\青浦.jpeg" alt="Card image cap" width="100%"
            class="card-img-top">
          <div class="card-body">
            <h1 class="card-title">青浦</h1>
            <a href="../list/html/青浦区.php" class="btn btn-primary" style="position:relative;">查看详细</a>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="card" style="border: thin"> <img src="photo\奉贤.jpeg" alt="Card image cap" width="100%"
            class="card-img-top">
          <div class="card-body">
            <h1 class="card-title">奉贤</h1>
            <a href="../list/html/奉贤区.php" class="btn btn-primary" style="position:relative;">查看详细</a>
          </div>
        </div>
      </div>
      <div class="col-3">
        <div class="card" style="border: thin"> <img src="photo\崇明.jpeg" alt="Card image cap" width="100%"
            class="card-img-top">
          <div class="card-body">
            <h1 class="card-title">崇明</h1>
            <a href="../list/html/崇明岛.php" class="btn btn-primary" style="position:relative;">查看详细</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <footer class="text-center">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <p></br></p>
          <p style="font-size:24px"><a href="选区1.php" class="a1">上一页 <a href="选区1.php" class="a1">1 </a>2 下一页</a></p>
          <p class="topmargin"></br> 本网站仅供学习使用，请不要在别处引用</p>
        </div>
      </div>
    </div>
  </footer>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="js/jquery-3.2.1.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap-4.0.0.js"></script>
</body>

</html>