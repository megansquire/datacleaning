<?php
//read in the file
$file = fopen('inputFile.csv', 'r');

//the first line is a header line
$headers = fgetcsv($file, 0, ',');
$complete = array();

//read each line and create array
while ($row = fgetcsv($file, 0, ',')) 
{
    $complete[] = array_combine($headers, $row);
}
fclose($file);

//json-encode each array item
$json_formatted = json_encode($complete);

//write out file
file_put_contents('enronEmail.json',$json_formatted);
?>
