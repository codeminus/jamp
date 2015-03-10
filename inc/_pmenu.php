<?php if($_SESSION["tec_cad_user"] == "S"){?>
<a href="javascript:ajaxRequest('inc/add/add_user.php?inc=<?php echo $_GET["inc"]?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Cadastrar Usu&aacute;rio">
    <img src="img/btn/add_user.png" width="16" height="16" border="0" />
</a>
<?php }?>
<?php 	if($_SESSION["tec_cad_prod"] == "S"){ ?>
<a href="javascript:ajaxRequest('inc/add/add_forn.php?inc=<?php echo $_GET["inc"]?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Cadastrar fornecedor">
    <img src="img/btn/add_fornecedor.png" width="16" height="16" border="0" />
</a>
<a href="javascript:ajaxRequest('inc/add/add_classprod.php?inc=<?php echo $_GET["inc"]?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Cadastrar classifica&ccedil;&atilde;o de produto">
    <img src="img/btn/add_classprod.png" width="16" height="16" border="0" />
</a>
<?php
		
		$cp_sql = "SELECT classprod_id FROM classprod WHERE classprod_status='A'";
		$cp_sqlQuery = mysql_query($cp_sql) or die(mysql_error());
		$cp_num = mysql_num_rows($cp_sqlQuery);
		
		if($cp_num > 0){
?>
			<a href="javascript:ajaxRequest('inc/add/add_prod.php?inc=<?php echo $_GET["inc"]?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Cadastrar produto">
                <img src="img/btn/add_prod.png" width="16" height="16" border="0" />
            </a>
<?php
		}else{
?>
			<img src="img/btn/add_prod_d.png" width="16" height="16" border="0" title="Cadastre pelo menos uma classifica&ccedil;&atilde;o"/>
<?php	
		}
		
		
?>
<a href="javascript:ajaxRequest('inc/add/add_serv.php?inc=<?php echo $_GET["inc"]?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Cadastrar servi&ccedil;o">
    <img src="img/btn/add_serv.png" width="16" height="16" border="0" />
</a>


<?php }?>

<?php if($_SESSION["tec_cad_cli"] == "S"){?>
<a href="javascript:ajaxRequest('inc/add/add_cli.php?inc=<?php echo $_GET["inc"]?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" title="Cadastrar cliente">
    <img src="img/btn/add_cli.png" width="16" height="16" border="0" />
</a>
<?php }?>
<?php if($_SESSION["tec_cad_equ"] == "S"){?>
<a href="index.php?inc=lcli&ot=0&msg=aequinfo" title="Cadastrar equipamento">
    <img src="img/btn/equipamento_a.png" width="16" height="16" border="0" />
</a>
<?php }?>
<?php if($_SESSION["tec_gerar_os"] == "S"){?>
<a href="index.php?inc=lequ&ot=0&msg=aosinfo" title="Gerar Ordem de servi&ccedil;o">
    <img src="img/btn/new_os.png" width="16" height="16" border="0" />
</a>
<?php }?>
