{**
 * Тестирование полей
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options' append}
	{$layoutShowSidebar = false}
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
				 name  = 'text'
				 note  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 label = 'Text field'}


		{* Select *}
		{test_heading sText='Select'}

		{include 'components/field/field.select.tpl'
				 selectedValue = 'item2'
				 name          = 'select'
				 label         = 'Select'
				 note          = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 items         = [
				     [ 'value' => 'item1', 'text' => 'Item 1' ],
				     [ 'value' => 'item2', 'text' => 'Item 2' ]
		         ]}

		{* Textarea *}
		{test_heading sText='Textarea'}

		{include 'components/field/field.textarea.tpl'
				 name    = 'textarea'
				 label   = 'Textarea'
				 note    = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 rows    = 5}


		{* Checkbox *}
		{test_heading sText='Checkbox'}

		{include 'components/field/field.checkbox.tpl'
				 name  = 'checkbox'
				 label = 'Checkbox'}

		{include 'components/field/field.checkbox.tpl'
				 name  = 'checkbox'
				 label = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, expedita, quos, eum sunt recusandae vitae vel voluptates molestias quae nesciunt perferendis quaerat voluptatem facere hic odio esse placeat amet nam iure deserunt animi a accusantium necessitatibus error praesentium laudantium unde.'}


		{* Radio *}
		{test_heading sText='Radio'}

		{include 'components/field/field.radio.tpl'
				 name  = 'radio'
				 label = 'Radio'}

		{include 'components/field/field.radio.tpl'
				 name  = 'radio'
				 label = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, expedita, quos, eum sunt recusandae vitae vel voluptates molestias quae nesciunt perferendis quaerat voluptatem facere hic odio esse placeat amet nam iure deserunt animi a accusantium necessitatibus error praesentium laudantium unde.'
				 note = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, hic, placeat.'}


		{* File *}
		{test_heading sText='File'}

		{include 'components/field/field.file.tpl'
				 name  = 'file'
				 label = 'File'}


		{* Captcha *}
		{test_heading sText='Captcha'}

		{include 'components/field/field.captcha.tpl'
				 name  = 'captcha'
				 label = 'Captcha'
				 note  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 inputClasses  = 'width-50'}
	</form>
{/block}