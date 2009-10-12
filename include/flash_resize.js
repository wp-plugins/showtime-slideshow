/* 
Methods for resizing the flash stage at runtime.
*/

function setSize(divid, newW, newH)
{
	document.getElementById(divid).style.width = newW;
	document.getElementById(divid).style.height = newH;		
}


function setFlashSize(divid, newW, newH)
{
	//window.scrollTo(0,0);
	
	var hscroll = (document.all ? document.scrollLeft : window.pageXOffset);
	var vscroll = (document.all ? document.scrollTop : window.pageYOffset);
	
	document.getElementById(divid).style.position = "absolute";
	
	document.getElementById(divid).style.background = "#000";		

	document.getElementById(divid).style.left = hscroll+"px";		
	document.getElementById(divid).style.top = vscroll+"px";		

	//document.body.style.overflow = "hidden";	
		
	setSize(divid, newW, newH);

	//return 1;
}


function resetFlashSize(divid, newW, newH)
{
	//window.scrollTo(0,0);
	document.getElementById(divid).style.position = "relative";		

	document.getElementById(divid).style.background = "none";		

	document.getElementById(divid).style.left = "auto";		
	document.getElementById(divid).style.top = "auto";		

	//document.body.style.overflow = "scroll";	
		
	setSize(divid, newW, newH);
	
	//return 0;
}

