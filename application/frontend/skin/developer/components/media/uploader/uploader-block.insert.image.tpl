{**
 * Опции вставки
 *
 * @param boolean $useSizes
 *}

{extends 'Component@uploader.uploader-block'}

{block 'block_options' append}
	{$classes = "{$classes} js-media-info-block"}
	{$attributes = array_merge( $attributes|default:[], [ 'data-type' => 'insert', 'data-filetype' => '1' ] )}
{/block}

{block 'block_title'}
	{lang name='media.insert.settings.title'}
{/block}

{block 'block_content'}
	<form method="post" action="" enctype="multipart/form-data">
		{* Выравнивание *}
		{component 'field' template='select'
			name  = 'align'
			label = {lang name='media.image_align.title'}
			items = [
				[ 'value' => '',       'text' => {lang name='media.image_align.no'} ],
				[ 'value' => 'left',   'text' => {lang name='media.image_align.left'} ],
				[ 'value' => 'right',  'text' => {lang name='media.image_align.right'} ],
				[ 'value' => 'center', 'text' => {lang name='media.image_align.center'} ]
			]}

		{* Размер *}
		{if $smarty.local.useSizes|default:true}
			{component 'field' template='select'
				name          = 'size'
				label         = {lang name='media.insert.settings.fields.size.label'}
				items         = [[ 'value' => 'original', 'text' => {lang name='media.insert.settings.fields.size.original'} ]]}
		{/if}
	</form>
{/block}