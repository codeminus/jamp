<?php
error_reporting(E_ERROR);
@header("Content-Type: text/html; charset=ISO-8859-1", true);
require("sys/_session.php");
require("inc/_incs.php");
require("sys/_config.php");
require("sys/_logoconfig.php");
require("inc/_msgs.php");
require("sys/_dbconn.php");
require("inc/_lib.php");
require("sys/_version.php");


if (!isset($_GET["inc"]) || $_GET["inc"] == "") {



    header("Location: index.php?inc=los&ot=5");
    exit;

    /*
      $sql = "SELECT usu_last_link FROM usuario WHERE usu_id=".$_SESSION["tec_id"];
      $sqlQuery = mysql_query($sql);
      $rs = mysql_fetch_object($sqlQuery);
      if(strpos($rs->usu_last_link,"inc=&") > -1){
      header("Location: index.php?inc=los&ot=5");
      }else{
      header("Location: ".$rs->usu_last_link);
      }

     */
}
/* else{		
  $last_link = substr($_SERVER['REQUEST_URI'],strrpos($_SERVER['REQUEST_URI'],"/")+1,strrpos($_SERVER['REQUEST_URI'],"&ot"));
  $last_link = substr($last_link,0,strpos($last_link,"&ot")+5);

  $sql = "UPDATE usuario SET usu_last_link='".$last_link."' WHERE usu_id=".$_SESSION["tec_id"];
  $sqlQuery = mysql_query($sql);
  } */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="SHORTCUT ICON" href="favicon.ico">
            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
            <title>jamp - Sistema de ordem de servi&ccedil;o online - <?php echo $config["emp_nome"] ?></title>
            <link type="text/css" rel="stylesheet" href="css/calendario.css"  />
            <script type="text/javascript" src="sys/calendario.js"></script>
            <script language="javascript" src="sys/functions.js"></script>
<?php
require("css/generalCSS.php");
require("css/mainCSS.php");
require("css/osCSS.php");
?>
    </head>

    <body>
        <div id="disable" style="display:none"></div>
        <div id="ajaxContent"></div>
        <center>
            <div id="all">
                <div id="top">
                    <div style="float:left">
                        <a href="<?php echo $config["emp_site"] ?>" title="<?php echo $config["emp_site"] ?>" target="_blank">
                            <img src="img/<?php echo $config["emp_logo"] ?>" border="0" />
                        </a>
                    </div>
                    <div style="float:right">
                        <a href="http://jamp.onlinemanager.com.br" target="_blank" title="Gerenciamento On-line">
                            <img src="img/jamp-logo.jpg" width="198" height="75" border="0" />
                        </a>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div id="topline">
                    <div style="float: left">Painel de controle</div>    	
                    <div style="float:right;">
                        <a href="javascript:confirmLogout()" style="color: #ffffff" title="Sair do  sistema">
                            <img src="img/btn/logout.png" width="15" height="15" border="0">
                        </a>
                    </div>
                    <div align="right" style="float:right; margin-right: 4px">
                        <font color="<?php echo $colors[1] ?>">
<?php echo $_SESSION["tec_nome"] ?>
                        </font>             
                    </div>
                    <div style="clear:both"></div>
                </div>    
                <div id="middle">
<?php
if (isset($_GET["msg"])) {
    ?>
                        <center>
                            <div id="msg">
                        <?php echo $msgs[$_GET["msg"]] ?>
                            </div>
                        </center>	
                                <?php
                            }
                            ?>

                    <?php
                    if (isset($_GET["inc"])) {
                        include("inc/" . $incs[$_GET["inc"]]);
                    }
                    ?>        
                    <div id="indexBottomline" style="clear: both">        	
                        <div style="float:left">
                            <a href="javascript:ajaxRequest('inc/support.php?inc=<?php echo $_GET["inc"] ?>&ot=<?php echo $_GET["ot"] ?>&pg=<?php echo $pg ?>','ajaxContent')" style="color: #ffffff" title="Entre em contato com a equipe de suporte">
                                <img src="img/btn/info.png" border="0" width="16" height="16" />
                            </a>
                        </div>
                        <div style="float:left">
                            <a href="javascript:ajaxRequest('inc/support.php?inc=<?php echo $_GET["inc"] ?>&ot=<?php echo $_GET["ot"] ?>&pg=<?php echo $pg ?>','ajaxContent')" style="color: #ffffff" title="Entre em contato com a equipe de suporte">
                                <b>suporte</b>
                            </a>
                        </div>
                        <div align="right" style="float: right">
                            jamp <?php echo $version ?>
                        </div>
                        <div style="clear:both"></div>
                    </div>
                </div>           
            </div>
        </center>
<?php if ($_SESSION["tec_last_ip"] == "" && $_SESSION["flag"] != "1") { ?>
            <script>
                ajaxRequest('inc/welcome.php?inc=<?php echo $_GET["inc"] ?>&ot=<?php echo $_GET["ot"] ?>&pg=<?php echo $pg ?>', 'ajaxContent');
            </script>
<?php } elseif ($_SESSION["tec_view_alertas"] == "S" && $_SESSION["flag"] != "1") { ?>
            <script>
                ajaxRequest('inc/view/view_alerts.php?inc=<?php echo $_GET["inc"] ?>&ot=<?php echo $_GET["ot"] ?>&pg=<?php echo $pg ?>', 'ajaxContent');
            </script>
<?php } ?>
    </body>
</html>