{**
 * Заголовки
 *}
{test_heading text='Заголовки'}

{capture 'headings'}
<h1>Заголовок H1 <small>Мелкий текст</small></h1>
<h2>Заголовок H2 <small>Мелкий текст</small></h2>
<h3>Заголовок H3 <small>Мелкий текст</small></h3>
<h4>Заголовок H4 <small>Мелкий текст</small></h4>
<h5>Заголовок H5 <small>Мелкий текст</small></h5>
<h6>Заголовок H6 <small>Мелкий текст</small></h6>
{/capture}

{test_example content=$smarty.capture.headings}

{test_heading text='Строчные заголовки'}

<p>Для оформления строчных элементов можно использовать классы <code>.h1</code> ... <code>.h2</code></p>

{capture 'headings_inline'}
<span class="h1">Заголовок H1 <small>Мелкий текст</small></span>
<span class="h2">Заголовок H2 <small>Мелкий текст</small></span>
<span class="h3">Заголовок H3 <small>Мелкий текст</small></span>
<span class="h4">Заголовок H4 <small>Мелкий текст</small></span>
<span class="h5">Заголовок H5 <small>Мелкий текст</small></span>
<span class="h6">Заголовок H6 <small>Мелкий текст</small></span>
{/capture}

{test_example content=$smarty.capture.headings_inline}


{**
 * Строчные элементы
 *}
{test_heading text='Строчные элементы'}

{capture 'inline'}
Для <mark>выделения текста</mark> используется тег <code>mark</code> <br>
Для <del>удаленного текста</del> используется тег <code>del</code> <br>
Для <s>зачеркнутого текста</s> используется тег <code>s</code> <br>
Для <ins>добавленного текста</ins> используется тег <code>ins</code> <br>
Для <u>подчеркнутого текста</u> используется тег <code>u</code> <br>
Для <strong>акцентирования текста</strong> используется тег <code>strong</code> <br>
Для <small>мелкого текста</small> используется тег <code>small</code> <br>
Для <em>наклонного текста</em> используется тег <code>em</code>
{/capture}

{test_example content=$smarty.capture.inline}


{**
 * Аббревиатуры
 *}
{test_heading text='Аббревиатуры'}

{capture 'abbr'}
Для аббревиатур используется тег <code>abbr</code>: <abbr title="HyperText Markup Language">HTML</abbr>
{/capture}

{test_example content=$smarty.capture.abbr}


{**
 * Адреса
 *}
{test_heading text='Адреса'}

{capture 'address'}
<address>
    <strong>Twitter, Inc.</strong><br>
    795 Folsom Ave, Suite 600<br>
    San Francisco, CA 94107<br>
    <abbr title="Phone">P:</abbr> (123) 456-7890
</address>
{/capture}

{test_example content=$smarty.capture.address}


{**
 * Цитата
 *}
{test_heading text='Цитата'}

{capture 'blockquote'}
<blockquote>
    <p>I contend that we are both atheists. I just believe in one fewer
    god than you do. When you understand why you dismiss all the other
    possible gods, you will understand why I dismiss yours.</p>
    <cite>Stephen Roberts</cite>
</blockquote>
{/capture}

{test_example content=$smarty.capture.blockquote}



<h3>Цитата с измененным направлением текста</h3>

{capture 'blockquote'}
<blockquote class="blockquote--reverse">
    <p>I contend that we are both atheists. I just believe in one fewer
    god than you do. When you understand why you dismiss all the other
    possible gods, you will understand why I dismiss yours.</p>
    <cite>Stephen Roberts</cite>
</blockquote>
{/capture}

{test_example content=$smarty.capture.blockquote}


{**
 * Списки
 *}
{test_heading text='Списки'}

{capture 'test_content'}
<ul>
    <li>Lorem ipsum dolor sit amet</li>
    <li>Lorem ipsum dolor</li>
        <ul>
            <li>Lorem ipsum dolor sit amet</li>
            <li>Lorem ipsum dolor</li>
            <li>Lorem ipsum dolor sit amet, consectetur</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing</li>
            <li>Lorem ipsum dolor sit</li>
        </ul>
    <li>Lorem ipsum dolor sit amet, consectetur</li>
    <li>Lorem ipsum dolor sit amet, consectetur adipisicing</li>
    <li>Lorem ipsum dolor sit</li>
