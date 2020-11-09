<?php

    //recebemos nosso parâmetro vindo do form
    $parametro = isset($_POST['pesquisaCliente']) ? $_POST['pesquisaCliente'] : null;
    $msg = "";
    //começamos a concatenar nossa tabela
    $msg .="<div class='table-responsive-sm'>";
    $msg .="<table class='table table-hover table-striped'>";
    $msg .="    <thead>";
    $msg .="        <tr>";
    $msg .="            <th>Documento</th>";
    $msg .="            <th>Nome</th>";
    $msg .="            <th>Data de Nascimento</th>";
    $msg .="        </tr>";
    $msg .="    </thead>";
    $msg .="    <tbody>";
                    try {
                        //conecta ao BD
                        $con = pg_connect("host=186.226.71.179 port=5432 dbname=db1 user=TI password=informatica")or
                        die ("Não foi possível conectar ao servidor PostGreSQL");
                        $sql = "select distinct cadpac.codpac, cadpac.nomepac, cadpac.datanasc
                        from cadpac
                        inner join arqatend ON arqatend.codpac = cadpac.codpac
                        where nomepac ilike '%{$parametro}%' 
                        or CAST(cadpac.codpac as VARCHAR) = '{$parametro}'
                        order by cadpac.nomepac
                        limit 10;";
                        $resultado = pg_query($con, $sql );
                        //$con->desconectar();
                        if ( $resultado == false ) {
                            echo $con->error;
                            exit;}
                        }catch (PDOException $e){
                            echo $e->getMessage();
                        }
                        $result = pg_fetch_all($resultado);
                        //resgata os dados na tabela
                        if($result){
                            foreach ($result as $res) {
 
    $msg .="                <tr>";
    $msg .="                    <td class = 'pesquisaPac'><a href='#'>".$res['codpac']."</a></td>";
    $msg .="                    <td>".$res['nomepac']."</td>";
    $msg .="                    <td>".date('d/m/Y',strtotime($res['datanasc']))."</td>";
    $msg .="                </tr>";
                            }   
                        }else{
                            $msg = "";
                            $msg .="Nenhum resultado foi encontrado...";
                        }
    $msg .="    </tbody>";
    $msg .="</table>";
    $msg .="</div>";
    //retorna a msg concatenada
    echo $msg;
    ?>

    <script src="js/jquery-3.5.1.min.js"></script>
	<script type="text/javascript">
		$( document ).ready(function() {
            $( ".pesquisaPac" ).click(function(){
                    //var cod_atendimento = $("#cdPaciente").val();
                    var $row = $(this).closest("tr"),        // Finds the closest row <tr> 
					$tds2 = $row.find("td:nth-child(1)");    //Finds the 3rd <td> element
				
					$.each($tds2, function() {               // Visits every single <td> element
						$cod_atendimento = $(this).text();
                    });
                    
					$.ajax({
						 url : "listaAtendimentos.php?cdPaciente="+$cod_atendimento ,
						 type : "post",
					})
					.done(function(resp){
						if (isJson(resp)){
							var a = JSON.parse(resp);
							
							if (a.cod == -1){
								alert(a.msg);
								return false;
							}
						}else{
                            $("#resultado").show();
                            $("#voltar").show();
                            $("#resultado").html(resp);
                        }
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