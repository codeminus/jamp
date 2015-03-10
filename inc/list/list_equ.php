<?php 

if($_SESSION["order"] == "DESC"){
	$_SESSION["order"] = "ASC";
}else{
	$_SESSION["order"] = "DESC";
}

#require("_lib.php");
# $orderType[$_GET["ot"]][0] :: nome do campo
# $orderType[$_GET["ot"]][1] :: tipo de ordena��o
						
$orderType[0][0] = "equ_nome";
$orderType[1][0] = "usu_nome";
$orderType[2][0] = "equ_modelo";
$orderType[3][0] = "equ_num_serie";
$orderType[4][0] = "equ_num_patrimonio";
$orderType[5][0] = "equ_id";
$orderType[6][0] = "equ_descricao";
$orderType[7][0] = "equ_dt_alter";
$orderType[8][0] = "equ_fabricante";

if(isset($_GET["ord"])){
	if($_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){
		$_SESSION[$orderType[$_GET["ot"]][0]] = "DESC";
	}else{
		$_SESSION[$orderType[$_GET["ot"]][0]] = "ASC";
	}
}


if($_SESSION["tec_tipo"] == "C"){
	$limitation = " AND equ_cli_id=".$_SESSION["tec_id"];					
}else{
	$limitation = "";
}

$sql = "SELECT * FROM equipamento 
		JOIN usuario ON equ_cli_id=usu_id
		JOIN cliente ON equ_cli_id=cli_usu_id";


