//correções para o IE
function navCorrection(){
	
}

function confirmLogout(){
	if(confirm("Deseja realmente finalizar a sessão?")){
		document.location = "login.php?cmd=quit";
	}	
}

//Ajax BEGIN=========================================================
function getXMLHTTP(){
	try{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try{
			xmlhttp = new ActiveXObject("Microsoft.XMPHTTP");
		}catch(ee){
			xmlhttp = new XMLHttpRequest();
		}
	} 
}

function getLoginTime(serverPage){
	getXMLHTTP();	
	var reCache = new Date().getTime(); 
	xmlhttp.open("GET",serverPage+"&rc="+reCache);
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
	
		}else{
			
		}
	}
	xmlhttp.send(null);
}


function getFields(formName){
	var fields = "?";
	var andVar = "";
	var fn = document.forms[formName];
	var checkboxName = "";		
	for(var i=0;i < fn.elements.length;i++){
		if(fn.elements[i].value != ""){			
			if(fn.elements[i].type == "text" || 
			   fn.elements[i].type == "select-one" ||
			   fn.elements[i].type == "hidden" ||
			   fn.elements[i].type == "password" ||
			   fn.elements[i].type == "textarea" ||
			   fn.elements[i].type == "file"){
				fields += andVar+fn.elements[i].name+"="+fn.elements[i].value;	
			}else if(fn.elements[i].type == "radio" && fn.elements[i].checked){
				fields += andVar+fn.elements[i].name+"="+fn.elements[i].value;
			}else if(fn.elements[i].type == "checkbox" && fn.elements[i].checked && fn.elements[i].name != checkboxName){			
				checkboxName = fn.elements[i].name;
				var checkboxValues = "";
				for (var ii = 0; ii < fn.elements[fn.elements[i].name].length; ii++) {
					if (fn.elements[fn.elements[i].name][ii].checked) {
						checkboxValues += fn.elements[fn.elements[i].name][ii].value+",";
					}
				}
				fields += andVar+fn.elements[i].name+"="+checkboxValues;
			}	
			andVar = "&";
			
		}
				
	}	
	
	for(i = 0; i < fields.length; i++){
		fields = fields.replace(/\n/,"<br/>");
		//fields = fields.replace(/\"/,"");
	}
	
	return fields;
}

function concluirOS(serverPage, ot, srch, pg){
	if(confirm("Deseja realmente concluir esta ordem de serviço?")){
		getXMLHTTP();	
		var reCache = new Date().getTime(); 
		xmlhttp.open("GET",serverPage+"&rc="+reCache);
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200){			
				document.location = "index.php?inc=los&ot="+ot+srch+"&msg=cos&pg="+pg;
			}else{
				
			}
		}	
		xmlhttp.send(null);		
	}
}

function excluirRec(serverPage, ot, srch, pg, inc, msg,  notificar){
	if(confirm("Deseja realmente excluir este registro?")){
		getXMLHTTP();	
		var reCache = new Date().getTime();
		if(notificar && confirm("Deseja notifica o cliente desta exclusão?")){
			xmlhttp.open("GET",serverPage+"&not=S&rc="+reCache);
		}else{
			xmlhttp.open("GET",serverPage+"&rc="+reCache);
		}
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200){			
				document.location = "index.php?inc="+inc+"&ot="+ot+srch+"&msg="+msg+"&pg="+pg;
			}else{
				
			}
		}	
		xmlhttp.send(null);		
	}
}


function ajaxSender(formName, serverPage, ot, srch, pg, inc, msg){		
	if(!tryToSubmit(formName)){
		return false;	
	}
	
	fields = getFields(formName);	
	
	getXMLHTTP();	
	var reCache = new Date().getTime();	
	
	xmlhttp.open("GET",serverPage+fields+"&rc="+reCache);
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			
			if(xmlhttp.responseText == ""){
				document.location = "index.php?inc="+inc+"&ot="+ot+srch+"&msg="+msg+"&pg="+pg;
			}else{
				document.location = "index.php?inc="+inc+"&ot="+ot+srch+"&msg="+xmlhttp.responseText+"&pg="+pg;
			}
			
			document.forms[formName].elements['s'].style.display = "inline";
			document.getElementById('os_loader').style.display = "none";
		}else{
			document.forms[formName].elements['s'].style.display = "none";
			document.getElementById('os_loader').style.display = "inline";
		}
	}	
	xmlhttp.send(null);
}

