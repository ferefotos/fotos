// kép léptető nyilakra hover
document.getElementById("next").onmouseover = function(){
    document.getElementById("nextarrow").style.background="#8f8f8f";
}
document.getElementById("next").onmouseleave = function(){
    document.getElementById("nextarrow").style.background="none";
}

document.getElementById("prev").onmouseover = function(){
    document.getElementById("prevarrow").style.background="#8f8f8f";
}
document.getElementById("prev").onmouseleave = function(){
    document.getElementById("prevarrow").style.background="none";
}

// kép módosítás, törlés menü kapcsolása
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
