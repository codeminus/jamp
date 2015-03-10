<?php
if($_SESSION["tec_cad_prod"] == "N"){
?>
	<script>document.location = "index.php?inc=los&ot=5&msg=pprod";</script>
<?php
	exit;
}

if($_SESSION["order"] == "DESC"){
	$_SESSION["order"] = "ASC";
}else{
	$_SESSION["order"] = "DESC";
}


$orderType[0][0] = "mov_entr_prod_id";
$orderType[1][0] = "classprod_nome";
$orderType[2][0] = "prod_nome";
$orderType[3][0] = "prod_fabricante";
$orderType[4][0] = "prod_modelo";
$orderType[5][0] = "forn_nome";
$orderType[6][0] = "mov_entr_num_nf";
$orderType[7][0] = "mov_entr_nf_dt_emissao";
$orderType[8][0] = "mov_entr_vcusto";
$orderType[9][0] = "mov_entr_quant_entrada";
$orderType[10][0] = "mov_entr_id";

if(isset($_GET["ord"])){
	if($_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){
		$_SESSION[$orderType[$_GET["ot"]][0]] = "DESC";
	}else{
		$_SESSION[$orderType[$_GET["ot"]][0]] = "ASC";
	}
}


$sql = "SELECT
			mov_entr_id,
			mov_entr_prod_id,
			classprod_nome,
			prod_nome,
			prod_fabricante,
			prod_modelo,
			prod_unid_medida,
			forn_nome,
			mov_entr_num_nf,
			mov_entr_nf_dt_emissao,
			mov_entr_vcusto,
			mov_entr_quant_entrada
		FROM produto
		JOIN classprod ON classprod_id=prod_classprod_id
		JOIN mov_entrada ON mov_entr_prod_id=prod_id
		JOIN fornecedor ON mov_entr_forn_id=forn_id";



$searchStr = "";
if($_POST["cmd"] == "srch"){	
	
	$fields = array_keys($_POST);
	$values = array_values($_POST);
	
	for($i =1; $i < (count($_POST)); $i++){
		if($_POST[$fields[$i]] !=""){
			$sql .= " WHERE ";
			break;
		}
	}
	
	$isFirst = true;
	$searchStr = "&cmd=".$_POST["cmd"];
	for($i =1; $i < (count($_POST)); $i++){
		if($_POST[$fields[$i]] !=""){
			if($isFirst){
				if($fields[$i] == "mov_entr_nf_dt_emissao"){
					
					$dt = split("/",$values[$i]);
					
					$sql .= $fields[$i]."=".mktime(0,0,0,$dt[1],$dt[0],$dt[2]);
				}else{
					
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				}
				$isFirst = false;
			}else{
				if($fields[$i] == "mov_entr_nf_dt_emissao"){
					$dt = split("/",$values[$i]);
					
					$sql .= " AND ".$fields[$i]."=".mktime(0,0,0,$dt[1],$dt[0],$dt[2]);
				}else{
					$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
				}
			}
			$searchStr	.= "&".$fields[$i]."=".$values[$i];	
		}
	}				
}elseif($_GET["cmd"] == "srch"){
	
	$fields = array_keys($_GET);
	$values = array_values($_GET);
	(strpos($_SERVER['REQUEST_URI'],"ord") > -1)? $begin = 4: $begin = 3;
	for($i = $begin; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !=""){
			$sql .= " WHERE ";
			break;
		}
	}
	
	$isFirst = true;
	$searchStr = "&cmd=".$_GET["cmd"];
	for($i = $begin; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !="" && $fields[$i] != "msg"){
			if($isFirst){
				if($fields[$i] == "mov_entr_nf_dt_emissao"){
					
					$dt = split("/",$values[$i]);
					
					$sql .= $fields[$i]."=".mktime(0,0,0,$dt[1],$dt[0],$dt[2]);
				}else{
					
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				}
				$isFirst = false;
			}else{
				if($fields[$i] == "mov_entr_nf_dt_emissao"){
					$dt = split("/",$values[$i]);
					
					$sql .= " AND ".$fields[$i]."=".mktime(0,0,0,$dt[1],$dt[0],$dt[2]);
				}else{
					$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
				}
			}
			$searchStr	.= "&".$fields[$i]."=".$values[$i];
		}
	}				
}

$_SESSION["srch"] = $searchStr;


$sql .= " ORDER BY ".$orderType[$_GET["ot"]][0]." ".$_SESSION[$orderType[$_GET["ot"]][0]];