function ajaxSenderOnly(serverPage, ot, srch, pg, inc, msg){	
	getXMLHTTP();	
	var reCache = new Date().getTime();	
	
	xmlhttp.open("GET",serverPage+"&rc="+reCache);
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			
			if(xmlhttp.responseText == ""){
				document.location = "index.php?inc="+inc+"&ot="+ot+srch+"&msg="+msg+"&pg="+pg;
			}else{
				document.location = "index.php?inc="+inc+"&ot="+ot+srch+"&msg="+xmlhttp.responseText+"&pg="+pg;
			}			
			
		}else{
			
		}
	}	
	xmlhttp.send(null);
}

//usuario
//var alterState = false;
function setPemission(serverPage, objID){	
	var obj = document.getElementById(objID);
	var oldObjContent = obj.innerHTML;		
	obj.setAttribute("src","");
	getXMLHTTP();	
	var reCache = new Date().getTime(); 
	xmlhttp.open("GET",serverPage+"&rc="+reCache);
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){			
			
		}else{
			
		}
	}
	if(oldObjContent.indexOf("ok.png") > -1){
					
	obj.innerHTML = '<img title="Clique para alterar a permiss&atilde;o" height=16 src="img/btn/ok_d.png" width=16  border=0 style="margin-bottom: 1px">';
		
	}else{
		
	obj.innerHTML = '<img title="Clique para alterar a permiss&atilde;o" height=16 src="img/btn/ok.png" width=16  border=0  style="margin-bottom: 1px">'	
		
	}
	xmlhttp.send(null);	
}

function setLocker(serverPage, objID, doRefresh){
	var obj = document.getElementById(objID);
	var oldObjContent = obj.innerHTML;		
	obj.setAttribute("src","");
	getXMLHTTP();	
	var reCache = new Date().getTime(); 
	xmlhttp.open("GET",serverPage+"&rc="+reCache);
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){			
			if(doRefresh){				
				url = document.location.toString();
				if(url.indexOf("&ord=1") > -1){					
					tmp = url.replace("&ord=1","");
				}else{
					tmp = url;
				}
				document.location = tmp;
				//location.reload();
			}	
		}else{
			
		}
	}
	
	if(!doRefresh){
		if(oldObjContent.indexOf("lock.png") > -1){
						
		obj.innerHTML = '<img title="Clique para bloque&aacute;-lo" height=16 src="img/btn/lock_d.png" width=16  border=0 style="margin-bottom: 1px">';
			
		}else{
			
		obj.innerHTML = '<img title="Clique para desbloque&aacute;-lo" height=16 src="img/btn/lock.png" width=16  border=0  style="margin-bottom: 1px">'	
			
		}
	}
	xmlhttp.send(null);	
}

var teste = "";
function ajaxRequest(serverPage, objID){
	document.getElementById('sform').style.display= "none";

	showRequests();
	
	getXMLHTTP();
	var obj = document.getElementById(objID);
	var reCache = new Date().getTime(); 
	xmlhttp.open("GET",serverPage+"&rc="+reCache);
	xmlhttp.onreadystatechange = function(){
		
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			obj.innerHTML = xmlhttp.responseText;					
		}else{	
			obj.innerHTML = "<div id=\"loadbox\"><img src=\"img/loader.gif\"></div>";
		}
	}			
	xmlhttp.send(null);
}

function hideRequests(){
	document.getElementById('disable').style.display= "none";
	document.getElementById('ajaxContent').style.display= "none";
}
function showRequests(){
	document.getElementById('disable').style.display = "block";
	document.getElementById('disable').style.height = document.getElementById('listing').clientHeight+235+"px";
	
	var ajaxContent = document.getElementById('ajaxContent');
	ajaxContent.style.display = "block";	
	ajaxContent.scrollIntoView(true);	
}

