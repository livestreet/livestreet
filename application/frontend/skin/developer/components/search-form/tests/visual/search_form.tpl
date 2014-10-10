{**
 * Тестирование форм поиска
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$bNoSidebar = true}
{/block}

{block 'layout_page_title'}
	Component <span>search-form</span>
{/block}

{block 'layout_content'}
	{function test_heading}
		<br><h3>{$sText}</h3>
	{/function}


	{test_heading sText='Default'}

	{include 'components/search-form/search-form.tpl'
			 sName  = 'text'
			 sNote  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'}


	{test_heading sText='Light'}

	{include 'components/search-form/search-form.tpl'
			 sName  = 'text'
			 sMods  = 'light'
			 sNote  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'}
{/block}