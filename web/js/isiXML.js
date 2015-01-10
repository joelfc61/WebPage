// =========================================
// = Developed By Josema Gonzalez (EnZo)   =
// = Version 1                             =
// = http://isixml.sourceforge.net         =
// = License: GPL (General Public License) =
// =========================================
function isiXML(x){this.Query=
function(sql){var dostablas,selec,i,j,k,n=new Array(),resul=new Array(),resuln=0,VER=new RegExp(/^(atrib|node)_(value|node|atrib)(\[([0-9\-]+)\])?$/gi);var NODE_ID,NODE_ROW,NODE_PATH,NODE_LEVEL,NODE_VALUE,NODE_NODE;var ATRIB_ID,ATRIB_ROW,ATRIB_PATH,ATRIB_LEVEL,ATRIB_VALUE,ATRIB_NODE,ATRIB_ATRIB;var VER_NODE_NODE,VER_NODE_VALUE,VER_ATRIB_NODE,VER_ATRIB_VALUE,VER_ATRIB_ATRIB;this.Resul=
function(verono){var retorno,k;if(selec.length > 1){retorno=new Array();for(k=0;k<selec.length;++k)
retorno[k]=(selec[k].match(VER)&&verono)? eval("VER_"+selec[k].toUpperCase()):eval(selec[k].toUpperCase());}
else
retorno=(selec[0].match(VER)&&verono)? eval("VER_"+selec[0].toUpperCase()):eval(selec[0].toUpperCase());return retorno;};dostablas = (sql.match(/(node\..*atrib\.)|(atrib\..*node\.)/gi)) ? true : false;if (!dostablas) nodoatrib = (sql.match(/node\./gi)) ? true : false;sql = sql.replace(/=/g,"==");sql = sql.replace(/>==/g,">=");sql = sql.replace(/<==/g,"<=");sql = sql.replace(/<>/g,"!=");sql = sql.replace(/( | )+/g," ");sql = sql.replace(/\s+and\s+/gi," && ");sql = sql.replace(/\s+or\s+/gi," || ");sql = sql.replace(/(atrib|node)\.(id|row|path|level|value|node|atrib)/gi,"$1_$2");sql = sql.replace(/\(([0-9]{1,2})\)/gi,"[$1-1]");sql = sql.replace(/select (([a-z]+_[a-z]+(\[[0-9\-]+\])? ?,? ?)+) where (.+) limit (-?[0-9]+( ?,? ?-?[0-9]+)?)$/gi,"new Array(true, \"$1\", \"$4\", \"$5\")");sql = sql.replace(/select (([a-z]+_[a-z]+(\[[0-9\-]+\])? ?,? ?)+) limit (-?[0-9]+( ?,? ?-?[0-9]+)?)$/gi,"new Array(false, \"$1\", true, \"$4\")");sql = sql.replace(/select (([a-z]+_[a-z]+(\[[0-9\-]+\])? ?,? ?)+) where (.+)$/gi,"new Array(true, \"$1\", \"$4\", 0)");sql = sql.replace(/select (([a-z]+_[a-z]+(\[[0-9\-]+\])? ?,? ?)+)$/gi,"new Array(false, \"$1\", true, 0)");sql=eval(sql);sql[1] = sql[1].replace(/ |,$/gi,"");selec=sql[1].toUpperCase().split(",");nodoatrib=((dostablas&&this.xml[0].length > this.xml[1].length)||(!dostablas&&nodoatrib))? new Array(0,1):new Array(1,0);for(i=1;i<this.xml[nodoatrib[0]].length;++i){if(sql[0]||dostablas){if(dostablas){for(j=1;j<this.xml[nodoatrib[1]].length;++j){n=(nodoatrib[0]==0)? Array(i,j):Array(j,i);VER_NODE_NODE=this.xml[0][n[0]][2],VER_NODE_VALUE=this.xml[0][n[0]][3],VER_ATRIB_NODE=this.xml[1][n[1]][2],VER_ATRIB_VALUE=this.xml[1][n[1]][3],VER_ATRIB_ATRIB=this.xml[1][n[1]][4];NODE_ID=n[0],NODE_ROW=this.xml[0][n[0]][0],NODE_PATH=this.xml[0][n[0]][1],NODE_LEVEL=NODE_PATH.length,NODE_NODE=VER_NODE_NODE.toUpperCase(),NODE_VALUE=VER_NODE_VALUE.toUpperCase();ATRIB_ID=n[1],ATRIB_ROW=this.xml[1][n[1]][0],ATRIB_PATH=this.xml[1][n[1]][1],ATRIB_LEVEL=ATRIB_PATH.length,ATRIB_NODE=VER_ATRIB_NODE.toUpperCase(),ATRIB_VALUE=new Array(),ATRIB_ATRIB=new Array();for(k=0;k<VER_ATRIB_VALUE.length;++k)
ATRIB_VALUE[k]=VER_ATRIB_VALUE[k].toUpperCase(),ATRIB_ATRIB[k]=VER_ATRIB_ATRIB[k].toUpperCase();if(sql[0])
sql[2]=sql[2].toUpperCase();if(NODE_ROW==ATRIB_ROW&&(eval(sql[2])))
resul[resuln++]=this.Resul(true);}
}
else{if(nodoatrib[0]==0){VER_NODE_NODE=this.xml[0][i][2],VER_NODE_VALUE=this.xml[0][i][3];NODE_ID=i,NODE_ROW=this.xml[0][i][0],NODE_PATH=this.xml[0][i][1],NODE_LEVEL=NODE_PATH.length,NODE_NODE=VER_NODE_NODE.toUpperCase(),NODE_VALUE=VER_NODE_VALUE.toUpperCase();}
else{VER_ATRIB_NODE=this.xml[1][i][2],VER_ATRIB_VALUE=this.xml[1][i][3],VER_ATRIB_ATRIB=this.xml[1][i][4]
ATRIB_ID=i,ATRIB_ROW=this.xml[1][i][0],ATRIB_PATH=this.xml[1][i][1],ATRIB_LEVEL=ATRIB_PATH.length,ATRIB_NODE=VER_ATRIB_NODE.toUpperCase(),ATRIB_VALUE=new Array(),ATRIB_ATRIB=new Array();for(k=0;k<VER_ATRIB_VALUE.length;++k)
ATRIB_VALUE[k]=VER_ATRIB_VALUE[k].toUpperCase(),ATRIB_ATRIB[k]=VER_ATRIB_ATRIB[k].toUpperCase();}
if(eval(sql[2].toUpperCase())){resul[resuln++]=this.Resul(true);}}
}
else{if(nodoatrib[0]==0)
NODE_ID=i,NODE_ROW=this.xml[0][i][0],NODE_PATH=this.xml[0][i][1],NODE_LEVEL=NODE_PATH.length,NODE_NODE=this.xml[0][i][2],NODE_VALUE=this.xml[0][i][3];else
ATRIB_ID=i,ATRIB_ROW=this.xml[1][i][0],ATRIB_PATH=this.xml[1][i][1],ATRIB_LEVEL=ATRIB_PATH.length,ATRIB_NODE=this.xml[1][i][2],ATRIB_VALUE=this.xml[1][i][3],ATRIB_ATRIB=this.xml[1][i][4];resul[resuln++]=this.Resul(false);}
}
return(sql[3]!=0)? eval("resul.slice("+sql[sql.length-1]+")"):resul;};this.Leer=
function(x){var resultado=new Array(new Array(),new Array());var ok=new Array(1,1),total=new Array(),i=new Array();var lvl=0,id=0,xml,lvlUP;var NoEsIE=(navigator.appName=="Microsoft Internet Explorer"&&navigator.userAgent.indexOf("Opera")==-1)? false:true;this.Actualiza=
function(){var k,str="x";for(k=0;k<=lvl;++k)
str+=".childNodes["+i[k]+"]";return eval(str);};this.Atrib=
function(cual){var k,atrib=new Array();for(k=0;k<xml.attributes.length;++k)
atrib[k]=(cual)? xml.attributes[k].value:xml.attributes[k].name;return atrib;};this.Ruta=
function(){var k,ruta=new Array();for(k=1;k<=lvl;++k)
ruta[k-1]=(NoEsIE)?(i[k]+1)/2:i[k]+1;return ruta;};for(i[0]=0,total[0]=20;i[lvl] < total[lvl];++i[lvl]){xml=this.Actualiza();if(xml.nodeType==1){++id;if(xml.attributes.length > 0)
resultado[1][ok[1]++]=new Array(id,this.Ruta(),xml.nodeName,this.Atrib(true),this.Atrib(false));if(xml.childNodes.length > 1||(xml.childNodes.length==1&&xml.firstChild.childNodes.length > 0))
i[++lvl]=-1,total[lvl]=xml.childNodes.length;else if(xml.childNodes.length==1&&xml.childNodes[0].nodeValue!=null)
resultado[0][ok[0]++]=new Array(id,this.Ruta(),xml.nodeName,xml.childNodes[0].nodeValue);}
lvlUP=lvl;for(var j=0;j<lvlUP;++j)
if(total[lvl] <=(i[lvl]+1)&&lvl > 1)
--lvl;}
return resultado;};this.Tables=
function(id){var datos='<table width="100%" border="1" cellpadding="5" cellspacing="0"><tr><td colspan="6" bgcolor="#333333"><h2 style="color:#FFFFFF; padding-left:20px">Node</h2></td></tr><tr><td bgcolor="#AAAAAA"><b>ID</b></td><td bgcolor="#AAAAAA"><b>Row</b></td><td bgcolor="#AAAAAA"><b>Path</b></td><td bgcolor="#AAAAAA"><b>Level</b></td><td bgcolor="#AAAAAA"><b>Node</b></td><td bgcolor="#AAAAAA"><b>Value</b></td></tr>';for(i=1;i<this.xml[0].length;++i)
datos+='<tr><td>'+i+'</td><td>'+this.xml[0][i][0]+'</td><td>'+this.xml[0][i][1]+'</td><td>'+this.xml[0][i][1].length+'</td><td>'+this.xml[0][i][2]+'</td><td>'+this.xml[0][i][3]+'</td></tr>';datos+='</table><br><table width="100%" border="1" cellpadding="5" cellspacing="0"><tr><td colspan="7" bgcolor="#333333"><h2 style="color:#FFFFFF; padding-left:20px">Atrib</h2></td></tr><tr><td bgcolor="#AAAAAA"><b>ID</b></td><td bgcolor="#AAAAAA"><b>Row</b></td><td bgcolor="#AAAAAA"><b>Path</b></td><td bgcolor="#AAAAAA"><b>Level</b></td><td bgcolor="#AAAAAA"><b>Node</b></td><td bgcolor="#AAAAAA"><b>Value</b></td><td bgcolor="#AAAAAA"><b>Atrib</b></td></tr>';for(i=1;i<this.xml[1].length;++i)
datos+='<tr><td>'+i+'</td><td>'+this.xml[1][i][0]+'</td><td>'+this.xml[1][i][1]+'</td><td>'+this.xml[1][i][1].length+'</td><td>'+this.xml[1][i][2]+'</td><td><ol><li>'+this.xml[1][i][3].join('</li><li>')+'</li></ol></td><td><ol><li>'+this.xml[1][i][4].join('</li><li>')+'</li></ol></td></tr>';document.getElementById(id).innerHTML=datos;};this.xml=this.Leer(x);}