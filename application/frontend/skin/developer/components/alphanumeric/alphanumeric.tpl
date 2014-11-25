{**
 * Алфавитный указатель
 *
 * @param array  $letters
 * @param array  $type
 *
 * @param string $mods
 * @param string $classes
 * @param string $attributes
 *}

{* Название компонента *}
{$component = 'alphanumeric'}

<ul class="{$component} {cmods name=$component mods=$smarty.local.mods} js-search-alphabet {$smarty.local.classes}" {if $smarty.local.type}data-type="{$smarty.local.type}"{/if}>
	<li class="{$component}-item active js-search-alphabet-item" data-letter="">
		<a href="#">{lang 'alphanumeric.all'}</a>
	</li>

	{foreach $smarty.local.letters as $letter}
		<li class="{$component}-item js-search-alphabet-item" data-letter="{$letter}">
			<a href="#">{$letter}</a>
		</li>
	{/foreach}
</ul>