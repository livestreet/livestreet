{**
 * Форма добавления/редактирования
 *
 * @param object $blog
 *}

{$blog = $smarty.local.blog}

<form method="post" enctype="multipart/form-data" class="js-form-validate">
    {hook run='form_add_blog_begin'}

    {* Название блога *}
    {include 'components/field/field.text.tpl'
        name  = 'blog_title'
        rules = [ 'required' => true, 'rangelength' => "[2,200]" ]
        note  = $aLang.blog.add.fields.title.note
        label = $aLang.blog.add.fields.title.label}

    {* URL блога *}
    {include 'components/field/field.text.tpl'
        name       = 'blog_url'
        rules      = [ 'required' => true, 'type' => 'alphanum', 'rangelength' => '[2,50]' ]
        isDisabled = $_aRequest.blog_id && ! $oUserCurrent->isAdministrator()
        note       = $aLang.blog.add.fields.url.note
        label      = $aLang.blog.add.fields.url.label}

    {* Категория блога *}
    {if Config::Get('module.blog.category_allow') && ($oUserCurrent->isAdministrator() or ! Config::Get('module.blog.category_only_admin'))}
        {* Подключаем блок для управления категориями *}
        {insert name='block' block='fieldCategory' params=[ 'target' => $blog, 'entity' => 'ModuleBlog_EntityBlog' ]}
    {/if}

    {* Тип блога *}
    {include 'components/field/field.select.tpl'
        name          = 'blog_type'
        label         = $aLang.blog.add.fields.type.label
        note          = $aLang.blog.add.fields.type.note_open
        inputClasses  = 'width-200 js-blog-add-type'
        selectedValue = $_aRequest.blog_type
        items         = [
            [ 'value' => 'open', 'text' => $aLang.blog.add.fields.type.value_open ],
            [ 'value' => 'close', 'text' => $aLang.blog.add.fields.type.value_close ]
        ]}

    {* Описание блога *}
    {include 'components/editor/editor.tpl'
        set             = 'light'
        mediaTargetType = 'blog'
        name            = 'blog_description'
        rules           = [ 'required' => true, 'rangelength' => '[10,3000]' ]
        inputClasses    = 'js-editor-default'
        label           = $aLang.blog.add.fields.description.label}

    {* Ограничение по рейтингу *}
    {include 'components/field/field.text.tpl'
        name         = 'blog_limit_rating_topic'
        rules        = [ 'required' => true, 'type' => 'number' ]
        value        = '0'
        inputClasses = 'width-100'
        note         = $aLang.blog.add.fields.rating.note
        label        = $aLang.blog.add.fields.rating.label}


    {hook run='form_add_blog_end'}

    {* Скрытые поля *}
    {include 'components/field/field.hidden.security_key.tpl'}

    {* Кнопки *}
    {include 'components/button/button.tpl'
        name = 'submit_blog_add'
        text = {lang "{( $sEvent == 'add' ) ? 'common.create' : 'common.save'}"}
        mods = 'primary'}
</form>