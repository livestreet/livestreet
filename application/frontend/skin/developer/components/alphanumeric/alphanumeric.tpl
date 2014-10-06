{**
 * Алфавитный указатель
 *
 * @param array $aAlphaLetters
 *
 * @styles assets/css/common.css
 *}

{* Название компонента *}
{$_sComponentName = 'alphanumeric'}

<ul class="{$_sComponentName} {mod name=$_sComponentName mods=$sMods} js-search-alphabet" {if $smarty.local.sType}data-type="{$smarty.local.sType}"{/if}>
	<li class="{$_sComponentName}-item active js-search-alphabet-item" data-letter=""><a href="#">{lang 'alphanumeric.all'}</a></li>

	{foreach $aAlphaLetters as $sLetter}
		<li class="{$_sComponentName}-item js-search-alphabet-item" data-letter="{$sLetter}"><a href="#">{$sLetter}</a></li>
	{/foreach}
</ul>