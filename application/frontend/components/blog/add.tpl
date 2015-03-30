{**
 * Форма добавления/редактирования
 *
 * @param object $blog
 *}

{$blog = $smarty.local.blog}

<form method="post" enctype="multipart/form-data" class="js-blog-add js-form-validate">
    {hook run='form_add_blog_begin'}

    {* Название блога *}
    {component 'field' template='text'
        name  = 'blog_title'
        rules = [ 'required' => true, 'length' => "[2,200]" ]
        note  = $aLang.blog.add.fields.title.note
        label = $aLang.blog.add.fields.title.label}

    {* URL блога *}
    {component 'field' template='text'
        name       = 'blog_url'
        rules      = [ 'required' => true, 'regexp' => '^[\w- ]{2,50}$' ]
        isDisabled = $_aRequest.blog_id && ! $oUserCurrent->isAdministrator()
        note       = $aLang.blog.add.fields.url.note
        label      = $aLang.blog.add.fields.url.label}

    {* Категория блога *}
    {if Config::Get('module.blog.category_allow') && ($oUserCurrent->isAdministrator() or ! Config::Get('module.blog.category_only_admin'))}
        {* Подключаем блок для управления категориями *}
        {insert name='block' block='fieldCategory' params=[ 'target' => $blog, 'entity' => 'ModuleBlog_EntityBlog' ]}
    {/if}

    {* Тип блога *}
    {component 'field' template='select'
        name          = 'blog_type'
        label         = $aLang.blog.add.fields.type.label
        note          = $aLang.blog.add.fields.type.note_open
        classes       = 'js-blog-add-field-type'
        inputClasses  = 'width-200 js-blog-add-type'
        selectedValue = $_aRequest.blog_type
        items         = [
            [ 'value' => 'open', 'text' => $aLang.blog.add.fields.type.value_open ],
            [ 'value' => 'close', 'text' => $aLang.blog.add.fields.type.value_close ]
        ]}

    {* Описание блога *}
    {component 'editor'
        set             = 'light'
        mediaTargetType = 'blog'
        name            = 'blog_description'
        rules           = [ 'required' => true, 'length' => '[10,3000]' ]
        inputClasses    = 'js-editor-default'
        label           = $aLang.blog.add.fields.description.label}

    {* Ограничение по рейтингу *}
    {component 'field' template='text'
        name         = 'blog_limit_rating_topic'
        rules        = [ 'required' => true, 'type' => 'number' ]
        value        = '0'
        inputClasses = 'width-100'
        note         = $aLang.blog.add.fields.rating.note
        label        = $aLang.blog.add.fields.rating.label}


    {hook run='form_add_blog_end'}

    {* Скрытые поля *}
    {component 'field' template='hidden.security-key'}

    {* Кнопки *}
    {component 'button'
        name = 'submit_blog_add'
        text = {lang "{( $sEvent == 'add' ) ? 'common.create' : 'common.save'}"}
        mods = 'primary'}
</form>