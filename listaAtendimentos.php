<?php
	require_once("util/rtf.php");

	error_reporting(1);

	//conecta ao BD
	$con = pg_connect("host=186.226.71.179 port=5432 dbname=db1 user=TI password=informatica")or
	die ("Não foi possível conectar ao servidor PostGreSQL");


	$numProntuario = $_GET['cdPaciente'];
	$sql = "select cadpac.codpac codigoPaciente, cadpac.nomepac as NomePaciente, 
			atnd.numatend as numeroAtendimento,
			conv.nomeconv as convenio, 
			serv.nomeserv as servico, 
			resp.nomeesp as especialidade,
			--cc.nomecc, --ver
			diag.diagno as diagnostico, 
			prin.descrcid as descrcidPrin, 
			desc1.descrcid as descrcidDesc, ope.nomeope operador, 
			atu.nomeope, 
			numresp.nomeresp, proven.nomeproven,tiguia.descrguia as guia,
			atnd.motivo, cbo.descricbo, transp.nometransp as tipotransporte 
			from arqatend as atnd --atendimento
			inner join cadpac ON atnd.codpac = cadpac.codpac -- pacientes
			inner join cadplaco p 
			inner join cadconv conv on p.codconv = conv.codconv --convênio
			on atnd.codplaco = p.codplaco 
			inner join cadserv serv on atnd.codserv = serv.codserv -- serviço
			inner join cadesp resp on atnd.codesp = resp.codesp  -- especialidade
			inner join cadcc cc on atnd.codcc = cc.codcc --ver
			inner join cadprest prest on atnd.codprest = prest.codprest --prestadores
			inner join caddiag diag on atnd.coddiag = diag.coddiag --diagnostico
			left join tabcid prin on atnd.cidprin = prin.codcid --codigo internacional da doença principal
			left join tabcid desc1 on atnd.cidsec = desc1.codcid --codigo internacional da doença secundário
			inner join cadope ope on atnd.opecad = ope.codope --operador
			inner join cadope atu on atnd.opeatu = atu.codope --operador
			left join cadresp pacres on atnd.codpacres = pacres.codpac --responsáveis
			left join cadresp numresp on atnd.numresp = numresp.codpac --responsaveis
			inner join cdproven proven on atnd.codproven = proven.codproven --locais de proveniencia
			left join cadguia tiguia on atnd.codtipguia = tiguia.codtipguia --guia
			left join tabcbo cbo on atnd.codcbo = cbo.codcbo --ver
			left join cdtransp transp on atnd.codtransp = transp.codtransp --transporte
			--left join evoenf evo on atnd.numatend = evo.numatend -- evolução
			where cadpac.codpac = '{$numProntuario}'
			order by atnd.datatend";
	$retorno = pg_query($con, $sql );

	if ( $retorno == false ) {
		echo $con->error;
		exit;
	}

	$result = pg_fetch_all($retorno);
	
	if (!$result){
		
		$a = array(
			'cod' => -1,
			'msg' => 'Não existem prontuários para esse código de prontuário.'
		);

		$b = json_encode($a);
		
		echo $b;
		exit;
	}
?>
<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<head>
		<title>Atendimentos</title>
		<link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous">
	</head>
	<body>
		<!--<a href="listando.php">Voltar</a>-->
		<!--<br><br>-->
		<div id="tabela2" style="display:none">
		</div>
		<div id="tabela1" >
		<div class="table-responsive-sm">
			<table class="table table-sm table-hover table-striped table-bordered">
				<tr>
					<th colspan="2">Prontuário: <b><span id="codigo_paciente"></span></b></th>
					<th colspan="4">Nome: <b><span id="nome_paciente"></span></b></th>
				</tr>
				<tr>
					<th>Atendimento</th>
					<th>Convenio</th>
					<th>Serviço</th>
					<th>Especialidade</th>
					<th>Diagnostico</th>
					<th>CID Principal</th>
					<th>Descrição do CID</th>
					<th>Operador</th>
					<th>Responsável</th>
					<th>Origem</th>
					<th>Guia</th>
					<th>Motivo</th>
					<th>CBO</th>
					<th>Tipo de Transporte</th>
				</tr>
				<?php					

					$codigo_paciente = $result[0]['codigopaciente'];
					$nome_paciente = $result[0]['nomepaciente'];

					foreach($result as $registro){
						echo "<tr>
							<td class = 'pesquisaAtd'><a href='#'>".$registro["numeroatendimento"]."</a></td>
							<td>".utf8_encode($registro["convenio"])."</td>
							<td>".utf8_encode($registro["servico"])."</td>
							<td>".utf8_encode($registro["especialidade"])."</td>
							<td>".utf8_encode($registro["diagnostico"])."</td>
							<td>".utf8_encode($registro["descrcidprin"])."</td>
							<td>".utf8_encode($registro["descrciddesc"])."</td>
							<td>".utf8_encode($registro["operador"])."</td>
							<td>".utf8_encode($registro["nomeresp"])."</td>
							<td>".utf8_encode($registro["nomeproven"])."</td>
							<td>".utf8_encode($registro["guia"])."</td>
							<td>".utf8_encode($registro["motivo"])."</td>
							<td>".utf8_encode($registro["descricbo"])."</td>
							<td>".utf8_encode($registro["tipotransporte"])."</td>
						</tr>";
					}
					
				 ?>
			</table>
		</div>
	</div>
		<style>
			table,th,td {
			   text-align: center;   
			}
		</style>
		<script src="js/jquery-3.5.1.min.js"></script>
		<script type="text/javascript">
			$( document ).ready(function() {
				var codigo_paciente = '<?= $codigo_paciente; ?>';
				var nome_paciente = '<?= $nome_paciente; ?>';
				
				$("#codigo_paciente").html(codigo_paciente);
				$("#nome_paciente").html(nome_paciente);
				
				$(".pesquisaAtd").click(function() {
					var $row = $(this).closest("tr"),        // Finds the closest row <tr> 
					$tds2 = $row.find("td:nth-child(1)");    //Finds the 3rd <td> element
				
					$.each($tds2, function() {               // Visits every single <td> element
						$atd = $(this).text();
					});

					$.ajax({
						 url : "listar.php?atd="+$atd,						 
						 type : "post"
					})
					.done(function(resp){
												
					if (isJson(resp)){
						var a = JSON.parse(resp);

						if (a.cod == -1){
							alert(a.msg);
							return false;
						}
					}else{
						$("#tabela1").hide();
						$("#tabela2").show();
						$("#tabela2").html(resp);
					}
						
						
					})
					.fail(function(jqXHR, textStatus, msg){
						 alert(msg);
					});
				});
			});
			
			function isJson(str) {
				try {
					JSON.parse(str);
				} catch (e) {
					return false;
				}

				return true;
			}	
		</script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>