<section class="block block-type-foldable block-type-talk-search">
	<header class="block-header">
		<h3><a href="#" class="link-dotted" onclick="jQuery('#block_talk_search_content').toggle(); return false;">{$aLang.talk_filter_title}</a></h3>
	</header>
	
	<div class="block-content" id="block_talk_search_content" {if $_aRequest.submit_talk_filter}style="display:block;" {/if}>
		<form action="{router page='talk'}" method="GET" name="talk_filter_form">
			<p><label for="talk_filter_sender">{$aLang.talk_filter_label_sender}:</label>
			<input type="text" id="talk_filter_sender" name="sender" value="{$_aRequest.sender}" class="input-text input-width-full" />
			<small class="note">{$aLang.talk_filter_notice_sender}</small></p>

			<p><label for="talk_filter_keyword">{$aLang.talk_filter_label_keyword}:</label>
			<input type="text" id="talk_filter_keyword" name="keyword" value="{$_aRequest.keyword}" class="input-text input-width-full" />
			<small class="note">{$aLang.talk_filter_notice_keyword}</small></p>

			<p><label for="talk_filter_keyword_text">{$aLang.talk_filter_label_keyword_text}:</label>
				<input type="text" id="talk_filter_keyword_text" name="keyword_text" value="{$_aRequest.keyword_text}" class="input-text input-width-full" />
				<small class="note">{$aLang.talk_filter_notice_keyword}</small></p>

			<p><label for="talk_filter_start">{$aLang.talk_filter_label_date}:</label>
			<input type="text" id="talk_filter_start" name="start" value="{$_aRequest.start}" style="width: 43%" class="input-text date-picker" readonly="readonly" /> &mdash;
			<input type="text" id="talk_filter_end" name="end" value="{$_aRequest.end}" style="width: 43%" class="input-text date-picker" readonly="readonly" /></p>

			<p><label for="talk_filter_favourite"><input type="checkbox" {if $_aRequest.favourite}checked="checked" {/if} class="input-checkbox" name="favourite" value="1" id="talk_filter_favourite" />
			{$aLang.talk_filter_label_favourite}</label></p>

			<input type="submit" name="submit_talk_filter" value="{$aLang.talk_filter_submit}" class="button button-primary" />
			<input type="submit" name="" value="{$aLang.talk_filter_submit_clear}" class="button" onclick="return ls.talk.clearFilter();" />
		</form>
	</div>
</section>