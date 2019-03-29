/************************************
 * foto.php-hoz kapcsolodo scriptek *
 ************************************/

/* Kép teljes nézetre váltás *******************************************************/
document.getElementById("close_div").onmouseover =function(){
    document.getElementById("close").style.background="#8f8f8f";
}
document.getElementById("close_div").onmouseleave =function(){
    document.getElementById("close").style.background="none";
}
document.getElementById("enlarge").onclick = function(){
    nagyit_bezar("flex");
}
document.getElementById("close_div").onclick = function(){
    nagyit_bezar("none");
}
function nagyit_bezar(disp){
    document.getElementsByClassName("nagykep_div")[0].style.display=disp;
}

/* Kép léptető nyilakra hover. Mivel nem csak a nyílra kattintva működik, a léptetés, 
    hanem egy adott területen, hogy könnyebb legyen a léptetni. Ha erre a területre 
    visszük az egérmutatót akkor a nyilak háttérszíne változik. Mivel teljes képernyős 
    nézetben is kell, az azonosítás osztály alapján történik. */
document.getElementsByClassName("next")[0].onmouseover = function(){
    background("nextarrow", 0, "#8f8f8f");
}
document.getElementsByClassName("next")[0].onmouseleave = function(){
    background("nextarrow", 0, "none");
}
document.getElementsByClassName("prev")[0].onmouseover = function(){
    background("prevarrow", 0, "#8f8f8f");
}
document.getElementsByClassName("prev")[0].onmouseleave = function(){
    background("prevarrow", 0, "none");
}
document.getElementsByClassName("next")[1].onmouseover = function(){
    background("nextarrow", 1, "#8f8f8f");
}
document.getElementsByClassName("next")[1].onmouseleave = function(){
    background("nextarrow", 1, "none");
}
document.getElementsByClassName("prev")[1].onmouseover = function(){
    background("prevarrow", 1, "#8f8f8f");
}
document.getElementsByClassName("prev")[1].onmouseleave = function(){
    background("prevarrow", 1, "none");
}

function background(cls, i, bg){
    document.getElementsByClassName(cls)[i].style.background=bg;
}

/* Kép módosítás, törlés menü kapcsolása *****************************************/
function show_modmenu(){
    document.getElementById("dotmenu-sub").style.display="block";
    document.getElementById("keptorles").style.display="none";
}
function hide_modmenu(){
    document.getElementById("dotmenu-sub").style.display="none";
}
function show_delmenu(){
    document.getElementById("dotmenu-sub").style.display="none";
    document.getElementById("keptorles").style.display="flex";
}
function hide_delmenu(){
    document.getElementById("keptorles").style.display="none";
}
