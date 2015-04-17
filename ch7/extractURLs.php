<?php
// connect to db
$dbc = mysqli_connect('localhost', 'username', 'password', 'sentiment140')
    or die('Error connecting to database!' . mysqli_error());
$dbc->set_charset("utf8"); 
     
// pull out the tweets
$select_query = "SELECT id, tweet_text FROM sentiment140";
$select_result = mysqli_query($dbc, $select_query);

// die if the query failed
if (!$select_result)
    die ("SELECT failed! [$select_query]" .  mysqli_error());

// pull out the URLS, if any
$urls = array();
# this pattern is from http://daringfireball.net/2010/07/improved_regex_for_matching_urls
$pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';

while($row = mysqli_fetch_array($select_result))
{
    echo "<br/>working on tweet id: " . $row["id"];
    if (preg_match_all(
        $pattern,
        $row["tweet_text"],
        $urls
    ))
    { 
        foreach ($urls[0] as $name) 
        {
            echo "<br/>----url: ".$name;
            $insert_query = "INSERT into sentiment140_urls (id, tweet_id, url) 
                VALUES (NULL," . $row["id"] . ",'$name')";
            echo "<br />$insert_query";
            $insert_result = mysqli_query($dbc, $insert_query);
            // die if the query failed
            if (!$insert_result)
                die ("INSERT failed! [$insert_query]" .  mysqli_error());
        }
    }                                         
}
?>
