/************************************
 *           Drop-down menü         *  
 ************************************/

/* A cid változóban tároljuk annak a menüpontnak az azonosítóját, ami fölött az egér van
 * Fogyelni kell, hogy az egér a menü felett van, vagy elhagyta (over). Arra is figyelni kell,
 * hogy amíg nem csukódik vissza a menü, és közben újra rávisszük az egeret, csukódjon vissza,
 * ne indulhasson ellentétes folyamat, ami a menü remegését okozza. Időzítéseket is kell bele tenni, 
 * ügyelve, hogy egyszerre csak egy időzítés legyen. */
var cid = "";
var over = false;
var down = false;
function onmenu(clicked) {
    over = true;
    cid = clicked.id;
    if (!down){
        down = true;
        setTimeout(function(){
            disp_mod("block");
            anim("down ease-in 0.5s 0s 1");
        },100);
    }
}
function outmenu() {
    over = false;
    setTimeout(hide, 200);
}
function hide() {
    if (!over && !log) {
        anim("up ease-in 0.5s 0s 1");
        setTimeout(function() {
            down = false;
            disp_mod("none")
        }, 480);
    }
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
function inlog() { 
    log = true; 
}
function outlog() {
    log = false;
   if (!over)
        hide();
}
/******************************************************************************************/
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

/******************************************************************************************/
/* A galéria képek alján megjelenít egy sávot a kép szerzőjével, és a like valamint kedvencnek jelölő gombokkal.*/
function showinfo(clicked) {
    $(clicked).mousemove(function () {
        $("#" + clicked.id + " div").css("display", "flex");
    });

    $(clicked).mouseleave(function () {
        $("#" + clicked.id + " div").css("display", "none");
    });
}
/******************************************************************************************/
//User-info megjelenítése és elrejtése (Felhasználó fotóira szűréskor)
var d="flex";
function show_userinfo(){
    document.getElementById("userinfo").style.display=d;
    if(d=="flex"){
        document.getElementById("arrow").src="items/up.png";
        d="none";
    }else{
        d="flex";
        document.getElementById("arrow").src="items/down.png";
    }
}
/******************************************************************************************/
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
//Feltöltés ikon hover
function img_upload(clicked, img){
    document.getElementById(clicked.id).src = "items/" + img;
}
