{**
 * Тестирование полей
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
	Component <span>field</span>
{/block}

{block 'layout_content'}
	{function test_heading}
		<br><h3>{$sText}</h3>
	{/function}


	<form method="post" enctype="multipart/form-data">
		{* Hidden *}
		{component 'field' template='hidden.security-key'}

		{* Text *}
		{test_heading sText='Text'}

		{component 'field' template='text'
				 name  = 'text'
				 note  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 label = 'Text field'}


		{* Select *}
		{test_heading sText='Select'}

		{component 'field' template='select'
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

		{component 'field' template='textarea'
				 name    = 'textarea'
				 label   = 'Textarea'
				 note    = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 rows    = 5}


		{* Checkbox *}
		{test_heading sText='Checkbox'}

		{component 'field' template='checkbox'
				 name  = 'checkbox'
				 label = 'Checkbox'}

		{component 'field' template='checkbox'
				 name  = 'checkbox'
				 label = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, expedita, quos, eum sunt recusandae vitae vel voluptates molestias quae nesciunt perferendis quaerat voluptatem facere hic odio esse placeat amet nam iure deserunt animi a accusantium necessitatibus error praesentium laudantium unde.'}


		{* Radio *}
		{test_heading sText='Radio'}

		{component 'field' template='radio'
				 name  = 'radio'
				 label = 'Radio'}

		{component 'field' template='radio'
				 name  = 'radio'
				 label = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique, expedita, quos, eum sunt recusandae vitae vel voluptates molestias quae nesciunt perferendis quaerat voluptatem facere hic odio esse placeat amet nam iure deserunt animi a accusantium necessitatibus error praesentium laudantium unde.'
				 note = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, hic, placeat.'}


		{* File *}
		{test_heading sText='File'}

		{component 'field' template='file'
				 name  = 'file'
				 label = 'File'}


		{* Captcha *}
		{test_heading sText='Captcha'}

		{component 'field' template='captcha'
				 name  = 'captcha'
				 label = 'Captcha'
				 note  = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Velit, libero.'
				 inputClasses  = 'width-50'}
	</form>
{/block}