
//Adatkapcsolat az űrlapok és a feldolgozó php fájlok között, JQuery Ajax
var reset = false;
$("#reset").click(function () { reset = true; });

var formid="";
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

//teszt
//logout=$('#test');
//var usermenu= "<ul class= \"submenu submenu-user\" id=\"usermenu-sub\">\n<li><a href=\"#\">Profil</a></li>\n<li><a href=\"#\">Kedvencek</a></li>\n<form method=\"post\"><input type=\"submit\" class=\"gomb\" name=\"logout\" id=\"logout\" value=\"Kijelentkezés\"></form>\n</ul>";
// $('#test').append(usermenu);
//$( usermenu ).replaceAll( "#usermenu-sub" );

/***** */

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
    //teszt vizsgálat
    for(var pair of formData.entries()) {
        console.log(pair[0]+ ', '+ pair[1]); 
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
            //alert(response.status);
            if (response.status == "ERR") {
                //Hiba van
                document.getElementById(errors).innerHTML = response.error;
                if(formid=="#usermenu-sub"){
                    inlog(); //hogy hiba esetén ne záródjon vissza a login ablak
                }

                if (typeof hidden_input !== "undefined") {
                    hidden_input.val(response.foto);
                }
                // $( "#reg-gombok" ).append( response.sor );

            }
            if (response.status == "OK") {
                //Nincs hiba
                //location.reload();
                window.location.replace("indexx.php");
            }

        }

    });

});


/**************************************************************************************** */
//Űrlapok megjelenítése
var d = "";
function openreg(clicked) {

    if (clicked.id == "reglink" || clicked.id == "uplink") { /*reglink*/
        d = "block";
        /*swidth=screen.width;
        if(swidth<900){
        var link="regisztracio.html"
        window.open(link,"_self");
        }*/
    }

    if (clicked.id == "elsotetit") {
        d = "none";
        document.getElementById("reg").style.display = d;
        document.getElementById("upform").style.display = d;
    }

    if (clicked.id == "reglink") {
        document.getElementById("reg").style.display = d;
    }

    if (clicked.id == "uplink") {
        document.getElementById("upform").style.display = d;
    }

    document.getElementById("elsotetit").style.display = d;
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
function inlog(){ log = true; }
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