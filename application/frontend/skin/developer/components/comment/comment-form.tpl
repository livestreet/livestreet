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
{$component = 'comment-form'}

{* Переменные *}
{$targetId = $smarty.local.targetId}
{$targetType = $smarty.local.targetType}


{* Форма *}
<form method           = "post"
      class            = "{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}"
      enctype          = "multipart/form-data"
      data-target-id   = "{$targetId}"
      data-target-type = "{$targetType}"
      {$smarty.local.attributes}>

    {block 'comment-form'}
        {hook run='comment-form-begin'}

        {block 'comment-form-fields'}
            {* Скрытые поля *}
            {include 'components/field/field.hidden.tpl' name='reply' value='0' inputClasses='js-comment-form-id'}
            {include 'components/field/field.hidden.tpl' name='cmt_target_id' value=$targetId}

            {* Текст комментария *}
            {include 'components/editor/editor.tpl'
                set             = $smarty.local.editorSet|default:'light'
                name            = 'comment_text'
                inputClasses    = 'js-editor-default js-comment-form-text'
                help            = false
                mediaTargetType = 'comment'}
        {/block}

        {hook run='comment-form-end'}

        {**
         * Кнопки
         *}

        {* Кнопка добавления *}
        {include 'components/button/button.tpl' name='submit_comment' text=$aLang.common.add mods='primary' classes='js-comment-form-submit'}

        {* Кнопки редактирования *}
        {include 'components/button/button.tpl' name='submit_comment' type='button' text=$aLang.common.save mods='primary' classes='js-comment-form-update-submit hide'}
        {include 'components/button/button.tpl' name='submit_comment' type='button' text=$aLang.common.cancel classes='js-comment-form-update-cancel fl-r hide'}

        {* Кнопка превью текста *}
        {include 'components/button/button.tpl' text=$aLang.common.preview_text type='button' classes='js-comment-form-preview'}
    {/block}
</form>