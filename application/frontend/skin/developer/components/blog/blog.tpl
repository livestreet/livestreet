{**
 * Блог
 *
 * @param object $blog       Блог
 * @param string $mods       Модификаторы
 * @param string $attributes Дополнительные атрибуты основного блока
 * @param string $classes    Дополнительные классы
 *
 * TODO: Сделать универсальным
 *}

{* Название компонента *}
{$component = 'blog'}

{* Переменные *}
{$blog = $smarty.local.blog}
{$blogs = $smarty.local.blogs}

{* Подключаем модальное окно удаления блога если пользователь админ *}
{if $oUserCurrent && $oUserCurrent->isAdministrator()}
    {include './modals/modal.blog-delete.tpl' blog=$blog blogs=$blogs}
{/if}

{* Является ли пользователь администратором или управляющим блога *}
{$isBlogAdmin = $oUserCurrent && ($oUserCurrent->getId() == $blog->getOwnerId() || $oUserCurrent->isAdministrator() || $blog->getUserIsAdministrator())}


{* Блог *}
<div class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}" data-id="{$blog->getId()}" {cattr list=$smarty.local.attributes}>
    <header class="{$component}-header">
        {* Заголовок *}
        <h2 class="page-header blog-title">
            {if $blog->getType() == 'close'}
                <i title="{$aLang.blog.private}" class="icon icon-lock"></i>
            {/if}

            {$blog->getTitle()|escape}
        </h2>
    </header>


    {* Информация о блоге *}
    <div class="{$component}-content">
        {* Описание *}
        <div class="{$component}-description text">
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