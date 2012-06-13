{assign var="oUser" value=$oTopic->getUser()}

<h3 class="profile-page-header">{$aLang.topic_preview}</h3>

<article class="topic topic-type-{$oTopic->getType()}">
	<header class="topic-header">
		<h1 class="topic-title">
			{$oTopic->getTitle()|escape:'html'}
		</h1>

		<div class="topic-info">
			<time datetime="{date_format date=$oTopic->getDateAdd() format='c'}" pubdate title="{date_format date=$oTopic->getDateAdd() format='j F Y, H:i'}">
			{date_format date=$oTopic->getDateAdd() format="j F Y, H:i"}
			</time>
		</div>
	</header>

	<div class="topic-content text">
		{hook run='topic_preview_content_begin' topic=$oTopic}

		{$oTopic->getText()}

		{hook run='topic_preview_content_end' topic=$oTopic}
	</div>

	<footer class="topic-footer">
		<ul class="topic-tags">
			<li>{$aLang.block_tags}:</li>
			{strip}
				{if $oTopic->getTagsArray()}
					{foreach from=$oTopic->getTagsArray() item=sTag name=tags_list}
						<li>{if !$smarty.foreach.tags_list.first}, {/if}<a rel="tag" href="{router page='tag'}{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a></li>
					{/foreach}
				{else}
					<li>{$aLang.topic_tags_empty}</li>
				{/if}
			{/strip}
		</ul>

		<ul class="topic-info">
			<li class="topic-info-author"><a rel="author" href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></li>
			{hook run='topic_preview_show_info' topic=$oTopic}
		</ul>

		{hook run='topic_preview_show_end' topic=$oTopic}
	</footer>
</article>


<button type="submit"  name="submit_topic_publish" class="button button-primary fl-r" onclick="jQuery('#submit_topic_publish').trigger('click');">{$aLang.topic_create_submit_publish}</button>
<button type="submit"  name="submit_preview" onclick="jQuery('#text_preview').html('').hide(); return false;" class="button">{$aLang.topic_create_submit_preview_close}</button>
<button type="submit"  name="submit_topic_save" class="button" onclick="jQuery('#submit_topic_save').trigger('click');">{$aLang.topic_create_submit_save}</button>