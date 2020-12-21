<?php 
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
//deletecookie();
if(is_login($link)){
	skip('../选区/选区1.php','attention','你已经登录，请不要重复登录！');
}
if(isset($_POST['submit1'])){
  
	include '../inc/check_login.inc.php';
	escape($link,$_POST);
	$query="select * from member where name='{$_POST['user']}' and password=md5('{$_POST['pwd']}')";
	$result=execute($link, $query);
	if(mysqli_num_rows($result)==1){
		setcookie('member[name]',$_POST['user'],time()+3600,'/');
		setcookie('member[pw]',sha1(md5($_POST['pwd'])),time()+3600,'/');
        //skip('../spot/spot1.php','ok','登录成功！'); //跳转到登陆后的页面：选区
        header('Location:../选区/选区1.php');
	}
	else{
        skip('start.php', 'error', '用户名或密码填写错误！');
	}
}
if(isset($_POST['submit2'])){
	include '../inc/check_register.inc.php';
	escape($link,$_POST);
    $query="insert into member(name,password,gender) values('{$_POST['user']}',md5('{$_POST['pwd']}'),'{$_POST['gender']}')";
    $result=execute($link, $query);
	if(mysqli_affected_rows($link)==1){ 
        setcookie('member[name]',$_POST['user'],time()+3600,'/');
        setcookie('member[pw]',sha1(md5($_POST['pwd'])),time()+3600,'/');
        setcookie('member[gender]',$_POST['gender'],time()+3600,'/');
        skip('../选区/选区1.php','ok','注册成功！'); 
	}else{
        skip('start.php','eror','注册失败,请重试！');//跳转到注册页面
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <title>Viewing 看见</title>
    <style>
        * {
    margin: 0;
    padding: 0;
}
body{
    font-family: "Microsoft YaHei","黑体","宋体",sans-serif;
}
li{
    list-style: none;
}
a{
    text-decoration: none;
    color: inherit;
}
button:focus{
    border: 0 none;
    outline: none;
}

.submit-disabled{
    width: 100%;
    background-color: #c05547;
    color: white;
    font-size: 20px;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    outline: none;
}
.banner{
    position: fixed;
    top: 0;
    left: 0;
    height: 60px;
    width: 100%;
    background-color: rgba(68,68,68,0.7);
    z-index: 3;
}
.banner h1{
    float: left;
    margin-left: 50px;
    margin-top: 8px;
    color: rgb(222, 225, 228);
}
.banner button{
    float: right;
    background-color: transparent;
    border: none;
    width: 45px;
    margin-right: 3%;
    margin-top: 18px;
    font-size: 20px;
    color: rgb(222, 225, 228);
}
.main{
    position: relative;
    left: 0;
    width: 100%;
    height: 1050px;
    overflow: hidden;
}
.main ul li{
    float: left;
    width: 10%;
}
.main li>img{
    width: 100%;
}
.main ul{
    z-index: 1;
    position: absolute;
    left: -100%;
    width: 1000%;
}
#arrowl{
    z-index: 2;
    position: absolute;
    left: 0;
    top: 450px;
    height: 30px;
    display: none;
}
#arrowr{
    z-index: 2;
    position: absolute;
    right: 0;
    top: 450px;
    height: 30px;
    display: none;
}
.modal{
    z-index: 4;
    position: fixed;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.247);
    display: none;
}
.modal .box{
    background-color: #fff;
    margin-top: 200px;
    height: 400px;
    display: none;
    box-shadow: 0px 0px 12px 0px rgba(0, 0, 0, 0.1);
}
.modal p{
    width: 300px;
    margin: 50px auto;
    font-weight: bold;
    color: black;
    font-size: 20px;
}
p input{
    outline: none;
    border: none;
    border-bottom: 4px solid gray;
    padding-bottom: 5px;
    font-size: 17px;
}
.modal h1{
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 1px solid black;
    color: rgb(116,119,116);
}

