{**
 * Блок настройки ленты активности
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.stream_block_config_title}{/block}
{block name='block_type'}activity{/block}

{block name='block_content'}
	{if $oUserCurrent}
		<small class="note">{$aLang.stream_settings_note_filter}</small>
		
		<ul class="activity-settings-filter">
			{foreach $aStreamEventTypes as $sType => $aEventType}
				{if ! ($oConfig->get('module.stream.disable_vote_events') && substr($sType, 0, 4) == 'vote')}
					<li>
						<label>
							<input type="checkbox"
								   id="strn_et_{$sType}"
								   {if in_array($sType, $aStreamTypesList)}checked{/if}
								   onclick="ls.stream.switchEventType('{$sType}')" />
							{$langKey = "stream_event_type_`$sType`"}
							{$aLang.$langKey}
						</label>
					</li>
				{/if}
			{/foreach}
		</ul>
	{/if}
{/block}