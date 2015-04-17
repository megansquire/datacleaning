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

// pull out the mentions, if any
$mentions = array();
while($row = mysqli_fetch_array($select_result))
{
    if (preg_match_all(
        "/(?<!\pL)@(\pL+)/iu",
        $row["tweet_text"],
        $mentions
    ))
    { 
        foreach ($mentions[0] as $name) 
        {
            $insert_query = "INSERT into sentiment140_mentions (id, tweet_id, mention) VALUES (NULL," . $row["id"] . ",'$name')";
            echo "<br />$insert_query";
            $insert_result = mysqli_query($dbc, $insert_query);
            // die if the query failed
            if (!$insert_result)
                die ("INSERT failed! [$insert_query]" .  mysqli_error());
        }
    }                                         
}
?>