.box{
    margin: 0 auto;
    width: 500px;
    height: 300px;
    border-radius: 12px;
    padding-top: 20px;
}
.modal .box-register{
    margin-top: 150px;
    height: 590px;
}
.slogan{
    z-index: 3;
    position: absolute;
    top: 270px;
    left: 500px;
    font-weight: bolder;
    font-size: 70px;
    color: rgb(29,29,31);
}
.line1,.line2{
    display: none;
}
.footer{
    width: 100%;
    height: 100px;
    background-color: rgb(245,245,246);

}
    </style>
</head>
<body>
    <!-- 登录模态框 -->
    <div class="modal1 modal" >
        <div class="box">
            <h1>登录</h1>
            <form  method='post'>
                <p >用户名<br><input type="text" name="user"></p>
                <p>密码<br><input type="password" name="pwd" ></p>
                <input type="submit" value="GO!" class="submit-disabled" disabled="disabled" name="submit1">
            </form>

        </div>
    </div>
    <!-- 注册模态框 -->
    <div class="modal2 modal" >
        <div class="box box-register">
            <h1>注册</h1>
            <form action="" method='post' onsubmit="return checkform();">
                <p>性别:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="gender" value=1 checked>男&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="gender" value=0>女</p>
                <p >用户名<br><input type="text" name="user"></p>
                <p>密码<br><input type="password" name="pwd"></p>
                <p>确认密码<br><input type="password" name="pwd-confirm"></p>
                <input type="submit" value="GO!" class="submit-disabled" disabled="disabled" name="submit2">
            </form>

        </div>
    </div>
    <!-- Banner -->
    <div class="banner">
        <h1>Viewing 看见</h1>
        <button class="register">注册</button>
        <button class="login">登录</button>
    </div>
    <!-- Main Section -->
    <div class="main">
        <p class="slogan"><a href="#"><span class="line1">看见  ,</span><br><br><span class="line2">属于自己的城市记忆</span></a></p>
        <img src="../inc/arrowL.ico" alt="arrowLeft" class="arrow" id="arrowl">
        <img src="../inc/arrowR.ico" alt="arrowRight" class="arrow" id="arrowr">
        <ul>
            <li><img src="#" alt="view"></li>
            <li><img src="../img/1.jpg" alt="view"></li>
            <li><img src="../img/2.jpg" alt="view"></li>
            <li><img src="../img/3.jpg" alt="view"></li>
            <li><img src="../img/4.jpg" alt="view"></li>
            <li><img src="../img/5.jpg" alt="view"></li>
            <li><img src="../img/6.jpg" alt="view"></li>
            <li><img src="#" alt="view"></li>
        </ul>
    </div>
    <!-- Footer -->
    <!-- <div class="footer"></div> -->
    <script>
        $('.slogan').click(function(){})
    </script>
    <script>
        // 注册提交前密码验证
        function checkform(){
            if($('.box-register #pwd').val()===$('.box-register #pwd-confirm').val()){
                return true;
            }
            else{
                $('.box-register #pwd').val('');
                $('.box-register #pwd-confirm').val('');
                $('.submit-disabled').attr('disabled','true').css('backgroundColor',' #c05547');
                $('.box-register #pwd-confirm').css('borderBottom','4px solid gray');
                $('.box-register #pwd').css('borderBottom','4px solid gray');
                alert("两次密码不同，请重新输入!");
                return false;
            }
        }

    var width=$('.main ul li').css('width');
    var width_number=parseInt(width);
        $(window).ready(function () {
            $('.main').css('height',0.5625*$('.main').width()+'px')
            //$('.main ul').css('left','-'+width);
            $('.line1').fadeIn(1000,function(){
                $('.line2').fadeIn(1000);
        });
    })

        $('.login,.register').mouseover(function(){
    $(this).animate({fontSize:'21px'},20);
})
$('.login,.register').mouseout(function(){
    $(this).animate({fontSize:'20px'},20);
})
$('.login').click(function(){
    $(this).css('border','none');
    $(this).animate({marginTop:'10px'},80).animate({marginTop:'18px'},80);
    $('.modal1').fadeIn(300);
    $('.modal1 .box').slideDown(300);
});
$('.register').click(function(){
    $(this).css('border','none');
    $(this).animate({marginTop:'10px'},80).animate({marginTop:'18px'},80);
    $('.modal2').fadeIn(300);
    $('.modal2 .box').slideDown(300);
});
$('.modal .box').click(function(event){
    event.stopPropagation();//阻止事件冒泡
});
$('.modal').click(function(){
    $('.modal').fadeOut(300);
    $('.modal .box').fadeOut(300,function(){
        $('#user,#pwd,#pwd-confirm').val('').css('borderBottom','4px solid gray');
        $('.submit-disabled').attr('disabled','true').css('backgroundColor',' #c05547');
    });
});

