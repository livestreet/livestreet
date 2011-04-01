<ul class="menu">
	<li {if $sMenuItemSelect=='settings'}class="active"{/if}>
		<a href="{router page='settings'}">{$aLang.settings_menu}</a>
		{if $sMenuItemSelect=='settings'}
			<ul class="sub-menu" >
				<li {if $sMenuSubItemSelect=='profile'}class="active"{/if}><a href="{router page='settings'}profile/">{$aLang.settings_menu_profile}</a></li>
				<li {if $sMenuSubItemSelect=='tuning'}class="active"{/if}><a href="{router page='settings'}tuning/">{$aLang.settings_menu_tuning}</a></li>
				{hook run='menu_settings_settings_item'}
			</ul>
		{/if}
	</li>

	{if $oConfig->GetValue('general.reg.invite')}
		<li {if $sMenuItemSelect=='invite'}class="active"{/if}>
			<a href="{router page='settings'}invite/">{$aLang.settings_menu_invite}</a>
			{if $sMenuItemSelect=='invite'}
				<ul class="sub-menu" >
					{hook run='menu_settings_invite_item'}
				</ul>
			{/if}
		</li>
	{/if}

	{hook run='menu_settings'}
</ul>
		
		
		

