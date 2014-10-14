{**
 * Прямой эфир
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_title'}
	<a href="{router page='stream'}">{lang 'activity.block_recent.title'}</a>
{/block}

{block 'block_options' append}
	{$mods = "{$mods} stream nopadding"}
	{$classes = "{$classes} js-block-default"}
{/block}

{* Кнопка обновления *}
{block 'block_header_end'}
	<div class="block-update js-block-update-tabs"></div>
{/block}

{* Навигация *}
{block 'block_content'}
	{**
	 * TODO: Выпадающее меню
	 * Показывается если в меню что выше пунктов больше установленного значения (по умолчанию - 2)
	 *}
	{include 'components/tabs/tabs.tpl' classes='js-tabs-block js-activity-block-recent-tabs' tabs=[
		[ 'text' => {lang 'activity.block_recent.comments'}, 'url' => "{router page='ajax'}stream/comment", 'content' => $sStreamComments ],
		[ 'text' => {lang 'activity.block_recent.topics'},   'url' => "{router page='ajax'}stream/topic" ]
	]}
{/block}

{* Подвал *}
{block 'block_footer'}
	<a href="{router page='rss'}allcomments/">{lang 'activity.block_recent.feed'}</a>
{/block}