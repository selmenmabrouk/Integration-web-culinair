$(function(){
    
    $continent = $('#geo_continent');
    $pays = $("#geo_pays");

    setPays();
    setVille();

    $continent.change(function(){
        var $form = $(this).closest('form');
        var data = {};
        data[$continent.attr('name')] = $continent.val();
        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(html) {
                $('#geo_pays').replaceWith(
                    $(html).find('#geo_pays')
                );
                setUpChangePays();
                setPays();
            }
        });
    })
    
    setUpChangePays();

    function setUpChangePays() {
        $pays = $("#geo_pays");
        $continent = $('#geo_continent');
        $pays.change(function(){
            var $form = $(this).closest('form');
            var data = {};
            data[$pays.attr('name')] = $pays.val();
            data[$continent.attr('name')] = $continent.val();
            $.ajax({
                url : $form.attr('action'),
                type: $form.attr('method'),
                data : data,
                success: function(html) {
                    $('#geo_ville').replaceWith(
                        $(html).find('#geo_ville')
                    );
                    setVille();
                }
            });
        });
    }

    function setPays() {
        $('#geo_pays option:eq(1)').prop('selected', true);
        $('#geo_pays').trigger('change');
        setVille();
    }

    function setVille() {
        $('#geo_ville option:eq(1)').prop('selected', true);
    }

    


    

});

