<div class="block">
	<h2>{$aLang.talk_filter_title}</h2>

	{literal}
	<script language="JavaScript" type="text/javascript">
		document.addEvent('domready', function() {
			new Autocompleter.Request.HTML(
				$('talk_filter_sender'),
				 DIR_WEB_ROOT+'/include/ajax/userAutocompleter.php?security_ls_key='+LIVESTREET_SECURITY_KEY,
				 {
					'indicatorClass': 'autocompleter-loading',
					'minLength': 1,
					'selectMode': 'pick',
					'multiple': false
				}
			);
			new vlaDatePicker(
				$('talk_filter_start'),
				{
					separator: '.',
					leadingZero: true,
					twoDigitYear: false,
					alignX: 'center',
					alignY: 'top',
					offset: { y: 3 },
					filePath: DIR_WEB_ROOT+'/engine/lib/external/MooTools_1.2/plugs/vlaCal-v2.1/inc/',
					prefillDate: false,
					startMonday: true
				}
			);
			new vlaDatePicker(
				$('talk_filter_end'),
				{
					separator: '.',
					leadingZero: true,
					twoDigitYear: false,
					alignX: 'center',
					alignY: 'top',
					offset: { y: 3 },
					filePath: DIR_WEB_ROOT+'/engine/lib/external/MooTools_1.2/plugs/vlaCal-v2.1/inc/',
					prefillDate: false,
					startMonday: true
				}
			);
		});

		function eraseFilterForm() {
			$$("#talk_filter_sender, #talk_filter_keyword, #talk_filter_start, #talk_filter_end").each(
				function(item,index){
					return item.set('value','');
				}
			);
			return false;
		}
	</script>
	{/literal}

	<div class="block-content">
		<form action="{router page='talk'}" method="GET" name="talk_filter_form">
			<p><label for="talk_filter_sender">{$aLang.talk_filter_label_sender}:</label><br />
			<input type="text" id="talk_filter_sender" name="sender" value="{$_aRequest.sender}" class="input-wide" /><br />
			<span class="note">{$aLang.talk_filter_notice_sender}</span></p>

			<p><label for="talk_filter_keyword">{$aLang.talk_filter_label_keyword}:</label><br />
			<input type="text" id="talk_filter_keyword" name="keyword" value="{$_aRequest.keyword}" class="input-wide" /><br />
			<span class="note">{$aLang.talk_filter_notice_keyword}</span></p>

			<p><label for="talk_filter_start">{$aLang.talk_filter_label_date}:</label><br />
			<input type="text" id="talk_filter_start" name="start" value="{$_aRequest.start}" style="width: 43%" readonly="readonly" /> &mdash;
			<input type="text" id="talk_filter_end" name="end" value="{$_aRequest.end}" style="width: 43%" readonly="readonly" /><br />
			<span class="note">{$aLang.talk_filter_notice_date}</span></p>

			<input type="submit" name="submit_talk_filter" value="{$aLang.talk_filter_submit}" />
		</form>
	</div>

	<div class="bottom"><a href="#" onclick="return eraseFilterForm();">{$aLang.talk_filter_erase_form}</a> | <a href="{router page='talk'}">{$aLang.talk_filter_erase}</a></div>
</div>