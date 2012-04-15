<div class="modal modal-write" id="modal_write">
	<header class="modal-header">
		<h3>Написать</h3>
		<a href="#" class="close jqmClose"></a>
	</header>
	
	{strip}
	<div class="modal-content">
		<ul class="write-list">
			<li class="write-item-type-topic">
				<a href="{router page='topic'}add" class="write-item-image"></a>
				<a href="{router page='topic'}add" class="write-item-link">Топик</a>
			</li>
			<li class="write-item-type-poll">
				<a href="{router page='question'}add" class="write-item-image"></a>
				<a href="{router page='question'}add" class="write-item-link">Опрос</a>
			</li>
			<li class="write-item-type-link">
				<a href="{router page='link'}add" class="write-item-image"></a>
				<a href="{router page='link'}add" class="write-item-link">Ссылка</a>
			</li>
			<li class="write-item-type-photoset">
				<a href="{router page='photoset'}add" class="write-item-image"></a>
				<a href="{router page='photoset'}add" class="write-item-link">Фототопик</a>
			</li>
			<li class="write-item-type-blog">
				<a href="{router page='blog'}add" class="write-item-image"></a>
				<a href="{router page='blog'}add" class="write-item-link">Блог</a>
			</li>
			{if $iUserCurrentCountTopicDraft}
				<li class="write-item-type-draft">
					<a href="{router page='topic'}saved/" class="write-item-image"></a>
					<a href="{router page='topic'}saved/" class="write-item-link">{$aLang.topic_menu_saved} ({$iUserCurrentCountTopicDraft})</a>
				</li>
			{/if}
		</ul>
	</div>
	{/strip}
</div>
	