{**
 * Форма комментирования
 *
 * @param integer $targetId
 * @param string  $targetType
 * @param string  $editorSet        (light) Стиль редактора
 *
 * @param string  $classes          Дополнительные классы
 * @param string  $attributes       Атрибуты
 * @param string  $mods             Модификаторы
 *}

{* Название компонента *}
{$component = 'ls-comment-form'}

{* Переменные *}
{$targetId = $smarty.local.targetId}
{$targetType = $smarty.local.targetType}


{* Форма *}
<form method           = "post"
      class            = "{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}"
      enctype          = "multipart/form-data"
      data-target-id   = "{$targetId}"
      data-target-type = "{$targetType}"
      {cattr list=$smarty.local.attributes}>
    {block 'comment-form'}
        {* @hook Начало формы комментирования *}
        {hook run='comment_form_begin' params=$smarty.local.params}

        {block 'comment-form-fields'}
            {* Скрытые поля *}
            {component 'field' template='hidden' name='reply' value='0' inputClasses='js-comment-form-id'}
            {component 'field' template='hidden' name='comment_target_id' value=$targetId}

            {* Текст комментария *}
            {component 'editor'
                set             = $smarty.local.editorSet|default:'light'
                name            = 'comment_text'
                inputClasses    = 'js-comment-form-text'
                help            = false
                mediaTargetType = 'comment'}
        {/block}

        {* @hook Хук расположенный после полей формы и перед кнопками управления формой *}
        {hook run='comment_form_fields_after' params=$smarty.local.params}

        {**
         * Кнопки
         *}

        {* Кнопка добавления *}
        {component 'button' name='submit_comment' text=$aLang.common.add mods='primary' classes='js-comment-form-submit'}

        {* Кнопки редактирования *}
        {component 'button' name='submit_comment' text=$aLang.common.save mods='primary' classes='js-comment-form-update-submit hide'}
        {component 'button' name='submit_comment' type='button' text=$aLang.common.cancel classes='js-comment-form-update-cancel ls-fl-r'}

        {* Кнопка превью текста *}
        {component 'button' text=$aLang.common.preview_text type='button' classes='js-comment-form-preview'}

        {* @hook Конец формы комментирования *}
        {hook run='comment_form_end' params=$smarty.local.params}
    {/block}
</form>