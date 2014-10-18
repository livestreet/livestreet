{**
 * Алфавитный указатель
 *
 * @param array $letters
 *
 * @styles assets/css/common.css
 *}

{* Название компонента *}
{$component = 'alphanumeric'}

<ul class="{$component} {mod name=$component mods=$mods} js-search-alphabet {$smarty.local.classes}" {if $smarty.local.type}data-type="{$smarty.local.type}"{/if}>
	<li class="{$component}-item active js-search-alphabet-item" data-letter="">
		<a href="#">{lang 'alphanumeric.all'}</a>
	</li>

	{foreach $letters as $letter}
		<li class="{$component}-item js-search-alphabet-item" data-letter="{$letter}">
			<a href="#">{$letter}</a>
		</li>
	{/foreach}
</ul>