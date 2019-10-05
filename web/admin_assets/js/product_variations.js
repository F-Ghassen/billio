var $collectionHolder;

var $addTagLink = $('<a href="#" class="btn btn-default add_tag_link" style="margin: 10px;">Add Element</a>');
var $newLinkLi = $('<li></li>').append($addTagLink);

jQuery(document).ready(function() {
    $collectionHolder = $('ul.tags');
    $collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function(e) {
        e.preventDefault();
        addTagForm($collectionHolder, $newLinkLi);

        //---------------------------------------------------------------------------
        /*var $addTagLinkImage = $('<a href="#" class="btn btn-default add_tag_link" style="margin: 10px;">Add Image</a>');
        var $newLinkLiImage = $('<li></li>').append($addTagLinkImage);
        $('#product_variations_0_images').append('<ul class="tags_images list-group" style="padding: 20px;margin: 20px" data-prototype="{{ form_widget(tag.images.vars.prototype)|e(\'html_attr\') }}"><li><a href="#" class="btn btn-default add_tag_link" style="margin: 10px;">Add Image</a></li></ul>');
        $collectionHolderImages = $('ul.tags_images');
        $collectionHolderImages.find('li').each(function() {
            addTagFormDeleteLink($(this));
        });
        $collectionHolderImages.append($newLinkLiImage);
        $collectionHolderImages.data('index', $collectionHolderImages.find(':input').length);

        $addTagLinkImage.on('click', function(e) {
            e.preventDefault();
            addTagForm($collectionHolderImages, $newLinkLiImage);
        });*/
        //---------------------------------------------------------------------------
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<li class="list-group-item row"></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#" class="badge badge-danger">&times;</a>');
    $tagFormLi.append($removeFormA);
    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $tagFormLi.remove();
    });
}