$('#user,#pwd,#pwd-confirm').focus(function(){
    $(this).css('borderBottom','4px solid rgb(68,137,195)');
})
$('#user,#pwd,#pwd-confirm').blur(function(){
    if($(this).val()==='')
    $(this).css('borderBottom','4px solid gray');
    else
    $(this).css('borderBottom','4px solid rgb(40, 201, 40)');
})
$('.modal1').keyup(function(){
    if($('.modal1 #user').val()!==''&&$('.modal1 #pwd').val()!==''){
        $('.submit-disabled').removeAttr('disabled').css('backgroundColor',' #4CAF50');
    }
    else{
        $('.submit-disabled').attr('disabled','true').css('backgroundColor',' #c05547');
    }
})
$('.modal2').keyup(function(){
    if($('.modal2 #user').val()!==''&&$('.modal2 #pwd').val()!==''&&$('.modal2 #pwd-confirm').val()!==''){
        $('.submit-disabled').removeAttr('disabled').css('backgroundColor',' #4CAF50');
    }
    else{
        $('.submit-disabled').attr('disabled','true').css('backgroundColor',' #c05547');
    }
})


$('.main').mouseover(function () {
    $('.arrow').css('display', 'block');
})
$('.main').mouseout(function () {
    $('.arrow').css('display', 'none');
})

var picNum=document.querySelectorAll('.main li').length-2;
var img=document.querySelectorAll('.main ul li img');
img[0].src=img[picNum].src;
img[picNum+1].src=img[1].src;
var index=0;//当前图片索引参数
//自动滚动效果

function autoChange(){
    if(index==picNum-1){
        index=0;
        $('.main ul').animate({left:'-='+$('.main ul li').css('width')},function(){
            $('.main ul').css('left','-'+width);
        });
    }
    else{
        index++;
        $('.main ul').animate({left:'-='+$('.main ul li').css('width')});
    }
}
var timer=setInterval(autoChange,4000);

//重置滚动时间
function resetTimer(){
    clearInterval(timer);
    timer=setInterval(autoChange,4000);
}
//点击滚动效果
var isClick=false;//当前时间是否处于被点击后的滚动画面
$('#arrowl').click(function(){
    //若未处于滚动画面，则进行滚动，并将isClick置为true
    if(!isClick){
        isClick=true;
        $(this).animate({left:'10px'},30).animate({left:'0'},30);
        resetTimer();//重置自动滚动时间
        if(index==0){
            index=picNum-1;
            $('.main ul').animate({left:'+='+$('.main ul li').css('width')},function(){
                $('.main ul').css('left','-'+picNum*width_number+'px');
                isClick=false;//动画结束后将isClick置为false
            });
        }
        else{
            index--;
            $('.main ul').animate({left:'+='+$('.main ul li').css('width')},function(){
                isClick=false;//动画结束后将isClick置为false
            });
        }
    }
})
$('#arrowr').click(function(){
    //若未处于滚动画面，则进行滚动，并将isClick置为true
    if(!isClick){
        isClick=true;
        $(this).animate({right:'10px'},30).animate({right:'0'},30);
    resetTimer();//重置自动滚动时间
    if(index==picNum-1){
        index=0;
        $('.main ul').animate({left:'-='+$('.main ul li').css('width')},function(){
            $('.main ul').css('left','-'+width);
            isClick=false;//动画结束后将isClick置为false
        });
    }
    else{
        index++;
        $('.main ul').animate({left:'-='+$('.main ul li').css('width')},function(){
            isClick=false;//动画结束后将isClick置为false
        });
    }
    }
})

    </script>
</body>
</html>