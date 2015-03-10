<?php require("_colors.php"); ?>
<style type="text/css">
<!--
*{
	font-family: Tahoma, Sans;
	font-size: 8pt;
	outline: none;
}
body{
	margin: 0px;
	padding: 0px;	
	color: <?php echo $colors[0]?>;
	
	/*definido para que o height da div para disabilitar o conteudo tenha o container body como referência*/
	height: 100%;
}
a, a:visited{
	text-decoration: none;
	color: <?php echo $colors[0]?>;
}

#disable{
	position: absolute;	
	width: 100%;
	height: 100%;
	background-color: #FFFFFF;
	z-index:1;
	
	/*tratamento para transparência*/
	opacity:0.65;
	-moz-opacity: 0.65;
	filter: alpha(opacity=65);
}

#ajaxContent{
	position: absolute;
	width: 100%;
	height: 100%;
	z-index:2;
	display: none;
}

#loadbox{
	background-color: #FFFFFF;
	position: absolute;
	width: 250px; height: 36px;
	top: 50%; left: 50%;
	margin-left: -125px; margin-top: -18px;
	border: solid 2px <?php echo $colors[2]?>;
	text-align: center;
	padding-top: 2px;
}

#msg{
	color: <?php echo $colors[0]?>;
	background-color: <?php echo $colors[5]?>;	
	padding: 2px;
	margin-top: 2px;
	text-align:center
}
#all{
	min-width: 770px;
	padding: 2px;	
}

#top{
	text-align: center;
}
#middle{
	height: 200px;
	text-align:center;
}
#conteudo{
	margin: 2px;
}

#topline{
	background-image:url(img/topline.jpg); 
	background-repeat: repeat-x; 
	height: 20px;
	color: <?php echo $colors[1]?>;	
	padding: 3px;
	padding-left: 4px;
	text-align:left;
}

#session{
	background-color: <?php echo $colors[3]?>;
	margin: 1px;
	border-left: solid 6px <?php echo $colors[0]?>
}
#sessionlabel{
	font-size: 12pt;
	padding-left: 4px;
	letter-spacing: 1px;
	float: left;
}
#sessionmenu{
	margin-top: 1px;
	margin-right: 1px;
	float: right;
}

#headline{
	background-color: <?php echo $colors[0]?>;
	color: <?php echo $colors[1]?>;
	padding: 2px;
	padding-left: 4px;
	text-align:left;
}


.heading{
	background-color: <?php echo $colors[2]?>;
	color: <?php echo $colors[0]?>;
	padding: 2px;
	padding-left: 4px;
}

#headline1{
	background-color: <?php echo $colors[2]?>;
	color: <?php echo $colors[0]?>;
	padding: 2px;
	padding-left: 4px;
	text-align:left;
}

#indexBottomline{
	background-image:url(img/topline.jpg); 
	background-repeat: repeat-x; 
	height: 20px;
	color: <?php echo $colors[1]?>;
	padding: 3px;
	padding-left: 4px;
	text-align: right;
}

#bottomline{
	background-color: <?php echo $colors[0]?>;
	color: <?php echo $colors[1]?>;
	padding: 2px;
	text-align: right;
}

#bottomline1{
	background-color: <?php echo $colors[2]?>;
	color: <?php echo $colors[0]?>;
	padding: 2px;
	text-align: right;
}
input, textarea, select{
	border: solid 1px <?php echo $colors[2] ?>;	
	background-color: #FFF;
	color: <?php echo $colors[0] ?>
}

.standardBtn{
	background-color: <?php echo $colors[0]?>; 
	color: <?php echo $colors[1]?>;	
	width: 70px;
}
.saveInfoBtn{
	background-color: <?php echo $colors[0]?>; 
	color: <?php echo $colors[1]?>;
	margin-top: 2px; 
	margin-bottom: 4px;
	width: 150px;
}

.supLabel{
	font-weight: bold;
}
.supField td{
	padding-left: 10px;
}

.fldLabel{
	
	font-size: 6pt;
}
.fldField td{
	padding-left: 10px;
	text-align: left;
}

.alignleft{
	text-align: left;
}

.alignleftDotted tr td{
	border-bottom: dashed 1px <?php echo $colors[2]?>;
}


#cliview{
	width: 460px;
	background-color: #FFFFFF;		
	margin-top: 10%;
	border: solid 2px <?php echo $colors[2]?>;
}

#listing tr td{
}

.disabledLine, .disabledLine a, .disabledLine a:visited{
	color: <?php echo $colors[4]?>;
}

.dashedBottom0{
	border-bottom: dashed 1px <?php echo $colors[0]?>;
}
.dashedBottom2{
	border-bottom: dashed 1px <?php echo $colors[2]?>;
}

/*Pendência*/
.act *, .act a, .act a:visited {
	color: #00CC00;
}
.ra *, .ra a, .ra a:visited{
	color: <?php echo $colors[4]?>;
}

.rns *, .rns a, .rns a:visited{
	color: #FF0000;
}

/*dsearch*/
#result *{
	font-size: 7pt;
}

font b{
	font-size: 9px;	
}

/*Botão - ancora*/

.ancorButton{
	display: block;
	padding: 4px;
	margin: 4px;
	width: 300px;
	background-color: <?php echo $colors[2]?>;
}
.ancorButton a{
	width: 300px;	
}

-->
</style>