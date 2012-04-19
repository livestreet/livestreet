<script>
	jQuery(function($){
		var menu = $('#create-dropdown-menu');
	
		var pos = $('#create-dropdown-link').position();
		menu.css({ 'left': pos.left - 5 });
	
		$('.create-dropdown-link').click(function(){
			menu.slideToggle(); 
			return false;
		});
		
		$(document).click(function(){
			menu.slideUp();
		});
	
		$('body').on("click", "#create-dropdown, #create-dropdown-link", function(e) {
			e.stopPropagation();
		});
	});
</script>

<div class="create-dropdown" id="create-dropdown">
	{strip}
		<h2 class="page-header">Создать <a href="#" class="create-dropdown-link link-dashed" id="create-dropdown-link">
			{if $sMenuItemSelect=='topic'}
				{$aLang.topic_menu_add}
			{elseif $sMenuItemSelect=='blog'}
				{$aLang.blog_menu_create}
			{else}
				{hook run='menu_create_item_select' sMenuItemSelect=$sMenuItemSelect}
			{/if}
		</a></h2>
	{/strip}
	<ul class="create-dropdown-menu" id="create-dropdown-menu" style="display: none">
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
