{include file='topic_part_header.tpl'}


<div class="topic-content text">
	{hook run='topic_content_begin' topic=$oTopic bTopicList=$bTopicList}
	
	{$oTopic->getText()}
	
	{hook run='topic_content_end' topic=$oTopic bTopicList=$bTopicList}
</div> 


{include file='topic_part_footer.tpl'}