if(isset($_GET["pg"])){
	$pg = $_GET["pg"];
}else{
	$pg = 1;
}

$usu_sql = "SELECT usu_reg_per_page FROM usuario WHERE usu_id=".$_SESSION["tec_id"];
$sqlQuery = mysql_query($usu_sql);
$usu_rs = mysql_fetch_object($sqlQuery);

$pm = new PagingMaker($sql, $usu_rs->usu_reg_per_page, $_GET["pg"]);

?>
<div id="conteudo" align="center">
	<div>
    	<div align="left" style=" float:left">
            <?php require("inc/_menu.php")?>
        </div>    	
    	<div align="right" style="padding: 2px; float: right">
        	<?php require("inc/_pmenu.php")?>        	
        </div>        
        <div id="sform" style="display:none; clear:both">
    	<form name="search_form" action="index.php?inc=lmoventr&amp;ot=0"  method="post" style="display:inline">
        <input type="hidden" name="cmd" value="srch" />
		<table cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 4px">			
            <tr class="fldField">
            	<td>
                	N&uacute;mero da nota fiscal:<br/>
                    <input type="text" name="mov_entr_num_nf" value="<?php echo $_POST["mov_entr_num_nf"].$_GET["mov_entr_num_nf"]?>" style="width:120px" />
				</td>
                <td>
                	Data de emiss&atilde;o:<br/>
                    <input type="text" name="mov_entr_nf_dt_emissao" value="<?php echo $_POST["mov_entr_nf_dt_emissao"].$_GET["mov_entr_nf_dt_emissao"]?>" 
                    	   style="width:120px; text-align:center" />
                    <a href="javascript:void(0)" onClick="displayCalendar(document.forms['search_form'].mov_entr_nf_dt_emissao,'dd/mm/yyyy',this)" title="Adicionar data">
                        <img src="img/btn/add_calendario.png" width="16" height="16" border="0" style="margin-bottom: -3px">
                    </a>
				</td>
				<td>
                	C&oacute;digo do produto:<br/>
                    <input type="text" name="mov_entr_prod_id" value="<?php echo $_POST["mov_entr_prod_id"].$_GET["mov_entr_prod_id"]?>" 
                    	   style="width:120px" />
				</td>
				<td>
                	<?php 
						$sql = "SELECT * FROM classprod WHERE classprod_status='A' ORDER BY classprod_nome ASC";
						$sqlQuery = mysql_query($sql);
					?>	
                    	Classifica&ccedil;&atilde;o:<br/>
						<select name="classprod_id" style="width: 120px">
                        	<option value=""></option>
					<?php    
						while($rs = mysql_fetch_object($sqlQuery)){
							echo("<option value=\"".$rs->classprod_id."\">".$rs->classprod_nome."</option>");
						}
					?>
						</select>
				</td>				
			</tr>
            <tr class="fldField">            	
				<td>
                	Nome do produto:<br/>
                    <input type="text" name="prod_nome" value="<?php echo $_POST["prod_nome"].$_GET["prod_nome"]?>" style="width:120px" />
				</td>
                <td>
                	Modelo:<br/>
                    <input type="text" name="prod_modelo" value="<?php echo $_POST["prod_modelo"].$_GET["prod_modelo"]?>" style="width:120px" />
				</td>
                <td>
                	Fabricante:<br/>
                    <input type="text" name="prod_fabricante" value="<?php echo $_POST["prod_fabricante"].$_GET["prod_fabricante"]?>" style="width:120px" />
				</td>                
                <td>
                	Fornecedor:<br/>
                    <input type="text" name="forn_nome" value="<?php echo $_POST["forn_nome"].$_GET["forn_nome"]?>" style="width:120px" />
				</td>                
                <td valign="bottom">
                	<input type="submit" value="pesquisar" 
                    	   style="background-color: <?php echo $colors[0]?>; color: <?php echo $colors[1]?>" />
                </td>
			</tr>
		</table>
        </form>
        </div>
        <div style="clear:both"></div>
	</div>
    <div id="session" >
    	<div id="sessionlabel">Movimenta&ccedil;&otilde;es de entrada de produto</div>
        <div id="sessionmenu">
        	<?php
				$sql = "SELECT mov_susoint_id FROM mov_saida_usointerno";
				$sqlQuery = mysql_query($sql);
				$num_mov_susoint = mysql_num_rows($sqlQuery);
				
				$sql = "SELECT mov_sos_id FROM mov_saida_os";
				$sqlQuery = mysql_query($sql);
				$num_mov_sos = mysql_num_rows($sqlQuery);
				
				if($_SESSION["tec_cad_prod"] == "S"){
					if($num_mov_susoint > 0){
			?>
            	<a href="index.php?inc=lmovsusoint&amp;ot=2">
                    <img src="img/btn/mov_out.png" title="Movimenta&ccedil;&otilde;es de sa&iacute;da por uso interno" border="0" />
                </a>
            <?php 	}else{?>
                    <img src="img/btn/mov_out_d.png" title="Nenhuma movimenta&ccedil;&atilde;o de sa&iacute;da por uso interno cadastrada" border="0" />                
            <?php 	}
					if($num_mov_sos > 0){
			?>
            	<a href="index.php?inc=lmovsos&amp;ot=3">
                    <img src="img/btn/mov_out_os.png" title="Movimenta&ccedil;&otilde;es de sa&iacute;da por ordem de servi&ccedil;o" border="0" />
                </a>
            <?php }else{?>            	
                    <img src="img/btn/mov_out_os_d.png" title="Nenhuma movimenta&ccedil;&atilde;o de sa&iacute;da por ordem de servi&ccedil;o cadastrada" border="0" />                
            <?php 	}
				}
			?>
        	<a href="javascript:showHide('sform','sinal')">
            	<span id="sinal" style="display:none"></span><img src="img/btn/pesquisar.png" title="pesquisar" border="0" />
            </a>
            <a href="index.php?inc=lmoventr&amp;ot=0">
            	<img src="img/btn/todos.png" title="Mostrar todos" border="0" />
            </a>
            <?php (isset($_GET["ot"])? $ordem = $_GET["ot"]: $ordem = 0)?>
            <?php if($pm->getNumRows() > 0){?>
            <a href="javascript:createDocFromList('inc/doc/doc_moventr.php','<?php echo $ordem?>','<?php echo $_SESSION[$orderType[$_GET["ot"]][0]]?>','<?php echo $searchStr?>','mov_entrada.doc','<?php echo $_SESSION["tec_id"]?>','<?php echo $_SESSION["tec_nome"]?>','<?php echo $_SESSION["tec_tipo"]?>','ajaxContent')">
            	<img src="img/btn/create_rel.png" title="Gerar documento" border="0" />
            </a>
            <?php }else{?>
            	<img src="img/btn/create_rel_d.png" title="Sem conte&uacute;do para gerar documento" border="0" />
            <?php }?>
        </div>
        <div style="clear:both"></div>
    </div>
	<div id="headline1" style="background-color: <?php echo $colors[1]?>">
    	<div style="float: left"><?php echo $pm->getNumRows()?> movimenta&ccedil;&otilde;es em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=lmoventr&ot=".$_GET["ot"].$searchStr,
									  "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
			?>
        </div>
        <div style="clear:both"></div>
    </div>
    <table id="listing" width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
    	<tr style="background-image:url(img/bar.jpg)" height="27">
        	<td style="background-image:url(img/bar_side_l.jpg); background-repeat: no-repeat;" width="15">&nbsp;</td>
            <td align="center">
            	<a href="index.php?inc=lmoventr&amp;ot=10&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por c&oacute;digo da movimenta&ccedil;&atilde;o">
                   Cod. mov.
                   <?php 
						if($_GET["ot"] == 10 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 10 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
        	<td align="center">
            	<a href="index.php?inc=lmoventr&amp;ot=0&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por c&oacute;digo do produto">
                   Cod. produto
                   <?php 
						if($_GET["ot"] == 0 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 0 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="left">
            	<a href="index.php?inc=lmoventr&amp;ot=1&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por nome da classifica&ccedil;&atilde;o">
                   Classifica&ccedil;&atilde;o
                   <?php 
						if($_GET["ot"] == 1 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 1 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="left">
            	<a href="index.php?inc=lmoventr&amp;ot=2&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por nome do produto">
                   Produto
                   <?php 
						if($_GET["ot"] == 2 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 2 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="left">
            	<a href="index.php?inc=lmoventr&amp;ot=4&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por modelo">
                   Modelo
                   <?php 
						if($_GET["ot"] == 4 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 4 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>            
            <td align="left">
            	<a href="index.php?inc=lmoventr&amp;ot=3&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por fabricante">
                   Fabricante
                   <?php 
						if($_GET["ot"] == 3 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 3 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>            
            <td align="left">
            	<a href="index.php?inc=lmoventr&amp;ot=5&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por fornecedor">
                   Fornecedor
                   <?php 
						if($_GET["ot"] == 5 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 5 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center">
            	<a href="index.php?inc=lmoventr&amp;ot=6&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por n&uacute;mero de nota fiscal">
                   Nota Fiscal
                   <?php 
						if($_GET["ot"] == 6 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 6 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="105px">
            	<a href="index.php?inc=lmoventr&amp;ot=7&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por data de emiss&atilde;o da nota fiscal">
                   Dt. Emiss&atilde;o
                   <?php 
						if($_GET["ot"] == 7 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 7 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="105px">
            	<a href="index.php?inc=lmoventr&amp;ot=8&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por valor de custo">
                   Custo unid.(R$)
                   <?php 
						if($_GET["ot"] == 8 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 8 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center" width="105px">
            	<a href="index.php?inc=lmoventr&amp;ot=9&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por quantidade de entrada">
                   Quant. Entrada
                   <?php 
						if($_GET["ot"] == 9 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 9 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="center">A&ccedil;&otilde;es</td>
            <td style="background-image:url(img/bar_side_r.jpg); background-repeat: no-repeat;" width="14">&nbsp;</td>
        </tr>        
        <?php 	
		
		if($pm->getNumRows() == 0){
			echo "<tr><td colspan=\"14\" style=\"padding: 30px\" align=\"center\">Nenhuma movimenta&ccedil;&atilde;o encontrada.</td></tr>";
		}else{
			$isColor = true;
			while($rs = $pm->getResultObject()){
				if($isColor){
					$color = $colors[3];
				}else{
					$color = $colors[1];
				}		
			?>                
			<tr bgcolor="<?php echo $color?>"             	
            	onmouseover="trFocus(this,'#FFFF99')" 
				onmouseout="trBlur(this,'<?php echo $color?>')">
				<td>&nbsp;</td>
                <td align="center"><font style="font-size: 7pt"><?php echo $rs->mov_entr_id?></font></td>
				<td align="center"><font style="font-size: 7pt"><?php echo $rs->mov_entr_prod_id?></font></td>                
				<td align="left">
                	<span title="<?php echo $rs->classprod_nome?>">
						<?php 
							echo substr($rs->classprod_nome,0,15);
							if(strlen($rs->classprod_nome) > 15){
								echo "...";
							}
						?>
                    </span>
                </td>				
				<td align="left">
                	<span title="<?php echo $rs->prod_nome?>">
						<?php 
							echo substr($rs->prod_nome,0,20);
							if(strlen($rs->prod_nome) > 20){
								echo "...";
							}
						?>
                   	</span>
                </td>
                <td align="left">
                	<span title="<?php echo $rs->prod_modelo?>">
						<?php 
							echo substr($rs->prod_modelo,0,15);
							if(strlen($rs->prod_modelo) > 15){
								echo "...";
							}
						?>
                   	</span>
                </td>
                <td align="left">
                	<span title="<?php echo $rs->prod_fabricante?>">
						<?php 
							echo substr($rs->prod_fabricante,0,15);
							if(strlen($rs->prod_fabricante) > 15){
								echo "...";
							}
						?>
                   	</span>
                </td>                
                <td align="left">
                	<span title="<?php echo $rs->forn_nome?>">
						<?php 
							echo substr($rs->forn_nome,0,15);
							if(strlen($rs->forn_nome) > 15){
								echo "...";
							}
						?>
                   	</span>
                </td>
                <td align="center"><?php echo $rs->mov_entr_num_nf?></td>
                <td align="center"><?php echo date("d-m-y",$rs->mov_entr_nf_dt_emissao)?></td>
                <td align="center"><?php echo number_format($rs->mov_entr_vcusto,"2",",",".")?></td>
                <td align="center"><?php echo $rs->mov_entr_quant_entrada." ".$rs->prod_unid_medida?></td>
				<td align="center">
					<a href="javascript:ajaxRequest('inc/view/view_moventr.php?mov_entr_id=<?php echo $rs->mov_entr_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Visualizar">
						<img src="img/btn/browse.png" width="16" height="16" border="0" />
					</a>                  
				</td>
				<td>&nbsp;</td>
			</tr>	
			<?php					
				$isColor = !$isColor;					
			}
			
		}
	?>
    </table>
    <div id="bottomline1" style="background-color: <?php echo $colors[1]?>">
    	<div style="float: left"><?php echo $pm->getNumRows()?> movimenta&ccedil;&otilde;es em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=lmoventr&ot=".$_GET["ot"].$searchStr,
									  "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
			?>
        </div>
        <div style="clear:both"></div>
    </div>
</div>