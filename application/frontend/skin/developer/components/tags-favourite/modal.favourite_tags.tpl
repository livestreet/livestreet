{**
 * Добавление пользовательских тегов к топику
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_options' append}
    {$id = "favourite-form-tags"}
    {$mods = "$mods favourite-tags"}
    {$classes = "$classes js-modal-default"}
    {$title = {lang 'favourite_tags.title'}}
{/block}

{block 'modal_content'}
    <form id="js-favourite-form">
        {component 'field' template='text'
            name         = 'tags'
            noMargin     = true
            autofocus    = true
            inputClasses = 'width-full autocomplete-tags-sep js-tags-form-input-list'}
    </form>
{/block}

{block 'modal_footer_begin'}
    {component 'button'
        form    = 'js-favourite-form'
        text    = $aLang.common.save
        classes = 'js-tags-form-submit'
        mods    = 'primary'}
{/block}