$(function(){
    
    var $continent = $('#geo_continent');
    var $pays = $('#geo_pays');

    var nb=0;

    setUpdatePays();

    $('#geo_continent').change(function(){
        console.log("changement continent");
        var $form = $(this).closest('form');
        console.log($(this).closest('form'));
        var data = {};
        data[$continent.attr('name')] = $continent.val();
        data[$pays.attr('name')] = $pays.val();
        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(html) {
                $('#geo_pays').replaceWith(
                    $(html).find('#geo_pays')
                );
                setUpdatePays();
                
            }
        });
    });

    function setUpdatePays() {

        $('#geo_pays').change(function(){
            console.log("changement pays");
            var $form = $(this).closest('form');
            console.log($(this).closest('form'));
            var data = {};
            data[$continent.attr('name')] = $continent.val();
            data[$pays.attr('name')] = $pays.val();
            $.ajax({
                url : $form.attr('action'),
                type: $form.attr('method'),
                data : data,
                success: function(html) {
                    $('#geo_pays').replaceWith(
                        $(html).find('#geo_pays')
                    );
                    $('#geo_ville').replaceWith(
                        $(html).find('#geo_ville')
                    );
                }
            });
        });

    }

    $('#ajouter').click(function(){
        ++nb;
        var tmpl = $('#geo_visites').data('prototype').replace(/__name__/g, nb);
        console.log(tmpl);
        $('#geo_visites').append(tmpl);
        $("#geo_visites_"+nb+"_pays").val($('#geo_pays>option:selected').text());
        $("#geo_visites_"+nb+"_pays").val($('#geo_pays>option:selected').val());
        $("#geo_visites_"+nb+"_remarque").hide();
        majBt()
    });



    function majBt() {
        $('button[data-action="affiche"]').click(function(){
            const target = this.dataset.target;
            ($(target).is(':visible')) ? $(target).hide() : $(target).show();
        })

        $('button[data-action="delete"]').click(function(){
            const target = this.dataset.target;
            console.log(target);
            $(target).remove();
        })
    }


    $('#ajouter').click(function(){
        ++nb;
        
        var tmpl = "<p id='interet_{id}'>";
        tmpl+= "<input type='text' id='poi_{id}' value='{***}' />";
        tmpl += "<input type='text' id='hidpoi_{id}' value='{+++}' />";
        tmpl += "</p>";

        tmpl = tmpl.replace("{id}", nb);
        tmpl = tmpl.replace("{***}",$('#geo_pays>option:selected').text());
        tmpl = tmpl.replace("{+++}", $('#geo_pays>option:selected').val());

        $('.interets').append(tmpl);
    })


});