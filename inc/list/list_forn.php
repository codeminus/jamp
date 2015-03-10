<?php
if($_SESSION["tec_cad_equ"] != "S"){
?>
	<script>document.location = "index.php?inc=los&ot=5&msg=pforn";</script>
<?php
	exit;
}

if($_SESSION["order"] == "DESC"){
	$_SESSION["order"] = "ASC";
}else{
	$_SESSION["order"] = "DESC";
}
						
$orderType[0][0] = "forn_nome";
$orderType[1][0] = "forn_email";
$orderType[2][0] = "forn_end_estado";
$orderType[3][0] = "forn_id";
$orderType[4][0] = "forn_cp";
$orderType[5][0] = "forn_dt_cad";
$orderType[6][0] = "forn_end_cidade";
$orderType[7][0] = "forn_end_pais";
$orderType[8][0] = "forn_end_logradouro";

if(isset($_GET["ord"])){
	if($_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){
		$_SESSION[$orderType[$_GET["ot"]][0]] = "DESC";
	}else{
		$_SESSION[$orderType[$_GET["ot"]][0]] = "ASC";
	}
}


$sql = "SELECT * FROM fornecedor";

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
}


$_SESSION["srch"] = $searchStr;

$sql .= " ORDER BY ".$orderType[$_GET["ot"]][0]." ".$_SESSION[$orderType[$_GET["ot"]][0]];
#$sql .= " ORDER BY ".$orderType[$_GET["ot"]][0];

