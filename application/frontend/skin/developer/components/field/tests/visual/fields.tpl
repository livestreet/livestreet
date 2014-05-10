{**
 * Создание блога
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$bNoSidebar = true}
{/block}

{block 'layout_page_title'}
	Component <span>field</span>
{/block}

{block 'layout_content'}
	{function test_heading}
		<br><h3>{$sText}</h3>
	{/function}


	<form method="post" enctype="multipart/form-data">
		{* Hidden *}
		{include 'components/field/field.hidden.security_key.tpl'}

		{* Text *}
		{test_heading sText='Text'}

		{include 'components/field/field.text.tpl'
				 sName  = 'text'
				 sNote  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 sLabel = 'Text field'}


		{* Select *}
		{test_heading sText='Select'}

		{include 'components/field/field.select.tpl'
				 sSelectedValue = 'item2'
				 sName          = 'select'
				 sLabel         = 'Select'
				 sNote          = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 aItems         = [
				     [ 'value' => 'item1', 'text' => 'Item 1' ],
				     [ 'value' => 'item2', 'text' => 'Item 2' ]
		         ]}

		{* Textarea *}
		{test_heading sText='Textarea'}

		{include 'components/field/field.textarea.tpl'
				 sName    = 'textarea'
				 sLabel   = 'Textarea'
				 sNote    = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 iRows    = 5}


		{* Checkbox *}
		{test_heading sText='Checkbox'}

		{include 'components/field/field.checkbox.tpl'
				 sName  = 'checkbox'
				 sLabel = 'Checkbox'}

		{include 'components/field/field.checkbox.tpl'
				 sName  = 'checkbox'
				 sLabel = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, expedita, quos, eum sunt recusandae vitae vel voluptates molestias quae nesciunt perferendis quaerat voluptatem facere hic odio esse placeat amet nam iure deserunt animi a accusantium necessitatibus error praesentium laudantium unde.'}


		{* Radio *}
		{test_heading sText='Radio'}

		{include 'components/field/field.radio.tpl'
				 sName  = 'radio'
				 sLabel = 'Radio'}

		{include 'components/field/field.radio.tpl'
				 sName  = 'radio'
				 sLabel = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, expedita, quos, eum sunt recusandae vitae vel voluptates molestias quae nesciunt perferendis quaerat voluptatem facere hic odio esse placeat amet nam iure deserunt animi a accusantium necessitatibus error praesentium laudantium unde.'
				 sNote = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, hic, placeat.'}


		{* File *}
		{test_heading sText='File'}

		{include 'components/field/field.file.tpl'
				 sName  = 'file'
				 sLabel = 'File'}


		{* Captcha *}
		{test_heading sText='Captcha'}

		{include 'components/field/field.captcha.tpl'
				 sName  = 'captcha'
				 sLabel = 'Captcha'
				 sNote  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 sInputClasses  = 'width-50'}
	</form>
{/block}