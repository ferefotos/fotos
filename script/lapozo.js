/**********************************************
 *       Végtelen lapozás a galériában        *
 **********************************************/

// Paraméterek lekérdezése az url-ből
$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
       return null;
    }
    return decodeURI(results[1]) || 0;
}
var katid = $.urlParam('katid'); 
var userid = $.urlParam('userid'); 
var search = $.urlParam('search'); 
var kedvenc = $.urlParam('kedvenc'); 
var toplist = $.urlParam('toplist'); 

//console.log($.urlParam('toplist'));  
//console.log(decodeURIComponent($.urlParam('userid'))); 

/* Az URL-ből lekérdezett GET-es paraméterek POST-tal lesznek továbbítva a feldolgozó gallery_list.php-nek. */

/* Letiltja az alapértelmezett submit eseményt a lájkolásnál, amit egyébként érzékelne a lapozó, 
   és az újratöltés miatt nem működne a lájk. */ 
$('#foto_like').on('submit', function (e) {
    e.preventDefault();
}); 
// Az AJAX lapozó
$(document).ready(function(){
    var limit = 20;
    var start = 0;
    var action = 'inactive';
    // Adatok továbbítása a feldolgozó php-nak.
    function load_gallery_data(limit, start){
        $.ajax({
            url:"gallery_list.php",
            method:"POST",
            data:{limit:limit, start:start, katid:katid, userid:userid, search:search, kedvenc:kedvenc, toplist:toplist},
            cache:false,
            // eredmény: a gallery_list.php által létrehozott galéria
            success:function(data){
                if(data!='0'){
                    $('#gallery').append(data); // Az adatok hozzáadása a galériához      
                }else if(start==0){
                    $('#gallery').html("<p>A keresésnek nincs eredménye.</p>");
                }       
                if(data == '0'){                  
                  // $('#message').html("<p></p>");
                    action = 'active';
                }else{
                  // $('#message').html("<p>sss</p>"); 
                    action = 'inactive';                          
                }
            }
        });
    }
    if(action == 'inactive'){
        action = 'active';
        load_gallery_data(limit, start);
    }
    /* Figyeli, hogy ha lentebb görgetünk, és elfogytak a már betöltött képek, 
       akkor újra hívja a load_gallery_data függvényt, amivel új képeket kér le. */
    $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() > $("#gallery").height() && action == 'inactive'){
            action = 'active';
            start = start + limit; 
            if(toplist == null || toplist !== null && start < 60){ // hogy a TOP60-nál csak max. 60 képig töltsön      
                setTimeout(function(){
                    load_gallery_data(limit, start);
                }, 200);           
            }
        }
    });
});
