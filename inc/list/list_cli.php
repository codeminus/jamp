<?php
if($_SESSION["tec_view_cli"] != "S"){
?>
	<script>document.location = "index.php?inc=los&ot=5&msg=pcli";</script>
<?php
	exit;
}

if($_SESSION["order"] == "DESC"){
	$_SESSION["order"] = "ASC";
}else{
	$_SESSION["order"] = "DESC";
}
						
$orderType[0][0] = "usu_nome";
$orderType[1][0] = "usu_email";
$orderType[2][0] = "usu_end_estado";
$orderType[3][0] = "usu_id";
$orderType[4][0] = "cli_cp";
$orderType[5][0] = "usu_tel";
$orderType[6][0] = "usu_end_cidade";
$orderType[7][0] = "usu_end_pais";
$orderType[8][0] = "usu_end_logradouro";

if(isset($_GET["ord"])){
	if($_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){
		$_SESSION[$orderType[$_GET["ot"]][0]] = "DESC";
	}else{
		$_SESSION[$orderType[$_GET["ot"]][0]] = "ASC";
	}
}


$sql = "SELECT * FROM usuario
		JOIN cliente ON usu_id=cli_usu_id";

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
    	<form name="search_form" action="index.php?inc=lcli&amp;ot=0"  method="post" style="display:inline">
        <input type="hidden" name="cmd" value="srch" />
		<table cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 4px">
			
            <tr class="fldField">
				<td>
                	CLID:<br/>
                    <input type="text" name="usu_id" value="<?php echo $_POST["usu_id"].$_GET["usu_id"]?>" style="width:120px" />
				</td>
				<td>
                	CPF/CNPJ:<br/>
                    <input type="text" name="cli_cp" value="<?php echo $_POST["cli_cp"].$_GET["cli_cp"]?>" style="width:120px" />
				</td>				
				<td>
                	Cliente:<br/>
                    <input type="text" name="usu_nome"  value="<?php echo $_POST["usu_nome"].$_GET["usu_nome"]?>" style="width:120px" />
				</td>                
                <td>
                	E-mail:<br/>
                    <input type="text" name="usu_email" value="<?php echo $_POST["usu_email"].$_GET["usu_email"]?>" style="width:120px" />
				</td>
                <td>
                	Observa&ccedil;&otilde;es:<br/>
                    <input type="text" name="usu_obs" value="<?php echo $_POST["usu_obs"].$_GET["usu_obs"]?>" style="width:120px" />
				</td>
			</tr>
            <tr class="fldField">
				<td>
                	Pais:<br/>
                    <input type="text" name="usu_end_pais" value="<?php echo $_POST["usu_end_pais"].$_GET["usu_end_pais"]?>" style="width:120px" />
				</td>
				<td>
                	Estado:<br/>
                    <input type="text" name="usu_end_estado" value="<?php echo $_POST["usu_end_estado"].$_GET["usu_end_estado"]?>" style="width:120px" />
				</td>				
				<td>
                	Cidade:<br/>
                    <input type="text" name="usu_end_cidade" value="<?php echo $_POST["usu_end_cidade"].$_GET["usu_end_cidade"]?>" style="width:120px" />
				</td>                
                <td>
                	Bairro:<br/>
                    <input type="text" name="usu_end_bairro" value="<?php echo $_POST["usu_end_bairro"].$_GET["usu_end_bairro"]?>" style="width:120px" />
				</td>
                <td>
                	Logradouro:<br/>
                    <input type="text" name="usu_end_logradouro" value="<?php echo $_POST["usu_end_logradouro"].$_GET["usu_end_logradouro"]?>" style="width:120px" />
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
    	<div id="sessionlabel">Clientes</div>
        <div id="sessionmenu">
        	<a href="javascript:showHide('sform','sinal')">
            	<span id="sinal" style="display:none"></span><img src="img/btn/pesquisar.png" title="pesquisar" border="0" />
            </a>
            <a href="index.php?inc=lcli&amp;ot=0">
            	<img src="img/btn/todos.png" title="Mostrar todos" border="0" />
            </a>
            <?php (isset($_GET["ot"])? $ordem = $_GET["ot"]: $ordem = 0)?>
            <?php if($pm->getNumRows() > 0){?>            
            <a href="javascript:ajaxRequest('inc/stat/stat_cli.php?ot=<?php echo $ordem?><?php echo $searchStr?>','ajaxContent')">
            	<img src="img/btn/grafico.png" title="Estat&iacute;sticas" border="0" />
            </a>
            <a href="javascript:createDocFromList('inc/doc/doc_cli.php','<?php echo $ordem?>','<?php echo $_SESSION[$orderType[$_GET["ot"]][0]]?>','<?php echo $searchStr?>','clientes.doc','<?php echo $_SESSION["tec_id"]?>','<?php echo $_SESSION["tec_nome"]?>','<?php echo $_SESSION["tec_tipo"]?>','ajaxContent')">
            	<img src="img/btn/create_rel.png" title="Gerar documento" border="0" />
            </a>            
            <?php }else{?>
            	<img src="img/btn/grafico_d.png" title="Sem conte&uacute;do para gerar estat&iacute;sticas" border="0" />
            	<img src="img/btn/create_rel_d.png" title="Sem conte&uacute;do para gerar documento" border="0" />
            <?php }?>
        </div>
        <div style="clear:both"></div>
    </div>
	<div id="headline1" style="background-color: <?php echo $colors[1]?>">
    	<div style="float: left"><?php echo $pm->getNumRows()?> clientes em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=lcli&ot=".$_GET["ot"].$searchStr,
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
        	<td align="center" width="40px">
            	<a href="index.php?inc=lcli&amp;ot=3&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por id">
                   CLID
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
            	<a href="index.php?inc=lcli&amp;ot=4&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
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
            	<a href="index.php?inc=lcli&amp;ot=0&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por nome">
                   Cliente
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
            	<a href="index.php?inc=lcli&amp;ot=1&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
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
            	<a href="index.php?inc=lcli&amp;ot=7&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
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
            	<a href="index.php?inc=lcli&amp;ot=2&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
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
            	<a href="index.php?inc=lcli&amp;ot=6&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por cidade">
                   Cidade
                   <?php 
						if($_GET["ot"] ==6 && $_SESSION[$orderType[$_GET["ot"]][0]] == "ASC"){ 
							echo '<img src="img/btn/arrow_down.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
						
						if($_GET["ot"] == 6 && $_SESSION[$orderType[$_GET["ot"]][0]] == "DESC"){ 
							echo '<img src="img/btn/arrow_up.png" border="0" style="margin-bottom: -4px; margin-left: -2px" />';
						}
					?>
                </a>
            </td>
            <td align="left">
            	<a href="index.php?inc=lcli&amp;ot=8&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
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
            <td align="left" >
            	<a href="index.php?inc=lcli&amp;ot=5&amp;ord=1<?php echo $searchStr?>&amp;pg=<?php echo $pm->getCp()?>" 
                   title="Organizar por telefones">
                   Telefones
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
			echo "<tr><td colspan=\"12\" style=\"padding: 30px\" align=\"center\">Nenhum cliente encontrado.</td></tr>";
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
				<td align="center"><font style="font-size: 7pt"><?php echo $rs->usu_id?></font></td>
                <td align="center"><font style="font-size: 7pt"><?php echo $rs->cli_cp?></font></td>
				<td align="left">
                	<span title="<?php echo $rs->usu_nome?>">
						<?php 
							echo substr($rs->usu_nome,0,20);
							if(strlen($rs->usu_nome) > 20){
								echo "...";
							}
						?>
                    </span>
                </td>				
				<td align="left">
                	<span title="<?php echo $rs->usu_email?>">
						<?php 
							echo substr($rs->usu_email,0,20);
							if(strlen($rs->usu_email) > 20){
								echo "...";
							}
						?>
                   	</span>
                </td>
                <td align="left">
                	<?php echo $rs->usu_end_pais?>
                </td>
                <td align="left">
                	<?php echo $rs->usu_end_estado?>
                </td>
                <td align="left">
                	<?php echo $rs->usu_end_cidade?>
                </td>
                <td align="left">
                	<?php
						$endereco = "";
						($rs->usu_end_logradouro != "")? $endereco = $rs->usu_end_logradouro.", " : $endereco = "";
						($rs->usu_end_bairro != "")? $endereco .= $rs->usu_end_bairro.", " : $endereco .= "";
						($rs->usu_end_cep != "")? $endereco .= "CEP: ".$rs->usu_end_cep : $endereco .= "";						
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
                <td align="left">
					<span title="<?php echo $rs->usu_tel?>">
						<?php 
							echo substr($rs->usu_tel,0,20);
							if(strlen($rs->usu_tel) > 20){
								echo "...";
							}
						?>
                   	</span>				
               	</td>
                <!--<td align="center">
					<font style="font-size: 6pt">					
					<?php echo date("H:i:s",$rs->usu_dt_criacao)?></font>&nbsp;
					<?php echo date("d-m-y",$rs->usu_dt_criacao)?>                    
				</td>-->
				<td align="center">
					<a href="javascript:ajaxRequest('inc/view/view_cli.php?cli_id=<?php echo $rs->usu_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Editar/Visualizar">
						<img src="img/btn/browse.png" width="16" height="16" border="0" />
					</a>
                    <?php if($_SESSION["tec_cad_equ"] == "S"){?>
                    <a href="javascript:ajaxRequest('inc/add/add_equ.php?cli_id=<?php echo $rs->usu_id?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Cadastrar equipamento">
						<img src="img/btn/equipamento_a.png" width="16" height="16" border="0" />
					</a>
                    <?php }else{?>
                    	<img src="img/btn/equipamento_ad.png" width="16" height="16" border="0" title="Sem permiss&atilde;o para cadastrar equipamentos" />
                    <?php }?>
                    <?php
                    	$sql = "SELECT equ_id FROM equipamento WHERE equ_cli_id=".$rs->usu_id;
						$cli_sqlQuery = mysql_query($sql);
						if(mysql_num_rows($cli_sqlQuery) > 0){						
					?>
                    <a href="index.php?inc=lequ&ot=0&ord=1&cmd=srch&usu_dt_criacao=<?php echo $rs->usu_dt_criacao?>&usu_id=<?php echo $rs->usu_id?>&pg=1" title="Visualizar equipamentos">
						<img src="img/btn/equipamento_v.png" width="16" height="16" border="0" />
					</a>
                    <?php }else{?>
                    	<img src="img/btn/equipamento_d.png" width="16" height="16" border="0" title="Nenhum equipamento cadastrado" />
                    <?php }?>
                    <?php
                    	$sql = "SELECT os_id FROM os WHERE os_cli_id=".$rs->usu_id." AND os_historico='N'";
						$os_sqlQuery = mysql_query($sql);
						if(mysql_num_rows($os_sqlQuery) > 0){						
					?>
                    <a href="index.php?inc=los&ot=5&ord=1&cmd=srch&usu_id=<?php echo $rs->usu_id?>&amp;usu_dt_criacao=<?php echo $rs->usu_dt_criacao?>&amp;pg=1" title="Visualizar ordens de servi&ccedil;o">
						<img src="img/btn/os_list.png" width="16" height="16" border="0" />
					</a>
                    <?php }else{?>                    	
						<img src="img/btn/os_list_d.png" width="16" height="16" border="0" 
                        	 title="Nenhum ordem de servi&ccedil;o gerada" />
					</a>
                    <?php }?>
                    <a href="javascript:ajaxRequest('inc/stat/stat_this_cli.php?cli_id=<?php echo $rs->usu_id?>','ajaxContent')" title="Estat&iacute;sticas">
						<img src="img/btn/grafico.png" width="16" height="16" border="0" />
					</a>
                    <?php 
						($rs->usu_status == "A" || $rs->usu_status == "AS")? $lock = "img/btn/lock_d.png": $lock = "img/btn/lock.png";
					?>
                    <?php if($_SESSION["tec_block"] == "S"){?>
                    <a id="<?php echo $rs->usu_id?>lock" href="javascript:setLocker('inc/save/saveuser.php?cmd=al&usu_id=<?php echo $rs->usu_id?>&p=<?php echo $rs->usu_status?>', '<?php echo $rs->usu_id?>lock',false)" title="Clique para bloquear ou desbloquear o usu&aacute;rio">
						<img src="<?php echo $lock?>" width="16" height="16" border="0" />
					</a>
                    <?php }else{?>
                    	<img src="<?php echo $lock?>" width="16" height="16" border="0" />
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
    	<div style="float: left"><?php echo $pm->getNumRows()?> clientes em <?php echo $pm->getTp()?> p&aacute;gina(s).</div>
        <div align="right" style="float:right; padding-right:0px">
        	<?php
				
        		echo $pm->getControls("index.php?inc=lcli&ot=".$_GET["ot"].$searchStr,
									  "<img src=\"img/btn/arrow_ll.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_l.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_r.png\" style=\"margin-bottom: -3px\" border=\"0\">",
									  "<img src=\"img/btn/arrow_rr.png\"  style=\"margin-bottom: -3px\" border=\"0\">");
			?>
        </div>
        <div style="clear:both"></div>
    </div>
</div>