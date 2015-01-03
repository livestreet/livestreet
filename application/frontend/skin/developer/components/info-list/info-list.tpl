{**
 * Список с информацией
 *
 * @styles css/common.css
 *}

{* Название компонента *}
{$component = 'info-list'}

{$list = $smarty.local.list}

{if $list}
	<div class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {cattr list=$smarty.local.attributes}>
		{* Заголовок *}
		{if $smarty.local.title}
			<h2 class="{$component}-title">{$smarty.local.title}</h2>
		{/if}

		{* Список *}
		<ul class="info-list">
			{foreach $list as $item}
				<li class="info-list-item">
					<div class="info-list-item-label">
						{$item['label']}
					</div>
					<strong class="info-list-item-content">{$item['content']}</strong>
				</li>
			{/foreach}
		</ul>
	</div>
{/if}