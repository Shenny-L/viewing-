<?php 
if(empty($_POST['user'])){
	skip('../home/start.php', 'error', '用户名不得为空！');
}
if(mb_strlen($_POST['user'])>30){
	skip('../home/start.php', 'error', '用户名长度不要超过30个字符！');
}
if(mb_strlen($_POST['pwd'])<6){
	skip('../home/start.php', 'error','密码不得少于6位！');
}
if($_POST['pwd']!=$_POST['pwd-confirm']){
	skip('../home/start.php', 'error','两次密码输入不一致！');
}
$_POST=escape($link,$_POST);
$query="select * from member where name='{$_POST['user']}'";
$result=execute($link, $query);
if(mysqli_num_rows($result)){
	skip('../home/start.php', 'error', '这个用户名已经被别人注册了！');
}
?>