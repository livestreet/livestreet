{**
 * Поиск по личным сообщениям
 *}

{capture 'talk_search_form'}
    <form action="{router page='talk'}" method="GET" name="talk_filter_form" {if $_aRequest.submit_talk_filter}style="display:block;"{/if}>
        {* Отправитель *}
        {component 'field' template='text'
                 name    = 'sender'
                 label   = $aLang.talk.search.fields.sender.label
                 note    = $aLang.talk.search.fields.sender.note
                 inputClasses = 'width-full autocomplete-users'}

        {* Получатель *}
        {component 'field' template='text'
                name    = 'receiver'
                label   = $aLang.talk.search.fields.receiver.label
                note    = $aLang.talk.search.fields.receiver.note
                inputClasses = 'width-full autocomplete-users'}

        {* Искать в заголовке *}
        {component 'field' template='text'
                 name    = 'keyword'
                 label   = $aLang.talk.search.fields.keyword.label}

        {* Искать в тексте *}
        {component 'field' template='text'
                 name    = 'keyword_text'
                 label   = $aLang.talk.search.fields.keyword_text.label}

        {* Ограничения по дате *}
        {component 'field' template='text'
                name         = 'start'
                placeholder  = $aLang.talk.search.fields.start.placeholder
                label        = $aLang.talk.search.fields.start.label
                inputClasses = 'width-200 js-date-picker'}

        {component 'field' template='text'
                name         = 'end'
                placeholder  = $aLang.talk.search.fields.end.placeholder
                inputClasses = 'width-200 js-date-picker'}

        {* Искать только в избранном *}
        {component 'field' template='checkbox' name='favourite' label=$aLang.talk.search.fields.favourite.label}

        {* Кнопки *}
        {component 'button'
                name  = 'submit_talk_filter'
                value = '1'
                mods  = 'primary'
                text  = $aLang.search.find}

        {component 'button' type='reset' text=$aLang.common.form_reset}
    </form>
{/capture}

{component 'details'
    classes = 'js-talk-search-form'
    title   = {lang 'talk.search.title'}
    content = $smarty.capture.talk_search_form}