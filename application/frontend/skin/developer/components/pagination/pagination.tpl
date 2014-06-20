{**
 * Пагинация
 *
 * @param string $aPaging     Массив с параметрами пагинации
 * @param string $sClasses    Дополнительные классы
 * @param string $sMods       Список классов-модификаторов
 * @param string $sAttributes Атрибуты
 *
 * @styles assets/css/components/pagination.css
 *
 * TODO: Сделать универсальные ссылки
 *}

{* Название компонента *}
{$_sComponentName = 'pagination'}

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
	<li class="{$_sComponentName}-item {if $bIsActive}active{/if}">
		{if $sUrl}
			<a class="{$_sComponentName}-item-inner {$_sComponentName}-item-link {$sLinkClasses}" href="{$sUrl}">{$sText}</a>
		{else}
			<span class="{$_sComponentName}-item-inner">{$sText}</span>
		{/if}
	</li>
{/function}

{**
 * Страницы
 *}
{if $_aPaging and $_aPaging.iCountPage > 1}
	<nav class="{$_sComponentName} {mod name=$_sComponentName mods=$smarty.local.sMods} {$smarty.local.sClasses} js-{$_sComponentName}" role="navigation" {$smarty.local.sAttributes}>
		{* Следущая / предыдущая страница *}
		<ul class="{$_sComponentName}-list">
			{* Следущая страница *}
			{if $_aPaging.iPrevPage}
				{item sUrl="{$_aPaging.sBaseUrl}{if $_aPaging.iPrevPage > 1}/page{$_aPaging.iPrevPage}{/if}/{$_aPaging.sGetParams}" sText="&larr; {$aLang.pagination.previous}" sLinkClasses="js-{$_sComponentName}-prev"}
			{else}
				{item sText="&larr; {$aLang.pagination.previous}"}
			{/if}

			{* Предыдущая страница *}
			{if $_aPaging.iNextPage}
				{item sUrl="{$_aPaging.sBaseUrl}/page{$_aPaging.iNextPage}/{$_aPaging.sGetParams}" sText="{$aLang.pagination.next} &rarr;" sLinkClasses="js-{$_sComponentName}-next"}
			{else}
				{item sText="{$aLang.pagination.next} &rarr;"}
			{/if}
		</ul>

		{* Список страниц *}
		<ul class="{$_sComponentName}-list">
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