function supContact(cliId){	
	var assunto = document.forms['contato_form'].elements['obrg_assunto'].value;
	var msg = document.forms['contato_form'].elements['obrg_msg'].value;
	var obj = document.getElementById('ajaxContent');
	if(assunto != "" && msg != ""){
		getXMLHTTP();
		var reCache = new Date().getTime();
		var page = "inc/supContact.php?cliId="+cliId+"&ass="+assunto+"&msg="+msg+"&rc="+reCache;
		xmlhttp.open("GET",page);
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
				obj.innerHTML = xmlhttp.responseText;
			}else{
				obj.innerHTML = "<div id=\"loadbox\"><img src=\"img/loader.gif\"></div>";			
			}
		}	
		xmlhttp.send(null);
	}else{
		alert('Preencha todos os campos.');	
	}
}


var minusIcon = '<img src="img/btn/menos.png" style="margin-bottom: -5px" border="0" />';
var plusIcon = '<img src="img/btn/mais.png" style="margin-bottom: -5px" border="0" />';
var bullet = '<img src="img/btn/green_ball.png" style="margin-bottom: -5px" border="0" />';
var bullet_delete = '<img src="img/btn/bullet_delete.png" style="margin-bottom: -5px" border="0" />';
var img_del = '<img src="img/btn/del.png" style="margin-bottom: -5px" border="0" />';
var arrow_up = '<img src="img/btn/arrow_up.png" style="margin-bottom: -5px" border="0" />';
var arrow_down = '<img src="img/btn/arrow_down.png" style="margin-bottom: -5px" border="0" />';
function showHide(elemento,sinal){
	var e = document.getElementById(elemento);	
	if(e.style.display == "none"){
		e.style.display = "block";		
		document.getElementById(sinal).innerHTML = minusIcon;
	}else{
		e.style.display = "none";		
		document.getElementById(sinal).innerHTML = plusIcon;
	}	
}

//ORÇAMENTO - begin ===================================================================
var orcid = null;

function createOrc(formName){
	if(!tryToSubmit(formName)){
		return false;	
	}
	
	fields = getFields(formName);	
	
	getXMLHTTP();	
	var reCache = new Date().getTime();	
	
	xmlhttp.open("GET","inc/saveorc.php"+fields+"&rc="+reCache);
	xmlhttp.onreadystatechange = function(){
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			
			document.forms[formName].elements['orc_id'].value = xmlhttp.responseText;			
			document.getElementById('c_loader').style.display = "none";
			document.getElementById('disabledCmd01').style.display = "none";
			document.getElementById('cmd01').style.display = "block";
			document.getElementById('cmd02').style.display = "block";
			document.getElementById('equeservDiv').style.display = "block";
			document.getElementById('prodDiv').style.display = "block";
			
			showHide('infog','infogSign');
			showHide('equeserv','equeservSign');
		}else{
			document.forms[formName].elements['c'].style.display = "none";
			document.getElementById('c_loader').style.display = "inline";
		}
	}	
	xmlhttp.send(null);
}

function doSearch(table, formName,searchFile, searchFld, resultObj){	
	var obj = document.getElementById(resultObj);
	
	if(searchFld.value != ""){
		obj.style.height = "73px";		
		getXMLHTTP();		
		var reCache = new Date().getTime(); 
		xmlhttp.open("GET","inc/search/"+searchFile+"?table="+table+					 
						   "&formName="+formName+
						   "&searchfld="+searchFld.name+
						   "&searchkey="+searchFld.value+
						   "&ro="+resultObj+
						   "&rc="+reCache);
		
		xmlhttp.onreadystatechange = function(){
			
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
				if(xmlhttp.responseText != ""){
					obj.innerHTML = xmlhttp.responseText;					
				}else{
					obj.innerHTML = "Nenhum item encontrado.";					
				}
				var rs = document.getElementById(resultObj);
				if(navigator.appName != "Netscape" && navigator.userAgent.indexOf("MSIE 7.0") < 0){					
					if(rs.scrollHeight > rs.clientHeight){
						rs.style.width = "96%";
						
					}else{
						rs.style.width = "100%";
					}
				}
			}else{
				obj.innerHTML = "carregando...";				
			}
		}			
		xmlhttp.send(null);
	}else{		
		obj.innerHTML = "";
		obj.style.height = "0px";
	}
}

