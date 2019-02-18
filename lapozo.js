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

//Ez szükséges, hogy a galériákban a lájkolásnál a submit-re ne töltsön újra az oldal
//letiltja az alapértelmezett submit eseményt
$('#foto_like').on('submit', function (e) {
    e.preventDefault();
}); 

$(document).ready(function(){
    var limit = 15;
    var start = 0;
    var action = 'inactive';
    function load_country_data(limit, start){
        $.ajax({
            url:"gallery_list.php",
            method:"POST",
            data:{limit:limit, start:start, katid:katid, userid:userid, search:search, kedvenc:kedvenc, toplist:toplist},
            cache:false,
            success:function(data){
                $('#gallery').append(data);       
                if(data == ''){
                   //$('#message').html("<p></p>");
                    action = 'active';
                }else{
                   // $('#message').html("<p></p>"); 
                    action = 'inactive';                          
                }
            }
        });
    }

    if(action == 'inactive'){
        action = 'active';
        load_country_data(limit, start);
    }
    $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() > $("#gallery").height() && action == 'inactive'){
            action = 'active';
            start = start + limit;
            if(toplist == null || toplist!==null && start<60){       
                setTimeout(function(){
                    load_country_data(limit, start);
                }, 200);           
            }
        }
    });
});
