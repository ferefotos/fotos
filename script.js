//Adatkapcsolat az űrlapok és a feldolgozó php fájlok között, JQuery Ajax
//Ajax változók
var reset = false;
$("#reset").click(function () {
    formid = "#regform";
    phpurl = "reg.php";
    errors = "reg-errors";
    reset = true;});
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



//Fénykép feltöltéshez:

//Üzenet, ha nincs bejelentkezve és képet akar feltölteni
$("#uplink-notlog").click(function () {
    document.getElementById("alert").innerHTML = "Kép feltöltéshez jelentkezz be!";
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

$(".form_background").click(function () {
    window.location.replace("indexx.php");  /**!!!!indexx>>index!!!! */
    });


var upload=false;
$("#feltolt").click(function () {
   upload=true; 
   formid = "#uploadform";
   phpurl = "uploadform.php";
   errors = "up-errors";
   
});

$("#cancel").click(function () {
    reset = true;
    formid = "#uploadform";
    phpurl = "uploadform.php";
    errors = "up-errors";
 });
 
$('form' + formid).on('submit', function (e) {
    if (formid !== "") {
        e.preventDefault();
    }
    var formData = new FormData($('form' + formid)[0]);

    if (reset) {
        formData.append('reset', 'reset');
    }

    if (logout) {
        formData.append('logout', 'logout');
    }

    if (upload) {
        formData.append('feltolt', 'feltolt');
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
            if (response.status == "ERR") {
                //Hiba van
                //alert(response.error);
                if(response.delete =="conf"){
                    window.location.replace("indexx.php");
                }
                document.getElementById(errors).innerHTML = response.error;
                if (formid == "#usermenu-sub") {
                    inlog(); //hogy hiba esetén ne záródjon vissza a login ablak
                }

                if (typeof hidden_input !== "undefined") {
                    hidden_input.val(response.foto);
                }

            }
            if (response.status == "OK") {
                //alert(response.status);
                //Nincs hiba
                //location.reload();
                window.location.replace("indexx.php"); /**!!!!indexx>>index!!!! */
            }
        }
    });
});



/*
function setUploadform(){
   // $(uploadform).replaceAll('#uploadformDiv');
    $('#uploadformDiv').append(uploadform);
    document.getElementById("upform").style.display = "block";
    document.getElementById("elsotetit").style.display = "block";
}*/





//************************************************************************************** */
//Regisztrációs űrlap megjelenítése
function openreg(clicked) {

    if (clicked.id == "reglink" || clicked.id == "regmod") { 
        document.getElementById("reg").style.display = "block";
        document.getElementById("elsotetit").style.display = "block";
    }

    if (clicked.id == "elsotetit") {
        location.reload();
        //document.getElementById("reg").style.display = "block";
        //document.getElementById("elsotetit").style.display = "block";
    }
}

/* Drop-down menü *****************************************/
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
/*Kiegészítés a drop-down menühöz
 *A login ablaknál figyeli hogy a felhasználónév vagy jelszó mezőbe klikkeltek -e
 *  és ha igen, akkor nem engedi visszacsukódni az ablakot, ha az egér nem lenne felette,
 *  csak akkor ha máshova klikkelnek.
 */
var log = false;
function inlog() { log = true; }
function outlog() {
    log = false;
    if (!over)
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


/*swidth=screen.width;
        if(swidth<900){
        var link="regisztracio.html"
        window.open(link,"_self");
        }*/