</ul>

<ol>
    <li>Lorem ipsum dolor sit amet</li>
    <li>Lorem ipsum dolor</li>
    <li>Lorem ipsum dolor sit amet, consectetur.
        <ol>
            <li>Lorem ipsum dolor sit amet</li>
            <li>Lorem ipsum dolor</li>
            <li>Lorem ipsum dolor sit amet, consectetur</li>
            <li>Lorem ipsum dolor sit amet, consectetur adipisicing</li>
            <li>Lorem ipsum dolor sit</li>
        </ol>
    </li>
    <li>Lorem ipsum dolor sit amet, consectetur adipisicing</li>
    <li>Lorem ipsum dolor sit</li>
</ol>
{/capture}

{test_example content=$smarty.capture.test_content}


<h3>Строчный список</h3>

<p>Для отображения списка в строчном виде используйте класс <code>line-inline</code></p>

{capture 'test_content'}
<ul class="list-inline">
    <li>Lorem ipsum dolor</li>
    <li>Lorem ipsum</li>
    <li>Lorem ipsum dolor sit</li>
</ul>
{/capture}

{test_example content=$smarty.capture.test_content}


{**
 * Код
 *}
{test_heading text='Код'}

<h3>Строчный код</h3>

{capture 'test_example_content'}
<p>When you call the <code>activate()</code> method on the
<code>robotSnowman</code> object, the eyes glow.</p>
{/capture}

{test_example content=$smarty.capture.test_example_content}


<h3>Блок с кодом</h3>

<p>Для ограничения блока по высоте используется класс <code>pre-scrollable</code></p>

{capture 'test_example_content'}
<pre><code>function Panel(element, canClose, closeHandler) {
    this.element = element;
    this.canClose = canClose;
    this.closeHandler = function () { if (closeHandler) closeHandler() };
}</code></pre>

<pre class="pre-scrollable">{section name=foo loop=10}{"Lorem ipsum dolor sit amet\n"}{/section}</pre>
{/capture}

{test_example content=$smarty.capture.test_example_content}


<h3>Предварительно форматированный текст</h3>

{capture 'test_example_content'}
<pre>
 _      _            _____ _                 _
| |    (_)          / ____| |               | |
| |     ___   _____| (___ | |_ _ __ ___  ___| |_
| |    | \ \ / / _ \\___ \| __| '__/ _ \/ _ \ __|
| |____| |\ V /  __/____) | |_| | |  __/  __/ |_
|______|_| \_/ \___|_____/ \__|_|  \___|\___|\__|
</pre>
{/capture}

{test_example content=$smarty.capture.test_example_content}


<h3>Тег kdb</h3>

<p>Тег <code>&lt;kbd&gt;</code> используется для обозначения текста, который набирается на клавиатуре или для названия клавиш.</p>

{capture 'test_example_content'}
To make George eat an apple, press <kbd><kbd>Shift</kbd>+<kbd>F3</kbd></kbd><br>
To make George eat an apple, select <kbd>File | Eat Apple...</kbd>
{/capture}

{test_example content=$smarty.capture.test_example_content}


<h3>Переменные</h3>

{capture 'test_example_content'}
Then he turned to the blackboard and picked up the chalk. After a few moment's
thought, he wrote <var>E</var> = <var>m</var> <var>c</var><sup>2</sup>. The teacher
looked pleased.
{/capture}

{test_example content=$smarty.capture.test_example_content}


<h3>Тег samp</h3>

<p>Тег <code>&lt;samp&gt;</code> используется для отображения текста, который является результатом вывода компьютерной программы или скрипта.</p>

{capture 'test_example_content'}
<p>The computer said <samp>Too much cheese in tray two</samp> but I didn't know what that meant.</p>
{/capture}

{test_example content=$smarty.capture.test_example_content}