$searchStr = "";
if($_POST["cmd"] == "srch"){
	
	$fields = array_keys($_POST);
	$values = array_values($_POST);
	
	for($i = 1; $i < (count($_POST)); $i++){
		
		if($_POST[$fields[$i]] != ""){
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
				$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				$isFirst = false;
			}else{
				$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
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
	for($i =$begin; $i < (count($_GET)-1); $i++){
		if($_GET[$fields[$i]] !="" && $fields[$i] != "msg" ){			
			if($isFirst){
				$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				$isFirst = false;
			}else{
				$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
			}
			$searchStr	.= "&".$fields[$i]."=".$values[$i];
		}
	}				
}


$_SESSION["srch"] = $searchStr;

$sql .= $limitation;

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
    	<form name="search_form" action="index.php?inc=lequ&amp;ot=0"  method="post" style="display:inline">
        <input type="hidden" name="cmd" value="srch" />
		<table cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 4px">
			
            <tr class="fldField">
				<td>
                	EQUID:<br/>
                    <input type="text" name="equ_id" value="<?php echo $_POST["equ_id"].$_GET["equ_id"]?>" style="width:120px" />
				</td>
				<td>
                	Num. Patrim&ocirc;nio:<br/>
                    <input type="text" name="equ_num_patrimonio" value="<?php echo $_POST["equ_num_patrimonio"].$_GET["equ_num_patrimonio"]?>" style="width:120px" />
				</td>
                <td>
                	Num. S&eacute;rie:<br/>
                    <input type="text" name="equ_num_serie" value="<?php echo $_POST["equ_num_serie"].$_GET["equ_num_serie"]?>" style="width:120px" />
				</td>
				<td>
                	Observa&ccedil;&otilde;es:<br/>
                    <input type="text" name="equ_descricao" value="<?php echo $_POST["equ_descricao"].$_GET["equ_descricao"]?>" style="width:120px" />
				</td>
			</tr>
            <tr class="fldField">
            	<td>
                	Cliente:<br/>
                    <input type="text" name="usu_nome" value="<?php echo $_POST["usu_nome"].$_GET["usu_nome"]?>" style="width:120px" />
				</td>
				<td>
                	Equipamento:<br/>
                    <input type="text" name="equ_nome" value="<?php echo $_POST["equ_nome"].$_GET["equ_nome"]?>" style="width:120px" />
				</td>                
                <td>
                	Modelo:<br/>
                    <input type="text" name="equ_modelo" value="<?php echo $_POST["equ_modelo"].$_GET["equ_modelo"]?>" style="width:120px" />
				</td>
                <td>
                	Fabricante:<br/>
                    <input type="text" name="equ_fabricante" value="<?php echo $_POST["equ_fabricante"].$_GET["equ_fabricante"]?>" style="width:120px" />
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
    	<div id="sessionlabel">Equipamentos</div>
        <div id="sessionmenu">
        	<a href="javascript:showHide('sform','sinal')">
            	<span id="sinal" style="display:none"></span><img src="img/btn/pesquisar.png" title="pesquisar" border="0" />
            </a>
            <a href="index.php?inc=lequ&amp;ot=0" style="color: <?php echo $colors[5]?>;">
            	<img src="img/btn/todos.png" title="Mostrar todos" border="0" />
            </a>
            <?php (isset($_GET["ot"])? $ordem = $_GET["ot"]: $ordem = 0)?>
            <?php if($pm->getNumRows() > 0){?>
            <a href="javascript:createDocFromList('inc/doc/doc_equ.php','<?php echo $ordem?>','<?php echo $_SESSION[$orderType[$_GET["ot"]][0]]?>','<?php echo $searchStr?>','equipamentos.doc','<?php echo $_SESSION["tec_id"]?>','<?php echo $_SESSION["tec_nome"]?>','<?php echo $_SESSION["tec_tipo"]?>','ajaxContent')">
            	<img src="img/btn/create_rel.png" title="Gerar documento" border="0" />
            </a>
            <?php }else{?>
            	<img src="img/btn/create_rel_d.png" title="Sem conte&uacute;do para gerar documento" border="0" />
            <?php }?>
        </div>
        <div style="clear:both"></div>
    </div>
	<div id="headline1" style="background-color: <?php echo $colors[1]?>">
    	<div style="float: left"><?php echo $pm->getNumRows()?> equipamentos em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=lequ&ot=".$_GET["ot"].$searchStr,
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
        	<td align="center" width="45px">
	            <a href="index.php?inc=lequ&amp;ot=5&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar por id do equipamento">
                   EQID
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
	            <a href="index.php?inc=lequ&amp;ot=4&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar por n&uacute;mero de patrim&ocirc;nio">
                   Num. Patrim&ocirc;nio
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
        	<td align="center">
	            <a href="index.php?inc=lequ&amp;ot=3&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar por n&uacute;mero de s&eacute;rie">
                   Num. S&eacute;rie
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
            	<a href="index.php?inc=lequ&amp;ot=0&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>"
                   title="Organizar por nome de equipamento">
                   Equipamento
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
            	<a href="index.php?inc=lequ&amp;ot=1&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por nome de cliente">
                   Cliente
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
            	<a href="index.php?inc=lequ&amp;ot=2&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por modelo">
                   Modelo
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
            	<a href="index.php?inc=lequ&amp;ot=8&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por fabricante">
                   Fabricante
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
            <td align="left">
            	<a href="index.php?inc=lequ&amp;ot=6&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por observa&ccedil;&atilde;o">
                   Observa&ccedil;&otilde;es
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
            <td align="center">A&ccedil;&otilde;es</td>
            <td style="background-image:url(img/bar_side_r.jpg); background-repeat: no-repeat;" width="14">&nbsp;</td>
        </tr>        
        <?php 
		if($pm->getNumRows() == 0){
			echo "<tr><td colspan=\"11\" style=\"padding: 30px\" align=\"center\">Nenhum equipamento encontrado.</td></tr>";
		}else{
			$isColor = true;
			while($rs = $pm->getResultObject()){
				if($isColor){
					$color = $colors[3];
				}else{
					$color = $colors[1];
				}		
			?>                
			<tr  bgcolor="<?php echo $color?>" onmouseover="trFocus(this,'#FFFF99')" 
				onmouseout="trBlur(this,'<?php echo $color?>')">
				<td>&nbsp;</td>
				<td align="center"><font style="font-size: 7pt"><?php echo $rs->equ_id?></font></td>
				<td align="center">
                	<span style="font-size: 7pt" title="<?php echo $rs->equ_num_patrimonio?>">
						<?php 
							echo substr($rs->equ_num_patrimonio,0,20);
							if(strlen($rs->equ_num_patrimonio) > 20){
								echo "...";
							}
						?>
                    </span>
                </td>
				<td align="center">
                		<span style="font-size: 7pt" title="<?php echo $rs->equ_num_serie?>">
							<?php 
								echo substr($rs->equ_num_serie,0,20);
								if(strlen($rs->equ_num_serie) > 20){
									echo "...";
								}
							?>
                        </span>
                </td>
				<td align="left">
                	<span title="<?php echo $rs->equ_nome?>">
						<?php 
							echo substr($rs->equ_nome,0,25);
							if(strlen($rs->equ_nome) > 25){
								echo "...";
							}
						?>
                   	</span>
                </td>				
				<td align="left">
                	<span title="<?php echo $rs->usu_nome?>">
						<?php 
							echo substr($rs->usu_nome,0,40);
							if(strlen($rs->usu_nome) > 40){
								echo "...";
							}
						?>
                    </span>
                </td>
                <td align="left">
                	<span title="<?php echo $rs->equ_modelo?>">
						<?php 
							echo substr($rs->equ_modelo,0,20);
							if(strlen($rs->equ_modelo) > 20){
								echo "...";
							}
						?>
                    </span>
				</td>
                <td align="left">
                	<span title="<?php echo $rs->equ_fabricante?>">
						<?php 
							echo substr($rs->equ_fabricante,0,20);
							if(strlen($rs->equ_fabricante) > 20){
								echo "...";
							}
						?>
                    </span>					
				</td>                
                <td align="left">
					<span title="<?php echo $rs->equ_descricao?>">
						<?php 
							echo substr($rs->equ_descricao,0,20);
							if(strlen($rs->equ_descricao) > 20){
								echo "...";
							}
						?>
                    </span>
				</td>                
				<td align="center">
					<a href="javascript:ajaxRequest('inc/view/view_equ.php?equ_id=<?php echo $rs->equ_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Editar/Visualizar">
						<img src="img/btn/browse.png" width="16" height="16" border="0" />
					</a>
                    <?php
                    	$sql = "SELECT os_id FROM os WHERE os_equ_id=".$rs->equ_id." AND os_sta_id<>5";
						$os_num_sqlQuery = mysql_query($sql);
					?>
                    <?php if($_SESSION["tec_gerar_os"] == "S" && $rs->equ_status == "A" && mysql_num_rows($os_num_sqlQuery) == 0){?>
                    <?php if($_SESSION["tec_tipo"] == "T"){?>
                        <a href="javascript:ajaxRequest('inc/add/add_os_tec.php?equ_id=<?php echo $rs->equ_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')">
                            <img src="img/btn/new_os.png" width="16" height="16" border="0" title="Gerar Ordem de servi&ccedil;o" />
                        </a>
                    <?php }else{?>
                        <a href="javascript:ajaxRequest('inc/add/add_os_cli.php?equ_id=<?php echo $rs->equ_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')">
                            <img src="img/btn/new_os.png" width="16" height="16" border="0" title="Gerar Ordem de servi&ccedil;o" />
                        </a>
                    <?php }?>
                    <?php }elseif(mysql_num_rows($os_num_sqlQuery) > 0){?>
                    	<img src="img/btn/new_os_dd.png" width="16" height="16" border="0" 
                        	 title="Existe uma ordem de servi&ccedil;o n&atilde;o conclu&iacute;da para este equipamento" />
                    <?php }else{?>                    
						<img src="img/btn/new_os_d.png" width="16" height="16" border="0" 
                        	 title="Sem permiss&atilde;o para gerar ordens de servi&ccedil;o" />
                    <?php }?>
                    <?php
                    	$sql = "SELECT os_id FROM os WHERE os_equ_id=".$rs->equ_id." AND os_historico='N'";
						$os_sqlQuery = mysql_query($sql);
						if(mysql_num_rows($os_sqlQuery) > 0){						
					?>
                    <a href="index.php?inc=los&amp;ot=5&amp;ord=1&amp;cmd=srch&amp;equ_id=<?php echo $rs->equ_id?>&amp;equ_dt_cad=<?php echo $rs->equ_dt_cad?>&amp;usu_id=<?php echo $rs->equ_cli_id?>&amp;pg=1" title="Visualizar ordens de servi&ccedil;o">
						<img src="img/btn/os_list.png" width="16" height="16" border="0" />
					</a>
                    <?php }else{?>                    	
						<img src="img/btn/os_list_d.png" width="16" height="16" border="0" 
                        	 title="Nenhum ordem de servi&ccedil;o gerada" />
					</a>
                    <?php }?>
                    <!--<a href="javascript:void(0)" title="Estat&iacute;sticas">
						<img src="img/btn/grafico.png" width="16" height="16" border="0" />
					</a>-->
                    <?php 
						($rs->equ_status == "A")? $lock = "src=\"img/btn/lock_d.png\" title=\"Clique para bloque&aacute;-lo\"": $lock = "src=\"img/btn/lock.png\" title=\"Clique para desbloque&aacute;-lo\"";
					?>
                    <?php if($_SESSION["tec_block"] == "S"){?>
                    <a id="<?php echo $rs->equ_id?>lock" href="javascript:setLocker('inc/save/saveequ.php?cmd=al&tec_id=<?php echo $_SESSION["tec_id"]?>&cli_id=<?php echo $rs->equ_cli_id?>&equ_id=<?php echo $rs->equ_id?>&p=<?php echo $rs->equ_status?>', '<?php echo $rs->equ_id?>lock',true)">
						<img <?php echo $lock?> width="16" height="16" border="0" />
					</a>
                    <?php }elseif($_SESSION["tec_tipo"] != "C"){?>
                    	<img <?php echo $lock?> width="16" height="16" border="0" />
                    <?php }?>
                    <?php
                    	$sql = "SELECT os_id FROM os WHERE os_equ_id=".$rs->equ_id." AND os_historico='N'";
						$sqlQuery = mysql_query($sql);
						$os_num = mysql_num_rows($sqlQuery);
					?>
                    <?php if($_SESSION["tec_cad_equ"] == "S"){?>
                    <?php  if($os_num == 0){?>
                    	<a href="javascript:excluirRec('inc/save/saveequ.php?cmd=excluir&equ_id=<?php echo $rs->equ_id?>&cli_id=<?php echo $rs->equ_cli_id?>',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $pg?>,'lequ','exequ', false)" title="Deletar equipamento">
                            <img src="img/btn/del.png" width="16" height="16" border="0" />
                        </a>
                    <?php }else{?>
                            <img src="img/btn/del_d.png" width="16" height="16" border="0" title="Para excluir o equipamento exclua todos as ordens de servi&ccedil;o vinculadas a ele" />
                        </a>
                    <?php }?>
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
        <div style="float: left"><?php echo $pm->getNumRows()?> equipamentos em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
            <?php
                
                echo $pm->getControls("index.php?inc=lequ&ot=".$_GET["ot"].$searchStr,
                                      "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
                                      "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
                                      "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
                                      "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
            ?>
        </div>            
        <div style="clear:both"></div>
    </div>
</div>