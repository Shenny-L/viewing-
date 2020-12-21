<?php 
if(empty($_POST['user'])){
	skip('../home/start.php', 'error', '用户名不得为空！');
}
if(mb_strlen($_POST['user'])>30){
	skip('../home/start.php', 'error', '用户名长度不要超过30个字符！');
}
if(empty($_POST['pwd'])){
	skip('../home/start.php', 'error', '密码不得为空！');
}
?>