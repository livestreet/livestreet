{**
 * Форма добавления опроса
 *}

<form action="" method="post" id="js-poll-form" data-action="{if $poll}update{else}add{/if}">
    {* Скрытые поля *}
    {if $poll}
        {component 'field' template='hidden' name='poll_id' value=$poll->getId()}
    {else}
        {component 'field' template='hidden' name='target[type]' value=$sTargetType}
        {component 'field' template='hidden' name='target[id]' value=$sTargetId}
    {/if}

    {component 'field' template='hidden' name='target[tmp]' value=$sTargetTmp}

    {* Заголовок *}
    {component 'field' template='text'
             name  = 'poll[title]'
             value = {($poll) ? $poll->getTitle() : '' }
             label = $aLang.poll.form.fields.title
             inputAttributes= [ 'autofocus' => true ]}

    <div class="ls-field-checkbox-group">
        {component 'field' template='checkbox'
                name    = 'poll[is_guest_allow]'
                checked = {($poll && $poll->getIsGuestAllow()) ? true : false }
                label   = $aLang.poll.form.fields.is_guest_allow}

        {component 'field' template='checkbox'
                name    = 'poll[is_guest_check_ip]'
                checked = {($poll && $poll->getIsGuestCheckIp()) ? true : false }
                label   = $aLang.poll.form.fields.is_guest_check_ip}
    </div>

    {* Кол-во вариантов которые может выбрать пользователь *}
    {if $poll && $poll->getCountVote()}
        {$bDisableChangeType = true}
    {/if}

    <p class="ls-mb-10">{$aLang.poll.form.fields.type.label}:</p>

    <div class="ls-field-checkbox-group">
        {component 'field' template='radio'
                 name  = 'poll[type]'
                 value = 'one'
                 label = $aLang.poll.form.fields.type.label_one
                 checked = ! $poll or $poll->getCountAnswerMax() == 1
                 isDisabled = $bDisableChangeType}

        {component 'field' template='radio'
                 displayInline = true
                 name          = 'poll[type]'
                 value         = 'many'
                 label         = $aLang.poll.form.fields.type.label_many
                 checked       = $poll && $poll->getCountAnswerMax() > 1
                 isDisabled    = $bDisableChangeType}
    </div>

    {component 'field' template='text'
             displayInline = true
             name          = 'poll[count_answer_max]'
             value         = ($poll) ? $poll->getCountAnswerMax() : 2
             classes       = 'ls-width-50'
             isDisabled    = $bDisableChangeType}


    {* Варианты ответов *}
    <div class="fieldset">
        <header class="fieldset-header">
            <h3 class="fieldset-title">{$aLang.poll.form.answers_title}</h3>
        </header>

        <div class="fieldset-body">
            <ul class="ls-poll-form-answer-list js-poll-form-answer-list">
                {if $poll}
                    {$aAnswers = $poll->getAnswers()}

                    {foreach $aAnswers as $oAnswer}
                        {component 'poll' template='form-item'
                            item    = $oAnswer
                            index       = $oAnswer@index
                            allowUpdate = $poll->isAllowUpdate()
                            allowRemove = $poll->isAllowUpdate() && ! $oAnswer->getCountVote()}
                    {/foreach}
                {else}
                    {component 'poll' template='form-item' showRemove=false}
                    {component 'poll' template='form-item' showRemove=false}
                {/if}
            </ul>
        </div>

        {if ! $poll or $poll->isAllowUpdate()}
            <footer class="fieldset-footer">
                {component 'button'
                    type       = 'button'
                    text       = $aLang.common.add
                    attributes = [ 'title' => '[Ctrl + Enter]' ]
                    classes    = 'js-poll-form-answer-add'}
            </footer>
        {/if}
    </div>
</form>

{* Шаблон ответа для добавления с помощью js *}
{component 'poll' template='form-item' isTemplate=true}