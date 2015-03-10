<?php
session_start();
include("../../sys/phplot/phplot.php");
include("../../sys/_dbconn.php");

if($_SESSION["tec_tipo"] == "C"){	
	$limitation = " AND os_cli_id=".$_SESSION["tec_id"];
}elseif($_SESSION["tec_tipo"] == "T" && $_SESSION["tec_view_os"] == "N"){
	$limitation = " AND os_tec_id=".$_SESSION["tec_id"];
}

$sql = "SELECT os_id FROM os where os_sta_id=6 AND os_historico='N'".$limitation;
$sqlQuery = mysql_query($sql);
$encaminhada = mysql_num_rows($sqlQuery);

$sql = "SELECT os_id FROM os where os_sta_id=4 AND os_historico='N'".$limitation;
$sqlQuery = mysql_query($sql);
$manutencao = mysql_num_rows($sqlQuery);

$sql = "SELECT os_id FROM os where os_sta_id=1 AND os_historico='N'".$limitation;
$sqlQuery = mysql_query($sql);
$orcamento = mysql_num_rows($sqlQuery);

$sql = "SELECT os_id FROM os where os_sta_id=8 AND os_historico='N'".$limitation;
$sqlQuery = mysql_query($sql);
$nao_auto = mysql_num_rows($sqlQuery);


$sql = "SELECT os_id FROM os where os_sta_id=5 AND os_historico='N'".$limitation;
$sqlQuery = mysql_query($sql);
$concluida = mysql_num_rows($sqlQuery);

$data = array(
  array('Encaminhada ao suporte', $encaminhada),
  array('Aguardando autorização', $orcamento),
  array('Não autorizada', $nao_auto),
  array('Em manutenção', $manutencao),
  array('Concluída', $concluida),
);

$plot = new PHPlot(500,300);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('pie');
$plot->SetDataType('text-data-single');
$plot->SetDataValues($data);
$plot->SetMarginsPixels(-180, 10, 30, 10);



# Set enough different colors;
$plot->SetDataColors(array('gray','yellow','red','cyan','DarkGreen'));

# Main plot title:
$plot->SetTitle("Ordens de Serviço");

# Build a legend from our data array.
# Each call to SetLegend makes one line as "label: value".
foreach ($data as $row)
  $plot->SetLegend(implode(': ', $row));

#$plot->SetLegendWorld(10,0);
$plot->DrawGraph();
?>