function numberFormat(expr, decplaces) {
	// raise incoming value by power of 10 times the
	// number of decimal places; round to an integer; convert to string
	var str = "" + Math.round(eval(expr) * Math.pow(10,decplaces));
	// pad small value strings with zeros to the left of rounded number
	while (str.length <= decplaces) {
	str = "0" + str;
	}
	// establish location of decimal point
	var decpoint = str.length - decplaces;
	// assemble final result from: (a) the string up to the position of
	// the decimal point; (b) the decimal point; and (c) the balance
	// of the string. Return finished product.
	return str.substring(0,decpoint) + "," +
	str.substring(decpoint,str.length);
	/*
	var str = "" + expr * Math.pow(10,decplaces);
	
	while (str.length < decplaces) {
		str = "0" + str;
	}
	
	var decpoint = str.length - decplaces;
	return str.substring(0,decpoint) + "," + str.substring(decpoint,str.length);*/
}


//Vetor com todos os prefixos que faram parte do calculo para total e subtotal
var prefixArray = new Array("serv_","prod_");

var isColor = false;
function addItem(resultObj, table, quantItem, prefix, id, nome, desc, maxQuant, valor,unidMedida, color1, color2, color3){
	
	if(document.getElementById(prefix+id) == null){	
		
		
		var tb = document.getElementById(table);
		var tmp;
		
		//linha do item
		var nr = tb.insertRow(-1);
		nr.setAttribute("id",prefix+id);		
		
		
		//hide
		tmp = nr.insertCell(-1);		
		tmp.style.textAlign = "center";
		tmp.innerHTML = '';
		
		//delete
		tmp = nr.insertCell(-1);
		tmp.rowSpan = "2";
		tmp.style.textAlign = "center";
		tmp.innerHTML = '<a href="javascript:void(0)" onclick="removeItem(\''+prefix+'\',\''+id+'\',\''+quantItem+'\', false, \'\')" title="Remover" style="margin-top: 0px">'+img_del+'</a>';
								
		//nome do item
		tmp = nr.insertCell(-1);
		//tmp.style.width = "230px";
		tmp.innerHTML = '<font style="font-size: 12px">'+nome+'</font>';
		
		//Quantidade
		tmp = nr.insertCell(-1);		
		tmp.align = "left";
		tmp.rowSpan = "2";
		tmp.style.paddingTop = "4px";		
		tmp.innerHTML = '<input type="text" name="'+prefix+'quant_'+id+'" value="1" onkeypress="return isNum(event,\'int\')" onkeyup="checkQuant(this,'+maxQuant+',\''+prefix+'\', true)" onblur="checkQuantAgain(this,'+maxQuant+',\''+prefix+'\')"  style="width: 40px; text-align:right"/> '+unidMedida;
		
		
		
		
		//Valor
		tmp = nr.insertCell(-1);		
		tmp.align = "left";
		tmp.rowSpan = "2";
		tmp.style.paddingTop = "4px";
		
		tmp_valor = valor.replace(".","");
		tmp_valor = tmp_valor.replace(",",".");
		//alert(tmp_valor);
		tmp.innerHTML = '<input type="hidden" name="'+prefix+'valor_'+id+'" value="'+tmp_valor+'"/>R$ '+valor+'&nbsp;&nbsp;';
		//ID do produto
		tmp.innerHTML += '<input type="hidden" name="'+prefix+'id_'+id+'" value="'+id+'"/>';
		
		
		
		//linha de descrição
		var nr = tb.insertRow(-1);
		nr.setAttribute("id",prefix+"desc_"+id);
		
		//descrição
		tmp = nr.insertCell(-1);
		tmp.colSpan = "2";
		tmp.innerHTML = "&nbsp;";
		tmp = nr.insertCell(-1);
		//tmp.colSpan = "3";
		tmp.innerHTML = '<font style="font-size: 9px">'+desc+'</font>';
		
		//linha divisória do item
		var nr = tb.insertRow(-1);
		nr.setAttribute("id",prefix+"div_"+id);
		
		//divisão
		tmp = nr.insertCell(-1);
		tmp.colSpan = "6";
		tmp.style.textAlign = "center";
		tmp.innerHTML = '<hr style="bottom-border: dashed 1px; color:'+color2+'" />';
		
		//tmp.scrollIntoView();
		
		//alterando total de itens adicionados
		var quant = document.getElementById(quantItem);
		quant.innerHTML = parseInt(quant.innerHTML)+1;	
		
		calcTotal(prefixArray);
	}else{
		alert("Item já adicionado.");	
	}
	
}


