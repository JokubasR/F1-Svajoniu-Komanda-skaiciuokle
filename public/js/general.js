/**
 * Created by jokubas on 3/31/14.
 */

$(function(){

    $('select').select2({
        allowClear: true
    });

    $('form#form-stage-results button[type="submit"]').click(function(e){
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: $(this).parents('form').attr('action'),
            data: $(this).parents('form').serializeArray(),
            success:function(template){
                $('div#results-panel').html(template);
            }
        })
    });

    $('form#form-stage-results button[type="button"]').click(function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: $(this).parents('form').attr('data-url'),
            data: $(this).parents('form').serializeArray(),
            success: function (template) {
                $('div#best-team-panel').html(template);
            }
        })
    });
});
