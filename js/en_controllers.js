/*
	Angular controller.
*/

var uniApp = angular.module('uniApp', []);

uniApp.factory('uniFactory', function($http) {
    var factory = {}; 
});

uniApp.controller('indexCtrl', function($scope, $filter, uniFactory) {

	$scope.list_sendadores;

	$scope.list_gobernadores;

	$scope.senadores = false;

	$scope.gobernadores = false;

	$scope.seleccionada = "senador";

	$scope.senador_seleccionado;

	$scope.gobernador_seleccionado;

	$scope.id_seleccionado;

	$scope.show = function(id){
		console.log("entra");
		$(".opcion_color").removeClass("active");
		$("#"+id).addClass("active");
		$scope.seleccionada = id;
		$scope.colorear();
		/*Desmarcar estado seleccionado*/
		d3.selectAll("path").classed("marcado", false);
		$scope.id_seleccionado = "";
	}

	$scope.init = function(){
		$scope.list_sendadores = list_sendadores;		
		$scope.list_gobernadores = list_gobernadores;		
		$scope.senador_seleccionado = $scope.list_sendadores[0];
		$scope.gobernador_seleccionado = $scope.list_gobernadores[0];
		$scope.drawMap();		
	}

	$scope.mostrar = function(opt){
		console.log(opt);
	}

	$scope.drawMap = function(){

		var ancho = $(window).width();
		var width,
		    height = 500,
		    centered;
		if (ancho <= 428) {
		  width = 320
		}
		else {
		  width = 775
		};

		var projection = d3.geo.albersUsa()
		    .scale(1000)
		    .translate([width / 2, height / 2]);

		var path = d3.geo.path()
		    .projection(projection);

		/*var div = d3.select("body").append("div")   
		    .attr("class", "tooltip")               
		    .style("opacity", 0);*/

		var svg = d3.select("#state_map").append("svg")
		    .attr("width", width)
		    .attr("height", height);

		/*svg.append("rect")
		    .attr("class", "background")
		    .attr("width", width)
		    .attr("height", height)
		    .on("click", clicked);*/

		var g = svg.append("g");

		d3.json("/Gobernadores_Senadores/js/us.json", function(error, us) {
		  
		  g.append("path")
		      .datum(topojson.mesh(us, us.objects.states, function(a, b) { return a !== b; }))
		      .attr("id", "state-borders")
		      .attr("d", path);

		  g.append("g")
		      .attr("id", "states")		      
		    .selectAll("path")
		      .data(topojson.feature(us, us.objects.states).features)
		    .enter().append("path")
		    	.attr("id", function(d){ return 'state_'+d.id})		    	
		    	/*.style("fill", function(d){
		    		var arr = $.grep($scope.list_sendadores, function( a ) {
  						return a.FIPS_Code == d.id;
					});		    		
		    		if (arr[0]){
						var color = d3.scale.ordinal();
						color.domain([0,1,2,3]);
						color.range(["#BDCEDE","538635","97C47A","#FF00FF"]);												
						if (parseInt(arr[0].hayspecial) == 1) {
							return color(3)
						}else{
							var c = color(parseInt(arr[0].elections));							
							return c;
						}
					}
		    	})*/
		      .attr("d", path)
		      .on("click", clicked);


		   $scope.colorear();
		});

		console.log(ancho);
		if (ancho <= 428) {
		  g.attr("transform","translate(89,60)scale(.38)");
		};

		function clicked(d) {

			/* Marcar estado seleccionado */
			var marcado_id = d.id
			d3.selectAll("path").classed("marcado", false);
			d3.select("#state_"+marcado_id).classed("marcado", true);

		  var x, y, k, st_code, st, abb, centroid;

		  st_code = d.id;
		  //st = $scope.seek_name(st_code);
		  //st =""

		  if (d && centered !== d) {
		    centroid = path.centroid(d);
		    x = centroid[0];
		    y = centroid[1];
		    k = 4;
		    centered = d;
		    /*div.transition()
		      .duration(200)
		      .style("opacity", .9)
		    div.html("Code: "+st_code+"<br/>"+"State: "+st)
		      .style("left", (d3.event.pageX) + "px")
		      .style("top", (d3.event.pageY - 28) + "px");*/
		  }
		  else {
		    x = width / 2;
		    y = height / 2;
		    if (ancho > 428) {
		      k = 1;
		    }
		    else {
		      k = .38;
		    }
		    centered = null;
		    /*div.transition()
		      .duration(500)
		      .style("opacity", 0);*/
		  }

		  g.selectAll("path")
		      .classed("active", centered && function(d) { return d === centered; });

		  /*if (ancho <= 428) {
			g.transition()
				.duration(750)
				.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")")
				.style("stroke-width", 1.5 / k + "px");
			}
			*/


			/* Mostrar modal */				
				
				var scope = angular.element($('#body')).scope();         			
				if (scope.seleccionada == "senador"){

					var color_st = "";
					$.each($scope.list_sendadores, function(index,val){								
						if (parseInt(val.FIPS_Code) == d.id){
							if (parseInt(val.pollpercrep) > parseInt(val.pollpercdem)){
								color_st = "#E93838";
							}
							else {
								color_st = "#3E65AA";
							}
						}
					})

					d3.selectAll(".path_state_senador")
						.attr("d",$("#state_"+d.id).attr("d"))
						.style("fill",color_st);
					k = 1;
					if (d.id == 6 || d.id == 48 || d.id == 32 || d.id == 16){
						k = .6;
					}
					if (ancho<400){
						k = .4;						
						if (d.id == 6 || d.id == 48 || d.id == 32 || d.id == 16){
							k = .2;
						}						
						if ($scope.id_seleccionado != d.id){
							d3.selectAll(".g_senador")
								.attr("transform", "translate(" + 100 / 2 + "," + 70 / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")");
						}
					}else{
						if ($scope.id_seleccionado != d.id){							
							d3.selectAll(".g_senador")
								.attr("transform", "translate(" + 200 / 2 + "," + 120 / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")");
						}
					}


					scope.$apply(function(){
						var arr = $.grep($scope.list_sendadores, function( a ) {
  							return a.FIPS_Code == d.id;
						});		
						if (arr[0]){    		
							scope.senador_seleccionado = arr[0];
							if (($scope.hayspecial()) || ($scope.hayeleccion())){
		        				scope.senadores = true;
								scope.gobernadores = false;	
								scope.id_seleccionado = d.id;

							}else{
								scope.senadores = false;
								scope.gobernadores = false;	
							}
						}
						
	    			});				
				}
				else {
					var color_st_g = "";
					$.each($scope.list_gobernadores, function(index,val){								
						if (parseInt(val.FIPS_Code) == d.id){
							if (parseInt(val.pollpercrep) < parseInt(val.pollpercdem)){
								color_st_g = "#E93838";
							}
							else {
								color_st_g = "#3E65AA";
							}
						}
					})

					d3.select(".path_state_gobernador")
						.attr("d",$("#state_"+d.id).attr("d"))
						.style("fill",color_st_g);
					k = 1;
					if (d.id == 6 || d.id == 48 || d.id == 32 || d.id == 16){
						k = .6;
					}
					if (ancho<400){
						k = .4;						
						if (d.id == 6 || d.id == 48 || d.id == 32 || d.id == 16){
							k = .2;
						}
						if ($scope.id_seleccionado != d.id){
							d3.selectAll("#g_gobernador")
								.attr("transform", "translate(" + 100 / 2 + "," + 70 / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")");
						}
					}else{
						if ($scope.id_seleccionado != d.id){
							d3.select("#g_gobernador")
								.attr("transform", "translate(" + 200 / 2 + "," + 120 / 2 + ")scale(" + k + ")translate(" + -x + "," + -y + ")");
						}

					}

					scope.$apply(function(){
						var arr = $.grep($scope.list_gobernadores, function( a ) {
  							return a.FIPS_Code == d.id;
						});		
						if (arr[0]){    		
							scope.gobernador_seleccionado = arr[0];
							if ($scope.hayeleccion2()){
								scope.id_seleccionado = d.id;
		        				scope.senadores = false;
								scope.gobernadores = true;	
							}else{
								scope.senadores = false;
								scope.gobernadores = false;	
							}
						}
					});	
				}			
		}
	}

	$scope.colorear = function() {
		console.log($scope.seleccionada);
		if ($scope.seleccionada == "senador"){
			$.each($scope.list_sendadores, function(index,val) {								
				var id = "#state_"+val.FIPS_Code;
				d3.select("#state_"+val.FIPS_Code)
					.style("fill", function(d) {						
						var color = d3.scale.ordinal();
						color.domain([0,1,2,3]);
						color.range(["#BDCEDE","#538635","#97C47A","#004000"]);												
						if (parseInt(val.hayspecial) == 1) {
							return color(3)
						}else{
							var c = color(parseInt(val.elections));							
							return c;
						}
					})
					.style("stroke", function(d) {	
						var color = 'red';	
						console.log(val);
						if ( parseInt(val.anycandidatehispanic) == 1) return '#ff6700'; else return 'FFFFFF';			
					})
					.style("stroke-width", function(d) {	
						if ( parseInt(val.anycandidatehispanic) == 1) return '2.5px'; else return '.8px';			
					});
			})
		}
		else {
			$.each($scope.list_gobernadores, function(index,val) {				
				var id = "#state_"+val.FIPS_Code;
				d3.select("#state_"+val.FIPS_Code)
					.style("fill", function(d) {						
						var color = d3.scale.ordinal();
						color.domain([0,1,2]);
						color.range(["#BDCEDE","538635","97C47A"]);
						var c = color(parseInt(val.elections));							
						return c;
					})
					.style("stroke", function(d) {	
						var color = 'red';	
						console.log(val);
						if ( parseInt(val.anycandidatehispanic) == 1) return '#ff6700'; else return 'FFFFFF';			
					})
					.style("stroke-width", function(d) {	
						if ( parseInt(val.anycandidatehispanic) == 1) return '2.5px'; else return '.8px';			
					});
			})
		}
	}

	$scope.incumbent = function(){
		if ($scope.senador_seleccionado.senrunning_esp == "Candidato"){
			return true;
		}else{
			return false;
		}
	}

	$scope.incumbentSE = function(){
		if ($scope.senador_seleccionado.senrunning_esp_SE == "Candidato"){
			return true;
		}else{
			return false;
		}
	}

	$scope.hayspecial = function(){
		if ($scope.senador_seleccionado.hayspecial == "1"){
			return true;
		}else{
			return false;
		}
	}

	$scope.hayeleccion = function(){
		console.log($scope.senador_seleccionado);
		if ($scope.senador_seleccionado.elections != "0"){
			return true;
		}else{
			return false;
		}
	}

	$scope.getImageUrl = function(name){		
		name = name.split(' ').join('_');
		name = name.split('.').join('_');
		name = name+".jpg";
		console.log(name);
		return name;
	}

	$scope.getClassParty = function(p){
		if (p== "R")
			return "rep";
		if (p== "D")
			return "dem";
		return "som";
	}
	$scope.getParty = function(p){
		if (p== "R")
			return "Republican";
		if (p== "D")
			return "Democratic";
		if (p == "I")
			return "Independent";
	}

	/* BRYAN CONTROLLER */

	$scope.validar_estatus = function() {
		console.log($scope.gobernador_seleccionado);
		if (($scope.gobernador_seleccionado.notseekingreelection != 1) && ($scope.gobernador_seleccionado.cantbereelected != 1) &&( $scope.gobernador_seleccionado.lostprimary != 1)) {
			return false;
		}
		else {
			return true;
		}
	}

	$scope.validar_candidato = function() {
		console.log($scope.gobernador_seleccionado);
		if (($scope.gobernador_seleccionado.notseekingreelection != 1) && ($scope.gobernador_seleccionado.cantbereelected != 1) &&( $scope.gobernador_seleccionado.lostprimary != 1)) {
			return true;
		}
		else {
			return false;
		}
	}

	$scope.validar_candidato2 = function() {
		console.log($scope.gobernador_seleccionado);
		if (($scope.gobernador_seleccionado.notseekingreelection != 1) && ($scope.gobernador_seleccionado.cantbereelected != 1) &&( $scope.gobernador_seleccionado.lostprimary != 1)) {
			return false;
		}
		else {
			return true;
		}
	}

	$scope.check_puesto_1 = function() {
		if ($scope.gobernador_seleccionado.opcandidate1pastjob != '') {
			return true;
		}
		else {
			return false;
		}
	}

	$scope.check_puesto_2 = function() {
		if ($scope.gobernador_seleccionado.opcandidate2pastjob != '') {
			return true;
		}
		else {
			return false;
		}
	}

	$scope.check_puesto_3 = function() {
		if ($scope.gobernador_seleccionado.opcandidate3pastjob != '') {
			return true;
		}
		else {
			return false;
		}
	}

	$scope.hayeleccion2 = function() {
		console.log($scope.gobernador_seleccionado);
		if ($scope.gobernador_seleccionado.elections != "0"){
			return true;
		}else{
			return false;
		}
	}

	$scope.getImageUrl2 = function(name){		
		name = name.split(' ').join('_');
		name = name.split('.').join('_');
		name = name+".jpg";
		console.log(name);
		return name;
	}

	$scope.getClassParty2 = function(p){
		if (p == "R")
			return "rep";
		if (p == "D")
			return "dem";
		return "som";
	}
	$scope.getParty2 = function(p){
		if (p== "R")
			return "Republican";
		if (p== "D")
			return "Democratic";
		if (p == "I")
			return "Independent";
		if (p == "Libertarian")
			return "Libertarian";
		if (p == "Peace and Freedom")
			return "Peace and Freedom";
		if (p == "Liberty Union Party")
			return "Liberty Union Party";
	}
	

	$scope.init();
	$scope.colorear();


});