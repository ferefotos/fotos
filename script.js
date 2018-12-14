/*Űrlap megjelenítés*/

function openreg(clicked){
    var d="";
    if (clicked.id=="reglink") { /*reglink*/
        d="block";
    }
    
     if (clicked.id=="elsotetit")
        d="none";

     document.getElementById("reg").style.display=d;
     document.getElementById("elsotetit").style.display=d;
 }
 

 /* Legördülő menü *****************************************/
 var cid = "";
 var main = false;
 var sub = false;
 function mainover(clicked) {
     main = true;
     cid = clicked.id;
     disp_mod("block");
     anim("subcat ease-in 0.5s 0s 1");
 }

 function subover() {
     sub = true;
 }

 function mainout() {
     main = false;
     setTimeout(hide, 5);
 }

 function subout() {
     sub = false;
     setTimeout(hide, 5);
 }

 function hide() {
     if (!sub && !main) {
         anim("close ease-in 0.5s 0s 1");
         setTimeout(disp_none, 480);
     }
 }

 function disp_mod(disp) {
     document.getElementById(cid + "-sub").style.display = disp;
 }

 function disp_none() {
     disp_mod("none")
 }


 function anim(a) {
     document.getElementById(cid + "-sub").style.animation = a;
 }



 /*Info sáv a képek alján*/
 /*Jquery*/

 function showinfo(clicked) {
    $(clicked).mousemove(function () {
        $("#" + clicked.id + " div").css("display", "flex");

    });

    $(clicked).mouseout(function () {
        /*$("#" + aid + " div").css("animation", "close ease-in 0.5s 0s 1");*/
        $("#" + clicked.id + " div").css("display", "none");

    });
}