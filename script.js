
function esemeny(){
    alert("Működik!!!");
    
}


/*Űrlap megjelenítés*/

function openreg(clicked){
    var d="";
    if (clicked.id=="reglink") { /*reglink*/
        d="block";
        /*swidth=screen.width;
        if(swidth<900){
        var link="regisztracio.html"
        window.open(link,"_self");
        }*/
    }
    
     if (clicked.id=="elsotetit")
        d="none";

     document.getElementById("reg").style.display=d;
     document.getElementById("elsotetit").style.display=d;
 }


 /* Legördülő menü *****************************************/
 var cid = "";
 var over = false;
 var ismet=true; 
 function menuover(clicked) {
     over = true;
     cid = clicked.id;
     if(ismet){
        ismet=false; 
        disp_mod("block");
        anim("subcat ease-in 0.5s 0s 1"); 
    }
 }
 function menuout() {
     over = false;
     setTimeout(hide, 5);  
 }
 function hide() {
    if (!over) {
        anim("close ease-in 0.5s 0s 1");
        setTimeout(disp_none, 480);    
    }
 }
 function disp_none() {
    ismet=true;
    disp_mod("none")
}
 function disp_mod(disp) {
     document.getElementById(cid + "-sub").style.display = disp;
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