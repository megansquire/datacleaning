<?php
// connect to db, set up query, run the query
$dbc = mysqli_connect('localhost','username','password','enron')
	or die('Error connecting to database!' . mysqli_error());

$select_query = "SELECT concat(firstName,  \" \", lastName) as name, email_id 
	FROM employeelist ORDER BY lastName";
	
$select_result = mysqli_query($dbc, $select_query);

if (!$select_result)
	die ("SELECT failed! [$select_query]" .  mysqli_error());

// ----JSON output----
// build a new array, suitable for json
$counts = array();
while($row = mysqli_fetch_array($select_result))
{
	// add onto the json array  
	array_push($counts, 
		array('name' => $row['name'], 
		'email_id' => $row['email_id']));      
}
// encode query results array as json
$json_formatted = json_encode($counts);

// write out the json file
file_put_contents("enronEmail.json", $json_formatted);
?>
