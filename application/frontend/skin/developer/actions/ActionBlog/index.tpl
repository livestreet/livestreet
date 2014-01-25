{**
 * Список топиков только из коллективных блогов
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$sNav = 'topics'}
{/block}

{block 'layout_content'}
	{include 'topics/topic_list.tpl'}
{/block}