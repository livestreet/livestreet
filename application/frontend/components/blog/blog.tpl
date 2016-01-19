{**
 * Блог
 *
 * @param object $blog       Блог
 * @param object $blogs      Список блогов для переноса топиков (для модальника удаления)
 * @param string $mods       Модификаторы
 * @param string $attributes Дополнительные атрибуты основного блока
 * @param string $classes    Дополнительные классы
 *}

{* Название компонента *}
{$component = 'blog'}
{component_define_params params=[ 'blog', 'blogs', 'mods', 'classes', 'attributes' ]}

{* Подключаем модальное окно удаления блога если пользователь админ *}
{if $oUserCurrent && $oUserCurrent->isAdministrator()}
    {component 'blog' template='modal.delete' blog=$blog blogs=$blogs}
{/if}

{* Является ли пользователь администратором или управляющим блога *}
{$isBlogAdmin = $oUserCurrent && ($oUserCurrent->getId() == $blog->getOwnerId() || $oUserCurrent->isAdministrator() || $blog->getUserIsAdministrator())}

{* Блог *}
<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes} data-id="{$blog->getId()}">
    <header class="{$component}-header">
        {* Заголовок *}
        <h2 class="page-header blog-title">
            {if $blog->getType() == 'close'}
                {component 'icon' icon='lock' attributes=[ title => {lang 'blog.private'} ]}
            {/if}

            {$blog->getTitle()|escape}
        </h2>
    </header>

    {* Информация о блоге *}
    <div class="{$component}-content">
        {* Описание *}
        <div class="{$component}-description ls-text">
            {$blog->getDescription()}
        </div>

        {* Информация *}
        {$info = [
            [ 'label' => $aLang.blog.date_created, 'content' => "{date_format date=$blog->getDateAdd() hours_back='12' minutes_back='60' now='60' day='day H:i' format='j F Y'}" ],
            [ 'label' => $aLang.blog.topics_total, 'content' => $blog->getCountTopic() ],
            [ 'label' => $aLang.blog.rating_limit, 'content' => $blog->getLimitRatingTopic() ]
        ]}

        {if $blog->category->getCategory()}
            {$info[] = [ 'label' => "{$aLang.blog.categories.category}:", 'content' => $blog->category->getCategory()->getTitle() ]}
        {/if}

        {component 'info-list' list=$info}
    </div>
</div>