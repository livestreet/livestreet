{**
 * Подгрузка контента
 *}

<p>Компонент представляет из себя кнопку, которая предназначена для подгрузки контента в начало или конец указанного блока.</p>

{test_heading text='Использование'}

<p>Для добавления функционала подгрузки контента, для начала, нужно обернуть первоначальный контент (например список первых 10 товаров) в отдельный блок и прописать ему уникальный класс, например <code>.js-items</code></p>

{capture 'test_code'}
<div class="js-items">
    ... Список товаров ...
</div>
{/capture}

{test_code code=$smarty.capture.test_code}


<p>После этого блока подключаем компонент <code>more</code>, указав уникальный класс к которому будет привязываться js-виджет:</p>

{capture 'test_code'}
{ldelim}component 'more'
    classes    = 'js-items-more'
    count      = $itemsCount
    ajaxParams = [ 'next_page' => 2 ]{rdelim}
{/capture}

{test_code code=$smarty.capture.test_code}


<p>В итоге должно получиться:</p>

{capture 'test_example_content'}
    <div class="js-items">
        ... Список товаров ...
    </div>

    {component 'more'
        classes='js-items-more'
        count=30
        ajaxParams=[ 'next_page' => 2 ]}
{/capture}

{capture 'test_example_code'}
<div class="js-items">
    ... Список товаров ...
</div>

{ldelim}component 'more'
    classes    = 'js-items-more'
    ajaxParams = [ 'next_page' => 2 ]{rdelim}
{/capture}

{test_example content=$smarty.capture.test_example_content code=$smarty.capture.test_example_code}


<p>Теперь необходимо иниц-ать js-виджет <code>lsMore</code> чтобы кнопка начала работать.
В опциях виджета указываем <code>url</code> на который будет отсылаться запрос и <code>target</code> - селектор блока в который будет добавляться новый контент.</p>

{component 'alert' text='Обратите внимание, инициализировать все виджеты необходимо после dom ready.'}

{capture 'test_code'}
$('.js-items-more').lsMore({
    urls: {
        load: myurlhere
    }
    proxy: [ 'next_page' ],
    target: '.js-items',
});
{/capture}

{test_code code=$smarty.capture.test_code}


<p>Ответ сервера должен быть в формате JSON и иметь следующую структуру:</p>

{capture 'test_code'}
{
    // Код который будет добавляться в блок
    html: string,
    // Кол-во подгруженных элементов
    count_loaded: integer,
    // Если true, то кнопка скрывается,
    // необязательный параметр
    hide: boolean,
    // Ответ также должен содержать проксирующие параметры,
    // которые ранее были указаны в шаблоне и js-виджете.
    // В нашем случае это номер следующей страницы (3)
    next_page: integer
}
{/capture}

{test_code code=$smarty.capture.test_code}
