<?php
if($_SESSION["tec_tipo"] == "C"){
?>
	<script>document.location = "index.php?inc=los&ot=5&msg=pclassprod";</script>
<?php
	exit;
}

if($_SESSION["order"] == "DESC"){
	$_SESSION["order"] = "ASC";
}else{
	$_SESSION["order"] = "DESC";
}
						
$orderType[0][0] = "classprod_id";
$orderType[1][0] = "classprod_nome";
$orderType[2][0] = "classprod_desc";
$orderType[3][0] = "classprod_dt_cad";

if(isset($_GET["ord"])){
	if($_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){
		$_SESSION[$orderType[$_GET["ot"]][0]] = "DESC";
	}else{
		$_SESSION[$orderType[$_GET["ot"]][0]] = "ASC";
	}
}


$sql = "SELECT * FROM classprod";



$searchStr = "";
if($_POST["cmd"] == "srch"){
	
	$limitation = " AND classprod_status<>'E'";
	
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
				$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				$isFirst = false;
			}else{
				$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
			}
			$searchStr	.= "&".$fields[$i]."=".$values[$i];	
		}
	}				
}elseif($_GET["cmd"] == "srch"){
	
	$limitation = " AND classprod_status<>'E'";
	
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
				$sql .= $fields[$i]." LIKE '%".$values[$i]."%'";
				$isFirst = false;
			}else{
				$sql .= " AND ".$fields[$i]." LIKE '%".$values[$i]."%'";
			}
			$searchStr	.= "&".$fields[$i]."=".$values[$i];
		}
	}				
}else{
	
	$limitation = " WHERE classprod_status<>'E'";
	
}


$_SESSION["srch"] = $searchStr;

$sql .= $limitation;

