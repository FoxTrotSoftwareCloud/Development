<?php
//echo 'Welcome'; php_info();exit;
$mysqli = new mysqli("sql5c40n.carrierzone.com", "jjixgbv9my802728", "We3b2!12", "demo_jjixgbv9my802728");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}


$tbl = "clients1";
$db = 'demo_jjixgbv9my802728';

// Path to dbase file
$db_path = "fto_clients.DBF";

// Open dbase file
$dbh = dbase_open($db_path, 0)
or die("Error! Could not open dbase database file '$db_path'.");

// Get column information
$column_info = dbase_get_header_info($dbh);

$line = array();

foreach($column_info as $col) {
	switch($col['type']){

		case 'character':
			$line[]= "`$col[name]` VARCHAR( $col[length] )";
			break;
	
		case 'number':
			$line[]= "`$col[name]` FLOAT";
			break;

		case 'boolean':
			$line[]= "`$col[name]` BOOL";
			break;

		case 'date':
			$line[]= "`$col[name]` DATE";
			break;

		case 'memo':
			$line[]= "`$col[name]` TEXT";
			break;
	}
}

$str = implode(",",$line);
$sql = "CREATE TABLE `$tbl` ( $str );";

//mysql_select_db($db,$conn);

//mysql_query($sql,$conn);
$mysqli->query($sql);
set_time_limit(0); // I added unlimited time limit here, because the records I imported were in the hundreds of thousands.

// This is part 2 of the code

import_dbf($db, $tbl, $db_path, $mysqli);

function import_dbf($db, $table, $dbf_file,$mysqli){
	//global $conn;
	global $mysqli;
	if (!$dbf = dbase_open ($dbf_file, 0)){ die("Could not open $dbf_file for import."); }
	$num_rec = dbase_numrecords($dbf);
	$num_fields = dbase_numfields($dbf);
	$fields = array();

	for ($i=1; $i<=$num_rec; $i++){
	$row = @dbase_get_record_with_names($dbf,$i);
	$q = "insert into $db.$table values (";
	foreach ($row as $key => $val){
	if ($key == 'deleted'){ continue; }
	$q .= "'" . addslashes(trim($val)) . "',"; // Code modified to trim out whitespaces
	}

	if (isset($extra_col_val)){ $q .= "'$extra_col_val',"; }
	$q = substr($q, 0, -1);
	$q .= ')';
	//if the query failed - go ahead and print a bunch of debug info
	// if (!$result = mysql_query($q, $conn)){
	if (!$result = $mysqli->query($q)){
		print (mysql_error() . " SQL: $q\n");
		print (substr_count($q, ',') + 1) . " Fields total.";

		$problem_q = explode(',', $q);
		$q1 = "desc $db.$table";
		//$result1 = mysql_query($q1, $conn);
		$result1 = $mysqli->query($q1);
		$columns = array();

		$i = 1;

		while ($row1 = $result1->fetch_assoc()){
			$columns[$i] = $row1['Field'];
			$i++;
		}

		$i = 1;
		foreach ($problem_q as $pq){
			print "$i column: {$columns[$i]} data: $pq\n";
			$i++;
		}
		die();
	}
}
}

$mysqli->close();

?>