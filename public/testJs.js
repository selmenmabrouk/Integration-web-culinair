$(function(){
    
    var str_pays = $('#lesDatas')[0].dataset.pays;
    var str_ville = $('#lesDatas')[0].dataset.ville;
    
    pays = [];
    ville = [];

    var t_pays = str_pays.split("+++");
    t_pays.forEach(ligne => {
        t_ligne = ligne.split("***");
        var pays = [];
        pays[0] = t_ligne[0];
        pays[1] = t_ligne[1];
        pays[2] = t_ligne[2];
        pays.push(pays);
    });
    pays.pop();

    var t_ville = str_ville.split("+++");
    t_ville.forEach(ligne=> {
        t_ligne = ligne.split("***");
        var ville = [];
        ville[0] = t_ligne[0];
        ville[1] = t_ligne[1];
        ville[2] = t_ligne[2];
        ville.push(ville);
    });
    ville.pop();

    updatePays();
    updateVille();

    $('#continent_nom').change(function(){
        $('#pays').empty();
        $('#ville').empty();
        updatePays();
        updateVille();
    });

    $('#pays').change(function(){
        $('#ville').empty();
        updateVille();
    });
});

function updatePays() {
    const continent = $('#continent_nom').val();
    pays.forEach(pays => {
        if (pays[2] == continent) {
            optText = pays[1];
            optValue = pays[0];
            $('#pays').append(new Option(optText, optValue));
        }
    })
    var elt = $('#pays');
}

function updateVille() {
    const pays = $('#pays').val();
    ville.forEach(ville => {
        if(ville[2] == pays) {
            optText = ville[1];
            optValue =ville[0];
            $('#ville').append(new Option(optText, optValue));
        }
    })
}