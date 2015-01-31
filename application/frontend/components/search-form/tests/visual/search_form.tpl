{**
 * Тестирование форм поиска
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
	Component <span>search-form</span>
{/block}

{block 'layout_content'}
	{function test_heading}
		<br><h3>{$sText}</h3>
	{/function}


	{test_heading sText='Default'}

	{component 'search-form'
			 name  = 'text'
			 note  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'}


	{test_heading sText='Light'}

	{component 'search-form'
			 name  = 'text'
			 mods  = 'light'
			 note  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'}
{/block}