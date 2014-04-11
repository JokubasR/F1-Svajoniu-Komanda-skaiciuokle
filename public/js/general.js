/**
 * Created by jokubas on 3/31/14.
 */

$(function(){

    $('button').button();

    $('select').select2({
        allowClear: true
    });

    $('form#form-stage-results button[type="submit"]').click(function(e){
        e.preventDefault();

        var btn = $(this);
        btn.button('loading');

        $.ajax({
            type: "POST",
            url: $(this).parents('form').attr('action'),
            data: $(this).parents('form').serializeArray(),
            success:function(template){
                $('div#results-panel').html(template);
                btn.button('reset');
            }
        })
    });

    $('form#form-stage-results button[type="button"]').click(function (e) {
        e.preventDefault();

        var btn = $(this);
        btn.button('loading');

        $.ajax({
            type: "POST",
            url: $(this).parents('form').attr('data-url'),
            data: $(this).parents('form').serializeArray(),

            success: function (template) {
                $('div#best-team-panel').html(template);
                btn.button('reset');
            }
        })
    });

    $('form#form-stage-results select.form-control.with-image')
    .each(function (key, select) {
        var image = $('option:selected', select).attr('img');
        $('img' + $(this).attr('rel')).attr('src', image);
    })
    .change(function(e){
        var image = $('option:selected', this).attr('img');
        $('img'+$(this).attr('rel')).attr('src', image);
    });
});
