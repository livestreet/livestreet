{**
 * Первое сообщение в диалоге
 *}

{extends 'Component@article.article'}

{block 'article_options'}
	{$article = $smarty.local.talk}

	{$smarty.block.parent}

	{$type = 'talk'}
	{$classes = "{$classes} talk"}
{/block}

{* Информация *}
{block 'article_footer_info_items' append}
	{* Избранное *}
	<li class="{$component}-info-item {$component}-info-item--favourite">
		{component 'favourite' classes="js-favourite-{$type}" target=$article}
	</li>
{/block}