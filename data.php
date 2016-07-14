<?php 
	/*DB Connection*/
	$mysqli = new mysqli("mysql51-094.wc1.ord1.stabletransit.com", "871197_senadores", "D4.#2014.senadores", "871197_senadores", 3306);
	
	if ($mysqli->connect_errno) {
	    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	
	if (!$mysqli->set_charset("utf8")) {
	    printf("Error loading character set utf8: %s\n", $mysqli->error);
	}

	$senadores = $mysqli->query("SELECT * FROM Senadores");
	$rows = array();
	while ($row = $senadores->fetch_assoc()) {
    	$rows[]=$row;
	}
	$list_sendadores = json_encode($rows);
	
	$gobernadores = $mysqli->query("SELECT * FROM Gobernadores");
	$rows = array();
	while ($row = $gobernadores->fetch_assoc()) {
    	$rows[]=$row;
	}
	$list_gobernadores = json_encode($rows);
?>
