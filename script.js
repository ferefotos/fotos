
function esemeny(){
    alert("Működik!!!");
    
}


/*Űrlap megjelenítés*/

function openreg(clicked){
    var d="";
    if (clicked.id=="reglink" || clicked.id=="uplink") { /*reglink*/
        d="block";
        /*swidth=screen.width;
        if(swidth<900){
        var link="regisztracio.html"
        window.open(link,"_self");
        }*/
    }
    
     if (clicked.id=="elsotetit"){
        d="none";
        
        document.getElementById("reg").style.display=d;
        document.getElementById("upform").style.display=d;

     }

     if(clicked.id=="reglink"){
        document.getElementById("reg").style.display=d;
     }
     
     if(clicked.id=="uplink"){
        document.getElementById("upform").style.display=d;
     }
     
     document.getElementById("elsotetit").style.display=d;
 }


 /* Drop-down menü *****************************************/
 var cid = "";
 var over = false;
 var ismet=false; 
 function menuover(clicked) {
     over = true;
     cid = clicked.id;
     if(!ismet){
        ismet=true; 
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
    ismet=false;
    disp_mod("none")
}
 function disp_mod(disp) {
     document.getElementById(cid + "-sub").style.display = disp;
 }

 function anim(a) {
     document.getElementById(cid + "-sub").style.animation = a;
 }
 /*Kiegészítés a drop-down menühöz
  *A login ablaknál figyeli hogy a felhasználónév vagy jelszó mezőbe klikkeltek -e
  *  és ha igen, akkor nem engedi visszacsukódni az ablakot, ha az egér nem lenne felette,
  *  csak akkor ha máshova klikkelnek.
  */
 var log=false;
 function inlog(){log=true;}
 function outlog(){
     log=false;
     if(!over)
       hide();
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