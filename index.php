<html>
	<head>
		<title>Consulta Wareline</title>		
		<!--<meta http-equiv="Content-Type" content="text/html">-->
		<link rel="sortcut icon" href="img/favicon.ico" type="image/x-icon" />
		<meta charset="utf-8">
	</head>
	<link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous">

	<body class="bg-light">
		<div class="container-fluid ">
			<nav class="navbar navbar-light bg-success">
				<div class="row w-100">
					<div class="col-4">
						<a class="navbar-brand" href="#">
							<img src="img/LOGOINTS.png" width="200px" height="30" class="d-inline-block align-top" alt="" loading="lazy">
						</a>
					</div>
					<div class="col-4 text-center text-white font-weight-bold"><h3>Consulta Wareline</h3></div>
					<div class="col-4"></div>
				</div>

			</nav>
<br>
			<div class="row w-50 m-auto p-auto ">
				<div class="col">
					<div class="input-group mb-3">
						<input id="cdPaciente" class="form-control" placeholder="Digite o Número de Prontuário ou Nome do Paciente" 
						aria-label="Recipient's username" aria-describedby="basic-addon2" type="text"></input>				
						<div class="input-group-append">
							<button type="button" id="bt-busca" class="btn btn-outline-secondary">Buscar</button>
						</div>
					</div>	
				</div>				
			</div>
			<div class="row">
				<div class="col">
					<div id="contentLoading">
					<div id="loading"></div>
					</div>
					<div id="resultado" style="display:hidden"></div>
				</div>
			</div>
		</div>
		
		
		<script src="js/jquery-3.5.1.min.js"></script>
		<script type="text/javascript">
			$( document ).ready(function() {
				
				//Aqui a ativa a imagem de load
				function loading_show(){
					$('#loading').html('<div class="d-flex justify-content-center"><div class="spinner-border" role="status"> <span class="sr-only">Loading...</span></div></div>').fadeIn('fast');
				}

				//Aqui desativa a imagem de loading
				function loading_hide(){
					$('#loading').fadeOut('fast');
				}
				
				// aqui a função ajax que busca os dados em outra pagina do tipo html, não é json
				function load_dados(valor, page, div){
					$.ajax
						({
							type: 'POST',							
							url: page,
							data: {pesquisaCliente: valor},
							beforeSend: function(){//Chama o loading antes do carregamento
								loading_show();
							},
							success: function(msg)
							{
								loading_hide();
								var data = msg;
								$(div).html(data).fadeIn();             
							}
						});
				}

				//Aqui uso o evento key up para começar a pesquisar, se valor for maior q 0 ele faz a pesquisa
				$('#cdPaciente').keyup(function(){
         
					//var valores = $('#form_pesquisa').serialize()//o serialize retorna uma string pronta para ser enviada
					
					//pegando o valor do campo #pesquisaCliente
					var $parametro = $(this).val();
					
					if($parametro.length >= 3)
					{
						load_dados($parametro, 'pesquisa.php', '#resultado');
					}
				});
			});
		</script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>