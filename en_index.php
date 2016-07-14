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
	<link rel="stylesheet" type="text/css" href="css/style_mobile.css" />
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
	<script src="js/en_controllers.js"></script>
</head>
<body ng-controller="indexCtrl" id="body">
	<div class="content">
		<div class="header">
   			<div>Everything you wanted to know about the<br>Senate and Gubernatorial elections in November</div>
			<div class="lang_version">
   				<img id="lang" src="images/en_version.png">
  			</div>
  		</div>
		<div class="title">
			What are the chances that each party has of winning the majority of these races
		</div>
		<hr>
		<div class="contenido">
			<div class="modal" ng-show="modal">
				<div id="contenedor_instrucciones" >
					<div class="titulo_nota">						
						<div class="tituloT">Instructions</div>
					</div>
					<p>To explore the following infographic first choose the "Senate" or "Governor" option to see the corresponding map. Once you´ve done this, click on a state or look for the state's name in the search bar and then click on it.</p>
					<p>Once this step is completed, you'll be able to see relevant information pertaining to the state and candidates participating in the chosen state's election. You can always go back to the map by clicking on the "Return to map" button, where you can then select another state.</p>
					<br/>
					<div class="zoomout" ng-click="modal = false">Back to map</div>
				</div>			
			</div>
			<div class="autocomplete">
	  			  <input id="search">	  			  
			</div>
			<div class="candidatos">
				<span id="senador" class="opcion_color active" ng-click="show('senador');">
					SENATORS
					<svg height="16" width="16">
						<circle cx="8" cy="10.5" r="4" stroke-width="3" />
					</svg>
				</span>
				|
				<span id="gobernador" class="opcion_color" ng-click="show('gobernador');">
					<svg height="16" width="16">
						<circle cx="8" cy="10.5" r="4" stroke-width="3" />
					</svg>
					GOVERNORS
				</span>
			</div>
			<div id="info_cand_sena" ng-show="senadores">	
				<br/>
				<br/>
				<div class="zoomout" ng-click="senadores = false">Back to map</div>				
				<div ng-show="hayeleccion() == true">
					<div id="sena_details">
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
								<th width="125">What the polls say</th>
								<th class="tb_mid">Is it a toss-up race? </th>
								<th class="tb_mid">Hispanic population (%)</th>
								<th>Hispanic population over 18 years old with U.S. citizenship (%)</th>
							</tr>
							<tr>	
								<td>
									<p class="demo">{{senador_seleccionado.pollpercdem | number:0}}{{senador_seleccionado.pollpercdem != 'N/A' ? '%' : 'N/A' }}</p>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#3E65AA" stroke-width="3" fill="#3E65AA" />
									</svg>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#E93838" stroke-width="3" fill="#E93838" />
									</svg>
									<p class="rep">{{senador_seleccionado.pollpercrep | number:0}}{{senador_seleccionado.pollpercrep != 'N/A' ? '%' : 'N/A' }}</p>
								</td>
								<td class="tb_mid curve" ng-show="senador_seleccionado.competido_lean == '1'">Yes</td>
								<td class="tb_mid curve" ng-show="senador_seleccionado.competido_lean == '0'">No</td>
								<td class="tb_mid curve">{{senador_seleccionado.hisp_p | number:1}}%</td>
								<td class="curve">{{senador_seleccionado.hispvot_p | number:1}}%</td>
							</tr>
						</table>
						<table class="t2 web">
							<tr>
								<th>State Senator</th>
								<th class="tb_mid">Number of terms</th>
								<th class="tb_mid">Status for the election:</th>
								<th>Hispanic origin </th>
							</tr>
							<tr>
								<td>{{senador_seleccionado.senator_name}}</td>
								<td class="tb_mid curve">{{senador_seleccionado.senator_terms}}</td>
								<td class="tb_mid curve">{{senador_seleccionado.senrunning_ing}}</td>
								<td class="curve" ng-show="senador_seleccionado.sen_hisp == 1">Yes</td>
								<td class="curve" ng-show="senador_seleccionado.sen_hisp == 0">No</td>
							</tr>
						</table>
						<table class="extra movil">
							<tr>
								<td>What the polls say</td>
								<td class="dos">
									<p class="demo">{{senador_seleccionado.pollpercdem | number:0}}{{senador_seleccionado.pollpercdem != 'N/A' ? '%' : 'N/A' }}</p>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#3E65AA" stroke-width="3" fill="#3E65AA" />
									</svg>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#E93838" stroke-width="3" fill="#E93838" />
									</svg>
									<p class="rep">{{senador_seleccionado.pollpercrep | number:0}}{{senador_seleccionado.pollpercrep != 'N/A' ? '%' : 'N/A' }}</p>
								</td>
							</tr>
							<tr>
								<td class="tb_mid">Is it a toss-up race? </td>
								<td class="tb_mid curve dos" ng-show="senador_seleccionado.competido_lean == '1'">Yes</td>
								<td class="tb_mid curve dos" ng-show="senador_seleccionado.competido_lean == '0'">No</td>
							</tr>
							<tr>						
								<td class="tb_mid">Hispanic population (%)</td>
								<td class="tb_mid curve dos">{{senador_seleccionado.hisp_p | number:1}}%</td>
							</tr>
							<tr>
								<td>Hispanic population over 18 years old with U.S. citizenship (%)</td>				
								<td class="curve dos">{{senador_seleccionado.hispvot_p | number:1}}%</td>
							</tr>
						</table>
						<table class="extra movil">
							<tr>
								<td>State Senator</td>
								<td class="dos">{{senador_seleccionado.senator_name}}</td>
							</tr>
							<tr>
								<td class="tb_mid">Number of terms</td>
								<td class="tb_mid curve dos">{{senador_seleccionado.senator_terms}}</td>
							</tr>
							<tr>				
								<td class="tb_mid">Status for the election:</td>
								<td class="tb_mid curve dos">{{senador_seleccionado.senrunning_ing}}</td>
							</tr>
							<tr>
								<td>Hispanic origin </td>
								<td class="curve dos" ng-show="senador_seleccionado.sen_hisp == 1">Yes</td>
								<td class="curve dos" ng-show="senador_seleccionado.sen_hisp == 0">No</td>
							</tr>
						</table>
						<div ng-show="hayspecial() == true" class="special_election_msg">Special Election: both Senate seats are up for election</div>
					</div>		
					<div class="candidates_senadores_left">
						<hr class="line_cans_left">
						<p class="cand_num">Candidate 1</p>
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
										Number of terms <span class="data">{{senador_seleccionado.senator_terms}}</span>
									</li>
									<li>
										Hispanic origin  <span class="data data3" ng-show="senador_seleccionado.incumbent_hisp == 1">Yes</span>  <span class="data data3" ng-show="senador_seleccionado.incumbent_hisp == 0">No</span>
									</li>
									<li>
										Campaign funds <br/> received: <span class="data">{{senador_seleccionado.incumbent_funds}}</span>
									</li>
									<li>
										Committees:<br/>
									<span class="data3" ng-show='senador_seleccionado.committee1_ing != ""'>- {{senador_seleccionado.committee1_ing}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee2_ing != ""'>- {{senador_seleccionado.committee2_ing}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee3_ing != ""'>- {{senador_seleccionado.committee3_ing}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee4_ing != ""'>- {{senador_seleccionado.committee4_ing}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee5_ing != ""'>- {{senador_seleccionado.committee5_ing}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee6_ing != ""'>- {{senador_seleccionado.committee6_ing}}<br/></span>
									</li>
								</ul>
								<p class="l_incumbent">
									<span class="incumbent">(i)</span> incumbent candidate
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
										Campaign funds <br/> received: <span class="data">{{senador_seleccionado.candidato1_funds}}</span>
									</li>
									<li>								
										Past Job<br/>
										<span class="data3">{{senador_seleccionado.candidato1_job_ing}}</span>
									</li>								
								</ul>
							</div>
						</div>
					</div>	
					<div class="candidates_senadores_right">					
						<div ng-show="incumbent() == true">
							<div ng-show="senador_seleccionado.candidato1 !=''">
								<hr class="line_cans_right">
								<p class="cand_num">Candidate 2</p>
								<hr class="line_cans_right">							
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
											Campaign funds <br/> received: <span class="data">{{senador_seleccionado.candidato1_funds}}</span>
										</li>
										<li>
											Hispanic origin <span class="data3" ng-show="senador_seleccionado.candidato1_hispano == 1">Yes</span><span class="data3" ng-show="senador_seleccionado.candidato1_hispano == 0">No</span>
										</li>
										<li>
											Past Job <br/>																	
											<span class="data3" >{{senador_seleccionado.candidato1_job_ing}}</span>
										</li>
									</ul>								
								</div>
							</div>
						</div>
						<div ng-show="incumbent() == false">
							<div ng-show="senador_seleccionado.candidato2 !=''">
								<hr class="line_cans_right">
								<p class="cand_num">Candidate 2</p>
								<hr class="line_cans_right">							
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
											Campaign funds <br/> received: <span class="data">{{senador_seleccionado.candidato2_funds}}</span>
										</li>
										<li>
											Past Job <br/>																	
											<span class="data3">{{senador_seleccionado.candidato2_job_ing}}</span>
										</li>									
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div ng-show="hayspecial() == true">
					<div class="special_title">SPECIAL ELECTION</div>
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
								<th>State Senator</th>
								<th class="tb_mid">Number of terms</th>
								<th class="tb_mid">Status for the election:</th>
								<th>Hispanic origin </th>
								<th>What the polls say</th>
							</tr>
							<tr>
								<td>{{senador_seleccionado.senator_nameSE}}</td>
								<td class="tb_mid curve">{{senador_seleccionado.senator_termsSE}}</td>
								<td class="tb_mid curve">{{senador_seleccionado.senrunning_ing_SE}}</td>
								<td class="data3 curve" ng-show="senador_seleccionado.incumbent_hispSE == 1"> Yes</td>
								<td class="data3 curve" ng-show="senador_seleccionado.incumbent_hispSE == 0"> No</td>
								<td>
									<p class="demo">{{senador_seleccionado.pollpercdem_SE | number:0}}{{senador_seleccionado.pollpercdem_SE != 'N/A' ? '%' : 'N/A' }}</p>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#3E65AA" stroke-width="3" fill="#3E65AA" />
									</svg>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#E93838" stroke-width="3" fill="#E93838" />
									</svg>
									<p class="rep">{{senador_seleccionado.pollpercrep_SE | number:0}}{{senador_seleccionado.pollpercrep_SE != 'N/A' ? '%' : 'N/A' }}</p>
								</td>
							</tr>
						</table>

						<!-- Móvil -->
						<table class="extra movil">
							<tr>
								<td>State Senator</td>
								<td class="dos">{{senador_seleccionado.senator_nameSE}}</td>
							</tr>
							<tr>
								<td class="tb_mid">Number of terms</td>
								<td class="tb_mid curve dos">{{senador_seleccionado.senator_termsSE}}</td>
							</tr>
							<tr>
								<td class="tb_mid">Status for the election:</td>
								<td class="tb_mid curve dos">{{senador_seleccionado.senrunning_ing_SE}}</td>
							</tr>
							<tr>
								<td>Hispanic origin </td>
								<td class="data3 curve dos" ng-show="senador_seleccionado.incumbent_hispSE == 1">Yes</td>
								<td class="data3 curve dos" ng-show="senador_seleccionado.incumbent_hispSE == 0">No</td>
							</tr>
							<tr>
								<td>What the polls say</td>								
								<td class="dos">
									<p class="demo">{{senador_seleccionado.pollpercdem_SE | number:0}}%</p>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#3E65AA" stroke-width="3" fill="#3E65AA" />
									</svg>
									<svg height="16" width="16">
										<circle cx="8" cy="10.5" r="4" stroke="#E93838" stroke-width="3" fill="#E93838" />
									</svg>
									<p class="rep">{{senador_seleccionado.pollpercrep_SE | number:0}}%</p>
								</td>
							</tr>
						</table>
						<div ng-show="hayspecial() == true && hayeleccion() == false " class="special_election_msg">Special Election: both Senate seats are up for election</div>
					
					</div>		
					<div class="candidates_senadores_left">
						<hr class="line_cans_left">
						<p class="cand_num">Candidate 1</p>
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
									Number of terms <span class="data data3">{{senador_seleccionado.senator_termsSE}}</span>
								</li>
								<li>
									Hispanic origin <span class="data data3" ng-show="senador_seleccionado.incumbent_hispSE == 1">Yes</span> <span class="data3" ng-show="senador_seleccionado.incumbent_hispSE == 0">No</span>
								</li>
								<li>
									Campaign funds <br/> received: <span class="data data3">{{senador_seleccionado.incumbent_fundsSE}}</span>
								</li>
								<li>
									Committees:<br/>
									<span class="data3" ng-show='senador_seleccionado.committee1_ing_SE != ""'>- {{senador_seleccionado.committee1_ing_SE}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee2_ing_SE != ""'>- {{senador_seleccionado.committee2_ing_SE}}<br/></span>
									<span class="data3" ng-show='senador_seleccionado.committee3_ing_SE != ""'>- {{senador_seleccionado.committee3_ing_SE}}<br/></span>
								</li>
							</ul>	
							<p class="l_incumbent">
								<span class="incumbent">(i)</span> incumbent candidate
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
										Campaign funds <br/> received: <span class="data data3">{{senador_seleccionado.candidato1_fundsSE}}</span>
									</li>								
									<li>
										Past Job<br/>								
										<span class="data3" >{{senador_seleccionado.candidato1_job_ing_SE}}</span>
									</li>
								</ul>
							</div>
						</div>
					</div>	
					<div class="candidates_senadores_right">					
						<hr class="line_cans_right">
						<p class="cand_num">Candidate 2</p>
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
										Campaign funds<br/> received:<span class="data data3">{{senador_seleccionado.candidato1_fundsSE}}</span>
									</li>
									<li>
										Past Job<br/>
										<span class="data3" >{{senador_seleccionado.candidato1_job_ing_SE}}</span>
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
										Campaign funds<br/> received: <span class="data data3">{{senador_seleccionado.candidato2_fundsSE}}</span>
									</li>
									<li>
										Past Job<br/>
										<span class="data3">
											{{senador_seleccionado.candidato2_job_ing_SE}}
										</span>
									</li>
								</ul>
							</div>
						</div>
					</div>					
				</div>
				<br/>
				<div class="zoomout" ng-click="senadores = false">Back to map</div>				
			</div>
			<div class="infoExtra">
	  			<div class="verInstrucciones" ng-click="modal = true;">HELP <img src="images/ayuda.png"></div>
			</div>
			<div id="state_map"></div>
			<div class="code_colors web">
				<img src="images/info_web_sen_ing.png" ng-show="seleccionada == 'senador'">
				<img src="images/info_web_gob_ing.png" ng-show="seleccionada == 'gobernador'">
			</div>
			<div class="code_colors movil">
				<img src="images/info_mob_sen_ing.png" ng-show="seleccionada == 'senador'">
				<img src="images/info_mob_gob_ing.png" ng-show="seleccionada == 'gobernador'">
			</div>
			<div class="nota">
				<div class="titulo_nota">
					<p class="nm">Methodological Note:</p>
					<p class="linea web"></p>
				</div>
				<div class="parrafo">	
					<p>The information used in the visualization was taken from a series of sources dedicated to following the midterm elections in the United States.</p>
					<p>For the information regarding incumbents and candidates the sources used were: <a data-behavior="truncate" href="http://www.politico.com/2014-election/results/map/senate/" target="_blank">politico</a>, <a data-behavior="truncate" href="http://ballotpedia.org/Portal:Elections" target="_blank">Ballotpedia</a>, <a data-behavior="truncate" href="http://www.nga.org/cms/2014Elections" target="_blank">the National Governors Association</a> and <a data-behavior="truncate" href="http://cookpolitical.com/senate/charts/race-ratings" target="_blank">Cook Political </a></p>
					<p>The poll results were taken from <a data-behavior="truncate" href="http://www.realclearpolitics.com/epolls/2014/senate/2014_elections_senate_map.html" target="_blank">Real Clear Politics</a>, specifically we used the RCP poll average.</p>
					<p>The data regarding campaign funds was taken from the <a href="http://www.fec.gov/data/CandidateSummary.do">Federal Election Commission</a> page. </p>
					<p>The data on Hispanic population was obtained from the <a data-behavior="truncate" href="http://factfinder2.census.gov/faces/nav/jsf/pages/index.xhtml" target="_blank">American Community Survey</a>, which includes the 2012 census data calculated by state.</p>
					<p>All information was modified on September 10th, 2014, using the last updates made by the sources mentioned.</p>
					<p>Photo sources include Wikicommons and candidate's webpages; you can find the URL's in the database.</p>
					
				</div>
			</div>

			<div id="info_cand_gove" ng-show="gobernadores">
				<div class="zoomout" ng-click="gobernadores = false">Back to map</div>
				<div ng-show="hayeleccion2() == true">
					<div id="gove_details">
						<p class="state_can">{{gobernador_seleccionado.statename}}</p>												
						<div class="gov_left">
							<div class="svg_state">
								<svg>
									<g id="g_gobernador">
										<path class="path_state_gobernador"></path>
									<g>
								</svg>
							</div>
						</div>
						
						<div class="gov_right">
							<table class="t3 web">
								<tr>
									<th>What the polls say</th>
									<th class="tb_mid">Is it a toss-up race? </th>
									<th class="tb_mid">Can be revoked?</th>
									<th>Is there term limits?</th>
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
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.competido == 1">Yes</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.competido == 0">No</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.recall == 1">Yes</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.recall == 0">No</td>
									<td class="curve" ng-show="gobernador_seleccionado.canbereelected == 1">Yes</td>
									<td class="curve" ng-show="gobernador_seleccionado.canbereelected == 0">No</td>
								</tr>
							</table>
							<table class="t4 web">
								<tr>
									<th width="107">Years per term</th>
									<th class="tb_mid">Hispanic population (%)</th>
									<th>Hispanic population over 18 years old with U.S. citizenship (%)</th>
								</tr>
								<tr>
									<td class="curve">{{gobernador_seleccionado.termlength}}</td>
									<td class="tb_mid curve">{{gobernador_seleccionado.hisp_p | number:1}}%</td>
									<td class="curve">{{gobernador_seleccionado.hispvot_p | number:1}}%</td>
								</tr>
							</table>
							<table class="t5 web">
								<tr>
									<th>State Governor</th>
									<th class="tb_mid">May be reelected</th>
									<th>Number of terms</th>
								</tr>
								<tr>
									<td>{{gobernador_seleccionado.incgov}}</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.canbereelected == 1">Yes</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.canbereelected == 0">No</td>
									<td class="curve">{{gobernador_seleccionado.incumbentterms}}</td>
								</tr>
							</table>
							<table class="t6 web">
								<tr>
									<th>Hispanic origin </th>
									<th class="tb_mid" ng-show="validar_estatus()">Status for election</th>
									<th ng-show="gobernador_seleccionado.termnote_e !=''">Note on the mandate</th>
								</tr>
								<tr>
									<td ng-show="gobernador_seleccionado.incgovhispanic == 1">Yes</td>
									<td ng-show="gobernador_seleccionado.incgovhispanic == 0">No</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.notseekingreelection == 1">Not seeking re-election</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.cantbereelected == 1">Can not be reelected</td>
									<td class="tb_mid curve" ng-show="gobernador_seleccionado.lostprimary == 1">Lost the primary</td>
									<td class="curve" ng-show="gobernador_seleccionado.termnote_e !=''">{{gobernador_seleccionado.termnote}}</td>
								</tr>
							</table>							
						</div>

						<!-- para móvil -->
						<table class="extra movil">
								<tr>
									<td>What the polls say</td>
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
									<td class="tb_mid">Is it a toss-up race? </td>
									<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.competido == 1">Yes</td>
									<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.competido == 0">No</td>
								</tr>
								<tr>							
									<td class="tb_mid">Can be revoked?</td>
									<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.recall == 1">Yes</td>
									<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.recall == 0">No</td>
									
								</tr>
								<tr>
									<td>Is there term limits?</td>	
									<td class="curve dos" ng-show="gobernador_seleccionado.canbereelected == 1">Yes</td>
									<td class="curve dos" ng-show="gobernador_seleccionado.canbereelected == 0">No</td>
								</tr>
							</table>
							<table class="extra movil">
								<tr>
									<td width="107">Years per term</td>
									<td class="curve dos">{{gobernador_seleccionado.termlength}}</td>
								</tr>
								<tr>									
									<td class="tb_mid">Hispanic population (%)</td>
									<td class="tb_mid curve dos">{{gobernador_seleccionado.hisp_p | number:1}}%</td>
								</tr>
								<tr>
									<td>Hispanic population over 18 years old with U.S. citizenship (%)</td>								
									<td class="curve dos">{{gobernador_seleccionado.hispvot_p | number:1}}%</td>
								</tr>
							</table>
							<table class="extra movil">
								<tr>
									<td>State Governor</td>
									<td class="dos">{{gobernador_seleccionado.incgov}}</td>
								</tr>
								<tr>									
									<td class="tb_mid">May be reelected</td>
									<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.canbereelected == 1">Yes</td>
									<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.canbereelected == 0">No</td>
								</tr>
								<tr>
									<td>Number of terms</td>									
									<td class="curve dos">{{gobernador_seleccionado.incumbentterms}}</td>
								</tr>
							</table>
							<table class="extra movil">
								<tr>
									<td>Hispanic origin </td>
									<td class="dos" ng-show="gobernador_seleccionado.incgovhispanic == 1">Yes</td>
									<td class="dos" ng-show="gobernador_seleccionado.incgovhispanic == 0">No</td>
								</tr>
								<tr>									
									<td class="tb_mid" ng-show="validar_estatus()">Status for election</td>
									<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.notseekingreelection == 1">Not seeking re-election</td>
									<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.cantbereelected == 1">Can not be reelected</td>
									<td class="tb_mid curve dos" ng-show="gobernador_seleccionado.lostprimary == 1">Lost the primary</td>
								</tr>
								<tr>
									<td ng-show="gobernador_seleccionado.termnote_e !=''">Note on the mandate</td>								
									<td class="curve dos" ng-show="gobernador_seleccionado.termnote_e !=''">{{gobernador_seleccionado.termnote}}</td>
								</tr>
							</table>						
					</div>
					<div class="candidates_senadores_left f_l" ng-show="validar_candidato()">
						<hr class="line_cans_left">
						<p class="cand_num">Candidate 1</p>
						<hr class="line_cans_left">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.inccandidate)}}" class="can_pic">
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
									Number of terms<br/>
									<span class="data3">{{gobernador_seleccionado.incumbentterms}}</span>
								</li>
							</ul>	
							<p class="l_incumbent">
								<span class="incumbent">(i)</span> incumbent candidate
							</p>
						</div>
					</div>
					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate1 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidate 2</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidate 1</p>
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
									Past Job<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate1pastjob}}</span>
								</li>
							</ul>
						</div>
					</div>
					<div class="candidates_senadores_left f_l" ng-show="gobernador_seleccionado.opcandidate2 !=''">
						<hr class="line_cans_right">
						<p class="cand_num" ng-show="validar_candidato()">Candidate 3</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidate 2</p>
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
									Past Job<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate2pastjob}} </span>
								</li>
							</ul>
						</div>
					</div>
					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate3 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidate 4</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidate 3</p>
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
									Past Job<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate3pastjob}}</span>
								</li>
							</ul>
						</div>
					</div>
					<!-- Faltantes -->
					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate4 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidate 5</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidate 4</p>
						<hr class="line_cans_left">
						<img ng-src="fotos/{{getImageUrl2(gobernador_seleccionado.opcandidate4)}}" class="can_pic">
						<div class="sign_can {{getClassParty2(gobernador_seleccionado.opcandidate4partyid)}}">
							{{getParty2(gobernador_seleccionado.opcandidate4partyid)}}
						</div>
						<div class="details">
							<ul>
								<li class="name_candidate">
									{{gobernador_seleccionado.opcandidate4}}
								</li>
								<li style="word-wrap: break-word;">
									<a href="{{gobernador_seleccionado.link_c4}}" target="_blank" ng-show="gobernador_seleccionado.link_c4 != ''">{{gobernador_seleccionado.link_c4}}</a>
								</li>
								<li>
									Past Job<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate4pastjob}}</span>
								</li>
							</ul>
						</div>
					</div>
					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate5 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidate 6</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidate 5</p>
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
									Past Job<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate5pastjob}}</span>
								</li>
							</ul>
						</div>
					</div>

					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate6 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidate 7</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidate 6</p>
						<hr class="line_cans_left">
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
									Past Job<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate6pastjob}}</span>
								</li>
							</ul>
						</div>
					</div>

					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate7 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidate 8</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidate 7</p>
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
									Past Job<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate7pastjob}}</span>
								</li>
							</ul>
						</div>
					</div>

					<div class="candidates_senadores_right f_l" ng-show="gobernador_seleccionado.opcandidate8 !=''">
						<hr class="line_cans_left">
						<p class="cand_num" ng-show="validar_candidato()">Candidate 9</p>
						<p class="cand_num" ng-show="validar_candidato2()">Candidate 8</p>
						<hr class="line_cans_left">
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
									Past Job<br/>
									<span class="data3">{{gobernador_seleccionado.opcandidate8pastjob}}</span>
								</li>
							</ul>
						</div>
					</div>

				</div>
				<div class="zoomout" ng-click="gobernadores = false">Back to map</div>
			</div>
			<a href="https://www.dropbox.com/sh/pnu71cvs8n70dco/AABFXfP3QSXCLMgvPdg07YsNa?dl=0" target="_blank">
				<img src="images/en_btn_descarga.png" class="boton">
			</a>
		</div>
		<div class="footer">
			<div class="d4">
				<span>D4 for Univision Noticias.</span>
				<a href="http://www.data4.mx" target="_blank">http://www.data4.mx</a>
			</div>	
		</div>
	</div>
</body>
<script type="text/javascript">
	
</script>
</html>
