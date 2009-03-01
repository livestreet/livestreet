{include file='header.tpl'}

<div class=topic>
	

<h2>Управление статическими страницами</h2>
<br>	
{if $aParams.0=='new'}
	<h4>Создание новой страницы</h4>
	{include file='actions/ActionPage/add.tpl'}
{elseif $aParams.0=='edit'}
	<h4>Редактирование страницы «{$oPageEdit->getTitle()}»</h4>
	{include file='actions/ActionPage/add.tpl'}
{else}
	<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PAGE}/admin/new/">добавить страницу</a>
{/if}



<table width="100%" cellspacing="0" class="admin_page">
  	<tr>
    	<th align="left">Название</th>
    	<th align="center" width="250px">URL</th>    	
    	<th align="center" width="50px">Активна</th>    	   	
    	<th align="center" width="80px">Действие</th>    	   	
  	</tr>
  	
 
  {foreach from=$aPages item=oPage name=el2}    
  {if $smarty.foreach.el2.iteration % 2  == 0}
  	{assign var=className value=''}
  {else}
  	{assign var=className value='colored'}
  {/if}
  <tr class="{$className}" onmouseover="this.className='colored_sel';" onmouseout="this.className='{$className}';">  
    <td align="left" valign="middle">
    	<img src="{$DIR_STATIC_SKIN}/images/{if $oPage->getLevel()==0}folder{else}new{/if}_16x16.gif" alt="" title="" border="0" style="margin-left: {$oPage->getLevel()*20}px;"/>
    	<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PAGE}/{$oPage->getUrlFull()}/">{$oPage->getTitle()}</a>
    </td>
    <td align="left">
    	/{$oPage->getUrlFull()}/
    </td>   
    <td align="center">
    {if $oPage->getActive()}
    	да
    {else}
    	нет
    {/if}
    </td>
    <td align="center">  
    	<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PAGE}/admin/edit/{$oPage->getId()}/"><img src="{$DIR_STATIC_SKIN}/images/edit.gif" alt="Редактировать" title="Редактировать" border="0"/></a>      	
    	&nbsp;
      	<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PAGE}/admin/delete/{$oPage->getId()}/" onclick="return confirm('Вы действительно хотите удалить страницу «{$oPage->getTitle()}» со всеми вложенными страницами?');"><img src="{$DIR_STATIC_SKIN}/images/delete.gif" alt="Удалить" title="Удалить" border="0"/></a>        	    
    </td>   
  </tr>
  {/foreach}
  
</table>

	
</div>

{include file='footer.tpl'}