{include file='header.tpl'}

<h3>{$aLang.admin_list_blogcategory}</h3>

<a href="#" data-type="modal-toggle" data-option-url="{router page='admin'}blogcategory/modal-add/">{$aLang.admin_blogcategory_add}</a>

<table cellspacing="0" class="table">
    <thead>
    <tr>
        <th width="180px">{$aLang.admin_blogcategory_items_title}</th>
        <th align="center" >{$aLang.admin_blogcategory_items_url}</th>
        <th align="center" width="80px">{$aLang.admin_blogcategory_items_action}</th>
    </tr>
    </thead>

    <tbody>
	{foreach from=$aCategories item=oCategory}
    <tr>
        <td>
            <img src="{$aTemplateWebPathPlugin.page|cat:'images/'}{if $oCategory->getLevel()==0}folder{else}document{/if}.gif" alt="" title="" style="margin-left: {$oCategory->getLevel()*20}px;" />
            <a href="{$oCategory->getUrlWeb()}" border="0">{$oCategory->getTitle()|escape:'html'}</a>
        </td>
        <td>
            /{$oCategory->getUrlFull()}/
        </td>
        <td align="center">
            <a href="#" data-type="modal-toggle" data-option-url="{router page='admin'}blogcategory/modal-edit/" data-param-id="{$oCategory->getId()}"><img src="{$aTemplateWebPathPlugin.page|cat:'images/edit.png'}" /></a>
            <a href="{router page='admin'}blogcategory/delete/{$oCategory->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" onclick="return confirm('«{$oCategory->getTitle()|escape:'html'}»: {$aLang.admin_blogcategory_items_delete_confirm}');"><img src="{$aTemplateWebPathPlugin.page|cat:'images/delete.png'}" /></a>

            <a href="{router page='admin'}blogcategory/sort/{$oCategory->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}"><img src="{$aTemplateWebPathPlugin.page|cat:'images/up.png'}"  /></a>
            <a href="{router page='admin'}blogcategory/sort/{$oCategory->getId()}/down/?security_ls_key={$LIVESTREET_SECURITY_KEY}"><img src="{$aTemplateWebPathPlugin.page|cat:'images/down.png'}" /></a>
        </td>
    </tr>
	{/foreach}
    </tbody>
</table>



{include file='footer.tpl'}