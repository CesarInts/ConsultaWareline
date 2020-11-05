<html>
	<head>
		<title>Buscar Atendimento</title>
		<meta charset="UTF-8">
	</head>
	<body>
	
		<a href="listar.php">Voltar</a>
		<br><br>
		<table border="">
			<tr>
				<th>corpotexto</th>
			</tr>
			<?php
				require_once("util/rtf.php");
				
				error_reporting(1);
				
			
				//conecta ao BD
				$con = pg_connect("host=186.226.71.179 port=5432 dbname=db1 user=TI password=informatica")or
				die ("Não foi possível conectar ao servidor PostGreSQL");
				//echo "Conexão efetuada com sucesso!!";

				// Deu erro ao conectar?
				//if ( $con->connect_error ) {
				//	
				//	echo "Erro ao conectar!";
				//	
				//}	
				$data = $_POST['atd'];
				$data2 = $_POST['seq'];
				//echo "$data2";
				$sql = "select corpotexto from evoenf 
						where numatend = '{$data}'
						and numseq = '{$data2}'";
			
				$retorno = pg_query($con, $sql );
			
				if ( $retorno == false ) {
					
					echo $con->error;
					exit;
					
				}
				
				//$reader = new RtfReader();
				//$rtf = //file_get_contents("text.rtf"); // Ou uma string
				//$reader->Parse($rtf);
				//$reader->root->dump();
				//$formatter = new RtfHtml();
				//echo $formatter->Format($reader->root);
			
				while ( $registro = pg_fetch_array($retorno) ) {
					
					$reader = new RtfReader();
					$formatter = new RtfHtml();
					$rtf = $registro["corpotexto"];
					$reader->Parse($rtf);
					$cTexto = $formatter->Format($reader->root);
					
					$id = $registro["numatend"];
					$numseq = $registro["numseq"];
					//$Sexo = $registro["sexo"];
					//$Motivo_Alta = $registro["motivo_alta"];
					//$Data_cadastro = $registro["data_cadastro"];
					//$Obito = $registro["data_obito"];
					
					echo "<tr>
							<td>$cTexto</td>
						</tr>";
					/* <a onclick=\"return confirm('Deseja realmente apagar?');\" para confirmar a ação */
				}
			
			?>
			
		</table>
	
	</body>
</html>