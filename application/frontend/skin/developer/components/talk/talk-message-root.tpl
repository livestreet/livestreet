{**
 * Первое сообщение в диалоге
 *}

{extends 'components/article/article.tpl'}

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
		{include 'components/favourite/favourite.tpl' sClasses="js-favourite-{$type}" oObject=$article}
	</li>
{/block}