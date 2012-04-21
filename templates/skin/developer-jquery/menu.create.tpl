<script type="text/javascript">
	jQuery(function($){
		var trigger = $('#dropdown-create-trigger');
		var menu 	= $('#dropdown-create-menu');
		var pos 	= trigger.position();
	
	
		// Dropdown
		menu.css({ 'left': pos.left - 5 });
	
		trigger.click(function(){
			menu.slideToggle(); 
			return false;
		});
		
		
		// Hide menu
		$(document).click(function(){
			menu.slideUp();
		});
	
		$('body').on("click", "#dropdown-create-trigger, #dropdown-create-menu", function(e) {
			e.stopPropagation();
		});
	});
</script>


<div class="dropdown-create">
	{strip}
		<h2 class="page-header">{$aLang.block_create} <a href="#" class="dropdown-create-trigger link-dashed" id="dropdown-create-trigger">
			{if $sMenuItemSelect=='topic'}
				{$aLang.topic_menu_add}
			{elseif $sMenuItemSelect=='blog'}
				{$aLang.blog_menu_create}
			{else}
				{hook run='menu_create_item_select' sMenuItemSelect=$sMenuItemSelect}
			{/if}
		</a></h2>
	{/strip}
	
	<ul class="dropdown-menu" id="dropdown-create-menu" style="display: none">
		<li {if $sMenuItemSelect=='topic'}class="active"{/if}><a href="{router page='topic'}add/">{$aLang.topic_menu_add}</a></li>
		<li {if $sMenuItemSelect=='blog'}class="active"{/if}><a href="{router page='blog'}add/">{$aLang.blog_menu_create}</a></li>
		{hook run='menu_create_item' sMenuItemSelect=$sMenuItemSelect}
	</ul>
</div>


{if $sMenuItemSelect=='topic'}
	{if $iUserCurrentCountTopicDraft}
		<a href="{router page='topic'}saved/" class="drafts">{$aLang.topic_menu_saved} ({$iUserCurrentCountTopicDraft})</a>
	{/if}
	<ul class="nav nav-pills mb-30">
		<li {if $sMenuSubItemSelect=='topic'}class="active"{/if}><a href="{router page='topic'}add/">{$aLang.topic_menu_add_topic}</a></li>
		<li {if $sMenuSubItemSelect=='question'}class="active"{/if}><a href="{router page='question'}add/">{$aLang.topic_menu_add_question}</a></li>
		<li {if $sMenuSubItemSelect=='link'}class="active"{/if}><a href="{router page='link'}add/">{$aLang.topic_menu_add_link}</a></li>
		<li {if $sMenuSubItemSelect=='photoset'}class="active"{/if}><a href="{router page='photoset'}add/">{$aLang.topic_menu_add_photoset}</a></li>
		{hook run='menu_create_topic_item'}
	</ul>
{/if}


{hook run='menu_create' sMenuItemSelect=$sMenuItemSelect sMenuSubItemSelect=$sMenuSubItemSelect}