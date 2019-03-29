/************************************************
 *     Űrlapok kezelése AJAX technológiával     *
 ************************************************/

// Paraméterek megadása az AJAX-hoz
/* Attól függően melyik submit gombot nyomják meg, beállítja a megfelelő paramétereket*/

//Regisztráció és login:
var formid = "";
var phpurl = "";
var button = "";
$("#reset").click(function () {
    formid = "#regform";
    phpurl = "form/reg.php";
    errors = "reg-errors";
    button = "reset";
});
$("#elkuld").click(function () {
    formid = "#regform";
    phpurl = "form/reg.php";
    errors = "reg-errors";
    button = "elkuld";
});
$("#login").click(function () {
    formid = "#usermenu-sub";
    phpurl = "form/log.php";
    errors = "error";
    button = "login";
});
$("#logout").click(function () {
    formid = "#logoutform";
    phpurl = "form/log.php";
    button = "logout";
});
// Regisztrációs formon enterre submit, cancel-re kilép
document.querySelector("#regform").addEventListener("keydown", event => {
    if(event.keyCode==13){
    document.querySelector("#elkuld").click(); 
    event.preventDefault(); 
    }
    if(event.keyCode==27){
        document.querySelector("#reset").click(); 
        event.preventDefault(); 
    }   
});

//Paraméterek megadása a képfeltöltő űrlaphoz
$("#feltolt").click(function () {
    formid = "#uploadform";
    phpurl = "form/upload.php";
    errors = "up-errors";
    button = "feltolt";
});
$("#cancel").click(function () {
    formid = "#uploadform";
    phpurl = "form/upload.php";
    button = "reset";
});

//Üzenet, ha nincs bejelentkezve és képet akar feltölteni
$("#uplink-notlog").click(function () {
    document.getElementById("alert").innerHTML = "Kép feltöltéséhez jelentkezz be!";
    show_alert();
});
//Üzenet megjelenítése ha hiba van a file feltöltésnél
if (document.getElementById("alert").innerHTML != "") {
    show_alert();
}
function show_alert() {
    document.getElementById('alertbox').style.display = "block";
    setTimeout(function(){
        document.getElementById('alertbox').style.display = "none";
    }, 4000);
}

// Az elsötétített háttérre klikkelve kilép a formból oldal frissítéssel
$(".form_background").click(function () {
    //window.location.replace("index.php"); 
    window.location.href = window.location.href;
});
// Kép feltöltésnél amíg a feltöltésre vár, egy loading gif jelenik meg elsötétített háttérrel
$("#foto_up").change(function () {
    document.getElementById("loading").style.display = "flex";
    uploadform="";
});
// Képfeltöltő formon enterre submit, cancel-re kilép
if (typeof uploadform !== "undefined") {
    document.querySelector("#uploadform").addEventListener("keydown", e => {
        if(e.keyCode==13){
            document.querySelector("#feltolt").click(); 
            e.preventDefault(); 
        }
        if(e.keyCode==27){
            document.querySelector("#cancel").click(); 
            e.preventDefault(); 
        }   
    });
}
//lájkoláshoz, kedvencnek jelöléshez és kép hozzászóláshoz a paraméterek beállítása:

/*A galériában lájkolásnál (csak ott kell, a kép nézetben ez üres)
  * az id-ból határozza meg a file nevet amelyikre lájkoltunk (vagy kedvencnek jelöltük)
  * és ezt fogjuk elküldeni a feldolgozó php-nak.
  * (Azért nem rejtett input mezőből, mert akkor annak is minden képnél egyedi azonosító kellene)
  * Kép nézetben a fájlnév a rejtett input mezőből kerül elküldésre.*/
 var file=""; 
 var like_id=""; // a like gombok képének cseréjéhez is kell
 function liked(clicked){
     like_id=clicked.id;
     file=like_id.replace(like_id.charAt(like_id.length -1), "");
     if(like_id.charAt(like_id.length -1) == "K"){
         kedvenc_param();
     }
     if(like_id.charAt(like_id.length -1) == "L"){
         like_param();
     }
 }
// foto nézetben lájk vagy kedvencnek jelölés:
$(".like").click(function () {
    like_param();
});
$(".kedvenc").click(function () {
    kedvenc_param();
});
//paraméterek beállítása
function like_param(){
    formid = "#foto_like";
    phpurl = "react/like.php";
    button = "like";
    alert_id="#like_alert";
}
function kedvenc_param(){
    formid = "#foto_like";
    phpurl = "react/like.php";
    button = "kedvenc";
    alert_id = "#kedvenc_alert";
}
$("#hozzaszol").click(function () {
    formid = "#komment_form";
    phpurl = "react/comment.php";
    button = "hozzaszol";
});

