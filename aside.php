<?php
$sql="SELECT *
      FROM kategoria";

$eredmeny=mysqli_query($dbconn, $sql);


$kategoriak="<ul>";
while($sor = mysqli_fetch_assoc($eredmeny)){
    $kategoriak.="
    <li><a href=\"gallery.php?id={$sor['id']}/\">{$sor['kategoria']}</a></li>
    ";
}
$kategoriak.="<ul>";      

?>