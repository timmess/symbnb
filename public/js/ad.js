// Fonction permettant de rajouter un formulaire d'ajout d'image lors du click sur le bouton 'add-image'
$('#add-image').click(function () {
    //Récupération du numéro des futurs champs crées
    const index = +$('#widgets-counter').val();

    //Récupération du prototype des entrées
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

    //J'injecte ce code au sein de la div
    $('#ad_images').append(tmpl);

    $('#widgets-counter').val(index+1);

    //Je gère le boutton supprimer
    handleDeleteButtons();
});

// Permet de supprimer un formulaire d'ajout d'image lors du click sur le boutton
function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}

// Permet d'update le compteur d'ajout de div de la bonne manière pour éviter qu'une div se créer avec le même numéro
function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}

handleDeleteButtons();
updateCounter();