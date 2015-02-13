{**
 * Добавление пользовательских тегов к топику
 *}

{capture 'modal_content'}
    <form id="js-favourite-form">
        {component 'field' template='text'
            name         = 'tags'
            noMargin     = true
            autofocus    = true
            inputClasses = 'width-full autocomplete-tags-sep js-tags-form-input-list'}
    </form>
{/capture}

{component 'modal'
    title         = {lang 'favourite_tags.title'}
    content       = $smarty.capture.modal_content
    classes       = 'js-modal-default'
    mods          = 'favourite-tags'
    id            = 'favourite-form-tags'
    primaryButton  = [
        'text'    => {lang 'common.save'},
        'classes' => 'js-tags-form-submit',
        'form'    => 'js-favourite-form'
    ]}