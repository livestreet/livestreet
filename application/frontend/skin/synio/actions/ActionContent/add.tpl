{**
 * Создание/редактирование топика
 *
 * @parama object  $topicEdit
 * @parama string  $topicType
 * @parama array   $blogsAllow
 * @parama integer $blogId
 *}

{extends 'layouts/layout.content.form.tpl'}

{block 'layout_content'}
    {component 'topic.add-type' classes='syn-form-panel' topic=$topicEdit type=$topicType blogs=$blogsAllow blogId=$blogId skipBlogs=$skipBlogs}
{/block}