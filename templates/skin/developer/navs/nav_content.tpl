{if $nav or $nav_content}
	<div class="nav-group">
		{if $nav}
			{if in_array($nav,$aMenuContainers)}{$aMenuFetch.$nav}{else}{include file="navs/nav.$nav.tpl"}{/if}
		{/if}
		
		{if $nav_content}
			{include file="navs/nav.$nav_content.content.tpl"}
		{/if}
	</div>
{/if}