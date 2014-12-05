{**
 * Пагинация
 *
 * @param string $paging     Массив с параметрами пагинации
 * @param string $classes    Дополнительные классы
 * @param string $mods       Список классов-модификаторов
 * @param string $attributes Атрибуты
 *
 * TODO: Сделать универсальные ссылки
 *}

{* Название компонента *}
{$component = 'pagination'}

{* Переменные *}
{$_paging = $smarty.local.paging}

{**
 * Элемент пагинации
 *
 * @param bool   $isActive (false) Если true, то элемент помечается как активный (текущая страница)
 * @param string $url              Ссылка
 * @param string $text             Текст
 * @param string $linkClasses      Дополнительные классы для ссылки
 *}
{function item}
	<li class="{$component}-item {if $isActive}active{/if}">
		{if $url}
			<a class="{$component}-item-inner {$component}-item-link {$linkClasses}" href="{$url}">{$text}</a>
		{else}
			<span class="{$component}-item-inner">{$text}</span>
		{/if}
	</li>
{/function}

{**
 * Страницы
 *}
{if $_paging && $_paging.iCountPage > 1}
	<nav class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes} js-{$component}" role="navigation" {cattr list=$smarty.local.attributes}>
		{* Следущая / предыдущая страница *}
		<ul class="{$component}-list">
			{* Следущая страница *}
			{if $_paging.iPrevPage}
				{item url="{$_paging.sBaseUrl}{if $_paging.iPrevPage > 1}/page{$_paging.iPrevPage}{/if}/{$_paging.sGetParams}" text="&larr; {$aLang.pagination.previous}" linkClasses="js-{$component}-prev"}
			{else}
				{item text="&larr; {$aLang.pagination.previous}"}
			{/if}

			{* Предыдущая страница *}
			{if $_paging.iNextPage}
				{item url="{$_paging.sBaseUrl}/page{$_paging.iNextPage}/{$_paging.sGetParams}" text="{$aLang.pagination.next} &rarr;" linkClasses="js-{$component}-next"}
			{else}
				{item text="{$aLang.pagination.next} &rarr;"}
			{/if}
		</ul>

		{* Список страниц *}
		<ul class="{$component}-list">
			{* Первая страница *}
			{if $_paging.iCurrentPage > 1}
				{item url="{$_paging.sBaseUrl}/{$_paging.sGetParams}" text=$aLang.pagination.first}
			{/if}

			{* Страницы слева от текущей *}
			{foreach $_paging.aPagesLeft as $iPage}
				{item url="{$_paging.sBaseUrl}{if $iPage > 1}/page{$iPage}{/if}/{$_paging.sGetParams}" text=$iPage}
			{/foreach}

			{* Текущая активная страница *}
			{item isActive=true text=$_paging.iCurrentPage}

			{* Страницы справа от текущей *}
			{foreach $_paging.aPagesRight as $iPage}
				{item url="{$_paging.sBaseUrl}{if $iPage > 1}/page{$iPage}{/if}/{$_paging.sGetParams}" text=$iPage}
			{/foreach}

			{* Последняя страница *}
			{if $_paging.iCurrentPage < $_paging.iCountPage}
				{item url="{$_paging.sBaseUrl}/page{$_paging.iCountPage}/{$_paging.sGetParams}" text=$aLang.pagination.last}
			{/if}
		</ul>
	</nav>
{/if}