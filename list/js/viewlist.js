// JavaScript Document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function moveUp() {
	document.getElementById("arrow").style="display:none";
	document.getElementById("navbar").style.display="inline";
	$("#bg").animate({height:'350px'});
	document.body.style.overflow="visible";
}

function load(){
	document.getElementById("navbar").style.display="none";
	window.onmousewheel = document.onmousewheel=false;
	document.body.style.overflow="hidden";
}

//禁用滚轮
function disabledMouseWheel() {  
  if (document.addEventListener) {  
    document.addEventListener('DOMMouseScroll', scrollFunc, false);  
  }//W3C  
  window.onmousewheel = document.onmousewheel = scrollFunc;//IE/Opera/Chrome  
}

//开启滚轮
function scrollFunc(evt) {  
  evt = evt || window.event;  
    if(evt.preventDefault) {  
    // Firefox  
      evt.preventDefault();  
      evt.stopPropagation();  
    } else {  
      // IE  
      evt.cancelBubble=true;  
      evt.returnValue = false;  
  }  
  return false;  
}  

function fucusOn(x){
	var c=x.children;
	c[0].style.filter="blur(5px)";
	c[1].style.display="inline";
}

function deFucusOn(x){
	var c=x.children;
	c[0].style.filter="blur(0px)";
	c[1].style.display="none";
}