// A submitra az elküldendő adatok összeállítása 
$('form' + formid).on('submit', function (e) {
    //letiltja az alapértelmezett submit eseményt
    if (formid != "") {e.preventDefault();}
    
    //új formData objektum, az űrlapmezők ebben kerülnek elküldésre
    var formData = new FormData($('form' + formid)[0]);

    //gombok hozzáadása az elküldendő form adatokhoz
    if(button != ""){
        formData.append(button, '');
    }
    //fájlnév hozzáadása like vagy kedvencnek jelölés esetén
    /* A galériában lájkolásnál külön hozzá kell adni, mert nem rejtett input mezőből küldjük */
    if(file != ""){
        formData.append('liked', file);
    }
    //teszt - a formData adatait kiírja a konzolra
    /*for (var pair of formData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }*/

// Ajax---------------------------------------- 
    $.ajax({
        type: "POST",
        url: phpurl,
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        dataType: "json",
        success: function (response) {
            //Ha hiba üzenet jön a feldolgozó php-ból
            if (response.status == "ERR") {
                /*A regisztráció törlést meg kell erősíteni. Ez hiba státuszban 
                 * jön vissza, és ekkor frissteni kell az oldalt*/
                if (response.delete == "conf") {
                    window.location.replace("index.php");
                }
                //A hibák kiíratása a megfelelő helyre
                if(response.error.includes("MySqli hiba")){
                    alert(response.error);
                }else{
                    document.getElementById(errors).innerHTML = response.error;
                }
                /*hogy hiba esetén ne záródjon vissza a login ablak ha elvisszük fölüle az egeret
                   (mezőn kívülre klikkelve viszont visszazáródik) */
                if (formid == "#usermenu-sub") {
                    inlog();
                }
                /*A kiválasztott képfájl nevét vissza kell írni a form rejtett input mezőjébe
                 * (regisztrációs űrlap profilképnél) */  
                if(response.pkep_file !=""){
                    document.getElementById("pkep_file").value=response.pkep_file;
                }
            }
            // Ha sikeres az űrlapfeldolgozás
            if (response.status == "OK") {
               window.location.replace("index.php"); 
               //location.reload();
            }
            // Sikeres hozzászólás a képhez
            if (response.status == "KOMMENT_OK") {
                // Az űrlap textarea mezőjét kiüríti
                document.getElementById("komment").value="";
                // Új hozzászólás megjelenítése 
                if(response.komment != ""){             
                    $("#post_box_empty").append(
                        "<div class=post_box>" +
                        "<div class=post_pkep><img src=\"users/" + response.pkep + "\"></div>" +
                        "<div class= post_right>" +
                            "<div class=post_header>" +
                                "<a href=\"gallery.php?userid=" + response.userid + "\">" + response.nev + "</a>" +
                                "<span>" + response.date +  "</span>" +
                            "</div>" +
                            "<p class=post>" + response.komment + "</p>"+
                        "</div></div><br>");  
                }          
            }
            //Sikeres lájkolás és kedvencnek jelölés (vagy jelölés törlés) esetén ikonok cseréje, darabszám kiírása
            if(response.status == "like_be"){
                if(like_id == ""){ //egyes képnézetben
                    document.getElementById("like_img").src="items/heart40cb.png";
                    document.getElementById("count_like").innerHTML=response.db;
                }else{ //galéria nézetben
                    document.getElementById(like_id + "i").src="items/heart24cb.png";
                    document.getElementById(like_id + "c").innerHTML=response.db;
                } 
            }
            if(response.status == "like_ki"){
                if(like_id == ""){
                    document.getElementById("like_img").src="items/heart40c.png";
                    document.getElementById("count_like").innerHTML=response.db;
                }else{
                   document.getElementById(like_id + "i").src="items/heart24c.png";
                   document.getElementById(like_id + "c").innerHTML=response.db;
                }
            }
            if(response.status == "kedvenc_be"){
                if(like_id == ""){
                    document.getElementById("kedvenc_img").src="items/star40cy.png";
                    document.getElementById("count_kedvenc").innerHTML=response.db;
                }else{ 
                    document.getElementById(like_id + "i").src="items/star24cy.png";
                    document.getElementById(like_id + "c").innerHTML=response.db;
                }
            }
            if(response.status == "kedvenc_ki"){
                if(like_id == ""){ 
                    document.getElementById("kedvenc_img").src="items/star40c.png";
                    document.getElementById("count_kedvenc").innerHTML=response.db;
                }else{ 
                    document.getElementById(like_id + "i").src="items/star24c.png";
                    document.getElementById(like_id + "c").innerHTML=response.db;
                }
            }
            /* Ha nincs bejelentkezve a felhasználó, nem tud lájkolni, vagy kedvencnek jelölni
             * ilyenkor üzenetet kap.*/
            if (response.status == "LOGOUT") {
                if(like_id == ""){ //egyes képnézetben
                    show_like_alert();
                }else{ //galéria nézetben
                    kep_id=like_id.replace(like_id.substring(like_id.length -5), "");
                    alert_id="#" + kep_id + " .like_alert";
                    show_like_alert();
                }
            }
        }
    });
});
// Üzenet megjelenítése és elrejése (2mp után) ha nem bejelentkezve próbál valaki lájkolni
function show_like_alert() {
    $(alert_id).css("display", "block");
    setTimeout(function(){
        $(alert_id).css("display", "none");
    }, 2000);
}
/*----------------------------------------------------------------------------------------*/
/************************************************
 *    Regisztrációs űrlap profilkép kezelése    *
 ************************************************/

