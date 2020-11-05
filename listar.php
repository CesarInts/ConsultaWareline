<?php
	require_once("util/rtf.php");
				
	error_reporting(1);
					
	//conecta ao BD
	$con = pg_connect("host=186.226.71.179 port=5432 dbname=db1 user=TI password=informatica")or
	die ("Não foi possível conectar ao servidor PostGreSQL");

	$numatd = $_GET['atd'];
	$sql = "SELECT numseq, datagrav, corpotexto, cancelada, moticancel, datacancel FROM evoenf WHERE numatend = '{$numatd}' order by numseq";
	$sql2 = "SELECT numseq, datagrav, corpotexto, cancelada, moticancel, datacancel FROM evomed WHERE numatend = '{$numatd}' order by numseq";
	$sql3 = "SELECT numseq, datagrav, corpotexto, cancelada, moticancel, datacancel FROM evoana WHERE numatend = '{$numatd}' order by numseq";
	
	$retorno = pg_query($con, $sql );
	$retorno2 = pg_query($con, $sql2);
	$retorno3 = pg_query($con, $sql3);
			
	if ( $retorno == false || $retorno2 == false || $retorno3 == false ) {
		echo $con->error;
		exit;
	}
	
	$result = pg_fetch_all($retorno);
	$result2 = pg_fetch_all($retorno2);
	$result3 = pg_fetch_all($retorno3);

	if (!$result && !$result2 && !$result3){
		
		$a = array(
			'cod' => -1,
			'msg' => 'Atendimento não possui evolução'
		);

		$b = json_encode($a);
		
		echo $b;
		exit;
	}
	
	$result_ = "<table class='table table-sm table-hover table-bordered' id='tabela_evolucao'>
			 <thead class='thead-dark'>
			<tr>
				<th colspan='4'class='text-left'><b>Evoluções de Enfermagem e Equipe Multi</b></th>
			</tr>
			</thead>
			<tr>
				<th>Data Gravação</th>
				<th>Cancelada ?</th>
				<th>Motivo Cancelamento</th>
				<th>Data Cancelamento</th>
				<th>Prontuário</th>
			</tr>";
	$result2_ = "<table class='table table-sm table-hover table-bordered' id='tabela_evolucao_med'>
			 <thead class='thead-dark'>
			<tr>
				<th colspan='4' class='text-left'><b>Evoluções Médicas</b></th>
			</tr>
			</thead>
			<tr>
				<th>Data Gravação</th>
				<th>Cancelada ?</th>
				<th>Motivo Cancelamento</th>
				<th>Data Cancelamento</th>
				<th>Prontuário</th>
			</tr>";
	$result3_ = "<table class='table table-sm table-hover table-bordered' id='tabela_evolucao_med'>
			 <thead class='thead-dark'>
			<tr>
				<th colspan='4' class='text-left'><b>Anamnese</b></th>
			</tr>
			</thead>
			<tr>
				<th>Data Gravação</th>
				<th>Cancelada ?</th>
				<th>Motivo Cancelamento</th>
				<th>Data Cancelamento</th>
				<th>Prontuário</th>
			</tr>";
	//Anamnese
	foreach($result3 as $registro){
		
		$reader = new RtfReader();
		$formatter = new RtfHtml();
		$rtf = $registro["corpotexto"];
		$reader->Parse($rtf);
		$evolucao_paciente = $formatter->Format($reader->root);

		$result3_ .= "<tr>
			<td>".date('d/m/Y H:m:s',strtotime($registro["datagrav"]))."</td>
			<td>".$registro["cancelada"]."</td>";
			
		
			if ($registro["cancelada"] == 'S'){
				$result3_ .= "
					<td>".$registro["moticancel"]."</td>
					<td>".date('d/m/Y H:m:s',strtotime($registro["datacancel"]))."</td>";
			}else{
				$result3_ .= "<td> -- </td><td> -- </td>";
			}
			
			$result3_ .= "<td style='display:none'>".$evolucao_paciente."</td>
			<td><button type='button' class='btn btn-secondary bt-mostrar-rtf' data-toggle='modal' data-target='#modal_evolucao_paciente'>Visualizar</button></td>
		</tr>";
		
	}
	
	$result3_ .= "</table>";
	if($result3){
		echo $result3_;}
		
	//evolução de enf
	foreach($result as $registro){
		
		$reader = new RtfReader();
		$formatter = new RtfHtml();
		$rtf = $registro["corpotexto"];
		$reader->Parse($rtf);
		$evolucao_paciente = $formatter->Format($reader->root);

		$result_ .= "<tr>
			<td>".date('d/m/Y H:m:s',strtotime($registro["datagrav"]))."</td>
			<td>".$registro["cancelada"]."</td>";
			
		
			if ($registro["cancelada"] == 'S'){
				$result_ .= "
					<td>".$registro["moticancel"]."</td>
					<td>".date('d/m/Y H:m:s',strtotime($registro["datacancel"]))."</td>";
			}else{
				$result_ .= "<td> -- </td><td> -- </td>";
			}
			
			$result_ .= "<td style='display:none'>".$evolucao_paciente."</td>
			<td><button type='button' class='btn btn-secondary bt-mostrar-rtf' data-toggle='modal' data-target='#modal_evolucao_paciente'>Visualizar</button></td>
		</tr>";
		
	}
	
	$result_ .= "</table>";
	
	echo $result_;

	//Evolução medica
	foreach($result2 as $registro){
		
		$reader = new RtfReader();
		$formatter = new RtfHtml();
		$rtf = $registro["corpotexto"];
		$reader->Parse($rtf);
		$evolucao_paciente = $formatter->Format($reader->root);

		$result2_ .= "<tr>
			<td>".date('d/m/Y H:m:s',strtotime($registro["datagrav"]))."</td>
			<td>".$registro["cancelada"]."</td>";
			
		
			if ($registro["cancelada"] == 'S'){
				$result2_ .= "
					<td>".$registro["moticancel"]."</td>
					<td>".date('d/m/Y H:m:s',strtotime($registro["datacancel"]))."</td>";
			}else{
				$result2_ .= "<td> -- </td><td> -- </td>";
			}
			
			$result2_ .= "<td style='display:none'>".$evolucao_paciente."</td>
			<td><button type='button' class='btn btn-secondary bt-mostrar-rtf' data-toggle='modal' data-target='#modal_evolucao_paciente'>Visualizar</button></td>
		</tr>";
		
	}
	
	$result2_ .= "</table>";
	
	echo $result2_;
	
	echo '
<div class="modal fade" id="modal_evolucao_paciente" tabindex="-1" role="dialog" aria-labelledby="evolucao_paciente" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="evolucao_paciente">Evolução do Paciente</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div id="mostrar-rtf" class="modal-body">

			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>';

?>

<style>
	table,th,td {
	   text-align: center;   
	}
</style>

<script src="js/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
	$( document ).ready(function() {
		$(".bt-mostrar-rtf").click(function() {
			var $row = $(this).closest("tr"),
			$texto_rtf = $row.find("td:nth-child(5)").html();

			$("#mostrar-rtf").html($texto_rtf);
		});
		$(".bt-voltar").click(function() {
			$("#tabela2").hide();
			$("#tabela1").show();
		});
	});
			
</script>
<script src="js/bootstrap.min.js"></script>