function removeItem(prefix, id, quantItem, delFromTable, os_id){
	if(confirm("Deseja realmente remover este item?")){
		var itemObj = document.getElementById(prefix+id);
		itemObj.parentNode.removeChild(itemObj);
		var itemObj = document.getElementById(prefix+"desc_"+id);
		itemObj.parentNode.removeChild(itemObj);
		var itemObj = document.getElementById(prefix+"div_"+id);
		itemObj.parentNode.removeChild(itemObj);
		
		//alterando total de itens adicionados
		var quant = document.getElementById(quantItem);
		quant.innerHTML = parseInt(quant.innerHTML)-1;
		
		if(delFromTable){
			getXMLHTTP();	
			var reCache = new Date().getTime();			
			xmlhttp.open("GET","inc/save/saveos.php?cmd="+prefix+"del&os_id="+os_id+"&item_id="+id+"&rc="+reCache);
			
			xmlhttp.onreadystatechange = function(){
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200){			
					
				}else{
					
				}
			}	
			xmlhttp.send(null);	
		}
		
		calcTotal(prefixArray);
		
	}
}


function checkIndividualQuant(field, maxQuant) {	
	if(field.value > maxQuant){
		alert("O valor máximo permitido é "+maxQuant);
		field.value = "";
		
		field.focus();		
	}	
}

//verifica se a quantidade digitada esta dentro do máximo permitido
function checkQuant(field, maxQuant, prefix, needToRecalc) {	
	if(field.value > maxQuant){
		alert("O valor máximo permitido é "+maxQuant);
		field.value = "";
		field.style.backgroundColor = "#FFFF99";
		field.focus();		
	}	
	if(needToRecalc){
		calcTotal(prefixArray);
	}
}


function checkQuantAgain(field, maxQuant, prefix) {	
	if(field.value == ""){
		field.value = "1";
	}else if(field.value == "0"){
		field.style.backgroundColor = "#FFFF99";
	}else{
		field.style.backgroundColor = "";
	}
	
	calcTotal(prefixArray);
}


function calcSubTotal(prefix){
	var fm = document.forms['os_form'];	
	var tmpSubTotal = 0;
	for(var i = 0; i < fm.elements.length; i++){
		if(fm.elements[i].name.indexOf(prefix+"valor_") > -1){
			valor = fm.elements[i].value;
			var itemId = fm.elements[i].name.replace(prefix+"valor_","");
			var elem = prefix+"quant_"+itemId;			
			quant = fm.elements[elem].value;			
			/*trueValue = valor.replace(".","");
			trueValue = trueValue.replace(",",".");*/
			tmpSubTotal += valor*parseInt(quant);			
		}	
	}
	
	if(tmpSubTotal == null){
		tmpSubTotal = 0;		
	}
	
	var subtotal = document.getElementById(prefix+'subtotal');	
	subtotal.innerHTML = numberFormat(tmpSubTotal,2);	
	
	return tmpSubTotal;
}

function calcTotal(vetor){
	var tmpTotal = 0;
	for(var i = 0; i < vetor.length; i++){
		
		tmpTotal += calcSubTotal(vetor[i]);	
	}
	
	var total = document.getElementById('total');
	total.innerHTML = numberFormat(tmpTotal,2);
}

//ORÇAMENTO - end ==============================================================


//DOCUMENT CREATION - begin ======================================================

