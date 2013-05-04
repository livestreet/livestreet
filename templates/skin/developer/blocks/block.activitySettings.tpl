{**
 * Блок настройки ленты активности
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='options'}
	{assign var='noFooter' value=true}
	{assign var='noNav' value=true}
{/block}

{block name='title'}{$aLang.stream_block_config_title}{/block}
{block name='type'}activity{/block}

{block name='content'}
	{if $oUserCurrent}
		<small class="note">{$aLang.stream_settings_note_filter}</small>
		
		<ul class="activity-settings-filter">
			{foreach from=$aStreamEventTypes key=sType item=aEventType}
				{if !($oConfig->get('module.stream.disable_vote_events') && substr($sType, 0, 4) == 'vote')}
					<li>
						<label>
							<input class="streamEventTypeCheckbox input-checkbox"
								type="checkbox"
								id="strn_et_{$sType}"
								{if in_array($sType, $aStreamTypesList)}checked="checked"{/if}
								onClick="ls.stream.switchEventType('{$sType}')" />
							{assign var=langKey value="stream_event_type_`$sType`"}
							{$aLang.$langKey}
						</label>
					</li>
				{/if}
			{/foreach}
		</ul>
	{/if}
{/block}