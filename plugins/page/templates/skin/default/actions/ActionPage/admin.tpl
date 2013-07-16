{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_page_title'}
	<a href="{router page='page'}admin">{$aLang.plugin.page.admin}</a>
{/block}

{block name='layout_content'}
	{if $aParams.0=='new'}
		<h3 class="h6">{$aLang.plugin.page.create}</h3>
		{include file=$aTemplatePathPlugin.page|cat:'actions/ActionPage/add.tpl'}
	{elseif $aParams.0=='edit'}
		<h3 class="h6">{$aLang.plugin.page.edit} «{$oPageEdit->getTitle()}»</h3>
		{include file=$aTemplatePathPlugin.page|cat:'actions/ActionPage/add.tpl'}
	{else}
		<a href="{router page='page'}admin/new/" class="button button-primary">{$aLang.plugin.page.new}</a><br /><br />
	{/if}


	<table cellspacing="0" class="table">
		<thead>
			<tr>
				<th width="180px">{$aLang.plugin.page.admin_title}</th>
				<th align="center" >{$aLang.plugin.page.admin_url}</th>    	
				<th align="center" width="50px">{$aLang.plugin.page.admin_active}</th>    	   	
				<th class="ta-c" width="100px">{$aLang.plugin.page.admin_main}</th>    	   	
				<th class="ta-c" width="90px">{$aLang.plugin.page.admin_action}</th>
			</tr>
		</thead>
		
		<tbody>
			{foreach $aPages as $oPage} 	
				<tr>
					<td>
						<i class="{if $oPage->getLevel() == 0}icon-folder-close{else}icon-file{/if}" style="margin-left: {$oPage->getLevel()*20}px;"></i>
						<a href="{router page='page'}{$oPage->getUrlFull()}/">{$oPage->getTitle()}</a>
					</td>
					<td>
						/{$oPage->getUrlFull()}/
					</td>   
					<td align="center">
						{if $oPage->getActive()}
							{$aLang.plugin.page.admin_active_yes}
						{else}
							{$aLang.plugin.page.admin_active_no}
						{/if}
					</td>
					<td align="center">
						{if $oPage->getMain()}
							{$aLang.plugin.page.admin_active_yes}
						{else}
							{$aLang.plugin.page.admin_active_no}
						{/if}
					</td>
					<td align="center">  
						<a href="{router page='page'}admin/edit/{$oPage->getId()}/" class="icon-edit" title="{$aLang.plugin.page.admin_action_edit}"></a>
						<a href="{router page='page'}admin/delete/{$oPage->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" class="icon-remove" title="{$aLang.plugin.page.admin_action_delete}"></a>
						<a href="{router page='page'}admin/sort/{$oPage->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" class="icon-arrow-up" title="{$aLang.plugin.page.admin_sort_up} ({$oPage->getSort()})"></a>
						<a href="{router page='page'}admin/sort/{$oPage->getId()}/down/?security_ls_key={$LIVESTREET_SECURITY_KEY}" class="icon-arrow-down" title="{$aLang.plugin.page.admin_sort_down} ({$oPage->getSort()})"></a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
{/block}