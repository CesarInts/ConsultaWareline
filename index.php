<html>
	<head>
		<title>Atendimento</title>		
		<!--<meta http-equiv="Content-Type" content="text/html">-->
		<meta charset="utf-8">
	</head>
	<link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous">

	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col">
					Prontuario: <input id="cdPaciente" type="text" maxlength="7" onkeypress="return event.charCode >= 48 && event.charCode <= 57"></input>				
					<button type="button" id="bt-busca" class="btn btn-secondary">Buscar</button>
					<br><br>
				</div>				
			</div>
			<div class="row">
				<div class="col">
					<div id="resultado" style="display:hidden"></div>
				</div>
			</div>
		</div>
		
		
		<script src="js/jquery-3.5.1.min.js"></script>
		<script type="text/javascript">
			$( document ).ready(function() {
				
				$( "#bt-busca" ).click(function(){
					
					if ($("#cdPaciente").val() == ''){
						alert('Favor, insira um código de prontuário!');
						return false;
					}
						
					var cod_atendimento = $("#cdPaciente").val();
					$.ajax({
						 url : "listaAtendimentos.php?cdPaciente="+cod_atendimento ,
						 type : "post",
					})
					.done(function(resp){
						if (isJson(resp)){
							var a = JSON.parse(resp);
							
							if (a.cod == -1){
								$("#resultado").html('');
								alert(a.msg);
								return false;
							}
						}
						$("#resultado").show();
						$("#voltar").show();
						$("#resultado").html(resp);
					})
					.fail(function(jqXHR, textStatus, resp){
						 alert(resp);
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