{**
 * Тестирование компонента comment
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    Component <span>comment</span>
{/block}

{block 'layout_content'}
    {function test_heading}
        <br><h3>{$text}</h3>
    {/function}

    {* Полная версия *}
    {test_heading text='Default'}

    <div class="comments js-comments" id="comments">
        <div class="comment-wrapper js-comment-wrapper" data-id="{$comment1->getId()}">
            {component 'comment'
                oComment        = $comment1
                bShowVote       = true
                sDateLastRead   = '2014-01-01 00:00:00'}
        </div>

        <div class="comment-wrapper js-comment-wrapper" data-id="{$comment2->getId()}">
            {component 'comment'
                comment         = $comment2
                bShowVote       = true
                sDateLastRead   = '2014-01-01 00:00:00'}
        </div>

        {include 'comments/comment.form.tpl'}
    </div>
{/block}