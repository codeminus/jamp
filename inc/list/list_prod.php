<?php 
if($_SESSION["tec_tipo"] == "C"){
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

#require("_lib.php");
# $orderType[$_GET["ot"]][0] :: nome do campo
# $orderType[$_GET["ot"]][1] :: tipo de ordenação
						
$orderType[0][0] = "prod_id";
$orderType[1][0] = "classprod_nome";
$orderType[2][0] = "prod_nome";
$orderType[3][0] = "prod_modelo";
$orderType[4][0] = "prod_fabricante";
$orderType[5][0] = "prod_localizacao";
$orderType[6][0] = "prod_vvalor";
$orderType[7][0] = "prod_min_quant";
$orderType[8][0] = "prod_dt_cad";

if(isset($_GET["ord"])){
	if($_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){
		$_SESSION[$orderType[$_GET["ot"]][0]] = "DESC";
	}else{
		$_SESSION[$orderType[$_GET["ot"]][0]] = "ASC";
	}
}

$sql = "SELECT * FROM produto 
		JOIN classprod ON prod_classprod_id=classprod_id";

$limitation = " AND prod_status<>'E'";

$searchStr = "";
if($_POST["cmd"] == "srch"){
	
	$fields = array_keys($_POST);
	$values = array_values($_POST);
	
	for($i = 1; $i < (count($_POST)); $i++){
		
		if($_POST[$fields[$i]] != "" && $fields[$i] != "ating_min"){
			$sql .= " WHERE ";
			break;			
		}
	}

	
	$isFirst = true;
	$searchStr = "&cmd=".$_POST["cmd"];
	for($i =1; $i < (count($_POST)); $i++){
		if($_POST[$fields[$i]] !=""){
			$notEmpty = true;
			if($isFirst){
				if($fields[$i] == "ating_min" && $values[$i] == "A"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													 where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) <= prod_min_quant";
				}elseif($fields[$i] == "ating_min" && $values[$i] == "N"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) > prod_min_quant";
				}else{
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				}
				$isFirst = false;
			}else{
				if($fields[$i] == "ating_min" && $values[$i] == "A"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) <= prod_min_quant";
				}elseif($fields[$i] == "ating_min" && $values[$i] == "N"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) > prod_min_quant";
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
		if($_GET[$fields[$i]] !="" && $fields[$i] != "ating_min"){
			$sql .= " WHERE ";
			break;
		}
	}
	$isFirst = true;
	$searchStr = "&cmd=".$_GET["cmd"];
	for($i =$begin; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !="" && $fields[$i] != "msg" ){			
			if($isFirst){
				if($fields[$i] == "ating_min" && $values[$i] == "A"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) <= prod_min_quant";
				}elseif($fields[$i] == "ating_min" && $values[$i] == "N"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) > prod_min_quant";
				}else{
					$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				}
				$isFirst = false;
			}else{
				if($fields[$i] == "ating_min" && $values[$i] == "A"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) <= prod_min_quant";
				}elseif($fields[$i] == "ating_min" && $values[$i] == "N"){
					$sql .= " AND (IFNULL((select sum(mov_entr_quant_entrada)from mov_entrada where mov_entr_prod_id=prod_id group by mov_entr_prod_id),0) - 
								   IFNULL((select sum(mov_susoint_quant_saida) from mov_saida_usointerno 
													  where mov_susoint_prod_id=prod_id group by mov_susoint_prod_id),0)-
								   IFNULL((select sum(mov_sos_quant_saida) from mov_saida_os 
													  where mov_sos_prod_id=prod_id group by mov_sos_prod_id),0)) > prod_min_quant";	
				}else{
					$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
				}
			}
			$searchStr	.= "&".$fields[$i]."=".$values[$i];
		}
	}				
}


$_SESSION["srch"] = $searchStr;

$sql .= $limitation;

