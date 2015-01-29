{**
 * Создание опроса
 *}

{extends 'Component@modal.modal'}

{block 'modal_options' append}
    {$id = "modal-poll-create"}
    {$mods = "$mods poll-create"}
    {$classes = "$classes js-modal-media"}
    {$title = ( $oPoll ) ? {lang 'poll.form.title.edit'} : {lang 'poll.form.title.add'}}
{/block}

{block 'modal_content'}
    {component 'poll' template='form'}
{/block}

{block 'modal_footer_begin'}
    {component 'button'
        form    = 'js-poll-form'
        text    =  ($oPoll) ? $aLang.common.save : $aLang.common.add
        classes = 'js-poll-form-submit'
        mods    = 'primary'}
{/block}