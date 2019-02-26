<?php 
function tisztit($text){
    global $dbconn;
    return mysqli_real_escape_string($dbconn, stripslashes(strip_tags(trim($text))));
}

?>