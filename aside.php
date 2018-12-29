<?php
require("connect.php");
$sql="SELECT *
      FROM kategoria";

$result=mysqli_query($dbconn, $sql);


$kategoriak="<ul>";
while($sor = mysqli_fetch_assoc($result)){
    $kategoriak.="
    <li><a href=\"gallery.php?id={$sor['id']}/\">{$sor['kategoria']}</a></li>
    ";
}
$kategoriak.="<ul>";      

?><!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>weboldal</title>   
</head>

<body>
    <div class="logo">
        <img src="items/logo.png" alt="Zoom">
    </div>
    <nav>
        <form action="" method="get">
            <input type="search" name="kereso" id="kereso" title="keresés">
            <button type="submit" name="keres" value="">
                <img src="items/find.png" alt="keresés" title="keresés">
            </button>
        </form> 

        <h3>Kategóriák</h3>

        <?php echo $kategoriak;?>

        
    </nav>

</body>
</html>