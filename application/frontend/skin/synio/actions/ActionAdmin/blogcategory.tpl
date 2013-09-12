{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}
{/block}

{block name='layout_page_title'}<a href="{router page='admin'}">{$aLang.admin_header}</a> <span>&raquo;</span> {$aLang.admin_list_blogcategory}{/block}

{block name='layout_content'}
	<button class="button button-primary" data-type="modal-toggle" data-option-url="{router page='admin'}blogcategory/modal-add/">{$aLang.admin_blogcategory_add}</button>
	<br />
	<br />

	<table cellspacing="0" class="table">
		<thead>
			<tr>
				<th width="180px">{$aLang.admin_blogcategory_items_title}</th>
				<th align="center" >{$aLang.admin_blogcategory_items_url}</th>
				<th align="center" width="80px">{$aLang.admin_blogcategory_items_action}</th>
			</tr>
		</thead>

		<tbody>
			{foreach $aCategories as $oCategory}
				<tr>
					<td>
						<i class="icon-file" style="margin-left: {$oCategory->getLevel()*20}px;"></i>
						<a href="{$oCategory->getUrlWeb()}" border="0">{$oCategory->getTitle()|escape:'html'}</a>
					</td>
					<td>
						/{$oCategory->getUrlFull()}/
					</td>
					<td align="center">
						<a href="#" data-type="modal-toggle" data-option-url="{router page='admin'}blogcategory/modal-edit/" data-param-id="{$oCategory->getId()}" class="icon-edit"></a>
						<a href="{router page='admin'}blogcategory/delete/{$oCategory->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" onclick="return confirm('«{$oCategory->getTitle()|escape:'html'}»: {$aLang.admin_blogcategory_items_delete_confirm}');" class="icon-remove"></a>

						<a href="{router page='admin'}blogcategory/sort/{$oCategory->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" class="icon-arrow-up"></a>
						<a href="{router page='admin'}blogcategory/sort/{$oCategory->getId()}/down/?security_ls_key={$LIVESTREET_SECURITY_KEY}" class="icon-arrow-down"></a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
{/block}