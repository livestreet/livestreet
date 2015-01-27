{**
 * Кнопки
 *}
{test_heading text='Кнопки'}

{capture 'test_example_content'}
    {component 'button' text='Кнопка'}
    {component 'button' text='Ссылка' url='http://example.com'}
{/capture}

{capture 'test_example_code'}
{ldelim}component 'button' text='Кнопка'{rdelim}
{ldelim}component 'button' text='Ссылка' url='http://example.com'{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}


{**
 * Цвета
 *}
{test_heading text='Цвета'}

<p>Модификаторы <code>primary</code> <code>success</code> <code>info</code> <code>warning</code> <code>danger</code></p>

{capture 'test_example_content'}
    {component 'button' text='Default'}
    {component 'button' text='Primary' mods='primary'}
    {component 'button' text='Success' mods='success'}
    {component 'button' text='Info' mods='info'}
    {component 'button' text='Warning' mods='warning'}
    {component 'button' text='Danger' mods='danger'}
{/capture}

{capture 'test_example_code'}
{ldelim}component 'button' text='Default'{rdelim}
{ldelim}component 'button' text='Primary' mods='primary'{rdelim}
{ldelim}component 'button' text='Success' mods='success'{rdelim}
{ldelim}component 'button' text='Info' mods='info'{rdelim}
{ldelim}component 'button' text='Warning' mods='warning'{rdelim}
{ldelim}component 'button' text='Danger' mods='danger'{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}


{**
 * Размеры
 *}
{test_heading text='Размеры'}

<p>Модификаторы <code>large</code> <code>small</code> <code>xsmall</code></p>

{capture 'test_example_content'}
    <p>{component 'button' text='Large button' mods='large'}</p>
    <p>{component 'button' text='Default button' mods='default'}</p>
    <p>{component 'button' text='Small button' mods='small'}</p>
    <p>{component 'button' text='Xsmall button' mods='xsmall'}</p>
{/capture}

{capture 'test_example_code'}
{ldelim}component 'button' text='Large button' mods='large'{rdelim}
{ldelim}component 'button' text='Default button' mods='default'{rdelim}
{ldelim}component 'button' text='Small button' mods='small'{rdelim}
{ldelim}component 'button' text='Extra small button' mods='xsmall'{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}

<h3>Кнопка во всю ширину родительского блока</h3>

<p>Модификатор <code>block</code></p>

{capture 'test_example_content'}
<div style="background: #fafafa; padding: 20px; width: 200px;">
    {component 'button' text='Block button' mods='large block'}
</div>
{/capture}

{capture 'test_example_code'}
{ldelim}component 'button' text='Large button' mods='large block'{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}


{**
 * Иконки
 *}
{test_heading text='Иконки'}

<p>Параметр <code>icon</code></p>

{capture 'test_example_content'}
    <p>{component 'button' text='Save' icon='ok'}</p>
    {component 'button' mods='icon' icon='ok'}
{/capture}

{capture 'test_example_code'}
{ldelim}component 'button' text='Save' icon='ok'{rdelim}
{ldelim}component 'button' icon='ok' mods='icon'{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}


{**
 * Отправка формы
 *}
{test_heading text='Отправка формы'}

<p>Опция <code>form</code> позволяет указать id формы для отправки, это бывает полезно если кнопку отправки необходимо разместить вне формы.</p>

{capture 'test_code'}
<form id="myform">
    ...
</form>

{ldelim}component 'button' text='Отправить' form='myform'{rdelim}
{/capture}

{test_code code=$smarty.capture.test_code}


{**
 * Группировка кнопок
 *}
{test_heading text='Группировка кнопок'}

<p>Шаблон <code>group</code> позволяет группировать кнопки.</p>

