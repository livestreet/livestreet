{**
 * Оповещение о новом топике
 *}

{extends 'Component@email.email'}

{block 'content'}
    {lang name='emails.topic_new.text' params=[
        'user_url'   => $oUserTopic->getUserWebPath(),
        'user_name'  => $oUserTopic->getDisplayName(),
        'blog_name'  => $oBlog->getTitle()|escape,
        'topic_url'  => $oTopic->getUrl(),
        'topic_name' => $oTopic->getTitle()|escape
    ]}
{/block}