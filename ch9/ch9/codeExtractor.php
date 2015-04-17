<?php 
// connect to db
$dbc = mysqli_connect('localhost', 'username, 'password', 'stackoverflow')
    or die('Error connecting to database!' . mysqli_error());
$dbc->set_charset("utf8"); 
     
// pull out the text for posts with 
// postTypeId=1 (questions) 
// or postTypeId=2 (answers)
$code_query = "SELECT Id, Body 
    FROM test_posts 
    WHERE postTypeId=1 OR postTypeId=2
    AND Body LIKE '%<code>%'";

$code_result = mysqli_query($dbc, $code_query);
// die if the query failed
if (!$code_result)
    die ("SELECT failed! [$code_query]" .  mysqli_error());

// pull out the code snippets from each post
$codesnippets = array();
$pattern  = '/<code>(.*?)<\/code>/';

while($row = mysqli_fetch_array($code_result))
{
    echo "\nworking on post: " . $row["Id"];
    if (preg_match_all(
        $pattern,
        $row["Body"],
        $codesnippets
    ))
    {
    	$i=0; 
        foreach ($codesnippets[0] as $code) 
        {
        	$code = mysqli_escape_string($dbc, $code);
            $code_insert = "INSERT INTO clean_posts_code (id, postid, code) 
                VALUES (NULL," . $row["Id"] . ",'$code')";
            $code_insert_result = mysqli_query($dbc, $code_insert);
            if (!$code_insert_result)
    			die ("INSERT failed! [$code_insert]" .  mysqli_error());
            $i++;
        }
        if($i>0)
        {
        	echo "\n   Found $i snippets";
        }
    }                                         
}
?>