// előnézeti kép megjelenítése
function preview_image(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('profkep_image');
        output.src = reader.result;
        setTimeout(profkep_scale, 200); 
    }
    reader.readAsDataURL(event.target.files[0]);
}
// Csúszka és pozíció alapállapotba állítása
var height_value=130;
function profkep_scale(){
    document.getElementById("img_height").style.display = "block"; //csúszka megjelenítése
    if($( "#profkep_image" ).width() < $( "#profkep_image" ).height()){
        document.getElementById("img_height").value=200;
        height_value=200;
    }else{
        document.getElementById("img_height").value=130;
        height_value=130;
    }
    document.getElementById("profkep_image").style.height=height_value + "px";
    document.getElementById("profkep_image").style.top="0px";
    document.getElementById("profkep_image").style.left="0px";
}

 /* Profilkép méretezése és igazítása */ 

//A kép méretének változtatása a csúszkával
document.getElementById("img_height").onchange= function(){
    height_value = document.getElementById("img_height").value;
    document.getElementById("profkep_image").style.height=height_value + "px";
}

// Mikor a képen lenyomva tartják az egérgombot...   
$( "#profkep" ).mousedown(function(e) {   
    // Profilkép aktuális helyzete a befoglaló kerethez képest 
    getPicturePosition();
    // Egérmutató aktuális pozíciója a gomb lenyomásakor 
    start_cursor_x = e.pageX;
    start_cursor_y = e.pageY;
    // Kép méretének lekérdezése
    p_width = $( "#profkep_image" ).width();
    p_height = $( "#profkep_image" ).height();
    e.preventDefault();
    // Az egérmutató mozgatásakor...
    $( "#profkep" ).on( "mousemove", function(event) {
        document.getElementById("profkep_image").style.cursor="all-scroll";
        cursor_x = event.pageX;
        cursor_y = event.pageY;
        // A kép helyzetének meghatározása az elmozdulás mértékében
        left_pos += cursor_x - start_cursor_x; 
        top_pos += cursor_y - start_cursor_y;   
        //A kép pozícionálása, úgy hogy a keretben maradjon
        if(top_pos < 0 && top_pos > 130-p_height)    
            document.getElementById("profkep_image").style.top=top_pos +"px";      
        if(left_pos < 0 && left_pos > 130-p_width )   
            document.getElementById("profkep_image").style.left=left_pos +"px";  
        // Ha felengedjük az egérgombot, akkor megszűnik a pozícionálás    
        $(document).mouseup(function() {
            $("#profkep").off("mousemove");
            cursor_x =0;    
            cursor_y =0;    
        // kép pozíciójának lekérdezése, és a rejtett input mezőbe írása    
           getPicturePosition();
           document.getElementById("top_pos").value = Math.abs(top_pos);
           document.getElementById("left_pos").value = Math.abs(left_pos);
        });
     });
});

// Profilkép pozíció lekérdezése
function getPicturePosition(){
    p = $("#profkep_image");
    position = p.position();
    left_pos = position.left;  
    top_pos = position.top;
}
