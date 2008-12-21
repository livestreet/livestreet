{include file='header.tpl'}

<h1>Результаты поиска</h1>
Вы искали <span class="searched-item">{$aReq.q|escape:'html'}</span>.<br />
<br />
{if $bIsResults}
	<!-- Меню фильтрации результатов поиска --> 
	<ul id="sub-nav"> 
	{foreach from=$aRes.aCounts item=iCount key=sType name="sTypes"}
		<li {if $aReq.sType == $sType}class="current"{/if}><a href="/search/{$sType}/?q={$aReq.q}">{$iCount}&#160;{if $sType=="topics"}топиков{elseif $sType=="comments"}комментариев{/if}</a>{if $smarty.foreach.sTypes.last}.{else},{/if}</li> 
	{/foreach}
	</ul> 
	
	{if $aReq.sType == 'topics'}
		{include file='topic_list.tpl'}
	{elseif $aReq.sType == 'comments'}
		{include file='comment_list.tpl'}
	{/if}
{else}
	<h2>Удивительно, но поиск не дал результатов</h2> 
{/if}

{include file='footer.tpl'}