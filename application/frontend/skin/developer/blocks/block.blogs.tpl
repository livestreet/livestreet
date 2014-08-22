{**
 * Блок со списоком блогов
 *
 * @styles css/blocks.css
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
	{$mods = "{$mods} blogs nopadding"}
{/block}

{block 'block_title'}
	{$aLang.block_blogs}
{/block}

{block 'block_nav'}
	{include 'components/nav/nav.tabs.tpl' sName='block_blogs' sActiveItem='top' sMods='pills' sClasses='js-block-nav' aItems=[
		[ 'name' => 'top',  'url' => "{router page='ajax'}blogs/top",  'text' => $aLang.block_blogs_top,  'pane' => 'js-tab-pane-blogs' ],
		[ 'name' => 'join', 'url' => "{router page='ajax'}blogs/join", 'text' => $aLang.block_blogs_join, 'pane' => 'js-tab-pane-blogs', 'is_enabled' => !! $oUserCurrent ],
		[ 'name' => 'self', 'url' => "{router page='ajax'}blogs/self", 'text' => $aLang.block_blogs_self, 'pane' => 'js-tab-pane-blogs', 'is_enabled' => !! $oUserCurrent ]
	]}
{/block}

{block 'block_content'}
	<div id="js-tab-pane-blogs">
		{$sBlogsTop}
	</div>
{/block}

{block 'block_footer'}
	<a href="{router page='blogs'}">{$aLang.block_blogs_all}</a>
{/block}