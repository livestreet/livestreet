{**
 * Выпадающее меню
 *}

{$menu = [
    [ 'text' => 'Edit' ],
    [ 'text' => 'Delete' ],
    [ 'text' => 'Send' ]
]}

<script>
    jQuery(function ($) {
        $('.js-mydropdown').lsDropdown();
    });
</script>



<p>В параметре <code>menu</code> прописываем массив с пунктами меню, в параметре <code>classes</code> указываем класс к которому будем привязывать jquery-виджет <code>lsDropdown</code>.</p>

{capture 'test_example_content'}
    {component 'dropdown' text='Dropdown' classes='js-mydropdown' menu=$menu}
{/capture}

{capture 'test_example_code'}
<script>
    jQuery(function ($) {
        $('.js-mydropdown').lsDropdown();
    });
</script>

{ldelim}component 'dropdown' text='Dropdown' classes='js-mydropdown' menu=[
    [ 'text' => 'Edit' ],
    [ 'text' => 'Delete' ],
    [ 'text' => 'Send' ]
]{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}



{**
 * Цвета
 *}
{test_heading text='Цвета'}

<p>В параметре <code>mods</code> можно указывать цвет кнопки.</p>
<p>Модификаторы <code>primary</code> <code>success</code> <code>info</code> <code>warning</code> <code>danger</code>.</p>

{capture 'test_example_content'}
    {component 'dropdown' text='Default' classes='js-mydropdown' menu=$menu}
    {component 'dropdown' text='Primary' classes='js-mydropdown' mods='primary' menu=$menu}
    {component 'dropdown' text='Success' classes='js-mydropdown' mods='success' menu=$menu}
    {component 'dropdown' text='Info'    classes='js-mydropdown' mods='info' menu=$menu}
    {component 'dropdown' text='Warning' classes='js-mydropdown' mods='warning' menu=$menu}
    {component 'dropdown' text='Danger'  classes='js-mydropdown' mods='danger' menu=$menu}
{/capture}

{capture 'test_example_code'}
{ldelim}component 'dropdown' text='Default' classes='js-mydropdown' menu=...{rdelim}
{ldelim}component 'dropdown' text='Primary' classes='js-mydropdown' mods='primary' menu=...{rdelim}
{ldelim}component 'dropdown' text='Success' classes='js-mydropdown' mods='success' menu=...{rdelim}
{ldelim}component 'dropdown' text='Info'    classes='js-mydropdown' mods='info' menu=...{rdelim}
{ldelim}component 'dropdown' text='Warning' classes='js-mydropdown' mods='warning' menu=...{rdelim}
{ldelim}component 'dropdown' text='Danger'  classes='js-mydropdown' mods='danger' menu=...{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}



{**
 * Размеры
 *}
{test_heading text='Размеры'}

<p>Модификаторы <code>large</code> <code>small</code></p>

{capture 'test_example_content'}
    {component 'dropdown' classes='js-mydropdown' text='Large dropdown' mods='large' menu=$menu}
    <br>
    <br>
    {component 'dropdown' classes='js-mydropdown' text='Default dropdown' menu=$menu}
    <br>
    <br>
    {component 'dropdown' classes='js-mydropdown' text='Small dropdown' mods='small' menu=$menu}
{/capture}

{capture 'test_example_code'}
{ldelim}component 'dropdown' text='Large dropdown' classes='js-mydropdown' mods='large' menu=...{rdelim}
{ldelim}component 'dropdown' text='Default dropdown' classes='js-mydropdown' menu=...{rdelim}
{ldelim}component 'dropdown' text='Small dropdown' classes='js-mydropdown' mods='small' menu=...{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}



{**
 * Раздельный переключатель
 *}
{test_heading text='Раздельный переключатель'}

<p>Для того чтобы кнопка показывающая меню была отделена от текста, необходимо в параметре <code>isSplit</code> прописать <code>true</code>.</p>

{capture 'test_example_content'}
    {component 'dropdown' isSplit=true text='Actions' classes='js-mydropdown' menu=$menu}
    {component 'dropdown' isSplit=true text='Actions' classes='js-mydropdown' mods='success' menu=$menu}
{/capture}

{capture 'test_example_code'}
{ldelim}component 'dropdown' text='Actions' classes='js-mydropdown' isSplit=true menu=...{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}


{**
 * Группировка
 *}
{test_heading text='Группировка'}

{capture 'test_example_content'}
    {component 'dropdown' classes='js-mydropdown' menu=$menu}
    {component 'dropdown' classes='js-mydropdown' mods='info' menu=$menu}
    <br>
    <br>
    {component 'button' template='group' buttons=[
        [ 'text' => 'Hello' ],
        {component 'dropdown' classes='js-mydropdown' text='Dropdown' menu=$menu},
        {component 'dropdown' classes='js-mydropdown' text='Dropdown' icon='ok' menu=$menu},
        {component 'dropdown' classes='js-mydropdown' icon='ok' menu=$menu},
        [ 'text' => 'Hello' ],
        [ 'text' => 'Hello' ]
    ]}
    <br>
    <br>
    {component 'button' template='group' buttons=[
        {component 'dropdown' classes='js-mydropdown' text='Dropdown' menu=$menu}
    ]}
{/capture}

{capture 'test_example_code'}
TODO
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}