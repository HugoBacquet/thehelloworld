$(document).ready(function(){
    $("#dontknow-postalCode").on("click", function(){
        let input = $("#practitioner_form_postalCode");
        if(input[0].hasAttribute("disabled"))
            input.removeAttr("disabled");
        else input.attr("disabled", "true");
    });

    $("#dontknow-pathology").on("click", function(){
        let input = $("#practitioner_form_unmappedPathologies");
        if(input[0].hasAttribute("disabled"))
            input.removeAttr("disabled");
        else input.attr("disabled", "true");
    });
});