function createDocFromList(processFile, ot ,ord, srch, fileName, tecID, tecName, tecTipo, resultObj){
	getXMLHTTP();
	
	showRequests();
	
	var reCache = new Date().getTime();
	fileName = reCache+"_"+tecID+"_"+fileName;	
	
	xmlhttp.open("GET",processFile+"?ot="+ot+
				 	   "&fn="+fileName+
					   "&tid="+tecID+
					   "&tn="+tecName+
					   "&tt="+tecTipo+
					   "&ord="+ord+
					   srch+
					   "&rc="+reCache);
	
	xmlhttp.onreadystatechange = function(){
		
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){			
			hideRequests();
			var dl = document.location.toString();
			var lastbar = dl.lastIndexOf("/");
			dl = dl.substring(0,lastbar);
			document.location = dl+"/doc/"+fileName;			
		}else{
			document.getElementById(resultObj).innerHTML = "<div id=\"loadbox\"><img src=\"img/loader.gif\"></div>";
		}
	}			
	xmlhttp.send(null);
}

function createDoc(processFile,fileName, cmd, id, tecID, tecName, tecTipo, resultObj){
	getXMLHTTP();
	
	showRequests();
	
	var reCache = new Date().getTime();
	fileName = reCache+"_"+tecID+"_"+fileName;	
	
	xmlhttp.open("GET",processFile+"?cmd="+cmd+
				 	   "&fn="+fileName+
				 	   "&id="+id+
					   "&tid="+tecID+
					   "&tn="+tecName+
					   "&tt="+tecTipo+
					   "&rc="+reCache);
	
	xmlhttp.onreadystatechange = function(){
		
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			hideRequests();
			var dl = document.location.toString();
			var lastbar = dl.lastIndexOf("/");
			dl = dl.substring(0,lastbar);
			document.location = dl+"/doc/"+fileName;
			
		}else{
			document.getElementById(resultObj).innerHTML = "<div id=\"loadbox\"><img src=\"img/loader.gif\"></div>";
		}
	}			
	xmlhttp.send(null);
}

//DOCUMENT CREATE - end ======================================================

//Ajax END=========================================================

function trFocus(obj,newColor){	
	obj.style.cursor = "default";	
	obj.bgColor = newColor;
}
function trBlur(obj,orgColor){
	obj.bgColor = orgColor;	
}

function tryToSubmit(formName){
	var fn = document.forms[formName];
	
	//para forms que lidam com alteracao de senha
	var passFld;
	var passFlag = false;
	
	for(var i = 0; i < fn.elements.length; i++){		
		if(fn.elements[i].name.indexOf("obrg_") > -1){						
			if(fn.elements[i].value == ""){				
				alert("Preenchimento de campo obrigatório.");
				fn.elements[i].style.backgroundColor = "#FFFF99";
				try{
					fn.elements[i].select();
				}catch(e){
					fn.elements[i].focus();
				}
				return 0;
			}
			
			if(fn.elements[i].value.indexOf('"') > -1){
				alert("Caracter inválido.");
				//fn.elements[i].focus();
				fn.elements[i].style.backgroundColor = "#FFFF99";
				fn.elements[i].select();
				return 0;
			}
			
			if(fn.elements[i].name.indexOf("_email") > -1){
				var arroba = fn.elements[i].value.indexOf("@");
				var ponto = fn.elements[i].value.lastIndexOf(".");
				if(arroba <= 0 || ponto < arroba){
					alert("Formato de e-mail inválido.");
					//fn.elements[i].focus();
					fn.elements[i].style.backgroundColor = "#FFFF99";
					fn.elements[i].select();
					return 0;
				}
			}
			
			if(fn.elements[i].name.indexOf("_npass") > -1 && passFlag == false){
				passFld = fn.elements[i];
				passFlag = true;
			}
			
			if(fn.elements[i].name.indexOf("_rnpass") > -1){
				if(fn.elements[i].value != passFld.value){
					alert("Senhas não conferem.");
					fn.elements[i].style.backgroundColor = "#FFFF99";
					fn.elements[i].select();
					return 0;
				}
			}			
		}
		
		if(fn.elements[i].name.indexOf("_email") > -1 && fn.elements[i].value != ""){
			var arroba = fn.elements[i].value.indexOf("@");
			var ponto = fn.elements[i].value.lastIndexOf(".");
			if(arroba <= 0 || ponto < arroba){
				alert("Formato de e-mail inválido.");
				//fn.elements[i].focus();
				fn.elements[i].style.backgroundColor = "#FFFF99";
				fn.elements[i].select();
				return 0;
			}
		}
		
		
		fn.elements[i].style.backgroundColor = "";
		
	}
	return true;
}

