<?php
// connect to db, set up query, run the query
$dbc = mysqli_connect('localhost','username','password','enron')
	or die('Error connecting to database!' . mysqli_error());
       
$select_query = "SELECT concat(firstName,  \" \", lastName) as name, email_id 
	FROM employeelist ORDER BY lastName";
	
$select_result = mysqli_query($dbc, $select_query);

if (!$select_result)
	die ("SELECT failed! [$select_query]" .  mysqli_error());

// ----CSV output----
// set up a file stream
$file = fopen('php://output', 'w');
if ($file && $select_result) 
{
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="enronEmail.csv"');
    // write each result row to the file in csv format
    while($row = mysqli_fetch_assoc($select_result))
    {
   		fputcsv($file, array_values($row));
    }
}
?>
