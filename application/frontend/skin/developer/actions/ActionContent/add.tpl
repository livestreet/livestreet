{**
 * Создание/редактирование топика
 *
 * @parama object  $topicEdit
 * @parama string  $topicType
 * @parama array   $blogsAllow
 * @parama integer $blogId
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
    {if $sEvent == 'add'}
        {$sNav = 'create'}
    {/if}
{/block}

{block 'layout_page_title'}
    {if $sEvent == 'add'}
        {$aLang.topic.add.title.add}
    {else}
        {$aLang.topic.add.title.edit}
    {/if}
{/block}

{block 'layout_content'}
    {component 'topic' template='add-type' topic=$topicEdit type=$topicType blogs=$blogsAllow blogId=$blogId skipBlogs=$skipBlogs}
{/block}