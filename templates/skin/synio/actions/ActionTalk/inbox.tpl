{**
 * Список сообщений
 *}

{extends file='layouts/layout.user.messages.tpl'}

{block name='layout_options' append}
	{$bNoSystemMessages = false}
{/block}

{block name='layout_content'}
	{if $aTalks}
		{**
		 * Поиск по личным сообщениям
		 *}
		<div class="talk-search {if $_aRequest.submit_talk_filter}opened{/if}" id="block_talk_search">
			<header>
				<button type="submit"  onclick="ls.talk.makeReadTalks()" class="button">{$aLang.talk_inbox_make_read}</button>
				<button type="submit"  onclick="if (confirm('{$aLang.talk_inbox_delete_confirm}')){ ls.talk.removeTalks() };" class="button">{$aLang.talk_inbox_delete}</button>

				<a href="#" class="link-dotted close" onclick="ls.talk.toggleSearchForm(); return false;">{$aLang.talk_filter_title}</a>
			</header>
			
			<div class="talk-search-content" id="block_talk_search_content" >
				<form action="{router page='talk'}" method="GET" name="talk_filter_form">
					<p><input type="text" id="talk_filter_sender" name="sender" value="{$_aRequest.sender}" placeholder="{$aLang.talk_filter_label_sender}" class="input-text input-width-250" /></p>

					<p><input type="text" id="talk_filter_keyword" name="keyword" value="{$_aRequest.keyword}" placeholder="{$aLang.talk_filter_label_keyword}" class="input-text input-width-250" /></p>

					<p><input type="text" id="talk_filter_keyword_text" name="keyword_text" value="{$_aRequest.keyword_text}" placeholder="{$aLang.talk_filter_label_keyword_text}" class="input-text input-width-250" /></p>

					<p><label for="talk_filter_start">{$aLang.talk_filter_label_date}:</label>
					<input type="text" id="talk_filter_start" name="start" value="{$_aRequest.start}" class="input-text input-text input-width-250 date-picker" readonly="readonly" /> &mdash;
					<input type="text" id="talk_filter_end" name="end" value="{$_aRequest.end}" class="input-text input-text input-width-250 date-picker" readonly="readonly" /></p>

					<p><label for="talk_filter_favourite"><input type="checkbox" {if $_aRequest.favourite}checked {/if} class="input-checkbox" name="favourite" value="1" id="talk_filter_favourite" />
					{$aLang.talk_filter_label_favourite}</label></p>

					<input type="submit" name="submit_talk_filter" value="{$aLang.talk_filter_submit}" class="button button-primary" />
					<input type="submit" name="" value="{$aLang.talk_filter_submit_clear}" class="button" onclick="return ls.talk.clearFilter();" />
				</form>
			</div>
		</div>


		{**
		 * Список сообщений
		 *}
		<form action="{router page='talk'}" method="post" id="form_talks_list">
			<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
			<input type="hidden" name="submit_talk_read" id="form_talks_list_submit_read" value="" />
			<input type="hidden" name="submit_talk_del" id="form_talks_list_submit_del" value="" />

			{include file='actions/ActionTalk/message_list.tpl' bMessageListCheckboxes=true}
		</form>
	{else}
		<div class="notice-empty">{$aLang.talk_inbox_empty}</div>
	{/if}

				
	{include file='pagination.tpl' aPaging=$aPaging}
{/block}