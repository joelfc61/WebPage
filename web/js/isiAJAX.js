
function isiAJAX(idmain,idloader){this.objAjax=null;this.idmain=idmain;this.idloader=idloader;this.debug=true;this.aborta=true;this.working=false;this.noajax='Su navegador no es compatible para trabajar con esta web.';this.Request=
function(data){var parent=this;data=this.Default(data);this.objAjax.open(data.method,data.url,data.async);if(data.headers)
for(var i=0;i<data.headers.length;i+=2)
this.objAjax.setRequestHeader(data.headers[i],data.headers[i+1])
if(data.async){this.working=true;this.Cargador(true);this.objAjax.onreadystatechange=
function(){if(parent.objAjax.readyState==4){if(parent.aborta){if(data.onComplete)
data.onComplete(parent.objAjax,data);if((parent.objAjax.status==200||parent.objAjax.status==304)&&data.onOK)
data.onOK(parent.objAjax,data);else if(data.onError)
data.onError(parent.objAjax,data);else if(parent.debug)
alert('Error: '+parent.objAjax.status+' '+parent.objAjax.statusText);}
else
parent.aborta=true;parent.Cargador(false);parent.working=false;}
};}
this.objAjax.send(data.param);};this.Actualiza=
function(objAjax,data){switch(data.type){case 1:{if(data.iner==0)
$(data.id).innerHTML=objAjax.responseText+$(data.id).innerHTML;else if(data.iner==1)
$(data.id).innerHTML=$(data.id).innerHTML+objAjax.responseText;else
$(data.id).innerHTML=objAjax.responseText;break;}
case 2:{var objxml=new isiXML(objAjax.responseXML);data.id(objxml.Query(data.query),objAjax);break;}
case 3:{if(data.iner==0)
$(data.id).value=objAjax.responseText+$(data.id).value;else if(data.iner==1)
$(data.id).value=$(data.id).value+objAjax.responseText;else
$(data.id).value=objAjax.responseText;break;}
case 4:{var obj=$(data.id);var objxml=new isiXML(objAjax.responseXML);var resultado=objxml.Query('SELECT node.value, atrib.value');var total=obj.options.length;if(total > 0)
for(var i=0;i<=total;++i)
obj.remove(obj.options[i])
for(var i=0;i<resultado.length;++i){var newone=new Option(resultado[i][0],resultado[i][1]);try{obj.add(newone,obj.options.length);}
catch(ex){obj.add(newone,obj.options[obj.options.length]);}
}
break;}
case 5:{eval(objAjax.responseText);break;}
}
};this.Link=
function(url,id,iner){this.Request({url:this.Url(url),
id:id,
async:(id)? true:false,
iner:iner,
type:1,
onOK:this.Actualiza
});};this.Form=
function(idform,id,iner){var form=$(idform);this.Request({url:this.Url(form.action),
method:form.method,
id:id,
param:this.DataForm(form),
async:(id)? true:false,
iner:iner,
type:1,
onOK:this.Actualiza
});};this.XML=
function(url,funcion,query){this.Request({url:this.Url(url),
id:funcion,
query:query,
type:2,
onOK:this.Actualiza
});};this.Value=
function(url,id,iner){this.Request({url:this.Url(url),
id:id,
iner:iner,
type:3,
onOK:this.Actualiza
});};this.Select=
function(url,id){this.Request({url:this.Url(url),
id:id,
type:4,
onOK:this.Actualiza
});};this.Run=
function(url){this.Request({url:this.Url(url),
type:5,
onOK:this.Actualiza
});};this.Img=
function(url,id,w,h){var temp=new Image();var parent=this;this.working=true;this.Cargador(true);temp.onload=function(){if(parent.aborta){$(id).src=temp.src;if(w)$(id).width=w;if(h)$(id).height=h;}
parent.Cargador(false);parent.aborta=true;parent.working=false;};temp.onerror=function(){parent.Cargador(false);parent.aborta=true;parent.working=false;if(parent.debug)
alert('Error: 404 Not Found');};temp.src=url;};this.Cancel=
function(){if(this.working){this.Cargador(false);this.aborta=false;this.working=false;this.objAjax.abort();}
else
this.aborta=true;};this.DataForm=
function(obj){var resultado='';this.Add=
function(nombre,valor){resultado+=nombre+'='+valor;if((i+1)< obj.length)
resultado+='&';};for(i=0;i<obj.length;i++){var tipo=obj[i].type;var nombre=obj[i].name;if(tipo=='select-multiple'){var n=0;for(j=0;j<obj[i].options.length;j++)
if(obj[i].options[j].selected)
this.Add(nombre+'['+(n++)+']',obj[i].options[j].value);}
else if(tipo=='radio'||tipo=='checkbox'){if(obj[i].checked)
this.Add(nombre,obj[i].value);}
else
this.Add(nombre,obj[i].value);}
return(resultado);};this.CSS=
function(obj,atributo){if(obj.style[atributo])
return(obj.style[atributo]);else if(obj.currentStyle)
return(obj.currentStyle[atributo]);else if(document.defaultView&&document.defaultView.getComputedStyle)
return document.defaultView.getComputedStyle(obj,"").getPropertyValue(atributo);else
return(null);};this.Default=
function(obj){var argumentos={url:this.homepage,
method:'GET',
async:true
};for(var i in argumentos)
if(obj[i]==null)
obj[i]=argumentos[i];if(obj.method.toUpperCase()=='GET'&&obj.param){obj.url+=(obj.url.indexOf('?')==-1)? '?'+obj.param:'&'+obj.param;obj.param=null;}
else if(obj.method.toUpperCase()=='POST'&&obj.headers==null)
obj.headers=['Content-Type','application/x-www-form-urlencoded; charset=ISO-8859-1'];return(obj);};this.Url=
function(str){var resultado='',obj=str.split('#');if(obj.length > 1){for(var i=1;i<obj.length;++i){resultado+=obj[i];if((i+1)< obj.length)
resultado+='#';}
}
else
resultado=obj[0];return(resultado);};this.Cargador=
function(onoff,id){if(id==null)
id=this.idloader;if(id)
$(id).style.visibility=(onoff)? 'visible':'hidden';};this.Cargador(false);if(typeof XMLHttpRequest!='undefined')
this.objAjax=new XMLHttpRequest();else if(typeof ActiveXObject!='undefined')
this.objAjax=(Number(navigator.appVersion.substr(0,3))>=5)? new ActiveXObject('Msxml2.XMLHTTP'):new ActiveXObject('Microsoft.XMLHTTP');if(this.objAjax==null)
alert(this.noajax);else if(this.idmain&&window.location.hash!=''&&window.location.hash!='#')
this.Link(window.location.href,this.idmain);}
function $(id){return(document.getElementById(id));}
/*
url: '',			//url web
method: 'GET',		//metodo de transferencia
async: true,		//si es true recibira datos si es false no
param: null,		//parametos en plan: var1=valor1&var2=valor2
id: null,			//id donde va a ser cargado
headers: null,		//cabeceras
iner: null,
type: null,
onOK: null,
onError: null,
onComplete: null
*/
