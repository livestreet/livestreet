{**
 * Пагинация
 *
 * @param string $aPaging     Массив с параметрами пагинации
 * @param string $classes    Дополнительные классы
 * @param string $mods       Список классов-модификаторов
 * @param string $attributes Атрибуты
 *
 * @styles assets/css/components/pagination.css
 *
 * TODO: Сделать универсальные ссылки
 *}

{* Название компонента *}
{$component = 'pagination'}

{* Переменные *}
{$_aPaging = $smarty.local.aPaging}

{**
 * Элемент пагинации
 *
 * @param bool   $bIsActive (false) Если true, то элемент помечается как активный (текущая страница)
 * @param string $sUrl              Ссылка
 * @param string $sText             Текст
 * @param string $sLinkClasses      Дополнительные классы для ссылки
 *}
{function item}
	<li class="{$component}-item {if $bIsActive}active{/if}">
		{if $sUrl}
			<a class="{$component}-item-inner {$component}-item-link {$sLinkClasses}" href="{$sUrl}">{$sText}</a>
		{else}
			<span class="{$component}-item-inner">{$sText}</span>
		{/if}
	</li>
{/function}

{**
 * Страницы
 *}
{if $_aPaging and $_aPaging.iCountPage > 1}
	<nav class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes} js-{$component}" role="navigation" {$smarty.local.attributes}>
		{* Следущая / предыдущая страница *}
		<ul class="{$component}-list">
			{* Следущая страница *}
			{if $_aPaging.iPrevPage}
				{item sUrl="{$_aPaging.sBaseUrl}{if $_aPaging.iPrevPage > 1}/page{$_aPaging.iPrevPage}{/if}/{$_aPaging.sGetParams}" sText="&larr; {$aLang.pagination.previous}" sLinkClasses="js-{$component}-prev"}
			{else}
				{item sText="&larr; {$aLang.pagination.previous}"}
			{/if}

			{* Предыдущая страница *}
			{if $_aPaging.iNextPage}
				{item sUrl="{$_aPaging.sBaseUrl}/page{$_aPaging.iNextPage}/{$_aPaging.sGetParams}" sText="{$aLang.pagination.next} &rarr;" sLinkClasses="js-{$component}-next"}
			{else}
				{item sText="{$aLang.pagination.next} &rarr;"}
			{/if}
		</ul>

		{* Список страниц *}
		<ul class="{$component}-list">
			{* Первая страница *}
			{if $_aPaging.iCurrentPage > 1}
				{item sUrl="{$_aPaging.sBaseUrl}/{$_aPaging.sGetParams}" sText=$aLang.pagination.first}
			{/if}

			{* Страницы слева от текущей *}
			{foreach $_aPaging.aPagesLeft as $iPage}
				{item sUrl="{$_aPaging.sBaseUrl}{if $iPage > 1}/page{$iPage}{/if}/{$_aPaging.sGetParams}" sText=$iPage}
			{/foreach}

			{* Текущая активная страница *}
			{item bIsActive=true sText=$_aPaging.iCurrentPage}

			{* Страницы справа от текущей *}
			{foreach $_aPaging.aPagesRight as $iPage}
				{item sUrl="{$_aPaging.sBaseUrl}{if $iPage > 1}/page{$iPage}{/if}/{$_aPaging.sGetParams}" sText=$iPage}
			{/foreach}

			{* Последняя страница *}
			{if $_aPaging.iCurrentPage < $_aPaging.iCountPage}
				{item sUrl="{$_aPaging.sBaseUrl}/page{$_aPaging.iCountPage}/{$_aPaging.sGetParams}" sText=$aLang.pagination.last}
			{/if}
		</ul>
	</nav>
{/if}