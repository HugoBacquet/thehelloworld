$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
    $(".star-rating__stars label").attr("class", "star-rating__label").css("color", "transparent");

    $("#home_pathologies").change(function() {
        if ($("select option:selected").length > 1)
            $("#select2-home_pathologies-container li:first-child button").trigger('click');
    });
    $("#note_practitioner").change(function() {
        if ($("select option:selected").length > 1)
            $("#select2-note_practitioner-container li:first-child button").trigger('click');
    });
    $("#speciality_specialities").change(function() {
        if ($("select option:selected").length > 1)
            $("#select2-speciality_specialities-container li:first-child button").trigger('click');
    });
});