jQuery.jPrintArea=function(el)
{
	var loader = $j('<div id="loader" align="center"><p><b class="txt_txt">Imprimiendo...</b><br><img src="images/ajax.gif" alt="loading..." /></p></div>')
	.appendTo("#mensajes");
	loader.hide();
	loader.fadeIn('slow');

	var iframe=document.createElement('IFRAME');
	var doc=null;
	$j(iframe).attr('style','position:absolute;width:0px;height:0px;left:-500px;top:-500px;');
	document.body.appendChild(iframe);
	doc=iframe.contentWindow.document;
	var links=window.document.getElementsByTagName('link');
	for(var i=0;i<links.length;i++)
		if(links[i].rel.toLowerCase()=='stylesheet')
			doc.write('<link type="text/css" rel="stylesheet" href="print.css" media="all""></link>');
	doc.write('<div class="'+$j(el).attr("class")+'">'+$j(el).html()+'</div>');
	doc.close();
	iframe.contentWindow.focus();
	iframe.contentWindow.print();
    loader.animate({opacity: 1.0}, 6000);
    loader.fadeOut('slow', function() {
      $j(this).remove();
    });
	
	setTimeout('',5000);
	//document.body.removeChild(iframe);
	
		
}