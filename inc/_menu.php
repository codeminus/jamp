<?php if($_SESSION["tec_view_user"] == "S"){?>
    <a href="index.php?inc=luser&ot=1" title="Usu&aacute;rios">
        <img src="img/btn/user.png" width="16" height="16" border="0" />
    </a>
<?php }?>
<?php if($_SESSION["tec_cad_prod"] == "S"){?>
    <a href="index.php?inc=lforn&ot=0" title="Fornecedores">
        <img src="img/btn/fornecedor.png" width="16" height="16" border="0" />
    </a>
<?php }?>
<?php if($_SESSION["tec_tipo"] != "C"){?>
    <a href="index.php?inc=lclassprod&ot=1" title="Classifica&ccedil;&otilde;es de produto">
        <img src="img/btn/classprod.png" width="16" height="16" border="0" />
    </a>
<?php }?>
<?php if($_SESSION["tec_tipo"] != "C"){?>
    <a href="index.php?inc=lprod&ot=2" title="Produtos">
        <img src="img/btn/prod_list.png" width="16" height="16" border="0" />
    </a>    
<?php }?>
<?php if($_SESSION["tec_tipo"] != "C"){?>
    <a href="index.php?inc=lserv&ot=1" title="Servi&ccedil;os">
        <img src="img/btn/serv_list.png" width="16" height="16" border="0" />
    </a>
<?php }?>

<?php if($_SESSION["tec_view_cli"] == "S"){?>
    <a href="index.php?inc=lcli&ot=0" title="Clientes">
        <img src="img/btn/cliente_v.png" width="16" height="16" border="0" />
    </a>
<?php }?>
<?php if($_SESSION["tec_view_equ"] == "S"){?>
<a href="index.php?inc=lequ&ot=0" title="Equipamentos">
    <img src="img/btn/equipamento_v.png" width="16" height="16" border="0" />
</a>
<?php }?>
<a href="index.php?inc=los&ot=5" title="Ordens de Servi&ccedil;o">
    <img src="img/btn/os_list.png" width="16" height="16" border="0" />
</a>
<?php if($_SESSION["tec_view_orc"] == "SSS"){?>
<a href="index.php?inc=lserv&ot=0" title="Servi&ccedil;os">
    <img src="img/btn/serv_list.png" width="16" height="16" border="0" />
</a>
<a href="index.php?inc=lequ&ot=0" title="Or&ccedil;amentos">
    <img src="img/btn/orc_list.png" width="16" height="16" border="0" />
</a>
<?php }?>
<a href="javascript:ajaxRequest('inc/view/view_info.php?inc=<?php echo $_GET["inc"]?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" 
   title="Minhas informa&ccedil&otilde;es pessoais">
    <img src="img/btn/edit_user.png" width="16" height="16" border="0" />
</a>
<a href="javascript:ajaxRequest('inc/view/view_alerts.php?inc=<?php echo $_GET["inc"]?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" 
   title="Alertas">
    <img src="img/btn/alerta.png" width="16" height="16" border="0" />
</a>

<?php if($_SESSION["tec_id"] == "1"){?>
    <a href="javascript:ajaxRequest('inc/view/view_conf.php?inc=<?php echo $_GET["inc"]?>&ot=<?php echo $_GET["ot"]?>&pg=<?php echo $pg?>','ajaxContent')" 
       title="Configura&ccedil&otilde;es">
        <img src="img/btn/config.png" width="16" height="16" border="0" />
    </a>

<?php }?>