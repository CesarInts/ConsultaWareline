<html>
	<head>
		<title>Atendimento</title>
		<meta charset="UTF-8">
	</head>
	<body>
	
		<a href="cadastrar.php">Cadastrar</a>
		<br><br>
	
		<table border="">
			<tr>
				<th>ID</th>
				<th>Nascimento</th>
				<th>Sexo</th>
				<th>Motivo_Alta</th>
				<th>Data_cadastro</th>
				<th>Obito</th>
			</tr>
			<?php
			
				error_reporting(1);
			
				//conecta ao BD
				$con = pg_connect("host=192.168.25.5 port=5432 dbname=integracao_smpep user=integracao password=smpep123")or
				die ("Não foi possível conectar ao servidor PostGreSQL");
				echo "Conexão efetuada com sucesso!!";

				// Deu erro ao conectar?
				//if ( $con->connect_error ) {
				//	
				//	echo "Erro ao conectar!";
				//	
				//}	
				
				$sql = "SELECT * FROM public.vw_integracao_alta_medica
					WHERE data_obito is not null
					AND data_obito > '2020-09-01'
";
			
				$retorno = pg_query($con, $sql );
			
				if ( $retorno == false ) {
					
					echo $con->error;
					exit;
					
				}
			
				while ( $registro = pg_fetch_array($retorno) ) {
					
					$id = $registro["id_atendimento"];
					$Nascimento = $registro["data_nascimento"];
					$Sexo = $registro["sexo"];
					$Motivo_Alta = $registro["motivo_alta"];
					$Data_cadastro = $registro["data_cadastro"];
					$Obito = $registro["data_obito"];
					
					echo "<tr>
							<td>$id</td>
							<td>$Nascimento</td>
							<td>$Sexo</td>
							<td>$Motivo_Alta</td>
							<td>$Data_cadastro</td>
							<td>$Obito</td>
							<td><a href='ver.php?id=$id'>Ver</a></td>
							<td><a onclick=\"return confirm('Deseja realmente apagar?');\" href='apagar.php?id=$id'>Apagar</a></td>
						</tr>";
					/* <a onclick=\"return confirm('Deseja realmente apagar?');\" para confirmar a ação */
				}
			
			?>
			
		</table>
	
	</body>
</html>