{capture 'test_example_content'}
    {component 'button' template='group' buttons=[
        [ 'text' => 'Left' ],
        [ 'text' => 'Middle' ],
        [ 'text' => 'Middle' ],
        [ 'text' => 'Middle' ],
        [ 'text' => 'Right' ]
    ]}
    <br>
    {component 'button' template='group' buttons=[
        [ 'text' => 'Left', 'mods' => 'large' ],
        [ 'text' => 'Middle', 'mods' => 'large' ],
        [ 'text' => 'Middle', 'mods' => 'large' ],
        [ 'text' => 'Middle', 'mods' => 'large' ],
        [ 'text' => 'Right', 'mods' => 'large' ]
    ]}
    <br>
    {component 'button' template='group' buttons=[
        [ 'text' => 'Middle' ]
    ]}
{/capture}

{capture 'test_example_code'}
{ldelim}component 'button' template='group' buttons=[
    [ 'text' => 'Left' ],
    [ 'text' => 'Middle' ],
    [ 'text' => 'Middle' ],
    [ 'text' => 'Middle' ],
    [ 'text' => 'Right' ]
]{rdelim}

{ldelim}component 'button' template='group' buttons=[
    [ 'text' => 'Left', 'mods' => 'large' ],
    [ 'text' => 'Middle', 'mods' => 'large' ],
    [ 'text' => 'Middle', 'mods' => 'large' ],
    [ 'text' => 'Middle', 'mods' => 'large' ],
    [ 'text' => 'Right', 'mods' => 'large' ]
]{rdelim}

{ldelim}component 'button' template='group' buttons=[
    [ 'text' => 'Middle' ]
]{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}

<h3>Общие параметры кнопок</h3>

<p>Для всех кнопок в группе можно указать общие параметры в параметре <code>buttonParams</code>, например код:</p>

{capture 'test_code'}
{ldelim}component 'button' template='group' buttons=[
    [ 'text' => 'Left', 'mods' => 'large' ],
    [ 'text' => 'Middle', 'mods' => 'large' ],
    [ 'text' => 'Right', 'mods' => 'large' ]
]{rdelim}
{/capture}

{test_code code=$smarty.capture.test_code}

<p>Можно переписать как:</p>

{capture 'test_code'}
{ldelim}component 'button'
    template='group'
    buttonParams=[ 'mods' => 'large' ]
    buttons=[
        [ 'text' => 'Left' ],
        [ 'text' => 'Middle' ],
        [ 'text' => 'Right' ]
    ]{rdelim}
{/capture}

{test_code code=$smarty.capture.test_code}

<p>Таким образом модификтор <code>large</code> применится ко все кнопкам в группе.</p>


{**
 * Тулбар
 *}
{test_heading text='Тулбар'}

<p>Для отображения нескольких групп кнопок используется шаблон <code>toolbar</code> и параметр <code>groups</code>, который принимает массив с группами кнопок.</p>

{capture 'test_example_content'}
    {component 'button' template='toolbar' groups=[
        [
            'buttons' => [
                [ 'icon' => 'ok' ],
                [ 'icon' => 'remove' ],
                [ 'icon' => 'zoom-in' ],
                [ 'icon' => 'zoom-out' ]
            ]
        ],
        [
            'buttons' => [
                [ 'text' => '1' ],
                [ 'text' => '2' ],
                [ 'text' => '3' ],
                [ 'text' => '4' ]
            ]
        ],
        [
            'buttons' => [
                [ 'text' => '1' ]
            ]
        ]
    ]}
{/capture}

{capture 'test_example_code'}
{ldelim}component 'button' template='toolbar' groups=[
    [
        'buttons' => [
            [ 'icon' => 'ok' ],
            [ 'icon' => 'remove' ],
            [ 'icon' => 'zoom-in' ],
            [ 'icon' => 'zoom-out' ]
        ]
    ],
    [
        'buttons' => [
            [ 'text' => '1' ],
            [ 'text' => '2' ],
            [ 'text' => '3' ],
            [ 'text' => '4' ]
        ]
    ],
    [
        'buttons' => [
            [ 'text' => '1' ]
        ]
    ]
]{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}