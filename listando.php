<html>
	<head>
		<title>Atendimento</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<div id="ini">
			<label>Pesquisa:</label><br>
			Atendimento: <input type="text" id="atd" />
			<button type="button" id="bt-busca">Buscar</button>
			<br><br>
		</div>
		<div id="resultado" hidden></div>
		<script src="jquery-3.5.1.min.js"></script>
		<script type="text/javascript">
			$( document ).ready(function() {
				$( "#bt-busca" ).click(function(){
					$.ajax({
						 url : "listar.php",
						 
						 type : "post",
						 
						 data : {
							  'atd' : $("#atd").val()
						 },
					})
					.done(function(msg){
						$("#resultado").show();
						$("#resultado").html(msg);
					})
					.fail(function(jqXHR, textStatus, msg){
						 alert(msg);
					});
				});
			});
			
		</script>
	</body>
</html>