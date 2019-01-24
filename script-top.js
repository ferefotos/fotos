// Regisztrációs form profilkép előnézeti kép megjelenítése
function preview_image(event) {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById('profkep_image');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
// fénykép feltöltés, automatikus submit a kép választásra 
$("#foto").change(function () {
    $("#uploadform").submit();
    //document.getElementById("elsotetit").style.display = "block";
    location.reload();
});