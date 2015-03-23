//Drop Down/ Overlapping Content: http://www.dynamicdrive.com
//**Updated: Dec 19th, 07': Added ability to dynamically populate a Drop Down content using an external file (Ajax feature)
//**Updated: Feb 29th, 08':
				//1) Added ability to reveal drop down content via "click" of anchor link (instead of default "mouseover")
				//2) Added ability to disable drop down content from auto hiding when mouse rolls out of it
				//3) Added hidediv(id) public function to directly hide drop down div dynamically

//**Updated: Sept 11th, 08': Fixed bug whereby drop down content isn't revealed onClick of anchor in Safari/ Google Chrome
//**Updated: April 9th, 10': Minor change

var dropdowncontent={
	disableanchorlink: true, //when user clicks on anchor link, should link itself be disabled (always true if "revealbehavior" above set to "click")
 hidedivmouseout: [true, 200], //Set hiding behavior within Drop Down DIV itself: [hide_div_onmouseover?, miliseconds_before_hiding]
	ajaxloadingmsg: "Loading content. Please wait...", //HTML to show while ajax page is being feched, if applicable
	ajaxbustcache: true, //Bust cache when fetching Ajax pages?

	getposOffset:function(what, offsettype){
		
		return (what.offsetParent)? what[offsettype]+this.getposOffset(what.offsetParent, offsettype) : what[offsettype]
			//return (what.offsetParent)? what[offsettype]+what.offsetParent : what[offsettype]
	},
	
	isContained:function(m, e){
		var e=window.event || e
		var c=e.relatedTarget || ((e.type=="mouseover")? e.fromElement : e.toElement)
		while (c && c!=m)try {c=c.parentNode} catch(e){c=m}
		if (c==m)
			return true
		else
			return false
	},

	show:function(anchorobj, subobj, e){
		//alert(anchorobj.offsetParent.offsetTop);
		//alert(subobj.offsetTop);
		//alert(subobj.offsetHeight);
		if (!this.isContained(anchorobj, e) || (e && e.type=="click")){
			var e=window.event || e
			 var obj=  jQuery("#menu").offset();;
			// var objsub	=	 document.getElementById('searchlink');
			var objleft=obj.left;
      		var objtop=obj.top;
			//alert('Menu Top'+objtop);
/*form these two lines you can find (x,y) of the element (that is topleft x,y)*/
		var anchor_id	=	 anchorobj.id;
			var ancobj	 =jQuery("#"+anchor_id).offset();
			var ancobjtop	=	ancobj.top;
	//	alert(jQuery(window).scrollTop());
			//alert('Anchor Top'+ancobjtop);
			/*find the Anchor Tag Position */
			
			//var anctagheight	=	anctagpos['offsetTop'];
			
			
    		var objwidth=obj['offsetWidth'];

   			var objheight=obj['offsetHeight'];
			//alert('Left'+ objleft);
			//alert('Top'+ objtop);
			//alert('width'+ objwidth);
			//alert('Height'+ objheight);
			
			var anctopheight	=	ancobjtop-objtop;
			//alert('Anchor Top Height'+anctopheight);
			var boxheight		=	 subobj.offsetHeight;
			//alert('Box Height'+boxheight);
			var vistopvalue	=	anctopheight-boxheight;
			var horizontaloffset=(subobj.dropposition[0]=="left")? (subobj.offsetWidth-anchorobj.offsetWidth) : 20 //calculate user added horizontal offset
			var verticaloffset=(subobj.dropposition[1]=="top")? subobj.offsetHeight : anchorobj.offsetHeight //calculate user added vertical offset
			//subobj.style.left=this.getposOffset(anchorobj, "offsetLeft") + horizontaloffset + "px"
			//subobj.style.top=(this.getposOffset(anchorobj, "offsetTop") -objtop)-subobj.offsetTop+ "px"
			subobj.style.left = horizontaloffset +"px"
			//alert('MenuTop'+objtop);
			//alert(this.getposOffset(anchorobj, "offsetTop"));
			//alert('SearchLink'+objtopsub);
			//alert('VerticalOffset'+verticaloffset);
			//alert('Menu Box Height'+objheight);
			//alert('SubContent'+subobj.offsetHeight);
			//alert('anchor'+anchorobj.offsetHeight);
			
			//To Calculate Viewable Area
			var windowheight	=	 jQuery(window).height();
			var scrolltop	=	jQuery(window).scrollTop();	
			var scrollbottom	=	 jQuery(window).scrollTop()+windowheight;
			
			//To  Calculate Viewable Area
			var scroll_top_view_area	=	 ancobjtop-scrolltop;
			var scroll_bot_view_area	=	  scrollbottom	-	ancobjtop;
			
			if(scroll_top_view_area>=boxheight)
			{
				subobj.style.top	=	 vistopvalue+"px";
			}else if(scroll_top_view_area<boxheight  && scroll_bot_view_area>=boxheight)
			{
				subobj.style.top	=	 anctopheight+anchorobj.offsetHeight+"px";
			}else{
				//alert('test');
				subobj.style.top		=		(anctopheight)/2+"px";	
			}
			//alert('Top Value'+subobj.style.top);
			//subobj.style.top=verticaloffset- anchorobj.offsetTop+ "px"
			//alert(subobj.style.top);
			//subobj.style.clip=(subobj.dropposition[1]=="top")? "rect(auto auto auto 0)" : "rect(0 auto 0 0)" //hide drop down box initially via clipping
			subobj.style.visibility="visible"
			subobj.startTime=new Date().getTime()
			subobj.contentheight=parseInt(subobj.offsetHeight)
			if (typeof window["hidetimer_"+subobj.id]!="undefined") //clear timer that hides drop down box?
				clearTimeout(window["hidetimer_"+subobj.id])
			this.slideengine(subobj, (subobj.dropposition[1]=="top")? "up" : "down")
		}
	},

	curveincrement:function(percent){
		return (1-Math.cos(percent*Math.PI)) / 2 //return cos curve based value from a percentage input
	},

	slideengine:function(obj, direction){
		var elapsed=new Date().getTime()-obj.startTime //get time animation has run
		if (elapsed<obj.glidetime){ //if time run is less than specified length
			var distancepercent=(direction=="down")? this.curveincrement(elapsed/obj.glidetime) : 1-this.curveincrement(elapsed/obj.glidetime)
			var currentclip=(distancepercent*obj.contentheight)+"px"
			//obj.style.clip=(direction=="down")? "rect(0 auto "+currentclip+" 0)" : "rect("+currentclip+" auto auto 0)"
			window["glidetimer_"+obj.id]=setTimeout(function(){dropdowncontent.slideengine(obj, direction)}, 10)
		}
		else{ //if animation finished
			//obj.style.clip="rect(0 auto auto 0)"
		}
	},

	hide:function(activeobj, subobj, e){
		if (!dropdowncontent.isContained(activeobj, e)){
			window["hidetimer_"+subobj.id]=setTimeout(function(){
				subobj.style.visibility="hidden"
				subobj.style.left=subobj.style.top=0
				clearTimeout(window["glidetimer_"+subobj.id])
			}, dropdowncontent.hidedivmouseout[1])
		}
	},

	hidediv:function(subobjid){
		document.getElementById(subobjid).style.visibility="hidden"
	},

	ajaxconnect:function(pageurl, divId){
		var page_request = false
		var bustcacheparameter=""
		if (window.XMLHttpRequest) // if Mozilla, IE7, Safari etc
			page_request = new XMLHttpRequest()
		else if (window.ActiveXObject){ // if IE6 or below
			try {
			page_request = new ActiveXObject("Msxml2.XMLHTTP")
			} 
			catch (e){
				try{
				page_request = new ActiveXObject("Microsoft.XMLHTTP")
				}
				catch (e){}
			}
		}
		else
			return false
		document.getElementById(divId).innerHTML=this.ajaxloadingmsg //Display "fetching page message"
		page_request.onreadystatechange=function(){dropdowncontent.loadpage(page_request, divId)}
		if (this.ajaxbustcache) //if bust caching of external page
			bustcacheparameter=(pageurl.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()
		page_request.open('GET', pageurl+bustcacheparameter, true)
		page_request.send(null)
	},

	loadpage:function(page_request, divId){
		if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1)){
			document.getElementById(divId).innerHTML=page_request.responseText
		}
	},

 init:function(anchorid, pos, glidetime, revealbehavior){
		var anchorobj=document.getElementById(anchorid)
		if (anchorobj)
			var subobj=document.getElementById(anchorobj.getAttribute("rel"))
		if (!anchorobj || !subobj)
			return
		var subobjsource=anchorobj.getAttribute("rev")
		if (subobjsource!=null && subobjsource!="")
			this.ajaxconnect(subobjsource, anchorobj.getAttribute("rel"))
		subobj.dropposition=pos.split("-")
		subobj.glidetime=glidetime || 1000
		subobj.style.left=subobj.style.top=0
		if (typeof revealbehavior=="undefined" || revealbehavior=="mouseover"){
			anchorobj.onmouseover=function(e){dropdowncontent.show(this, subobj, e)}
			anchorobj.onmouseout=function(e){dropdowncontent.hide(subobj, subobj, e)}
			if (this.disableanchorlink) anchorobj.onclick=function(){return false}
		}
		else
			anchorobj.onclick=function(e){dropdowncontent.show(this, subobj, e); return false}
		if (this.hidedivmouseout[0]==true) //hide drop down DIV when mouse rolls out of it?
			subobj.onmouseout=function(e){dropdowncontent.hide(this, subobj, e)}
	}
}