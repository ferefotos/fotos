<?php
require("config.php");
require("header.php");
require(ROOT_PATH. "/form/uploadform.php");
require(ROOT_PATH. "/form/regform.php");
require_once(ROOT_PATH. "/form/common.php");
?>
                <div id=guide_container>
                    <div id="guide">
                        <h2>Tájékoztató az oldal használatáról</h2>
                        <h4>Kedves látogató!</h4>
                        <p>Üdvözlünk a Zoom fotós közösségi oldalon! Az alábbiakban szeretnénk tájékoztatni 
                            az oldal használatával kapcsolatos tudnivalókról.</p>
                        <p>Az oldal célja, hogy a fotózás iránt érdeklődő lelkes amatőr fotósok meg tudják mutatni egymásnak
                            a legjobban sikerült képeiket, abból a célból hogy másoknak ötletet adjanak, tanuljanak egymástól,
                            mások értékeljék alkotásaikat, illetve maguk is értékeljék másokét. Ezzel kapcsolatban fontos hogy olvasd el 
                            a működési szabályzatot, amely minden felhasználóra nézve kötelező! Ezen az oldalon az oldal használatához
                            nyújtunk segítséget. </p>
                        <p>Az oldal funkcióit csak regisztrált felhasználók érik el, regisztráció nélkül csak a képek megnézésére
                            van lehetőség. A főoldalon a legutóbb feltöltött 30 képet láthatod. A bal oldalon található kategória menüből választva 
                            tematikus galériákban nézheted a fotókat. Ha az egérmutatót a fotók fölé viszed, a kép alján megjelenik a
                            kép szerzője és láthatod hányan kedvelik a fotót és hányan mentették a kedvenc képek galériába. Regisztrált
                            felhasználóként itt is tudod "lájkolni" &nbsp<img src="items/heart24c.png" width="20"> &nbsp a képet, vagy kedvencnek
                            &nbsp<img width="20" src="items/star24c.png"> &nbsp jelölni. A jelölések visszavonhatóak. Ha a kép szerzőjének a nevére 
                            klikkelsz, akkor annak fotóit egy külön galériában nézheted meg. A felhasználói galéria címsorára klikkelve egy lenyíló 
                            mezőben a felhasználó adatai jelennek meg:</p>
                        <div id="user_gallery_title" onclick="show_userinfo()"><img src="users/tesztelek.jpg">
                            <h3>Teszt Elek fotói</h3><img src="items/down.png" id="arrow" ></div>
                        <div id="userinfo" class="userinfo_guide">
                            <div id="userinfo_cam">
                                <div><img src="items/cam_blue.png"><p>Canon EOS 800D</p></div>
                                <div><img src="items/lens_blue.png"><p>Canon 24-105 F4 L</p></div>
                            </div>  
                                <div id="userinfo_rolam"><img src="items/pen_blue.png"><p>10 éves korom óta érdekel a fotózás...</p></div>
                        </div>
                        <p>A kategória menü felett található keresővel egy kifejezésre (akár szó töredékre is) kereshetsz. 
                            A keresés eredménye a kifejezéshez tartozó kép vagy képek lesznek. A kereső a következő adatokban tud keresni:</p>  
                        <ul>
                            <li>képek címében és leírásában</li>
                            <li>kamerák és objektívek nevében, amelyekkel a képek készültek</li>
                            <li>képek hozzászólásaiban</li>
                            <li>felhasználók nevében</li>
                        </ul>  
                        <p>A galériában egy képre klikkelve a képet nagyobb nézetben láthatod, a kép adataival együtt. A fotó adatlapján 
                            a kép címe, leírása, szerzője és a kép exif adatai szerepelnek. Itt szintén van lehetőségünk a képet lájkolni
                            vagy kedvencnek jelölni, ha regisztrált felhasználó vagy. A kép alatt a képhez fűzött hozzászólásokat lehet 
                            olvasni, és regisztrált felhasználóként te is írhatsz véleményt.</p>
                        <img class="guide_foto" src="items/guide/login.png">
                        <p> A regisztráció, és a bejelentkezés a fejléc jobb oldalán található legördülő menüből érhető el. 
                            A regisztráció során az alábbi adatok megadása kötelező:</p>
                        <ul class= guide_right_ul>
                            <li>felhasználónév (min. 6, max. 16 karakter)</li>
                            <li>jelszó (min. 8 karakter)</li>
                            <li>megismételt jelszó</li>
                            <li>e-mail cím</li>
                            <li>valódi név, esetleg becenév (ez fog megjelenni a feltöltött képeknél)</li>
                        </ul> 
                        <p>Profilkép tölthető fel. A profilkép előnézetben tudod nagyítani és pozícionálni a képet. A keretben látható
                            kép lesz a profilképed. Opcionálisan megadható a fényképezőgéped típusa, az objektív típusa és egy rövid 
                            bemutatkozást is tudsz írni magadról. Ezek az információk mások számára is láthatóak lesznek, a fentebb 
                            leírtak szerint a felhasználói galéria felett.</p>
                        <p> Sikeres regisztráció esetén a rendszer automatikusan beléptet, és a "bejelentkezés" menüpont átalakul 
                            egy saját menüvé. A fejlécen a keresztneved és a profilképed jelenik meg, alatta pedig a legördülő 
                            menüből a következő lehetőségek közül választhatsz: A saját képeknél az általad feltöltött képeket 
                            nézheted meg, a kedvencek alatt pedig a már korábban említett kedvencnek jelölt képeket tudod újra 
                            megnézni. Itt tudsz kijelentkezni az oldalról, és az adataid módosítását is itt éred el. </p>   
                        <p> Minden adatod módosítani tudod, de a módosításhoz meg kell adnod a jelszavad. Az új jelszó és a jelszó
                            megismétlése mezőket csak akkor töltsd ki, ha módosítani akarod a jelszavad, egyébként hagyd üresen.
                            Amennyiben törölnéd a regisztrációt, a rendszer figyelmeztetni fog, hogy minden adatod, és a feltöltött 
                            fényképeid is törlődni fognak. Törlődni fognak továbbá a képekhez fűzött hozzászólásaid, és a jelölések is!
                            A regisztráció törlését meg kell erősítened, és ekkor is szükséges a jelszavad megadása.</p>     
                        <img class="guide_foto" src="items/guide/upload.png">
                        <p>Bejelentkezve tudsz képeket feltölteni, a fejlécen található felhő 
                            &nbsp<img width="30" src="items/upload.png"> &nbsp ikonra klikkelve. Egyszerre egy kép feltöltésére 
                            van lehetőség, melynek maximális mérete 16MB lehet. Lehetőleg kisebb méretű fájlt tölts fel, mert a nagyobb
                            méretű fájl feltöltése több időbe telhet, és a rendszer egyébként is lekicsinyíti a képet, hogy azok gyorsabban
                            betöltődjenek. Javasoljuk a max. HD felbontású (1920px x 1080px) és max. 5-6Mb méretű képek feltöltését! 
                            A feltölthető képek formátuma jpg, png vagy gif lehet. Válaszd ki a feltölteni kívánt képet, és várd meg 
                            amíg betöltődik az itt látható űrlap. Kategóriát egy lenyíló listából tudsz választani. Ennek megadása kötelező!
                            Olyan kategóriát válassz amelynek leginkább megfelel a kép témája. Lehetőleg adj a képnek címet és leírást is! 
                            Mondd el hol készült a kép vagy milyen technikával, vagy bármit ami érdekes lehet mások számára. A kép exif adatait
                            a program kiolvassa a fájlból, amennyiben az tartalmazza, segítve ezzel, hogy ne kelljen beírnod milyen beállításokkal 
                            és milyen felszereléssel készült a kép. Ha ezek a mezők üresek lennének, az azt jelenti, hogy a program valamiért 
                            nem tudta kiolvasni a fájlból. Ha tudod, kitöltheted az üres mezőket, vagy hagyd üresen. Ha a beolvasott adatok 
                            formátuma valamiért nem jó, akkor módosíthatod. Az exif adatok módosítására később már nem lesz lehetőséged! 
                            A képet megjelölheted "nem publikusnak" is, ami azt jelenti, hogy rajtad kívül más nem láthatja. Ezek a képek 
                            később publikussá tehetőek. A feltölt gombra klikkelve azonnal megjelenik a fotó a galériában.</p>
                        <p>A képek adatai, az egyes képnézetben láthatóak. Saját képeid adatait is itt tudod módosítani az adatlap
                            jobb alsó sarkában található 3 pontra klikkelve. Egy menüből választhatsz ekkor, módosítás illetve törlés. 
                            Módosítani a kép címét, leírását, kategóriáját tudod, és azt hogy a kép publikus legyen vagy sem.
                            A törlést választva megerősítést kér a rendszer, mivel a művelet nem vonható vissza. A kép minden adatával a hozzá
                            kapcsolódó jelölésekkel és hozzászólásokkal együtt törlődik. </p>
                        <p>Regisztrált felhasználóként a feltöltött képekhez hozzá tudsz szólni, a kép adatlapja alatt található űrlap segítségével.</p>

                    </div>
                    <aside></aside>
                </div>
            </div>
        </main>
    </div>
  <script src="script/ajaxform.js" type="text/javascript"></script>
  <script src="script/script.js" type="text/javascript"></script>
</body>
</html>