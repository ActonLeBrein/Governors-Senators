<?php include('data.php') ?>
<html>
<head>
</head>
<!DOCTYPE HTML>
<html lang="en" ng-app="uniApp">
<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gobernadores y Senadores</title>	
	<link rel="stylesheet" type="text/css" href="../fonts/stylesheet.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/style_mobile.css"/>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css">

	<!-- Scripts -->
	<script src="../sources/jquery.min1.11.1.js"></script>
	<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>	
	<script src="http://d3js.org/d3.v3.min.js"></script>
	<script src="http://d3js.org/topojson.v1.min.js"></script>
	<script src="http://d3js.org/queue.v1.min.js"></script>
	<script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>
	<script src="../sources/angular.min.js"></script>

	<script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>  

	<script>		
		var list_sendadores = <?php echo $list_sendadores; ?>;
		var list_gobernadores = <?php echo $list_gobernadores; ?>;

		var select_values = [];
		$.each( list_sendadores , function (index,value){           
			select_values.push(value.statename);
		});

		$(document).ready(function(){
			$("#search").autocomplete({
			    source: select_values,
			    select: function( event, ui ) {				      	
			 		var e = document.createEvent('UIEvents');
					e.initUIEvent('click', true, true, window, 1);
					var state_id = $.grep(list_sendadores, function( a ) {
  							return a.statename == ui.item.value;
					});	
					d3.select("#state_"+state_id[0].FIPS_Code).node().dispatchEvent(e);
					/*console.log(ui.item.value);*/
			  	}    
			});
		});
	</script>
	<script src="js/controllers.js"></script>
