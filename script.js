//Ajax paraméterek megadása
/* Attól függően melyik formból történik a submit, beállítja az AJAX számára a megfelelő paramétereket*/

//Regisztráció és login:
var reset = false;
$("#reset").click(function () {
    formid = "#regform";
    phpurl = "reg.php";
    errors = "reg-errors";
    reset = true;
});
var formid = "";
$("#elkuld").click(function () {
    formid = "#regform";
    phpurl = "reg.php";
    errors = "reg-errors";
    hidden_input = $("#filename");
});
$("#login").click(function () {
    formid = "#usermenu-sub";
    phpurl = "log.php";
    errors = "error";
});
var logout = false;
$("#logout").click(function () {
    formid = "#logoutform";
    phpurl = "log.php";
    logout = true;
});

//Paraméterek megadása a kép feltöltő űrlaphoz
var upload = false;
$("#feltolt").click(function () {
    upload = true;
    formid = "#uploadform";
    phpurl = "uploadform.php";
    errors = "up-errors";
});
$("#cancel").click(function () {
    reset = true;
    formid = "#uploadform";
    phpurl = "uploadform.php";
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
    setTimeout(hide_alert, 4000);
}
function hide_alert() {
    document.getElementById('alertbox').style.display = "none";
}
// Az elsötétített háttérre klikkelve kilép a formból oldal frissítéssel
$(".form_background").click(function () {
    window.location.replace("index.php"); 
    //location.reload();
});
// Kép feltöltésnél amíg a feltöltésre vár, egy loading gif jelenik meg elsötétített háttérrel
$("#foto_up").change(function () {
    document.getElementById("loading").style.display = "flex";
});

//like és kedvencnek jelöléshez a paraméterek beállítása:
var like_id="";
var like=false;
var kedvenc=false;
$(".like").click(function () {
    formid = "#foto_like";
    phpurl = "like.php";
    kedvenc=false;
    like=true;
    alert_id="#like_alert";
});
$(".kedvenc").click(function () {
    formid = "#foto_like";
    phpurl = "like.php";
    like=false;
    kedvenc=true;
    alert_id="#kedvenc_alert";
});
/*A galériában lájkolásnál az id-ból határozzok meg a file nevet amelyikre lájkoltunk 
  * (vagy kedvencnek jelöltük) és ezt fogjuk elküldeni a feldolgozó php-nak.
  * (Azért nem rejtett input mezőből, mert akkor annak is minden képnél egyedi azonosító kellene)*/
var file="";
function liked(clicked){
    like_id=clicked.id;
    file=like_id.replace(like_id.charAt(like_id.length -1), "");
}

//Ajax---------------------------------------- 
$('form' + formid).on('submit', function (e) {
    if (formid != "") {
        e.preventDefault();
    }
    var formData = new FormData($('form' + formid)[0]);
    //gombok hozzáadása az elküldendő form adatokhoz
    if (reset) {
        formData.append('reset', 'reset');
    }

    if (logout) {
        formData.append('logout', 'logout');
    }

    if (upload) {
        formData.append('feltolt', 'feltolt');
    }
    //Like és kedvenc gombok hozzáadása
    if (like) {
        formData.append('like', 'like');
    }
    if (kedvenc) {
        formData.append('kedvenc', 'kedvenc');
    }
    //file hozzáadása 
    /* A galériában lájkolásnál külön hozzá kell adni, mert nem rejtett input mezőből küldjük */
    if(file != ""){
        formData.append('liked', file);
    }
    //teszt vizsgálat
    for (var pair of formData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }
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
                //hogy hiba esetén ne záródjon vissza a login ablak
                if (formid == "#usermenu-sub") {
                    inlog();
                }
                /*A kiválasztott képfájl nevét vissza kell írni a form rejtett input mezőjébe
                 * (regisztrációs űrlap profilképnél) */  
                if (typeof hidden_input !== "undefined") {
                    hidden_input.val(response.foto);
                }
            }
            // Ha sikeres az űrlapfeldolgozás
            if (response.status == "OK") {
                //location.reload();
                window.location.replace("index.php"); 
            }
            //Sikeres lájkolás és kedvencnek jelölés (vagy jelölés törlés) esetén:
            if(response.status == "like_be"){
                if(like_id == ""){
                    document.getElementById("like_img").src="items/heart40cb.png";
                    document.getElementById("count_like").innerHTML=response.db;
                }else{
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
                if(like_id == ""){
                    show_like_alert();
                }else{
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
    setTimeout(hide_like_alert, 2000);
}
function hide_like_alert() {
    $(alert_id).css("display", "none");
}
// Lájk és kedvencnek jelölés gombok ikonjának módosítása ha felette van az egér, 
function like_hover(clicked){
    if(document.getElementById(clicked.id).src.includes("heart24c.png")){
        document.getElementById(clicked.id).src = "items/heart24g.png";
    }
    if(document.getElementById(clicked.id).src.includes("star24c.png")){
        document.getElementById(clicked.id).src = "items/star24g.png";
    }
    if(document.getElementById(clicked.id).src.includes("heart24cb.png")){
        document.getElementById(clicked.id).src = "items/heart24cbh.png";
    }
    if(document.getElementById(clicked.id).src.includes("star24cy.png")){
        document.getElementById(clicked.id).src = "items/star24cyh.png";
    }
    if(document.getElementById(clicked.id).src.includes("heart40c.png")){
        document.getElementById(clicked.id).src = "items/heart40g.png";
    }
    if(document.getElementById(clicked.id).src.includes("star40c.png")){
        document.getElementById(clicked.id).src = "items/star40g.png";
    }
    if(document.getElementById(clicked.id).src.includes("heart40cb.png")){
        document.getElementById(clicked.id).src = "items/heart40cbh.png";
    }
    if(document.getElementById(clicked.id).src.includes("star40cy.png")){
        document.getElementById(clicked.id).src = "items/star40cyh.png";
    }

}
 //Ha elvisszük az egeret a like és a kedvencnek jelölő gombok fölül...
function like_out(clicked){
    if(document.getElementById(clicked.id).src.includes("heart24g.png")){
        document.getElementById(clicked.id).src = "items/heart24c.png";
    }
    if(document.getElementById(clicked.id).src.includes("star24g.png")){
        document.getElementById(clicked.id).src = "items/star24c.png";
    }
    if(document.getElementById(clicked.id).src.includes("heart24cbh.png")){
        document.getElementById(clicked.id).src = "items/heart24cb.png";
    }
    if(document.getElementById(clicked.id).src.includes("star24cyh.png")){
        document.getElementById(clicked.id).src = "items/star24cy.png";
    }
    if(document.getElementById(clicked.id).src.includes("heart40g.png")){
        document.getElementById(clicked.id).src = "items/heart40c.png";
    }
    if(document.getElementById(clicked.id).src.includes("star40g.png")){
        document.getElementById(clicked.id).src = "items/star40c.png";
    }
    if(document.getElementById(clicked.id).src.includes("heart40cbh.png")){
        document.getElementById(clicked.id).src = "items/heart40cb.png";
    }
    if(document.getElementById(clicked.id).src.includes("star40cyh.png")){
        document.getElementById(clicked.id).src = "items/star40cy.png";
    }
}

//***************************************************************************************/
//Regisztrációs űrlap megjelenítése
function openreg(clicked) {

    if (clicked.id == "reglink" || clicked.id == "regmod") {
        document.getElementById("reg").style.display = "block";
        document.getElementById("elsotetit").style.display = "block";
    }

    if (clicked.id == "elsotetit") {
        location.reload();
    }
}

/* Drop-down menü ***********************************************************************/
var cid = "";
var over = false;
var ismet = false;
function menuover(clicked) {
    over = true;
    cid = clicked.id;
    if (!ismet) {
        ismet = true;
        disp_mod("block");
        anim("subcat ease-in 0.5s 0s 1");
    }
}
function menuout() {
    over = false;
    setTimeout(hide, 5);
}
function hide() {
    if (!over && !log) {
        anim("close ease-in 0.5s 0s 1");
        setTimeout(disp_none, 480);
    }
}
function disp_none() {
    ismet = false;
    disp_mod("none")
}
function disp_mod(disp) {
    document.getElementById(cid + "-sub").style.display = disp;
}

function anim(a) {
    document.getElementById(cid + "-sub").style.animation = a;
}
/* A login ablaknál figyeli hogy a felhasználónév vagy jelszó mezőbe klikkeltek -e
 *  és ha igen, akkor nem engedi visszacsukódni az ablakot, ha az egér nem lenne felette,
 *  csak akkor ha máshova klikkelnek.*/
var log = false;
function inlog() { log = true; }
function outlog() {
    log = false;
    if (!over)
        hide();
}
/***************************************************************************************/
/* A galéria képek alján megjelenít egy sávot a kép szerzőjével, és a like valamint kedvencnek
 * jelölő gombokkal.*/

function showinfo(clicked) {
    $(clicked).mousemove(function () {
        $("#" + clicked.id + " div").css("display", "flex");

    });

    $(clicked).mouseout(function () {
        $("#" + clicked.id + " div").css("display", "none");

    });
}
/***************************************************************************************/


/*swidth=screen.width;
        if(swidth<900){
        var link="regisztracio.html"
        window.open(link,"_self");
        }*/