$sql .= " ORDER BY ".$orderType[$_GET["ot"]][0]." ".$_SESSION[$orderType[$_GET["ot"]][0]];

$sql = ereg_replace("WHERE  AND","WHERE",$sql);

#echo $sql;

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
    	<form name="search_form" action="index.php?inc=lprod&amp;ot=1"  method="post" style="display:inline">
        <input type="hidden" name="cmd" value="srch" />
		<table cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 4px">
			
            <tr class="fldField">
				<td>
                	C&oacute;digo:<br/>
                    <input type="text" name="prod_id" value="<?php echo $_POST["prod_id"].$_GET["prod_id"]?>" style="width:120px" />
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
				<td>
                	Produto:<br/>
                    <input type="text" name="prod_nome" value="<?php echo $_POST["prod_nome"].$_GET["prod_nome"]?>" style="width:120px" />
				</td>
                <td>
                	Descri&ccedil;&atilde;o:<br/>
                    <input type="text" name="prod_desc" value="<?php echo $_POST["prod_desc"].$_GET["prod_desc"]?>" style="width:120px" />
				</td>                				                
			</tr>
            <tr class="fldField">
            	<td>
                	Modelo:<br/>
                    <input type="text" name="prod_modelo" value="<?php echo $_POST["prod_modelo"].$_GET["prod_modelo"]?>" style="width:120px" />
				</td>
            	<td>
                	Fabricante:<br/>
                    <input type="text" name="prod_fabricante" value="<?php echo $_POST["prod_fabricante"].$_GET["prod_fabricante"]?>" style="width:120px" />
				</td>            	
                <td>
                	Localiza&ccedil;&atilde;o:<br/>
                    <input type="text" name="prod_localizacao" value="<?php echo $_POST["prod_localizacao"].$_GET["prod_localizacao"]?>" style="width:120px" />
				</td>
                <td>
                	Quantidade:<br/>
                	<select name="ating_min" style="width: 120px">
                    	<option value=""></option>
                        <option value="A">Atingiram a quantidade m&iacute;nima</option>
                        <option value="N">N&atilde;o atingiram a quantidade m&iacute;nima</option>
                    </select>
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
    <div id="session">
    	<div id="sessionlabel">Produtos</div>
        <div id="sessionmenu">
        	<?php 
				$sql = "SELECT mov_entr_id FROM mov_entrada";
				$sqlQuery = mysql_query($sql);
				$num_mov_entr = mysql_num_rows($sqlQuery);
				
				$sql = "SELECT mov_susoint_id FROM mov_saida_usointerno";
				$sqlQuery = mysql_query($sql);
				$num_mov_susoint = mysql_num_rows($sqlQuery);
				
				$sql = "SELECT mov_sos_id FROM mov_saida_os";
				$sqlQuery = mysql_query($sql);
				$num_mov_sos = mysql_num_rows($sqlQuery);
				
				if($_SESSION["tec_cad_prod"] == "S"){
					if($num_mov_entr > 0){
			?>
            	<a href="index.php?inc=lmoventr&amp;ot=2">
                    <img src="img/btn/mov_in.png" title="Movimenta&ccedil;&otilde;es de entrada" border="0" />
                </a>
            <?php 	}else{?>            	
                    <img src="img/btn/mov_in_d.png" title="Nenhuma movimenta&ccedil;&atilde;o de entrada cadastrada" border="0" />                
            <?php 	}
					if($num_mov_susoint > 0){
			?>
            	<a href="index.php?inc=lmovsusoint&amp;ot=1">
                    <img src="img/btn/mov_out.png" title="Movimenta&ccedil;&otilde;es de sa&iacute;da por uso interno" border="0" />
                </a>
            <?php 	}else{?>
                    <img src="img/btn/mov_out_d.png" title="Nenhuma movimenta&ccedil;&atilde;o de sa&iacute;da por uso interno cadastrada" border="0" />                
            <?php 	}
					if($num_mov_sos > 0){
			?>
            	<a href="index.php?inc=lmovsos&amp;ot=1">
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
            <a href="index.php?inc=lprod&amp;ot=1" style="color: <?php echo $colors[5]?>;">
            	<img src="img/btn/todos.png" title="Mostrar todos" border="0" />
            </a>
            <?php (isset($_GET["ot"])? $ordem = $_GET["ot"]: $ordem = 0)?>
            <?php if($pm->getNumRows() > 0){?>
            <a href="javascript:createDocFromList('inc/doc/doc_prod.php','<?php echo $ordem?>','<?php echo $_SESSION[$orderType[$_GET["ot"]][0]]?>','<?php echo $searchStr?>','produtos.doc','<?php echo $_SESSION["tec_id"]?>','<?php echo $_SESSION["tec_nome"]?>','<?php echo $_SESSION["tec_tipo"]?>','ajaxContent')">
            	<img src="img/btn/create_rel.png" title="Gerar documento" border="0" />
            </a>
            <?php }else{?>
            	<img src="img/btn/create_rel_d.png" title="Sem conte&uacute;do para gerar documento" border="0" />
            <?php }?>
    	</div>
        <div style="clear:both"></div>
    </div>
	<div id="headline1" style="background-color: <?php echo $colors[1]?>">
    	<div style="float: left"><?php echo $pm->getNumRows()?> produtos em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=lprod&ot=".$_GET["ot"].$searchStr,
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
        	<td align="center" width="50px">
	            <a href="index.php?inc=lprod&amp;ot=0&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar por c&oacute;digo do produto">
                   C&oacute;digo
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
	            <a href="index.php?inc=lprod&amp;ot=1&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar por classifica&ccedil;&atilde;o">
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
	            <a href="index.php?inc=lprod&amp;ot=2&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
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
            	<a href="index.php?inc=lprod&amp;ot=3&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por modelo">
                   Modelo
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
            	<a href="index.php?inc=lprod&amp;ot=4&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar por fabricante">
                   Fabricante
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
            	<a href="index.php?inc=lprod&amp;ot=5&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por localiza&ccedil;&atilde;o">
                   Localiza&ccedil;&atilde;o
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
            	<a href="index.php?inc=lprod&amp;ot=6&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por valor de venda">
                   Venda(R$)
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
            <td align="center" width="">
            	<a title="Quantidade dispon&iacute;vel em estoque">Quant. Dispon&iacute;vel</a>
            </td>
            <td align="center" width="">
            	<a href="index.php?inc=lprod&amp;ot=7&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por quantidade m&iacute;nima">
                   Quant. M&iacute;nima
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
            <td align="center" width="">
            	<a href="index.php?inc=lprod&amp;ot=8&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por data de cadastro">
                   Cadastro
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
            <td align="center">A&ccedil;&otilde;es</td>
            <td style="background-image:url(img/bar_side_r.jpg); background-repeat: no-repeat;" width="14">&nbsp;</td>
        </tr>        
        <?php 
		if($pm->getNumRows() == 0){
			echo "<tr><td colspan=\"13\" style=\"padding: 30px\" align=\"center\">Nenhum produto encontrado.</td></tr>";
		}else{
			$isColor = true;
			while($rs = $pm->getResultObject()){
				if($isColor){
					$color = $colors[3];
				}else{
					$color = $colors[1];
				}
				
				
				$sql = "SELECT mov_entr_id, mov_entr_quant_entrada FROM mov_entrada WHERE mov_entr_prod_id=".$rs->prod_id;
				$mov_sqlQuery = mysql_query($sql);
				$quant_mov_entr = mysql_num_rows($mov_sqlQuery);
				$quant_entrada = 0;
				while($rs_mov_entr = mysql_fetch_object($mov_sqlQuery)){
					$quant_entrada += $rs_mov_entr->mov_entr_quant_entrada;
				}	
			
				
				$quant_saida = 0;
				
				$sql = "SELECT mov_sos_id,mov_sos_quant_saida FROM mov_saida_os WHERE mov_sos_prod_id=".$rs->prod_id;
				$mov_sqlQuery = mysql_query($sql);
				$quant_mov_sos = mysql_num_rows($mov_sqlQuery);
				
				while($rs_mov_sos = mysql_fetch_object($mov_sqlQuery)){
					$quant_saida += $rs_mov_sos->mov_sos_quant_saida;
				}					
				
				$sql = "SELECT mov_susoint_id,mov_susoint_quant_saida FROM mov_saida_usointerno WHERE mov_susoint_prod_id=".$rs->prod_id;
				$mov_sqlQuery = mysql_query($sql);
				$quant_mov_susoint = mysql_num_rows($mov_sqlQuery);
				while($rs_mov_susoint = mysql_fetch_object($mov_sqlQuery)){
					$quant_saida += $rs_mov_susoint->mov_susoint_quant_saida;
				}
				
				$quant_disponivel = $quant_entrada - $quant_saida;
				($quant_disponivel <= $rs->prod_min_quant)? $linecolor = "color: #F00" : $linecolor = "";
				
				/*if($_GET["ating_min"] == "A" || $_POST["ating_min"] == "A"){
					if($quant_disponivel > $rs->prod_min_quant){
						continue;	
					}					
				}				
				
				if($_GET["ating_min"] == "N" || $_POST["ating_min"] == "N"){
					if($quant_disponivel <= $rs->prod_min_quant){
						continue;	
					}					
				}*/
				
			?>                
			<tr  bgcolor="<?php echo $color?>" onmouseover="trFocus(this,'#FFFF99')" style="<?php echo $linecolor?>"
				onmouseout="trBlur(this,'<?php echo $color?>')">
				<td>&nbsp;</td>
				<td align="center"><font style="font-size: 7pt"><?php echo $rs->prod_id?></font></td>
				<td align="left">
                	<span title='<?php echo $rs->classprod_nome?>'>
						<?php 
							echo substr($rs->classprod_nome,0,20);
							if(strlen($rs->classprod_nome) > 20){
								echo "...";
							}
						?>
                    </span>
                </td>
				<td align="left">
                	<span title="<?php echo str_replace('"','&quot;',$rs->prod_nome)?>">
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
							echo substr($rs->prod_modelo,0,20);
							if(strlen($rs->prod_modelo) > 20){
								echo "...";
							}
						?>
                    </span>
                </td>
				<td align="left">
                	<span title="<?php echo $rs->prod_fabricante?>">
						<?php 
							echo substr($rs->prod_fabricante,0,20);
							if(strlen($rs->prod_fabricante) > 20){
								echo "...";
							}
						?>
                    </span>
                </td>                
                <td align="left">
                	<span title="<?php echo $rs->prod_localizacao?>">
						<?php 
							echo substr($rs->prod_localizacao,0,20);
							if(strlen($rs->prod_localizacao) > 20){
								echo "...";
							}
						?>
                    </span>
                </td>                
                <td align="center"><?php echo number_format($rs->prod_vvalor,2,",",".");?></td>
                <td align="center">
                <?php                	
					echo $quant_disponivel." ".$rs->prod_unid_medida;
				?>
                </td>
                <td align="center"><?php echo $rs->prod_min_quant." ".$rs->prod_unid_medida?></td>
                <td align="center">
					<font style="font-size: 6pt">
                    	<?php echo date("H:i:s",$rs->prod_dt_cad)?>
					</font>&nbsp;
                    <?php echo date("d-m-y",$rs->prod_dt_cad)?>
				</td>                
				<td align="center">
					<a href="javascript:ajaxRequest('inc/view/view_prod.php?prod_id=<?php echo $rs->prod_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Editar/Visualizar">
						<img src="img/btn/browse.png" width="16" height="16" border="0" />
					</a>
                    <?php 
						$sql = "SELECT forn_id FROM fornecedor";
						$sqlQuery = mysql_query($sql);
						$forn_num = mysql_num_rows($sqlQuery);
					?>
                    <?php if($_SESSION["tec_cad_prod"] == "S"){?>
                    <?php if($forn_num > 0){?>
                    <a href="javascript:ajaxRequest('inc/add/add_moventr.php?prod_id=<?php echo $rs->prod_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Cadastrar movimenta&ccedil;&atilde;o de entrada">
						<img src="img/btn/mov_in_add.png" width="16" height="16" border="0" />
					</a>
                    <?php }else{?>
                    	<img src="img/btn/mov_in_add_d.png" width="16" height="16" border="0" title="Cadastre pelo menos um fornecedor" />
                    <?php }?>
                    <?php if($quant_disponivel > 0){?>
                        <a href="javascript:ajaxRequest('inc/add/add_movsusoint.php?prod_id=<?php echo $rs->prod_id?>&quant_disp=<?php echo $quant_disponivel?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Cadastrar movimenta&ccedil;&atilde;o de sa&iacute;da por uso interno">
                            <img src="img/btn/mov_out_add.png" width="16" height="16" border="0" />
                        </a>	
                    <?php }else{?>                    	
                        <img src="img/btn/mov_out_add_d.png" width="16" height="16" border="0" title="Produto n&atilde;o dispon&iacute;vel" />                        
                    <?php }?>
                    <?php
						
						if(($quant_mov_entr + $quant_mov_susoint + $quant_mov_sos) > 0){						
					?>
                    <a href="javascript:ajaxRequest('inc/view/view_movs.php?prod_id=<?php echo $rs->prod_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Visualizar movimenta&ccedil;&otilde;es">
						<img src="img/btn/mov.png" width="16" height="16" border="0" />
					</a>
                    <?php }else{?>
                    	<img src="img/btn/mov_d.png" width="16" height="16" border="0" title="Nenhum movimenta&ccedil;&atilde;o cadastrada" />
                    <?php }?>                    
                    <?php }?>
                    <?php if($quant_mov_entr > 0){?>
                    <a href="javascript:ajaxRequest('inc/stat/stat_prod.php?prod_id=<?php echo $rs->prod_id?>','ajaxContent')" title="Estat&iacute;sticas">
						<img src="img/btn/grafico.png" width="16" height="16" border="0" />
					</a>
                    <?php }else{?>
                    	<img src="img/btn/grafico_d.png" width="16" height="16" border="0" title="Nenhum movimenta&ccedil;&atilde;o cadastrada" />
                    <?php }?>
                    <?php 
						($rs->prod_status == "D")? $lock = "src=\"img/btn/lock_d.png\" title=\"Clique para bloque&aacute;-lo\"": $lock = "src=\"img/btn/lock.png\" title=\"Clique para desbloque&aacute;-lo\"";
					?>
                    <?php if($_SESSION["tec_block"] == "S"){?>
                    <a id="<?php echo $rs->prod_id?>lock" href="javascript:setLocker('inc/save/saveprod.php?cmd=al&tec_id=<?php echo $_SESSION["tec_id"]?>&prod_id=<?php echo $rs->prod_id?>&p=<?php echo $rs->prod_status?>', '<?php echo $rs->prod_id?>lock',false)">
						<img <?php echo $lock?> width="16" height="16" border="0" />
					</a>
                    <?php }else{?>
                    	<img <?php echo $lock?> width="16" height="16" border="0" />
                    <?php }?>                                                       
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
        <div style="float: left"><?php echo $pm->getNumRows()?> produtos em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
            <?php
                
                echo $pm->getControls("index.php?inc=lprod&ot=".$_GET["ot"].$searchStr,
                                      "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
                                      "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
                                      "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
                                      "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
            ?>
        </div>            
        <div style="clear:both"></div>
    </div>
</div>