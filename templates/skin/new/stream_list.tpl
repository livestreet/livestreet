{if count($aStreamEvents)}
    <ul class="stream-list">
        {foreach from=$aStreamEvents item=aEvent}
            {assign var=initiatorId value=$aEvent.initiator}
            {assign var=initiator value=$aStreamUsers.$initiatorId}
            <li class="stream-item-type-{$aEvent.event_type}">
				<a href="{$initiator->getUserWebPath()}"><img src="{$initiator->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
				<span class="date">{date_format date=$aEvent.date_added}</span> 
				
				<a href="{$initiator->getUserWebPath()}"><strong>{$initiator->getLogin()}</strong></a>
                {if $aEvent.event_type == $STREAM_EVENT_TYPE.ADD_TOPIC.id}
                    {assign var=topicId value=$aEvent.target_id}
                    {assign var=topic value=$aStreamTopics.$topicId}
                    {$aLang.stream_list_event_add_topic} <a href="{$topic->getUrl()}">{$topic->getTitle()}</a>
                {elseif $aEvent.event_type == $STREAM_EVENT_TYPE.ADD_COMMENT.id}
                    {assign var=commentId value=$aEvent.target_id}
                    {assign var=topicId value=$aStreamComments.$commentId->getTargetId()}
                    {assign var=topic value=$aStreamTopics.$topicId}
                    {$aLang.stream_list_event_add_comment} <a href="{$topic->getUrl()}#comment{$aStreamComments.$commentId->getId()}">{$topic->getTitle()}</a>
                {elseif $aEvent.event_type == $STREAM_EVENT_TYPE.ADD_BLOG.id}
                    {assign var=blogId value=$aEvent.target_id}
                    {assign var=blog value=$aStreamBlogs.$blogId}
                    {$aLang.stream_list_event_add_blog} <a href="{$blog->getUrl()}">{$blog->getTitle()}</a>
                {elseif $aEvent.event_type == $STREAM_EVENT_TYPE.VOTE_BLOG.id}
                    {assign var=blogId value=$aEvent.target_id}
                    {assign var=blog value=$aStreamBlogs.$blogId}
                    {$aLang.stream_list_event_vote_blog} <a href="{$blog->getUrl()}">{$blog->getTitle()}</a>
                {elseif $aEvent.event_type == $STREAM_EVENT_TYPE.VOTE_TOPIC.id}
                    {assign var=topicId value=$aEvent.target_id}
                    {assign var=topic value=$aStreamTopics.$topicId}
                    {$aLang.stream_list_event_vote_topic} <a href="{$topic->getUrl()}">{$topic->getTitle()}</a>
                {elseif $aEvent.event_type == $STREAM_EVENT_TYPE.VOTE_COMMENT.id}
                    {assign var=commentId value=$aEvent.target_id}
                    {assign var=topicId value=$aStreamComments.$commentId->getTargetId()}
                    {assign var=topic value=$aStreamTopics.$topicId}
                    {$aLang.stream_list_event_vote_comment} <a href="{$topic->getUrl()}#comment{$aStreamComments.$commentId->getId()}">{$topic->getTitle()}</a>
                {elseif $aEvent.event_type == $STREAM_EVENT_TYPE.VOTE_USER.id}
                    {assign var=userId value=$aEvent.target_id}
                    {assign var=user value=$aStreamUsers.$userId}
                    {$user}
                    {$aLang.stream_list_event_vote_user} <a href="{$user->getUserWebPath()}">{$user->getLogin()}</a>
                {elseif $aEvent.event_type == $STREAM_EVENT_TYPE.JOIN_BLOG.id}
                    {assign var=blogId value=$aEvent.target_id}
                    {assign var=blog value=$aStreamBlogs.$blogId}
                    {$aLang.stream_list_event_join_blog} <a href="{$blog->getUrl()}">{$blog->getTitle()}</a>
                {elseif $aEvent.event_type == $STREAM_EVENT_TYPE.MAKE_FRIENDS.id}
                    {assign var=userId value=$aEvent.target_id}
                    {assign var=user value=$aStreamUsers.$userId}
                    {$aLang.stream_list_event_make_friends} <a href="{$user->getUserWebPath()}">{$user->getLogin()}</a>
                {/if}
            </li>
        {/foreach}
    </ul>
{/if}