<?php
$sql="SELECT * FROM kategoria
      LEFT JOIN foto ON foto.katid=kategoria.id
      WHERE katid IS NOT NULL
      GROUP BY katid ORDER BY kategoria";
$eredmeny=mysqli_query($dbconn, $sql);
$kategoriak="<li><a href=\"gallery.php?toplist=\">TOP 60 fotó</a></li>\n";
while($sor = mysqli_fetch_assoc($eredmeny)){
    $kategoriak .="<li><a href=\"gallery.php?katid={$sor['id']}\">{$sor['kategoria']}</a></li>";
}
?>
    <div class="logo">
        <a href="index.php"><img src="items/logo.png" alt="Zoom"></a>
    </div>
    <nav>
       <form action="gallery.php" method="get" id="searchform">
            <input type="search" name="search" id="kereso" title="keresés">
            <button type="submit" name="keres">
                <img src="items/find.png" alt="keresés" title="keresés">
            </button>
       </form>
        <h3>Kategóriák</h3>
        <div>
            <ul>
                <?php echo $kategoriak; ?>
            </ul>
        </div>
    </nav>