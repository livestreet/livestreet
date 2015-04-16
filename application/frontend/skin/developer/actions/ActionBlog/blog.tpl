{**
 * Блог
 *
 * @param object  $blog                    Блог
 * @param boolean $isPrivateBlog           Закрытый блог или нет
 * @param array   $topics                  Список топиков
 * @param array   $paging                  Пагинация
 * @param string  $periodSelectCurrent
 * @param string  $periodSelectRoot
 * @param array   $blogUsers               Читатели блога
 * @param array   $blogModerators          Модераторы блога
 * @param array   $blogAdministrators      Администраторы блога
 * @param integer $countBlogUsers          Кол-во читателей
 * @param integer $countBlogModerators     Кол-во модераторов
 * @param integer $countBlogAdministrators Кол-во администраторов
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {$sNav = 'topics.sub'}
{/block}

{block 'layout_content_header'}
    {component 'blog' blog=$blog blogs=$blogs}

    {$smarty.block.parent}

    {* Сообщение для забаненного пользователя *}
    {if $blogUserCurrent and $blogUserCurrent->getIsBanned()}
        {component 'alert' text=$aLang.blog.alerts.banned mods='error'}
    {/if}

    {* Список топиков *}
    {if $isPrivateBlog}
        {component 'alert' text=$aLang.blog.alerts.private mods='error'}
    {else}
        {component 'topic' template='list' topics=$topics paging=$paging}
    {/if}
{/block}