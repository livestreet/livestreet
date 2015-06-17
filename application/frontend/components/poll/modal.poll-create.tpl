{**
 * Создание опроса
 *}

{component 'modal'
    title         = ( $poll ) ? {lang 'poll.form.title.edit'} : {lang 'poll.form.title.add'}
    content       = {component 'poll' template='form'}
    classes       = 'js-modal-default'
    mods          = 'poll-create'
    id            = 'modal-poll-create'
    primaryButton = [
        'text'    => ($poll) ? $aLang.common.save : $aLang.common.add,
        'form'    => 'js-poll-form',
        'classes' => 'js-poll-form-submit'
    ]}