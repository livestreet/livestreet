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
    {include 'components/blog/blog.tpl' blog=$blog}

    {$smarty.block.parent}

    {* Сообщение для забаненного пользователя *}
    {* TODO: Вывод сообщения о бане *}
    {if false}
        {include 'components/alert/alert.tpl' text=$aLang.blog.alerts.banned mods='error'}
    {/if}

    {* Список топиков *}
    {if $isPrivateBlog}
        {include 'components/alert/alert.tpl' text=$aLang.blog.alerts.private mods='error'}
    {else}
        {include 'components/topic/topic-list.tpl' topics=$topics paging=$paging}
    {/if}
{/block}