</head>
<body ng-controller="indexCtrl" id="body">
	<div class="content">
		<div class="header">
   			<div>TODO LO QUE QUERRÍAS SABER SOBRE LAS ELECCIONES <br> DE NOVIEMBRE PARA SENADO Y GOBERNACIONES</div>
			<div class="lang_version">
   				<img id="lang" src="images/gov_sen_ing.png">
  			</div>
  		</div>
		<div class="title">
			¿Qué posibilidades tiene cada partido de obtener la mayoría en estas elecciones?
		</div>
		<hr>
		<div class="contenido">
			<div class="modal" ng-show="modal">
				<div id="contenedor_instrucciones" ng-show="instrucciones">
					<div class="titulo_nota">
						<div class="cerrar" ng-click="instrucciones = false; modal=false;"></div>
						<div class="tituloT">Instrucciones</div>
					</div>
					<p>Para navegar la siguiente visualización elige la opción de "Senado" o "Gobernadores" para ver el mapa de la elección de Senadores y Gobernadores respectivamente. Después haz clic en un estado que te interesa ver o búscalo en la barra de búsqueda y haz clic en su nombre.</p>
					<p>Al hacer esto se desplegará la información relevante del estado electo y de los candidatos que estarán participando en la elección. Siempre puedes regresar al mapa haciendo clic en el botón de "Regresar al mapa" y elegir otro estado.</p>
					<br>
					<div class="zoomout" ng-click="modal = false">Regresar al mapa</div>
				</div>
			</div>
			<div class="autocomplete">
	  			  <input id="search">	  			  
			</div>
			<div class="candidatos">
				<span id="senador" class="opcion_color active" ng-click="show('senador');">
					SENADORES
					<svg height="16" width="16">
						<circle cx="8" cy="10.5" r="4" stroke-width="3" />
					</svg>
				</span>
				|
				<span id="gobernador" class="opcion_color" ng-click="show('gobernador');">
					<svg height="16" width="16">
						<circle cx="8" cy="10.5" r="4" stroke-width="3" />
					</svg>
					GOBERNADORES
				</span>
			</div>
			<div id="info_cand_sena" ng-show="senadores">	
				<br/>
				<br/>
				<div class="zoomout" ng-click="senadores = false">Regresar al mapa</div>				
				<div ng-show="hayeleccion() == true">
					<div id="sena_details" class="web">
						<p class="state_can">{{senador_seleccionado.statename}}</p>
						<div class="svg_state">
							<svg>
								<g class="g_senador">
									<path class="path_state_senador"></path>
								<g>
							</svg>
						</div>
						<table class="t1">
							<tr>
								<th>Como se ven las encuestas</th>
								<th class="tb_mid">¿Es competido?</th>
								<th class="tb_mid">% de hispanos</th>
								<th>% de hispanos de 18 años o más con ciudadanía</th>
							</tr>
							<tr>
								<td>
									<!--p class="demo">{{senador_seleccionado.pollpercdem | number:0}}%</p-->
									<p class="demo">{{senador_seleccionado.pollpercdem | number:0}}{{senador_seleccionado.pollpercdem != 'N/A' ? '%' : 'N/A' }}</p>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#3E65AA" stroke-width="3" fill="#3E65AA" />
									</svg>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#E93838" stroke-width="3" fill="#E93838" />
									</svg>
									<!--p class="rep">{{senador_seleccionado.pollpercrep | number:0}}%</p-->
									<p class="rep">{{senador_seleccionado.pollpercrep | number:0}}{{senador_seleccionado.pollpercrep != 'N/A' ? '%' : 'N/A' }}</p>
								</td>
								<td class="tb_mid curve" ng-show="senador_seleccionado.competido_lean == '1'">Sí</td>
								<td class="tb_mid curve" ng-show="senador_seleccionado.competido_lean == '0'">No</td>
								<td class="tb_mid curve">{{senador_seleccionado.hisp_p | number:1}}%</td>
								<td class="curve">{{senador_seleccionado.hispvot_p | number:1}}%</td>
							</tr>
						</table>
						<table class="t2">
							<tr>
								<th>Senador actual</th>
								<th class="tb_mid">Número de mandatos</th>
								<th class="tb_mid">Estatus para la elección</th>
								<th>¿Es de orígen hispano?</th>
							</tr>
							<tr>
								<td>{{senador_seleccionado.senator_name}}</td>
								<td class="tb_mid curve">{{senador_seleccionado.senator_terms}}</td>
								<td class="tb_mid curve">{{senador_seleccionado.senrunning_esp}}</td>
								<td class="curve" ng-show="senador_seleccionado.sen_hisp == 1">Sí</td>
								<td class="curve" ng-show="senador_seleccionado.sen_hisp == 0">No</td>
							</tr>
						</table>

						<div ng-show="hayspecial() == true" class="special_election_msg">Elección especial: se eligen ambos senadores</div>
					
					</div>		


					<!-- Para móvil -->
					<div id="sena_details" class="movil">
						<p class="state_can">{{senador_seleccionado.statename}}</p>
						<div class="svg_state">
							<svg>
								<g class="g_senador">
									<path class="path_state_senador"></path>
								<g>
							</svg>
						</div>
						<table class="extra">
							<tr>
								<td>Como se ven las encuestas</td>
								<td class="dos">
									<!--p class="demo">{{senador_seleccionado.pollpercdem | number:0}}%</p-->
									<p class="demo">{{senador_seleccionado.pollpercdem | number:0}}{{senador_seleccionado.pollpercdem != 'N/A' ? '%' : 'N/A' }}</p>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#3E65AA" stroke-width="3" fill="#3E65AA" />
									</svg>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#E93838" stroke-width="3" fill="#E93838" />
									</svg>
									<!--p class="rep">{{senador_seleccionado.pollpercrep | number:0}}%</p-->
									<p class="rep">{{senador_seleccionado.pollpercrep | number:0}}{{senador_seleccionado.pollpercrep != 'N/A' ? '%' : 'N/A' }}</p>
								</td>
							</tr>
							<tr>
								<td>¿Es competido?</td>
								<td class="dos" ng-show="senador_seleccionado.competido_lean == '1'">Sí</td>
								<td class="dos" ng-show="senador_seleccionado.competido_lean == '0'">No</td>
							</tr>
							<tr>
								<td>% de hispanos</td>
								<td class="dos">{{senador_seleccionado.hisp_p | number:1}}%</td>
							</tr>
							<tr>
								<td>% de hispanos de 18 años o más con ciudadanía</td>
								<td class="dos">{{senador_seleccionado.hispvot_p | number:1}}%</td>
							</tr>							
						</table>
						<table class="t2">
							<tr>
								<td>Senador actual</td>
								<td class="dos">{{senador_seleccionado.senator_name}}</td>
							</tr>
							<tr>
								<td class="">Número de mandatos</td>
								<td class="dos">{{senador_seleccionado.senator_terms}}</td>								
							</tr>
							<tr>							
								<td>Estatus para la elección</td>
								<td class="dos">{{senador_seleccionado.senrunning_esp}}</td>
							</tr>
							<tr>							
								<td>¿Es de orígen hispano?</td>
								<td class="dos" ng-show="senador_seleccionado.sen_hisp == 1">Sí</td>
								<td class="dos" ng-show="senador_seleccionado.sen_hisp == 0">No</td>
							</tr>
						</table>

						<div ng-show="hayspecial() == true" class="special_election_msg">Elección especial: se eligen ambos senadores</div>
					
					</div>		




					<div class="candidates_senadores_left">
						<hr class="line_cans_left">
						<p class="cand_num">Candidato 1</p>
						<hr class="line_cans_left">						
						<!--img src="images/demo_sign.png" class="sign_can"-->						
						<div ng-show="incumbent() == true">
							<img ng-src="fotos/{{getImageUrl(senador_seleccionado.incumbent_candidate)}}" class="can_pic" ng-show="senador_seleccionado.incumbent_party != ''">
							<div class="sign_can {{getClassParty(senador_seleccionado.incumbent_party)}}" ng-show="senador_seleccionado.incumbent_party != ''">
								{{getParty(senador_seleccionado.incumbent_party)}}
							</div>
							<div class="details">
								<ul>
									<li class="name_candidate">
										{{senador_seleccionado.incumbent_candidate}} <span class="incumbent">(i)</span>
									</li>
									<li style="word-wrap: break-word;">
										<a href="{{senador_seleccionado.link_ic}}" target="_blank">{{senador_seleccionado.link_ic}}</a>
									</li>
									<li>
										Número de mandatos <span class="data2">{{senador_seleccionado.senator_terms}}</span>
									</li>
									<li>
										¿Es de orígen hispano?<span class="data2" ng-show="senador_seleccionado.incumbent_hisp == 1">Sí</span><span class="data2" ng-show="senador_seleccionado.incumbent_hisp == 0">No</span>
									</li>
									<li>
										Fondos <br/> recaudados <span class="data">{{senador_seleccionado.incumbent_funds}}</span>
									</li>
									<li>
										Comités<br/>
										<span class="data3" ng-show='senador_seleccionado.committee1_esp != ""'>- {{senador_seleccionado.committee1_esp}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee2_esp != ""'>- {{senador_seleccionado.committee2_esp}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee3_esp != ""'>- {{senador_seleccionado.committee3_esp}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee4_esp != ""'>- {{senador_seleccionado.committee4_esp}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee5_esp != ""'>- {{senador_seleccionado.committee5_esp}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee6_esp != ""'>- {{senador_seleccionado.committee6_esp}}<br/></span>
									</li>
								</ul>
								<p class="l_incumbent">
									<span class="incumbent">(i)</span> el candidato es el senador en turno
								</p>
							</div>
						</div>					
						<div ng-show="incumbent() == false">
							<img ng-src="fotos/{{getImageUrl(senador_seleccionado.candidato1)}}" class="can_pic" ng-show="senador_seleccionado.candidato1_party != ''">
						<div class="sign_can {{getClassParty(senador_seleccionado.candidato1_party)}}" ng-show="senador_seleccionado.candidato1_party != ''">
							{{getParty(senador_seleccionado.candidato1_party)}}
						</div>
							<div class="details">
								<ul>
									<li class="name_candidate">
										{{senador_seleccionado.candidato1}}
									</li>
									<li style="word-wrap: break-word;">
										<a href="{{senador_seleccionado.link_c1}}" target="_blank">{{senador_seleccionado.link_c1}}</a>
									</li>
									<li>
										Fondos <br/> recaudados <span class="data">{{senador_seleccionado.candidato1_funds}}</span>
									</li>
									<li>								
										Puesto anterior<br/>
										<span class="data3">{{senador_seleccionado.candidato1_job_esp}}</span>
									</li>								
								</ul>
							</div>
						</div>
					</div>	
					<div class="candidates_senadores_right" ng-show="senador_seleccionado.candidato1 != ''">					
						<hr class="line_cans_right">
						<p class="cand_num">Candidato 2</p>
						<hr class="line_cans_right">
						<div ng-show="incumbent() == true">
							<img ng-src="fotos/{{getImageUrl(senador_seleccionado.candidato1)}}" class="can_pic" ng-show="senador_seleccionado.candidato1_party != ''">
							<div class="sign_can {{getClassParty(senador_seleccionado.candidato1_party)}}" ng-show="senador_seleccionado.candidato1_party != ''">
								{{getParty(senador_seleccionado.candidato1_party)}}
							</div>
							<div class="details">
								<ul>
									<li class="name_candidate">
										{{senador_seleccionado.candidato1}}
									</li>
									<li style="word-wrap: break-word;">																		
										<a href="{{senador_seleccionado.link_c1}}" target="_blank">{{senador_seleccionado.link_c1}}</a>
									</li>
									<li>
										Fondos <br/> recaudados <span class="data">{{senador_seleccionado.candidato1_funds}}</span>
									</li>
									<li>
										¿Es de orígen hispano?<span class="data2" ng-show="senador_seleccionado.candidato1_hispano == 1">Sí</span><span class="data2" ng-show="senador_seleccionado.candidato1_hispano == 0">No</span>
									</li>
									<li>
										Puesto anterior <br/>																	
										<span class="data3">{{senador_seleccionado.candidato1_job_esp}}</span>
									</li>
								</ul>								
							</div>
						</div>
						<div ng-show="incumbent() == false">
							<img ng-src="fotos/{{getImageUrl(senador_seleccionado.candidato2)}}" class="can_pic" ng-show="senador_seleccionado.candidato2_party != ''">
							<div class="sign_can {{getClassParty(senador_seleccionado.candidato2_party)}}" ng-show="senador_seleccionado.candidato2_party != ''">
								{{getParty(senador_seleccionado.candidato2_party)}}
							</div>
							<div class="details">
								<ul>
									<li class="name_candidate">
										{{senador_seleccionado.candidato2}}
									</li>
									<li style="word-wrap: break-word;">
										<a href="{{senador_seleccionado.link_c2}}" target="_blank">{{senador_seleccionado.link_c2}}</a>
									</li>
									<li>
										Fondos <br/> recaudados <span class="data">{{senador_seleccionado.candidato2_funds}}</span>
									</li>
									<li>
										Puesto anterior <br/>																	
										<span class="data3">{{senador_seleccionado.candidato2_job_esp}}</span>
									</li>									
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div ng-show="hayspecial() == true">
					<div class="special_title">ELECCIÓN ESPECIAL</div>
					<div id="sena_details" class="especial">
						<p class="state_can">{{senador_seleccionado.statename}}</p>
						<div class="svg_state">
							<svg>
								<g class="g_senador">
									<path class="path_state_senador"></path>
								<g>
							</svg>
						</div>						
						<table class="t1 web">
							<tr>
								<th>Senador actual</th>
								<th class="tb_mid">Número de mandatos</th>
								<th class="tb_mid">Estatus para la elección</th>
								<th>¿Es de orígen hispano?</th>
								<th>Como se ven en las encuestas</th>
							</tr>
							<tr>
								<td>{{senador_seleccionado.senator_nameSE}}</td>
								<td class="tb_mid curve">{{senador_seleccionado.senator_termsSE}}</td>
								<td class="tb_mid curve">{{senador_seleccionado.senrunning_esp_SE}}</td>
								<td class="curve" ng-show="senador_seleccionado.incumbent_hispSE == 1">Sí</td>
								<td class="curve" ng-show="senador_seleccionado.incumbent_hispSE == 0">No</td>
								<td>
									<!--p class="demo">{{senador_seleccionado.pollpercdem_SE | number:0}}%</p-->
									<p class="demo">{{senador_seleccionado.pollpercdem_SE | number:0}}{{senador_seleccionado.pollpercdem_SE != 'N/A' ? '%' : 'N/A' }}</p>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#3E65AA" stroke-width="3" fill="#3E65AA" />
									</svg>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#E93838" stroke-width="3" fill="#E93838" />
									</svg>
									<!--p class="rep">{{senador_seleccionado.pollpercrep_SE | number:0}}%</p-->
									<p class="rep">{{senador_seleccionado.pollpercrep_SE | number:0}}{{senador_seleccionado.pollpercrep_SE != 'N/A' ? '%' : 'N/A' }}</p>
								</td>
							</tr>
						</table>

						<!-- Para móvil -->
						<table class="extra movil">
							<tr>
								<td>Senador actual</td>
								<td class="dos">{{senador_seleccionado.senator_nameSE}}</td>
							</tr>
							<tr>
								<td class="tb_mid">Número de mandatos</td>
								<td class="tb_mid curve dos">{{senador_seleccionado.senator_termsSE}}</td>
							</tr>
							<tr>
								<td class="tb_mid">Estatus para la elección</td>
								<td class="tb_mid curve dos">{{senador_seleccionado.senrunning_esp_SE}}</td>
							</tr>
							<tr>				
								<td>¿Es de orígen hispano?</td>
								<td class="curve dos" ng-show="senador_seleccionado.incumbent_hispSE == 1">Sí</td>
								<td class="curve dos" ng-show="senador_seleccionado.incumbent_hispSE == 0">No</td>
							</tr>
							<tr>
								<td>Como se ven en las encuestas</td>					
								<td class="dos">
									<!--p class="demo">{{senador_seleccionado.pollpercdem_SE | number:0}}%</p-->
									<p class="demo">{{senador_seleccionado.pollpercdem_SE | number:0}}{{senador_seleccionado.pollpercdem_SE != 'N/A' ? '%' : 'N/A' }}</p>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#3E65AA" stroke-width="3" fill="#3E65AA" />
									</svg>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#E93838" stroke-width="3" fill="#E93838" />
									</svg>
									<!--p class="rep">{{senador_seleccionado.pollpercrep_SE | number:0}}%</p-->
									<p class="rep">{{senador_seleccionado.pollpercrep_SE | number:0}}{{senador_seleccionado.pollpercrep_SE != 'N/A' ? '%' : 'N/A' }}</p>
								</td>
							</tr>
						</table>
						<div ng-show="hayspecial() == true && hayeleccion() == false" class="special_election_msg">Elección especial: se eligen ambos senadores</div>
					</div>		
					<div class="candidates_senadores_left">
						<hr class="line_cans_left">
						<p class="cand_num">Candidato 1</p>
						<hr class="line_cans_left">						
						<div ng-show="incumbentSE() == true">
							<img ng-src="fotos/{{getImageUrl(senador_seleccionado.incumbent_candidateSE)}}" class="can_pic">
							<div class="sign_can {{getClassParty(senador_seleccionado.incumbent_partySE)}}">
								{{getParty(senador_seleccionado.incumbent_partySE)}}
							</div>
							<div class="details">
							<ul>
								<li class="name_candidate">
									{{senador_seleccionado.incumbent_candidateSE}} <span class="incumbent">(i)</span>
								</li>
								<li style="word-wrap: break-word;">
									<a href="{{senador_seleccionado.link_ic_SE}}" target="_blank">{{senador_seleccionado.link_ic_SE}}</a>
								</li>
								<li>
									Número de mandatos <span class="data2">{{senador_seleccionado.senator_termsSE}}</span>
								</li>
								<li>
									¿Es de orígen hispano?<span class="data2" ng-show="senador_seleccionado.incumbent_hispSE == 1">Sí</span><span class="data2" ng-show="senador_seleccionado.incumbent_hispSE == 0">No</span>
								</li>
								<li>
									Fondos <br/> recaudados <span class="data">{{senador_seleccionado.incumbent_fundsSE}}</span>
								</li>
								<li>
									Comités<br/>
									<span class="data3" ng-show='senador_seleccionado.committee1_esp_SE != ""'>- {{senador_seleccionado.committee1_esp_SE}}<br/></span>
								<span class="data3" ng-show='senador_seleccionado.committee2_esp_SE != ""'>- {{senador_seleccionado.committee2_esp_SE}}<br/></span>
								<span class="data3" ng-show='senador_seleccionado.committee3_esp_SE != ""'>- {{senador_seleccionado.committee3_esp_SE}}<br/></span>
								<!--span class="data3" ng-show='senador_seleccionado.committe4_esp_SE != ""'>- {{senador_seleccionado.committe4_esp_SE}}<br/></span>
								<span class="data3" ng-show='senador_seleccionado.committe5_esp_SE != ""'>- {{senador_seleccionado.committe5_esp_SE}}<br/></span>
								<span class="data3" ng-show='senador_seleccionado.committe6_esp_SE != ""'>- {{senador_seleccionado.committe6_esp_SE}}<br/></span-->
								</li>
							</ul>	
							<p class="l_incumbent">
								<span class="incumbent">(i)</span> el candidato es el senador en turno
							</p>
							</div>							
						</div>					
						<div ng-show="incumbentSE() == false">
							<img ng-src="fotos/{{getImageUrl(senador_seleccionado.candidato1_SE)}}" class="can_pic">
							<div class="sign_can {{getClassParty(senador_seleccionado.candidato1_party_SE)}}">
								{{getParty(senador_seleccionado.candidato1_party_SE)}}
							</div>
							<div class="details">
								<ul>
									<li class="name_candidate">
										{{senador_seleccionado.candidato1_SE}}
									</li>
									<li style="word-wrap: break-word;">								
										<a href="{{senador_seleccionado.link_c1_SE}}" target="_blank">{{senador_seleccionado.link_c1_SE}}</a>
									</li>
									<li>
										Fondos <br/> recaudados <span class="data">{{senador_seleccionado.candidato1_fundsSE}}</span>
									</li>								
									<li>
										Puesto anterior<br/>								
										<span class="data3">{{senador_seleccionado.candidato1_job_esp_SE}}</span>
									</li>
								</ul>
							</div>
						</div>
					</div>	
					<div class="candidates_senadores_right">					
						<hr class="line_cans_right">
						<p class="cand_num">Candidato 2</p>
						<hr class="line_cans_right">
						<div ng-show="incumbentSE() == true">							
							<img ng-src="fotos/{{getImageUrl(senador_seleccionado.candidato1_SE)}}" class="can_pic">
							<div class="sign_can {{getClassParty(senador_seleccionado.candidato1_party_SE)}}">
								{{getParty(senador_seleccionado.candidato1_party_SE)}}
							</div>
							<div class="details">
								<ul>
									<li class="name_candidate">
										{{senador_seleccionado.candidato1_SE}}
									</li>
									<li style="word-wrap: break-word;">
										<a href="{{senador_seleccionado.link_c1_SE}}" target="_blank">{{senador_seleccionado.link_c1_SE}}</a>
									</li>
									<li>
										Fondos <br/> recaudados<span class="data">{{senador_seleccionado.candidato1_fundsSE}}</span>
									</li>
									<li>
										Puesto anterior<br/>
										<span class="data3">{{senador_seleccionado.candidato1_job_esp_SE}}</span>
									</li>
								</ul>								
							</div>
						</div>
						<div ng-show="incumbentSE() == false">
							<img ng-src="fotos/{{getImageUrl(senador_seleccionado.candidato2_SE)}}" class="can_pic">
							<div class="sign_can {{getClassParty(senador_seleccionado.candidato2_party_SE)}}">
								{{getParty(senador_seleccionado.candidato2_party_SE)}}
							</div>
							<div class="details">
								<ul>
									<li class="name_candidate">
										{{senador_seleccionado.candidato2_SE}}
									</li>
									<li style="word-wrap: break-word;">
										<a href="{{senador_seleccionado.link_c2_SE}}" target="_blank">{{senador_seleccionado.link_c2_SE}}</a>
									</li>
									<li>
										Fondos <br/> recaudados <span class="data">{{senador_seleccionado.candidato2_fundsSE}}</span>
									</li>
									<li>
										Puesto anterior<br/>
										<span class="data3">
											{{senador_seleccionado.candidato2_job_esp_SE}}
										</span>
									</li>
								</ul>
							</div>
						</div>
					</div>					
				</div>
				<br/>
				<div class="zoomout" ng-click="senadores = false">Regresar al mapa</div>				
			</div>
			<div class="infoExtra">
	  			<div class="verInstrucciones" ng-click="instrucciones = true; modal = true;">AYUDA <img src="images/ayuda.png"></div>
			</div>
			<div id="state_map"></div>
			<div class="code_colors web">
				<img src="images/info_web_sen_esp.png" ng-show="seleccionada == 'senador'">
				<img src="images/info_web_gob_esp.png" ng-show="seleccionada == 'gobernador'">
			</div>
			<div class="code_colors movil">			
				<img src="images/info_mob_sen_esp.png" ng-show="seleccionada == 'senador'">
				<img src="images/info_mob_gob_esp.png" ng-show="seleccionada == 'gobernador'">
			</div>
			<div class="nota">
				<div class="titulo_nota">
					<p class="nm">Nota Metodológica:</p>
					<p class="linea web"></p>
				</div>
				<div class="parrafo">
					<p>La información utilizada en esta visualización se tomó de una serie de fuentes dedicadas a seguir las elecciones intermedias de Estados Unidos.</p>
					<p>Para los Senadores y Gobernadores en turno y para los candidatos, la información se tomó de: <a data-behavior="truncate" href="http://www.politico.com/2014-election/results/map/senate/" target="_blank">Politico</a>, <a data-behavior="truncate" href="http://ballotpedia.org/Portal:Elections" target="_blank">Ballotpedia</a>, <a data-behavior="truncate" href="http://www.nga.org/cms/2014Elections" target="_blank">el National Governors Association</a> y <a data-behavior="truncate" href="http://cookpolitical.com/senate/charts/race-ratings" target="_blank">Cook Political</a>. </p>
					<p>Los resultados de las encuestas se tomaron de <a data-behavior="truncate" href="http://www.realclearpolitics.com/epolls/2014/senate/2014_elections_senate_m…" target="_blank">Real Clear Politics</a>, específicamente se tomó el promedio de encuestas que se hace en esta página.</p>
					<p>Los datos sobre fondos recaudados por lo candidatos al Senado se tomaron del <a data-behavior="truncate" href="http://www.fec.gov/data/CandidateSummary.do" target="_blank">Federal Election Commission</a>.</p>
					<p>Los datos de población hispana fueron obtenidos del <a data-behavior="truncate" href="http://factfinder2.census.gov/faces/nav/jsf/pages/index.xhtml" target="_blank">American Community Survey</a>, el cuál incluye datos censales de 2012 ya calculados por estado.</p>
					<p>Todos los datos se actualizaron el 10 de septiembre de 2014 y se utilizó la última modificación hecha por las fuentes mencionadas.</p>
					<p>Las fotos se obtuvieron de Wikicommons o de las páginas de cada candidato; las ligas las pueden encontrar en la base de datos.</p>
				</div>
			</div>			
			<div id="info_cand_gove" ng-show="gobernadores">
				<div class="zoomout" ng-click="gobernadores = false">Regresar al mapa</div>
				<div ng-show="hayeleccion2() == true">
					<div id="gove_details">
						<p class="state_can">{{gobernador_seleccionado.statename}}</p>
						<div class="gov_left">
							<div class="svg_state">
							<svg>
								<g id="g_gobernador">
									<path id="path_state_gobernador"></path>
								<g>
							</svg>
							</div>
						</div>
						<div class="gov_right">
							<table class="t3 web">
								<tr>
									<th>Como se ven las encuestas</th>
									<th class="tb_mid">¿Es competido?</th>
									<th class="tb_mid">¿Puede ser revocado?</th>
									<th>¿Hay límites a la reelección?</th>
								</tr>
								<tr>
									<td>
										<p class="demo">{{gobernador_seleccionado.pollpercdem | number:0}}%</p>
										<svg height="16" width="16">
											<circle cx="8" cy="10.5" r="4" stroke="#3E65AA" stroke-width="3" fill="#3E65AA" />
										</svg>
										<svg height="16" width="16">
											<circle cx="8" cy="10.5" r="4" stroke="#E93838" stroke-width="3" fill="#E93838" />
										</svg>
										<p class="rep">{{gobernador_seleccionado.pollpercrep | number:0}}%</p>
									</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.competido == 1">Sí</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.competido == 0">No</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.recall == 1">Sí</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.recall == 0">No</td>
									<td class="curve" ng-show="gobernador_seleccionado.canbereelected == 1">Sí</td>
									<td class="curve" ng-show="gobernador_seleccionado.canbereelected == 0">No</td>
								</tr>
							</table>
							<table class="t4 web">
								<tr>
									<th width ="142">Años por mandato</th>
									<th class="tb_mid">% de hispanos</th>
									<th>% de hispanos de 18 años o más con ciudadanía</th>
								</tr>
								<tr>
									<td class="curve">{{gobernador_seleccionado.termlength}}</td>
									<td class="tb_mid curve">{{gobernador_seleccionado.hisp_p | number:1}}%</td>
									<td class="curve">{{gobernador_seleccionado.hispvot_p | number:1}}%</td>
								</tr>
							</table>
							<table class="t5 web">
								<tr>
									<th width ="142">Gobernador actual</th>
									<th class="tb_mid">Puede ser reelecto</th>
									<th>¿Cuántos mandatos lleva?</th>
								</tr>
								<tr>
									<td>{{gobernador_seleccionado.incgov}}</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.canbereelected == 1">Sí</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.canbereelected == 0">No</td>
									<td class="curve">{{gobernador_seleccionado.incumbentterms_e}}</td>
								</tr>
							</table>
							<table class="t6 web">
								<tr>
									<th>¿Es de orígen hispano?</th>
									<th class="tb_mid" ng-show="validar_estatus()">Estatus para la elección</th>
									<th ng-show="gobernador_seleccionado.termnote_e !=''">Nota sobre el mandato</th>
								</tr>
								<tr>
									<td ng-show="gobernador_seleccionado.incgovhispanic == 1">Sí</td>
									<td ng-show="gobernador_seleccionado.incgovhispanic == 0">No</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.notseekingreelection == 1">No está buscando la reelección</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.cantbereelected == 1">No puede reelegirse</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.lostprimary == 1">Perdió la primaria</td>
									<td class="curve" ng-show="gobernador_seleccionado.termnote_e !=''">{{gobernador_seleccionado.termnote_e}}</td>
								</tr>
							</table>
						</div>


						<!-- Para móvil -->
						<table class="extra movil">
							<tr>
								<td>Como se ven las encuestas</td>
								<td class="dos">
									<p class="demo">{{gobernador_seleccionado.pollpercdem | number:0}}%</p>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#3E65AA" stroke-width="3" fill="#3E65AA" />
									</svg>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#E93838" stroke-width="3" fill="#E93838" />
									</svg>
									<p class="rep">{{gobernador_seleccionado.pollpercrep | number:0}}%</p>
								</td>
							</tr>
							<tr>
								<td class="tb_mid">¿Es competido?</td>
								<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.competido == 1">Sí</td>
								<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.competido == 0">No</td>
							</tr>
							<tr>							
								<td class="tb_mid">¿Puede ser revocado?</td>
								<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.recall == 1">Sí</td>
								<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.recall == 0">No</td>
							</tr>
							<tr>
								<td>¿Hay límites a la reelección?</td>					
								<td class="curve dos" ng-show="gobernador_seleccionado.canbereelected == 1">Sí</td>
								<td class="curve dos" ng-show="gobernador_seleccionado.canbereelected == 0">No</td>
							</tr>
						</table>
						<table class="extra movil">
							<tr>
								<td>Años por mandato</td>
								<td class="curve dos">{{gobernador_seleccionado.termlength}}</td>
							</tr>
							<tr>								
								<td class="tb_mid">% de hispanos</td>
								<td class="tb_mid curve dos">{{gobernador_seleccionado.hisp_p | number:1}}%</td>
							</tr>
							<tr>
								<td>% de hispanos de 18 años o más con ciudadanía</td>								
								<td class="curve dos">{{gobernador_seleccionado.hispvot_p | number:1}}%</td>
							</tr>
						</table>
						<table class="extra movil">
							<tr>
								<td>Gobernador actual</td>
								<td class="dos">{{gobernador_seleccionado.incgov}}</td>
							</tr>
							<tr>						
								<td class="tb_mid">Puede ser reelecto</td>
								<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.canbereelected == 1">Sí</td>
								<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.canbereelected == 0">No</td>
							</tr>
							<tr>
								<td>¿Cuántos mandatos lleva?</td>							
								<td class="curve dos">{{gobernador_seleccionado.incumbentterms_e}}</td>
							</tr>
						</table>
						<table class="extra movil">
							<tr>
								<td>¿Es de orígen hispano?</td>
								<td class="dos" ng-show="gobernador_seleccionado.incgovhispanic == 1">Sí</td>
								<td class="dos" ng-show="gobernador_seleccionado.incgovhispanic == 0">No</td>
							</tr>
							<tr>								
								<td class="tb_mid" ng-show="validar_estatus()">Estatus para la elección</td>
								<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.notseekingreelection == 1">No está buscando la reelección</td>
								<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.cantbereelected == 1">No puede reelegirse</td>
								<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.lostprimary == 1">Perdió la primaria</td>
							</tr>
							<tr>
								<td ng-show="gobernador_seleccionado.termnote_e !=''">Nota sobre el mandato</td>								
								<td class="curve dos" ng-show="gobernador_seleccionado.termnote_e !=''">{{gobernador_seleccionado.termnote_e}}</td>
							</tr>
						</table>
					</div>
					<div class="candidates_senadores_left_i f_l candidates_senadores_left_i_mov" ng-show="validar_candidato()">
						<hr class="line_cans_left">
						<p class="cand_num mov_cand_num">Candidato 1</p>
						<hr class="line_cans_left">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.inccandidate)}}" class="can_pic mov_can_pic">
						<div class="sign_can {{getClassParty2(gobernador_seleccionado.inccandidatepartyid)}}">
							{{getParty2(gobernador_seleccionado.inccandidatepartyid)}}
						</div>
						<div class="details">
							<ul>
								<li class="name_candidate">
									{{gobernador_seleccionado.inccandidate}} <span class="incumbent">(i)</span>
								</li>
								<li style="word-wrap: break-word;">
									<a href="{{gobernador_seleccionado.link_ig}}" target="_blank" ng-show="gobernador_seleccionado.link_ig != ''">{{gobernador_seleccionado.link_ig}}</a>
								</li>						
								<li>
									Número de mandatos<span class="data2">{{gobernador_seleccionado.incumbentterms_e}}</span>
								</li>
							</ul>
							<p class="l_incumbent">
								<span class="incumbent">(i)</span> el candidato es el gobernador en turno
							</p>					
						</div>
					</div>
					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate1 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidato 2</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidato 1</p>
						<hr class="line_cans_left">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.opcandidate1)}}" class="can_pic">
						<div class="sign_can {{getClassParty2(gobernador_seleccionado.opcandidate1partyid)}}">
							{{getParty2(gobernador_seleccionado.opcandidate1partyid)}}
						</div>
						<div class="details">
							<ul>
								<li class="name_candidate">
									{{gobernador_seleccionado.opcandidate1}}
								</li>
								<li style="word-wrap: break-word;">
									<a href="{{gobernador_seleccionado.link_c1}}" target="_blank" ng-show="gobernador_seleccionado.link_c1 != ''">{{gobernador_seleccionado.link_c1}}</a>
								</li>
								<li>
									Puesto anterior<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate1pastjob_e}}</span>
								</li>
							</ul>
						</div>
					</div>
					<div class="candidates_senadores_left f_l" ng-show="gobernador_seleccionado.opcandidate2 !=''">
						<hr class="line_cans_right">
						<p class="cand_num" ng-show="validar_candidato()">Candidato 3</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidato 2</p>
						<hr class="line_cans_right">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.opcandidate2)}}" class="can_pic">
						<div class="sign_can {{getClassParty2(gobernador_seleccionado.opcandidate2partyid)}}">
							{{getParty2(gobernador_seleccionado.opcandidate2partyid)}}
						</div>						
						<div class="details">
							<ul>								
								<li class="name_candidate">
									{{gobernador_seleccionado.opcandidate2}}
								</li>
								<li style="word-wrap: break-word;">
									<a href="{{gobernador_seleccionado.link_c2}}" target="_blank" ng-show="gobernador_seleccionado.link_c2 != ''">{{gobernador_seleccionado.link_c2}}</a>
								</li>
								<li>
									Puesto anterior<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate2pastjob_e}}</span>
								</li>
							</ul>
						</div>
					</div>
					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate3 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidato 4</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidato 3</p>
						<hr class="line_cans_left">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.opcandidate3)}}" class="can_pic">
						<div class="sign_can {{getClassParty2(gobernador_seleccionado.opcandidate3partyid)}}">
							{{getParty2(gobernador_seleccionado.opcandidate3partyid)}}
						</div>
						<div class="details">
							<ul>
								<li class="name_candidate">
									{{gobernador_seleccionado.opcandidate3}}
								</li>
								<li style="word-wrap: break-word;">
									<a href="{{gobernador_seleccionado.link_c3}}" target="_blank" ng-show="gobernador_seleccionado.link_c3 != ''">{{gobernador_seleccionado.link_c3}}</a>
								</li>
								<li>
									Puesto anterior<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate3pastjob_e}}</span>
								</li>
							</ul>
						</div>
					</div>
					<div class="candidates_senadores_left f_l" ng-show="gobernador_seleccionado.opcandidate4 !=''">
						<hr class="line_cans_right">
						<p class="cand_num" ng-show="validar_candidato()">Candidato 5</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidato 4</p>
						<hr class="line_cans_right">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.opcandidate4)}}" class="can_pic">
						<div class="sign_can {{getClassParty2(gobernador_seleccionado.opcandidate4partyid)}}">
							{{getParty2(gobernador_seleccionado.opcandidate4partyid)}}
						</div>
						<div class="details">
							<ul>
								<li class="name_candidate">
									{{gobernador_seleccionado.opcandidate4}}
								</li>
								<li>
									<a href="{{gobernador_seleccionado.link_c4}}" target="_blank" ng-show="gobernador_seleccionado.link_c4 != ''">{{gobernador_seleccionado.link_c4}}</a>
								</li>
								<li>
									Puesto anterior<br/>									
									<span class="data3">{{gobernador_seleccionado.opcandidate4pastjob_e}}</span>
								</li>
							</ul>
						</div>
					</div>
					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate5 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidato 6</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidato 5</p>
						<hr class="line_cans_left">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.opcandidate5)}}" class="can_pic">
						<div class="sign_can {{getClassParty2(gobernador_seleccionado.opcandidate5partyid)}}">
							{{getParty2(gobernador_seleccionado.opcandidate5partyid)}}
						</div>
						<div class="details">
							<ul>
								<li class="name_candidate">
									{{gobernador_seleccionado.opcandidate5}}
								</li>
								<li style="word-wrap: break-word;">
									<a href="{{gobernador_seleccionado.link_c5}}" target="_blank" ng-show="gobernador_seleccionado.link_c5 != ''">{{gobernador_seleccionado.link_c5}}</a>
								</li>
								<li>
									Puesto anterior<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate5pastjob_e}}</span>
								</li>
							</ul>
						</div>						
					</div>
					<div class="candidates_senadores_left f_l" ng-show="gobernador_seleccionado.opcandidate6 !=''">
						<hr class="line_cans_right">
						<p class="cand_num" ng-show="validar_candidato()">Candidato 6</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidato 7</p>
						<hr class="line_cans_right">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.opcandidate6)}}" class="can_pic">
						<div class="sign_can {{getClassParty2(gobernador_seleccionado.opcandidate6partyid)}}">
							{{getParty2(gobernador_seleccionado.opcandidate6partyid)}}
						</div>
						<div class="details">
							<ul>
								<li class="name_candidate">
									{{gobernador_seleccionado.opcandidate6}}
								</li>
								<li style="word-wrap: break-word;">
									<a href="{{gobernador_seleccionado.link_c6}}" target="_blank" ng-show="gobernador_seleccionado.link_c6 != ''">{{gobernador_seleccionado.link_c6}}</a>
								</li>								
								<li>
									Puesto anterior<br/>								
									<span class="data3">{{gobernador_seleccionado.opcandidate6pastjob_e}}</span>
								</li>
							</ul>
						</div>
					</div>
					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate7 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidato 7</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidato 8</p>
						<hr class="line_cans_left">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.opcandidate7)}}" class="can_pic">
						<div class="sign_can {{getClassParty2(gobernador_seleccionado.opcandidate7partyid)}}">
							{{getParty2(gobernador_seleccionado.opcandidate7partyid)}}
						</div>
						<div class="details">
							<ul>
								<li class="name_candidate">
									{{gobernador_seleccionado.opcandidate7}}
								</li>
								<li style="word-wrap: break-word;">
									<a href="{{gobernador_seleccionado.link_c7}}" target="_blank" ng-show="gobernador_seleccionado.link_c7 != ''">{{gobernador_seleccionado.link_c7}}</a>
								</li>								
								<li>
									Puesto anterior<br/>								
									<span class="data3">{{gobernador_seleccionado.opcandidate7pastjob_e}}</span>
								</li>
							</ul>
						</div>
					</div>
					<div class="candidates_senadores_left f_l" ng-show="gobernador_seleccionado.opcandidate8 !=''">
						<hr class="line_cans_right">
						<p class="cand_num" ng-show="validar_candidato()">Candidato 8</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidato 9</p>
						<hr class="line_cans_right">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.opcandidate8)}}" class="can_pic">
						<div class="sign_can {{getClassParty2(gobernador_seleccionado.opcandidate8partyid)}}">
							{{getParty2(gobernador_seleccionado.opcandidate8partyid)}}
						</div>
						<div class="details">
							<ul>
								<li class="name_candidate">
									{{gobernador_seleccionado.opcandidate8}}
								</li>
								<li style="word-wrap: break-word;">
									<a href="{{gobernador_seleccionado.link_c8}}" target="_blank" ng-show="gobernador_seleccionado.link_c8 != ''">{{gobernador_seleccionado.link_c8}}</a>
								</li>
								<li>
									Puesto anterior<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate8pastjob_e}}</span>
								</li>							
							</ul>
						</div>
					</div>
				</div>
				<div class="zoomout" ng-click="gobernadores = false">Regresar al mapa</div>
			</div>
			<a href="https://www.dropbox.com/sh/pnu71cvs8n70dco/AABFXfP3QSXCLMgvPdg07YsNa?dl=0" target="_blank">
				<img src="images/btn_descarga.png" class="boton">
			</a>
		</div>
		<div class="footer">
			<div class="d4">
				<span>D4 para Univision Noticias.</span>
				<a href="http://www.data4.mx" target="_blank">http://www.data4.mx</a>
			</div>	
		</div>
	</div>
</body>
<script type="text/javascript">
	
</script>
</html>
