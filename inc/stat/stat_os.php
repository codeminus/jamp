<?php	
	session_start();	
	require("../../sys/_dbconn.php");
	require("../../css/_colors.php");	
	
?>
    <table id="cliview" class="alignleft" align="center">
    	<tr>
            <td colspan="2">
                <table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr style="background-color: <?php echo $colors[2]?>;">
                        <td style="padding: 2px"><b>Ordens de Servi&ccedil;o</b></td>
                        <td align="right">
                            <a href="javascript:hideRequests()" title="Fechar">
                                <img src="img/btn/close.png" width="16" height="16" border="0" style="padding-right: 2px" />
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
        	<td align="center" valign="middle">
            	<img src="inc/dinimg/img_os.php" />
            </td>
        </tr>
        <tr style="background-color: <?php echo $colors[2]?>;">
            <td colspan="2" align="right">
            	<div>
                	<div align="center">
                    	&nbsp;
                    </div>
                    <div align="center">                    
                    </div>
                    <div style="clear:both"></div>
                </div>
            </td>
        </tr>       
     </table>   
</form>