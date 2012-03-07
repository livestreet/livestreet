<section class="block">
	<h3>{$aLang.talk_filter_title}</h3>
	
	
	<form action="{router page='talk'}" method="GET" name="talk_filter_form">
		<p><label for="talk_filter_sender">{$aLang.talk_filter_label_sender}:</label>
		<input type="text" id="talk_filter_sender" name="sender" value="{$_aRequest.sender}" class="input-text input-width-full" />
		<small class="note">{$aLang.talk_filter_notice_sender}</small></p>

		<p><label for="talk_filter_keyword">{$aLang.talk_filter_label_keyword}:</label>
		<input type="text" id="talk_filter_keyword" name="keyword" value="{$_aRequest.keyword}" class="input-text input-width-full" />
		<small class="note">{$aLang.talk_filter_notice_keyword}</small></p>

		<p><label for="talk_filter_start">{$aLang.talk_filter_label_date}:</label>
		<input type="text" id="talk_filter_start" name="start" value="{$_aRequest.start}" style="width: 43%" class="input-text date-picker" readonly="readonly" /> &mdash;
		<input type="text" id="talk_filter_end" name="end" value="{$_aRequest.end}" style="width: 43%" class="input-text date-picker" readonly="readonly" /></p>

		<input type="submit" name="submit_talk_filter" value="{$aLang.talk_filter_submit}" class="button" />
	</form>
</section>