if(isset($_GET["pg"])){
	$pg = $_GET["pg"];
}else{
	$pg = 1;
}
#echo $sql;
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
    	<form name="search_form" action="index.php?inc=lforn&amp;ot=0"  method="post" style="display:inline">
        <input type="hidden" name="cmd" value="srch" />
		<table cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 4px">
			
            <tr class="fldField">
				<td>
                	C&oacute;digo:<br/>
                    <input type="text" name="forn_id" value="<?php echo $_POST["forn_id"].$_GET["forn_id"]?>" style="width:120px" />
				</td>
				<td>
                	CPF/CNPJ:<br/>
                    <input type="text" name="forn_cp" value="<?php echo $_POST["forn_cp"].$_GET["forn_cp"]?>" style="width:120px" />
				</td>				
				<td>
                	Fornecedor:<br/>
                    <input type="text" name="forn_nome" value="<?php echo $_POST["forn_nome"].$_GET["forn_nome"]?>" style="width:120px" />
				</td>                
                <td>
                	E-mail:<br/>
                    <input type="text" name="forn_email" value="<?php echo $_POST["forn_email"].$_GET["forn_email"]?>" style="width:120px" />
				</td>
                <td>
                	Observa&ccedil;&otilde;es:<br/>
                    <input type="text" name="forn_observacao" value="<?php echo $_POST["forn_observacao"].$_GET["forn_observacao"]?>" style="width:120px" />
				</td>
			</tr>
            <tr class="fldField">
				<td>
                	Pais:<br/>
                    <input type="text" name="forn_end_pais" value="<?php echo $_POST["forn_end_pais"].$_GET["forn_end_pais"]?>" style="width:120px" />
				</td>
				<td>
                	Estado:<br/>
                    <input type="text" name="forn_end_estado" value="<?php echo $_POST["forn_end_estado"].$_GET["forn_end_estado"]?>" style="width:120px" />
				</td>				
				<td>
                	Cidade:<br/>
                    <input type="text" name="forn_end_cidade" value="<?php echo $_POST["forn_end_cidade"].$_GET["forn_end_cidade"]?>" style="width:120px" />
				</td>                
                <td>
                	Bairro:<br/>
                    <input type="text" name="forn_end_bairro" value="<?php echo $_POST["forn_end_bairro"].$_GET["forn_end_bairro"]?>" style="width:120px" />
				</td>
                <td>
                	Logradouro:<br/>
                    <input type="text" name="forn_end_logradouro" value="<?php echo $_POST["forn_end_logradouro"].$_GET["forn_end_logradouro"]?>" 
                    	   style="width:120px" />
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
    	<div id="sessionlabel">Fornecedores</div>
        <div id="sessionmenu">
        	<a href="javascript:showHide('sform','sinal')">
            	<span id="sinal" style="display:none"></span><img src="img/btn/pesquisar.png" title="pesquisar" border="0" />
            </a>
            <a href="index.php?inc=lforn&amp;ot=0">
            	<img src="img/btn/todos.png" title="Mostrar todos" border="0" />
            </a>
            <?php (isset($_GET["ot"])? $ordem = $_GET["ot"]: $ordem = 0)?>
            <?php if($pm->getNumRows() > 0){?>
            <a href="javascript:createDocFromList('inc/doc/doc_forn.php','<?php echo $ordem?>','<?php echo $_SESSION[$orderType[$_GET["ot"]][0]]?>','<?php echo $searchStr?>','fornecedores.doc','<?php echo $_SESSION["tec_id"]?>','<?php echo $_SESSION["tec_nome"]?>','<?php echo $_SESSION["tec_tipo"]?>','ajaxContent')">
            	<img src="img/btn/create_rel.png" title="Gerar documento" border="0" />
            </a>
            <?php }else{?>
            	<img src="img/btn/create_rel_d.png" title="Sem conte&uacute;do para gerar documento" border="0" />
            <?php }?>
        </div>
        <div style="clear:both"></div>
    </div>
	<div id="headline1" style="background-color: <?php echo $colors[1]?>">
    	<div style="float: left"><?php echo $pm->getNumRows()?> fornecedores em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=lforn&ot=".$_GET["ot"].$searchStr,
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
            	<a href="index.php?inc=lforn&amp;ot=3&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por c&oacute;digo">
                   C&oacute;digo
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
            <td align="center">
            	<a href="index.php?inc=lforn&amp;ot=4&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por cadastro de pessoa">
                   CPF/CNPJ
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
            	<a href="index.php?inc=lforn&amp;ot=0&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por nome">
                   Fornecedor
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
            	<a href="index.php?inc=lforn&amp;ot=1&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por endere&ccedil;o de e-mail">
                   E-mail
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
            	<a href="index.php?inc=lforn&amp;ot=7&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por pa&iacute;s">
                   Pa&iacute;s
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
            <td align="left">
            	<a href="index.php?inc=lforn&amp;ot=2&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por estado">
                   Estado
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
            	<a href="index.php?inc=lforn&amp;ot=6&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por cidade">
                   Cidade
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
            <td align="left">
            	<a href="index.php?inc=lforn&amp;ot=8&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por logradouro">
                   Endere&ccedil;o
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
            	<a href="index.php?inc=lforn&amp;ot=5&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por data de cadastro">
                   Cadastro
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
            <td align="center">A&ccedil;&otilde;es</td>
            <td style="background-image:url(img/bar_side_r.jpg); background-repeat: no-repeat;" width="14">&nbsp;</td>
        </tr>        
        <?php 	
		
		if($pm->getNumRows() == 0){
			echo "<tr><td colspan=\"12\" style=\"padding: 30px\" align=\"center\">Nenhum fornecedor encontrado.</td></tr>";
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
				<td align="center"><font style="font-size: 7pt"><?php echo $rs->forn_id?></font></td>
                <td align="center"><font style="font-size: 7pt"><?php echo $rs->forn_cp?></font></td>
				<td align="left">
                	<span title="<?php echo $rs->forn_nome?>">
						<?php 
							echo substr($rs->forn_nome,0,30);
							if(strlen($rs->forn_nome) > 30){
								echo "...";
							}
						?>
                    </span>
                </td>				
				<td align="left">
                	<span title="<?php echo $rs->forn_email?>">
						<?php 
							echo substr($rs->forn_email,0,20);
							if(strlen($rs->forn_email) > 20){
								echo "...";
							}
						?>
                   	</span>
                </td>
                <td align="left">
                	<?php echo $rs->forn_end_pais?>
                </td>
                <td align="left">
                	<?php echo $rs->forn_end_estado?>
                </td>
                <td align="left">
                	<?php echo $rs->forn_end_cidade?>
                </td>
                <td align="left">
                	<?php
						$endereco = "";
						($rs->forn_end_logradouro != "")? $endereco = $rs->forn_end_logradouro.", " : $endereco = "";
						($rs->forn_end_bairro != "")? $endereco .= $rs->forn_end_bairro.", " : $endereco .= "";
						($rs->forn_end_cep != "")? $endereco .= "CEP: ".$rs->forn_end_cep : $endereco .= "";						
					?>
                	<span title="<?php echo $endereco?>">
						<?php 
							echo substr($endereco,0,30);
							if(strlen($endereco) > 30){
								echo "...";
							}
						?>
                   	</span>
                </td>
                <td align="center">
					<font style="font-size: 6pt">					
					<?php echo date("H:i:s",$rs->forn_dt_cad)?></font>&nbsp;
					<?php echo date("d-m-y",$rs->forn_dt_cad)?>                    
				</td>
				<td align="center">
					<a href="javascript:ajaxRequest('inc/view/view_forn.php?forn_id=<?php echo $rs->forn_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Editar/Visualizar">
						<img src="img/btn/browse.png" width="16" height="16" border="0" />
					</a>                    
                    <?php 
						($rs->forn_status == "A")? $lock = "src=\"img/btn/lock_d.png\" title=\"Clique para bloque&aacute;-lo\"": $lock = "src=\"img/btn/lock.png\" title=\"Clique para desbloque&aacute;-lo\"";
					?>
                    <?php if($_SESSION["tec_block"] == "S"){?>
                    <a id="<?php echo $rs->forn_id?>lock" href="javascript:setLocker('inc/save/saveforn.php?cmd=al&tec_id=<?php echo $_SESSION["tec_id"]?>&forn_id=<?php echo $rs->forn_id?>&p=<?php echo $rs->forn_status?>', '<?php echo $rs->forn_id?>lock',false)">
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
    	<div style="float: left"><?php echo $pm->getNumRows()?> fornecedores em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=lforn&ot=".$_GET["ot"].$searchStr,
									  "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
			?>
        </div>
        <div style="clear:both"></div>
    </div>
</div>