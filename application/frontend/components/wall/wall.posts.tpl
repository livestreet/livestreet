{**
 * Список постов на стене
 *
 * @param array $posts Список постов
 *}

{component_define_params params=[ 'posts' ]}

{foreach $posts as $post}
    {$comments = $post->getLastReplyWall()}
    {$postId = $post->getId()}

    {* Запись *}
    {component 'wall' template='entry' entry=$post showReply=!$comments classes='wall-post js-wall-post' type='post'}

    {* Комментарии *}
    <div class="wall-comments js-wall-comment-wrapper" data-id="{$postId}">
        {* Кнопка подгрузки комментариев *}
        {if count( $comments ) < $post->getCountReply()}
            {component 'more'
                classes    = 'wall-more-comments js-wall-more-comments'
                count      = $post->getCountReply() - Config::Get('module.wall.count_last_reply')
                target     = ".js-wall-entry-container[data-id={$postId}]"
                ajaxParams = [
                   'last_id'   => $comments[0]->getId(),
                   'target_id' => $postId
                ]}
        {/if}

        {* Комментарии *}
        <div class="js-wall-entry-container" data-id="{$postId}">
            {if $comments}
                {component 'wall' template='comments' comments=$comments}
            {/if}
        </div>

        {* Форма добавления комментария *}
        {if $oUserCurrent}
            {component 'wall' template='form' id=$postId display=$comments placeholder=$aLang.wall.form.fields.text.placeholder_reply}
        {/if}
    </div>
{/foreach}