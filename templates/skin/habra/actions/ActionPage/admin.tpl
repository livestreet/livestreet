{include file='header.tpl'}

{include file='system_message.tpl'}


<BR>
<h2>Управление статическими страницами</h2>
	
{if $aParams.0=='new'}
	<h4>Создание новой страницы</h4>
	{include file='actions/ActionPage/add.tpl'}
{elseif $aParams.0=='edit'}
	<h4>Редактирование страницы «{$oPageEdit->getTitle()}»</h4>
	{include file='actions/ActionPage/add.tpl'}
{else}
	<a href="{$DIR_WEB_ROOT}/page/admin/new/">добавить страницу</a>
{/if}



<table width="100%" cellspacing="0" class="tbl">
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
    	<img src="{$DIR_STATIC_SKIN}/img/{if $oPage->getLevel()==0}folder{else}new{/if}_16x16.gif" alt="" title="" border="0" style="margin-left: {$oPage->getLevel()*20}px;"/>
    	<a href="{$DIR_WEB_ROOT}/page/{$oPage->getUrlFull()}/">{$oPage->getTitle()}</a>
    </td>
    <td align="left">
    	/{$oPage->getUrlFull()}/
    </td>   
    <td align="center">
    {if $oPage->getActive()}
    	<img src="{$DIR_STATIC_SKIN}/img/vote_comment_up.gif" alt="Активна" title="Активна" border="0"/>
    {else}
    	<img src="{$DIR_STATIC_SKIN}/img/vote_comment_up_gray.gif" alt="Не активна" title="Не активна" border="0"/>
    {/if}
    </td>
    <td align="center">  
    	<a href="{$DIR_WEB_ROOT}/page/admin/edit/{$oPage->getId()}/"><img src="{$DIR_STATIC_SKIN}/img/blog_edit.gif" alt="Редактировать" title="Редактировать" border="0"/></a>      	
    	&nbsp;
      	<a href="{$DIR_WEB_ROOT}/page/admin/delete/{$oPage->getId()}/" onclick="return confirm('Вы действительно хотите удалить страницу «{$oPage->getTitle()}» со всеми вложенными страницами?');"><img src="{$DIR_STATIC_SKIN}/img/delete_16x16.gif" alt="Удалить" title="Удалить" border="0"/></a>        	
    
    </td>   
  </tr>
  {/foreach}
  
  
	</table>




{include file='footer.tpl'}

