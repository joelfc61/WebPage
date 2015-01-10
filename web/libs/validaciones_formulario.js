var swOK=0;
var nEle=0;
var sError='';

function Verifica() {
  var nTot=0;
  var nPas=0;
  var nTorna=0;
  sError="Lista de errores:                                                   "+"\n";
  for (var j=0; j<6; j++) {
    nEle=j;

    var sNom=document.forms[0].elements[j].name;
    var sOne=sNom.substring(0,1);
    var sTwo=sNom.substring(1,2);

    if (sOne=='e' && sTwo=='r') {
       CaracterNoValid(document.forms[0].elements[j].value,'Er');
       nTot+=swOK;
    }
    else
       if (sOne=='e' && document.forms[0].elements[nEle].value!='') {
           CaracterNoValid(document.forms[0].elements[j].value,'Eo');
           nTot+=swOK;
         }

    if (sOne=='n' && sTwo=='r') {
       CaracterNoValid(document.forms[0].elements[j].value,'Nr');
       nTot+=swOK;
    }
    else
       if (sOne=='n' && document.forms[0].elements[nEle].value!='') {
           CaracterNoValid(document.forms[0].elements[j].value,'No');
           nTot+=swOK;
       }

    // CADENA Y OBLIGATORIA
    if (sOne=='s' && sTwo=='r') {
       CaracterNoValid(document.forms[0].elements[j].value,'Sr');
       nTot+=swOK;
    }

    // LISTA DE ERRORES
    if (nPas==0 && nTot>0) {
      document.forms[0].elements[nEle].focus()
      nPas=1
    }  
}

  if (nTot>0)
    alert(sError)
  else
    document.forms[0].submit();
}


function CaracterNoValid(pCaracter,pType) {
  swOK=0;
  // E-MAIL OBLIGATORIO
if (pType=='Eo') { 
if (isMail(document.forms[0].elements[nEle])==true) { 
sError+="Campo "+document.forms[0].elements[nEle].name.substr(2)+" ha de ser e-mail y NO es obligatorio" +"\r";
swOK=1;
}
return;
}
 

  // NUMERICO OBLIGATORIO
  if (pType=='Nr') {
    swOK=0;
    if (pCaracter=='') {
      swOK=1
      sError+="Campo "+document.forms[0].elements[nEle].name.substr(2)+" ha de ser numérico y es obligatorio" +"\r"
      return
    }

    for (var i=0;i<pCaracter.length;i++) { var sByte=pCaracter.substring(i,i+1); if (sByte<"0" || sByte>"9") {
        sError+="Campo "+document.forms[0].elements[nEle].name.substr(2)+" ha de ser numérico y es obligatorio" +"\r"
        swOK=1;
        return;
      } 
    }
  }

  // NUMERICO INFORMADO
  if (pType=='No') { 
    swOK=0;
    if (pCaracter=='')  {
      swOK=1
      sError+="Campo "+document.forms[0].elements[nEle].name.substr(2)+" ha de ser numérico y NO es obligatorio" +"\r"
      return
    }
    for (var i=0;i<pCaracter.length;i++) { var sByte=pCaracter.substring(i,i+1); if (sByte<"0" || sByte>"9") {
        sError+="Campo "+document.forms[0].elements[nEle].name.substr(2)+" ha de ser numérico y NO es obligatorio" +"\r"
        swOK=1;
        return;
      } 
    }
  }

  // CADENA
  if (pType=='Sr') { 
    if (pCaracter=='') {
       sError+="Campo "+document.forms[0].elements[nEle].name.substr(2)+" ha de ser texto y es obligatorio"+"\r"
       swOK=1;
       return
     }
  }
}


function isMail(obj){ 
var ValorCampo; 
var Form0=document.forms[0]; 
var error; 

ValorCampo=Form0.elements[obj.name].value; 
if(ValorCampo.search(/^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$/) == -1){ 
error=true; 
} 
else error=false; 

return error; 
} 