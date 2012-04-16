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


<a href="{router page='topic'}saved/" class="drafts">{$aLang.topic_menu_saved} {if $iUserCurrentCountTopicDraft}({$iUserCurrentCountTopicDraft}){/if}</a>
{hook run='menu_topic_action_saved_item'}


<div class="create-dropdown" id="create-dropdown">
	<h2 class="page-header">Создать <a href="#" class="create-dropdown-link link-dashed" id="create-dropdown-link">{$aLang.topic_menu_add_topic}</a></h2>

	<ul class="create-dropdown-menu" id="create-dropdown-menu" style="display: none">
		<li {if true}class="active"{/if}><a href="{router page='topic'}add/">{$aLang.topic_menu_add_topic}</a></li>
		<li {if false}class="active"{/if}><a href="{router page='blog'}add/">{$aLang.blog_menu_create}</a></li>
		{hook run='menu_topic_action'}
	</ul>
</div>


{if $sMenuSubItemSelect=='add' && $sMenuItemSelect!='add_blog'}
	<ul class="nav nav-pills mb-30">
		<li {if $sMenuItemSelect=='topic'}class="active"{/if}><a href="{router page='topic'}{$sMenuSubItemSelect}/">{$aLang.topic_menu_add_topic}</a></li>
		<li {if $sMenuItemSelect=='question'}class="active"{/if}><a href="{router page='question'}{$sMenuSubItemSelect}/">{$aLang.topic_menu_add_question}</a></li>
		<li {if $sMenuItemSelect=='link'}class="active"{/if}><a href="{router page='link'}{$sMenuSubItemSelect}/">{$aLang.topic_menu_add_link}</a></li>
		<li {if $sMenuItemSelect=='photoset'}class="active"{/if}><a href="{router page='photoset'}{$sMenuSubItemSelect}/">{$aLang.topic_menu_add_photoset}</a></li>
		{hook run='menu_topic_action_add_item'}
	</ul>
{/if}