$sql .= " ORDER BY ".$orderType[$_GET["ot"]][0]." ".$_SESSION[$orderType[$_GET["ot"]][0]];

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
    	<form name="search_form" action="index.php?inc=lclassprod&amp;ot=0"  method="post" style="display:inline">
        <input type="hidden" name="cmd" value="srch" />
		<table cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 4px">
			
            <tr class="fldField">
				<td>
                	C&oacute;digo:<br/>
                    <input type="text" name="classprod_id" value="<?php echo $_POST["classprod_id"].$_GET["classprod_id"]?>" style="width:120px" />
				</td>
				<td>
                	Classifica&ccedil;&atilde;o:<br/>
                    <input type="text" name="classprod_nome" value="<?php echo $_POST["classprod_nome"].$_GET["classprod_nome"]?>" style="width:120px" />
				</td>				
				<td>
                	Descri&ccedil;&atilde;o:<br/>
                    <input type="text" name="classprod_desc" value="<?php echo $_POST["classprod_desc"].$_GET["classprod_desc"]?>" style="width:120px" />
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
    	<div id="sessionlabel">Classifica&ccedil;&otilde;es de produto</div>
        <div id="sessionmenu">
        	<a href="javascript:showHide('sform','sinal')">
            	<span id="sinal" style="display:none"></span><img src="img/btn/pesquisar.png" title="pesquisar" border="0" />
            </a>
            <a href="index.php?inc=lclassprod&amp;ot=0">
            	<img src="img/btn/todos.png" title="Mostrar todos" border="0" />
            </a>
            <?php (isset($_GET["ot"])? $ordem = $_GET["ot"]: $ordem = 0)?>            
        </div>
        <div style="clear:both"></div>
    </div>
	<div id="headline1" style="background-color: <?php echo $colors[1]?>">
    	<div style="float: left"><?php echo $pm->getNumRows()?> classifica&ccedil;&otilde;es em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=lclassprod&ot=".$_GET["ot"].$searchStr,
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
        	<td align="center" width="60px">
            	<a href="index.php?inc=lclassprod&amp;ot=0&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por c&oacute;digo da classifica&ccedil;&atilde;o">
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
            	<a href="index.php?inc=lclassprod&amp;ot=1&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
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
            	<a href="index.php?inc=lclassprod&amp;ot=2&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por descri&ccedil;&atilde;o">
                   Descri&ccedil;&atilde;o
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
            <td align="center" width="105px">
            	<a href="index.php?inc=lclassprod&amp;ot=3&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por data de cadastro">
                   Cadastro
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
            <td align="center">A&ccedil;&otilde;es</td>
            <td style="background-image:url(img/bar_side_r.jpg); background-repeat: no-repeat;" width="14">&nbsp;</td>
        </tr>        
        <?php 	
		
		if($pm->getNumRows() == 0){
			echo "<tr><td colspan=\"7\" style=\"padding: 30px\" align=\"center\">Nenhuma classifica&ccedil;&atilde;o encontrada.</td></tr>";
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
				<td align="center"><font style="font-size: 7pt"><?php echo $rs->classprod_id?></font></td>                
				<td align="left">
                	<span title="<?php echo $rs->classprod_nome?>">
						<?php 
							echo substr($rs->classprod_nome,0,40);
							if(strlen($rs->classprod_nome) > 40){
								echo "...";
							}
						?>
                    </span>
                </td>				
				<td align="left">
                	<span title="<?php echo $rs->classprod_desc?>">
						<?php 
							echo substr($rs->classprod_desc,0,80);
							if(strlen($rs->classprod_desc) > 80){
								echo "...";
							}
						?>
                   	</span>
                </td>
                <td align="center">
					<font style="font-size: 6pt">					
					<?php echo date("H:i:s",$rs->classprod_dt_cad)?></font>&nbsp;
					<?php echo date("d-m-y",$rs->classprod_dt_cad)?>                    
				</td>                
				<td align="center">
					<a href="javascript:ajaxRequest('inc/view/view_classprod.php?classprod_id=<?php echo $rs->classprod_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Editar/Visualizar">
						<img src="img/btn/browse.png" width="16" height="16" border="0" />
					</a>                    
                    <?php
                    	$sql = "SELECT prod_id FROM produto WHERE prod_classprod_id=".$rs->classprod_id;
						$classprod_sqlQuery = mysql_query($sql);
						if(mysql_num_rows($classprod_sqlQuery) > 0){						
					?>
                    <a href="index.php?inc=lprod&amp;ot=0&amp;ord=1&amp;cmd=srch&amp;classprod_id=<?php echo $rs->classprod_id?>&amp;pg=1" title="Visualizar produtos">
						<img src="img/btn/prod_list.png" width="16" height="16" border="0" />
					</a>
                    <?php }else{?>
                    	<img src="img/btn/prod_list_d.png" width="16" height="16" border="0" title="Nenhum produto cadastrado" />
                    <?php }?>                    
                  <?php 
						($rs->classprod_status == "A")? $lock = "src=\"img/btn/lock_d.png\" title=\"Clique para bloque&aacute;-lo\"": $lock = "src=\"img/btn/lock.png\" title=\"Clique para desbloque&aacute;-lo\"";
					?>
                    <?php if($_SESSION["tec_block"] == "S"){?>
                    <a id="<?php echo $rs->classprod_id?>lock" href="javascript:setLocker('inc/save/saveclassprod.php?cmd=al&tec_id=<?php echo $_SESSION["tec_id"]?>&classprod_id=<?php echo $rs->classprod_id?>&p=<?php echo $rs->classprod_status?>', '<?php echo $rs->classprod_id?>lock',true)">
						<img <?php echo $lock?> width="16" height="16" border="0" />
					</a>
                    <?php }else{?>
                    	<img <?php echo $lock?> width="16" height="16" border="0" />
                    <?php }?>				
                    <?php
                    	$sql = "SELECT prod_id FROM produto WHERE prod_classprod_id=".$rs->classprod_id;
						$sqlQuery = mysql_query($sql);
						$prod_num = mysql_num_rows($sqlQuery);
					?>
                    <?php if($_SESSION["tec_cad_prod"] == "S"){?>
                    <?php  if($prod_num == 0){?>
                    	<a href="javascript:excluirRec('inc/save/saveclassprod.php?cmd=excluir&classprod_id=<?php echo $rs->classprod_id?>',<?php echo $_GET["ot"]?>,'<?php echo $_SESSION["srch"]?>',<?php echo $pg?>,'lclassprod','exclassprod', false)" title="Deletar classifica&ccedil;&atilde;o">
                            <img src="img/btn/del.png" width="16" height="16" border="0" />
                        </a>
                    <?php }else{?>
                            <img src="img/btn/del_d.png" width="16" height="16" border="0" title="Existem produtos vinculados a esta classifica&ccedil;&atilde;o" />
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
    	<div style="float: left"><?php echo $pm->getNumRows()?> classifica&ccedil;&otilde;es em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=lclassprod&ot=".$_GET["ot"].$searchStr,
									  "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
			?>
        </div>
        <div style="clear:both"></div>
    </div>
</div>