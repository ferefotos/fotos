<?php
$sql="SELECT * FROM kategoria";
$eredmeny=mysqli_query($dbconn, $sql);

//$kategoriak="<ul>";
$kategoriak="";
while($sor = mysqli_fetch_assoc($eredmeny)){
    $kategoriak .="
    <li><a href=\"gallery.php?katid={$sor['id']}\">{$sor['kategoria']}</a></li>
    ";
}
//$kategoriak.="</ul>";      
?>
<div class="logo">
    <a href="index.php"><img src="items/logo.png" alt="Zoom"></a>
    </div>
    <nav>
        <form action="gallery.php" method="get">
            <input type="search" name="search" id="kereso" title="keresés">
            <button type="submit" name="keres" value="">
                <img src="items/find.png" alt="keresés" title="keresés">
            </button>
        </form>
        <h3>Kategóriák</h3>
        <ul>
        <?php echo $kategoriak; ?>
        </ul>
    </nav>