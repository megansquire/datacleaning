<?php
// connect to db
$dbc = mysqli_connect('localhost', 'username', 'password', 'stackoverflow')
    or die('Error connecting to database!' . mysqli_error());
$dbc->set_charset("utf8"); 
     
// pull out the text for posts with 
// postTypeId=1 (questions) 
// or postTypeId=2 (answers)
$post_query = "SELECT Id, Body 
    FROM test_posts 
    WHERE postTypeId=1 OR postTypeId=2";

$comment_query = "SELECT tc.Id, tc.Text 
    FROM test_comments tc 
    INNER JOIN posts p ON tc.postId = p.Id 
    WHERE p.postTypeId=1 OR p.postTypeId=2";

$post_result = mysqli_query($dbc, $post_query);
// die if the query failed
if (!$post_result)
    die ("post SELECT failed! [$post_query]" .  mysqli_error());

// pull out the URLS, if any
// using the same URL pattern as in chapter 5, originally from
// daringfireball.net/2010/07/improved_regex_for_matching_urls
$urls = array();
$pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';

while($row = mysqli_fetch_array($post_result))
{
    echo "\nworking on post: " . $row["id"];
    if (preg_match_all(
        $pattern,
        $row["Body"],
        $urls
    ))
    { 
        foreach ($urls[0] as $url) 
        {
        	$url = mysqli_escape_string($dbc, $url);
            echo "\n----url: ".$url;
            $post_insert = "INSERT INTO clean_posts_urls (id, postid, url) 
                VALUES (NULL," . $row["Id"] . ",'$url')";
            echo "\n$post_insert";
            $post_insert_result = mysqli_query($dbc, $post_insert);
        }
    }                                         
}

$comment_result = mysqli_query($dbc, $comment_query);
// die if the query failed
if (!$comment_result)
    die ("comment SELECT failed! [$comment_query]" .  mysqli_error());

while($row = mysqli_fetch_array($comment_result))
{
    echo "\nworking on comment: " . $row["id"];
    if (preg_match_all(
        $pattern,
        $row["Text"],
        $urls
    ))
    { 
        foreach ($urls[0] as $url) 
        {
            echo "\n----url: ".$url;
            $comment_insert = "INSERT INTO clean_comments_urls (id, commentid, url) 
                VALUES (NULL," . $row["Id"] . ",'$url')";
            echo "\n$comment_insert";
            $comment_insert_result = mysqli_query($dbc, $comment_insert);
        }
    }                                         
}
?>
