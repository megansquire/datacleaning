<?php
// read in the file
$json = file_get_contents("outfile.json");

// convert JSON to an associative array
$array = json_decode ($json, true);

// open the file stream
$file = fopen('php://output', 'w');
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="enronEmail.csv"');

// loop through the array and write each item to the file
foreach ($array as $line) 
{
	fputcsv($file, $line);
}
?>