function isNum(evt,t) {
	var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);	
	if(t != "int"){
		if (charCode > 31 && 
			charCode != 37 && 
			charCode != 38 && 
			charCode != 39 && 
			charCode != 40 && 
			charCode != 44 && 			 
			charCode != 46 &&(charCode < 48|| charCode > 57 )) {
			alert("Digite apenas números reais");
			return false;
		}
	}else{
		if (charCode > 31 &&
			charCode != 37 && 
			charCode != 38 && 
			charCode != 39 && 			
			charCode != 40 &&(charCode < 48|| charCode > 57 )) {
			alert("Digite apenas números inteiros");
			return false;
		}
	}
	return true;
}

function checkZeroFill(field){
	if(field.value == "0"){		
		field.value = "1";
	}
}

function sendForm(formName){
	if(tryToSubmit(formName)){
		var fn = document.forms[formName];
		fn.submit();
	}
}

//login.php
function setFocus(formName, fld){
	try{
		var login = document.forms[formName].elements[fld];
		login.focus();
	}catch(e){
		
	}
	
	try{
		var pass = document.forms[formName].elements['obrg_npass'];
		pass.focus();
	}catch(e){
		
	}
	
	try{
		var email = document.forms[formName].elements['obrg_email'];
		email.focus();
	}catch(e){
		
	}
}

function logar(obj){
	obj.value = "entrando...";
	obj.style.width = "70px";
	obj.style.marginLeft = "137px";
	obj.disabled = true;
	document.forms['loginForm'].submit();
}

function changePass(obj){
	var senha = document.forms['loginForm'].elements['pass'];
	var n_senha = document.forms['loginForm'].elements['n_pass'];	
	
	if (senha.value == n_senha.value){	
		obj.value = "entrando...";
		obj.style.width = "70px";
		obj.style.marginLeft = "137px";
		obj.disabled = true;
		document.forms['loginForm'].submit();
	}else{
		alert('Campos de senha não combinam, por favor confirmar as senhas.');
		
	}	
}
//******************************************

//Verifica se a data está dentro do limite permitido
function verifyDate(field, minRange){
	var tmpNum = field.value;
	tmpNum = tmpNum.split("/");
	tmpNum = parseInt(tmpNum[2]+""+tmpNum[1]+""+tmpNum[0]);
	
	var tmpMinRange = minRange.split("/");
	tmpMinRange = parseInt(tmpMinRange[2]+""+tmpMinRange[1]+""+tmpMinRange[0]);
	
	if(tmpNum<tmpMinRange){
		alert("A data deve ser superior ou igual a "+minRange);
		field.value = "";
		field.style.backgroundColor = "#FFFF99";
	}
}

function checkIsNan(obj){	
	if(isNaN(obj.value)){
		obj.value = "";		
		obj.focus();
	}
}

function checkMaxLength(formName,obj,nextObj,maxLength){
	if(navigator.appName.toLowerCase().indexOf("microsoft") < 0){
			maxLength--;
	}
	if(obj.value.length == maxLength){
		document.forms[formName].elements[nextObj].focus();
	}
}
//******************************************

//permissoes de usuario
var isSelectAll = true;
function selectAll(formName,id,tp,obj){
	var img = "";
	var form = document.forms[formName];
	for(var i = 0; i < form.elements.length; i++){
		if(form.elements[i].type == tp && form.elements[i].name.indexOf(id) > -1){
			if(!form.elements[i].checked){
				isSelectAll = true;
			}			
		}
	}
	for(var i = 0; i < form.elements.length; i++){
		if(form.elements[i].type == tp && form.elements[i].name.indexOf(id) > -1){
			if(isSelectAll){
				img = "img/btn/shield.png";
				form.elements[i].checked = true;				
			}else{
				img = "img/btn/shield_d.png";
				form.elements[i].checked = false;
			}			
		}
	}
	
	document.getElementById(obj).src = img;
	isSelectAll = !isSelectAll;
}