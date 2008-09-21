<?
/**
 * Jevix — средство автоматического применения правил набора текстов, 
 * наделённое способностью унифицировать разметку HTML/XML документов, 
 * контролировать перечень допустимых тегов и аттрибутов, 
 * предотвращать возможные XSS-атаки в коде документов.
 * @author ur001 <ur001ur001@gmail.com>, http://jevix.ru/
 * @version 0.92 (beta)
 * 
 * История версий:
 * 0.92
 * 	+ Добавлена настройка cfgSetAutoBrMode. При установке в false, переносы строк не будут автоматически заменяться на BR
 * 	+ Изменена обработка HTML-сущностей. Теперь все сущности имеющие эквивалент в Unicode (за исключением <>)
 *    автоматически преобразуются в символ
 *  + 
 * 0.91
 * 	+ Добавлена обработка преформатированных тегов <pre>, <code>. Для задания используйте cfgSetTagPreformatted()
 *  + Добавлена настройка cfgSetXHTMLMode. При отключении пустые теги будут оформляться как <br>, при включенном - <br/> 
* 	+ Несколько незначительных багфиксов
 * 0.9
 * 	+ Первый бета-релиз
 * 
 * Известные баги и нереализованные фитчи:
 * 	+ При распознавании URL может захватить лишние символы
 *  + Не заменяет ' на апостроф
 *  + Знак дюйма "
 *  + Возможность задания списка разрешённых классов
 */

class Jevix{
	const PRINATABLE  = 0x8;	
	const SPACE       = 0x10;
	const ALPHA       = 0x20;
	const NUMERIC     = 0x40;
	const LAT         = 0x100;
	const NOPRINT     = 0x200;
	const URL         = 0x400;
	const PUNCTUATUON = 0x800;
	const NAME        = 0x1000;
	const HTML_QUOTE  = 0x2000;
	const TAG_QUOTE   = 0x4000;
	const QUOTE_CLOSE = 0x8000;	
	const NL          = 0x10000;
	const QUOTE_OPEN  = 0;
	
	const QUOTE_1     = 1;
	const QUOTE_2     = 2;
	const QUOTE_3     = 3;
	const QUOTE_4     = 4;
	
	const STATE_TEXT = 0;
	const STATE_TAG_PARAMS = 1;	
	const STATE_TAG_PARAM_VALUE = 2;
	const STATE_INSIDE_TAG = 3;
	const STATE_INSIDE_NOTEXT_TAG = 4;
	const STATE_INSIDE_PREFORMATTED_TAG = 5;
	
	//protected 
	public $tagsRules = array();

	public $entities0 = array('"'=>'&quot;', "'"=>'&#39;', '&'=>'&amp;', '<'=>'&lt;', '>'=>'&gt;');	
	public $entities1 = array();	
	public $entities2 = array('<'=>'&lt;', '>'=>'&gt;');	
	public $textQuotes = array(array('«', '»'), array('„', '“'));
	public $dash = " — ";
	public $apostrof = "’";
	public $dotes = "…";
	public $nl = "\r\n";
	
	protected $text;
	protected $textBuf;
	protected $textLen = 0;
	protected $curPos;
	protected $curCh;
	protected $curChOrd;
	protected $curChClass;
	protected $states;
	protected $quotesOpened = 0;
	protected $brAdded = 0;
	protected $state;
	protected $tagsStack;
	protected $openedTag;
	protected $autoReplace; // Автозамена
	protected $isXHTMLMode  = false; // <br/>, <img/>
	protected $isAutoBrMode = true; // \n = <br/>
	protected $br = "<br>"; // сделал пустым чтоб не съедал переносы строк
	
	public    $outBuffer = '';
	public    $errors;

	
	/**
	 * Константы для класификации тегов
	 *
	 */
	const TR_TAG_ALLOWED = 1; 		// Тег позволен
	const TR_PARAM_ALLOWED = 2; 	// Параметр тега позволен (a->title, a->src, i->alt)
	const TR_PARAM_REQUIRED = 3; 	// Параметр тега влятся необходимым (a->href, img->src)
	const TR_TAG_SHORT = 4;   		// Тег может быть коротким (img, br)
	const TR_TAG_CUT = 5;			// Тег необходимо вырезать вместе с контентом (script, iframe)
	const TR_TAG_CHILD = 6;			// Тег может содержать другие теги
	const TR_TAG_CONTAINER = 7;     // Тег может содержать лишь указанные теги. В нём не может быть текста
	const TR_TAG_CHILD_TAGS = 8;	// Теги которые может содержать внутри себя другой тег
	const TR_TAG_PARENT = 9;		// Тег в котором должен содержаться данный тег
	const TR_TAG_PREFORMATTED = 10;	// Преформатированные тег, в котором всё заменяется на HTML сущности типа <pre>, <code>
	
	protected $chClasses = array(0=>512,1=>512,2=>512,3=>512,4=>512,5=>512,6=>512,7=>512,8=>512,9=>16,10=>66048,11=>512,12=>512,13=>66048,14=>512,15=>512,16=>512,17=>512,18=>512,19=>512,20=>512,21=>512,22=>512,23=>512,24=>512,25=>512,26=>512,27=>512,28=>512,29=>512,30=>512,31=>512,32=>16,97=>4392,98=>4392,99=>4392,100=>4392,101=>4392,102=>4392,103=>4392,104=>4392,105=>4392,106=>4392,107=>4392,108=>4392,109=>4392,110=>4392,111=>4392,112=>4392,113=>4392,114=>4392,115=>4392,116=>4392,117=>4392,118=>4392,119=>4392,120=>4392,121=>4392,122=>4392,65=>4392,66=>4392,67=>4392,68=>4392,69=>4392,70=>4392,71=>4392,72=>4392,73=>4392,74=>4392,75=>4392,76=>4392,77=>4392,78=>4392,79=>4392,80=>4392,81=>4392,82=>4392,83=>4392,84=>4392,85=>4392,86=>4392,87=>4392,88=>4392,89=>4392,90=>4392,1072=>40,1073=>40,1074=>40,1075=>40,1076=>40,1077=>40,1078=>40,1079=>40,1080=>40,1081=>40,1082=>40,1083=>40,1084=>40,1085=>40,1086=>40,1087=>40,1088=>40,1089=>40,1090=>40,1091=>40,1092=>40,1093=>40,1094=>40,1095=>40,1096=>40,1097=>40,1098=>40,1099=>40,1100=>40,1101=>40,1102=>40,1103=>40,1040=>40,1041=>40,1042=>40,1043=>40,1044=>40,1045=>40,1046=>40,1047=>40,1048=>40,1049=>40,1050=>40,1051=>40,1052=>40,1053=>40,1054=>40,1055=>40,1056=>40,1057=>40,1058=>40,1059=>40,1060=>40,1061=>40,1062=>40,1063=>40,1064=>40,1065=>40,1066=>40,1067=>40,1068=>40,1069=>40,1070=>40,1071=>40,48=>5192,49=>5192,50=>5192,51=>5192,52=>5192,53=>5192,54=>5192,55=>5192,56=>5192,57=>5192,34=>57353,39=>16394,171=>8203,187=>40971,8222=>8204,8220=>40974,8221=>40970,46=>3080,44=>2056,33=>2056,63=>3080,58=>2056,59=>3080,1105=>40,1025=>40,47=>1032,38=>1032,37=>1032,45=>1032,95=>1032,61=>1032,43=>1032,35=>1032,124=>1032,);
		
	/**
	 * Установка конфигурационного флага для одного или нескольких тегов
	 *
	 * @param array|string $tags тег(и)
	 * @param int $flag флаг
	 * @param mixed $value значеник=е флага
	 * @param boolean $createIfNoExists если тег ещё не определён - создть его
	 */
	protected function _cfgSetTagsFlag($tags, $flag, $value, $createIfNoExists = true){
		if(!is_array($tags)) $tags = array($tags);
		foreach($tags as $tag){
			if(!isset($this->tagsRules[$tag])) {
				if($createIfNoExists){
					$this->tagsRules[$tag] = array();
				} else {
					throw new Exception("Тег $tag отсутствует в списке разрешённых тегов");
				}
			}
			$this->tagsRules[$tag][$flag] = $value;
		}		
	}
	
	/**
	 * КОНФИГУРАЦИЯ: Разрешение или запрет тегов
	 * Все не разрешённые теги считаются запрещёнными
	 * @param array|string $tags тег(и)
	 */
	function cfgAllowTags($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_ALLOWED, true);
	}
	
	/**
	 * КОНФИГУРАЦИЯ: Коротие теги типа <img>
	 * @param array|string $tags тег(и)
	 */
	function cfgSetTagShort($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_SHORT, true, false);
	}
	
	/**
	 * КОНФИГУРАЦИЯ: Преформатированные теги, в которых всё заменяется на HTML сущности типа <pre>, <code>
	 * @param array|string $tags тег(и)
	 */
	function cfgSetTagPreformatted($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_PREFORMATTED, true, false);
	}	
	
	/**
	 * КОНФИГУРАЦИЯ: Тег необходимо вырезать вместе с контентом (script, iframe)
	 * @param array|string $tags тег(и)
	 */
	function cfgSetTagCutWithContent($tags){
		$this->_cfgSetTagsFlag($tags, self::TR_TAG_CUT, true);
	}	
	
	/**
	 * КОНФИГУРАЦИЯ: Добавление разрешённых параметров тега
	 * @param string $tag тег
	 * @param string|array $params разрешённые параметры
	 */
	function cfgAllowTagParams($tag, $params){
		if(!isset($this->tagsRules[$tag])) throw new Exception("Тег $tag отсутствует в списке разрешённых тегов");
		if(!is_array($params)) $params = array($params);
		// Если ключа со списком разрешенных параметров не существует - создаём ео
		if(!isset($this->tagsRules[$tag][self::TR_PARAM_ALLOWED])) {
			$this->tagsRules[$tag][self::TR_PARAM_ALLOWED] = array();
		}
		foreach($params as $param){
			$this->tagsRules[$tag][self::TR_PARAM_ALLOWED][$param] = true;
		}
	}	
	
	/**
	 * КОНФИГУРАЦИЯ: Добавление необходимых параметров тега
	 * @param string $tag тег
	 * @param string|array $params разрешённые параметры
	 */
	function cfgSetTagParamsRequired($tag, $params){
		if(!isset($this->tagsRules[$tag])) throw new Exception("Тег $tag отсутствует в списке разрешённых тегов");
		if(!is_array($params)) $params = array($params);
		// Если ключа со списком разрешенных параметров не существует - создаём ео
		if(!isset($this->tagsRules[$tag][self::TR_PARAM_REQUIRED])) {
			$this->tagsRules[$tag][self::TR_PARAM_REQUIRED] = array();
		}		
		foreach($params as $param){
			$this->tagsRules[$tag][self::TR_PARAM_REQUIRED][$param] = true;
		}	
	}	

	/* КОНФИГУРАЦИЯ: Установка тегов которые может содержать тег-контейнер
	 * @param string $tag тег
	 * @param string|array $childs разрешённые теги
	 */
	function cfgSetTagChilds($tag, $childs, $isContainerOnly = false, $isChildOnly = false){
		if(!isset($this->tagsRules[$tag])) throw new Exception("Тег $tag отсутствует в списке разрешённых тегов");
		if(!is_array($childs)) $childs = array($childs);
		// Тег является контейнером и не может содержать текст
		if($isContainerOnly) $this->tagsRules[$tag][self::TR_TAG_CONTAINER] = true;
		// Если ключа со списком разрешенных тегов не существует - создаём ео
		if(!isset($this->tagsRules[$tag][self::TR_TAG_CHILD_TAGS])) {
			$this->tagsRules[$tag][self::TR_TAG_CHILD_TAGS] = array();
		}		
		foreach($childs as $child){
			$this->tagsRules[$tag][self::TR_TAG_CHILD_TAGS][$child] = true;
			//  Указанный тег должен сущеаствовать в списке тегов
			if(!isset($this->tagsRules[$child])) throw new Exception("Тег $child отсутствует в списке разрешённых тегов");
			if(!isset($this->tagsRules[$child][self::TR_TAG_PARENT])) $this->tagsRules[$child][self::TR_TAG_PARENT] = array();
			$this->tagsRules[$child][self::TR_TAG_PARENT][$tag] = true;
			// Указанные разрешённые теги могут находится только внтутри тега-контейнера			
			if($isChildOnly) $this->tagsRules[$child][self::TR_TAG_CHILD] = true;
		}
	}	

	/**
	 * Автозамена
	 *
	 * @param array $from с
	 * @param array $to на
	 */
	function cfgSetAutoReplace($from, $to){
		$this->autoReplace = array('from' => $from, 'to' => $to);
	}
	
	/**
	 * Включение или выключение режима XTML
	 *
	 * @param unknown_type $isXHTMLMode
	 */
	function cfgSetXHTMLMode($isXHTMLMode){
		$this->br = $isXHTMLMode ? '<br/>' : '<br>';
		$this->isXHTMLMode = $isXHTMLMode;
	}
	
	/**
	 * Включение или выключение режима замены новых строк на <br/>
	 *
	 * @param unknown_type $isXHTMLMode
	 */
	function cfgSetAutoBrMode($isAutoBrMode){
		$this->isAutoBrMode = $isAutoBrMode;
	}	
	
	protected function &strToArray($str){
		$chars = null;
		preg_match_all('/./su', $str, $chars);
		return $chars[0];
	}
		
	
	function parse($text, &$errors){
		$this->curPos = -1;
		$this->curCh = null;
		$this->curChOrd = 0;
		$this->state = self::STATE_TEXT;
		$this->states = array();
		$this->quotesOpened = 0;
		
		// Авто растановка BR?
		if($this->isAutoBrMode) {
			$this->text = preg_replace('/<br\/?>(\r\n|\n\r|\n)?/ui', $this->nl, $text);
		} else {
			$this->text = $text;
		}
		
		
		if(!empty($this->autoReplace)){
			$this->text = str_replace($this->autoReplace['from'], $this->autoReplace['to'], $this->text);
		}
		$this->textBuf = $this->strToArray($this->text);
		$this->textLen = count($this->textBuf);
		$this->getCh();
		$content = '';
		$this->outBuffer='';
		$this->brAdded=0;
		$this->tagsStack = array();	
		$this->openedTag = null;
		$this->errors = array();
		$this->skipSpaces();
		$this->anyThing($content);
		/*if($this->quotesOpened>0){
			$content.=$this->fixQuotes();
		}*/	
		$errors = $this->errors;
		return $content;
	}
	
	/**
	 * Получение следующего символа из входной строки
	 * @param bool $entityToChar автоматически превращать сущности в символы
	 * @return string считанный символ
	 */
	protected function getCh(){
		return $this->goToPosition($this->curPos+1);
	}
	
	/**
	 * Перемещение на указанную позицию во входной строке и считывание символа
	 * @param bool $entityToChar автоматически превращать сущности в символы
	 * @return string символ в указанной позиции
	 */	
	protected function goToPosition($position){
		$this->curPos = $position;
		if($this->curPos < $this->textLen){
			$this->curCh = $this->textBuf[$this->curPos];
			$this->curChOrd = uniord($this->curCh);
			$this->curChClass = $this->getCharClass($this->curChOrd);
		} else {
			$this->curCh = null;
			$this->curChOrd = 0;
			$this->curChClass = 0;
		}
		return $this->curCh;		
	}
	
	/**
	 * Сохранить текущее состояние
	 *
	 */
	protected function saveState(){
		$state = array(
			'pos'   => $this->curPos,
			'ch'    => $this->curCh,
			'ord'   => $this->curChOrd,
			'class' => $this->curChClass,
		);
		
		$this->states[] = $state;
		return count($this->states)-1;
	}
	
	/**
	 * Восстановить
	 *
	 */	
	protected function restoreState($index = null){
		if(!count($this->states)) throw new Exception('Конец стека');
		if($index == null){
			$state = array_pop($this->states);
		} else {
			if(!isset($this->states[$index])) throw new Exception('Неверный индекс стека');
			$state = $this->states[$index];
			$this->states = array_slice($this->states, 0, $index);
		}
		
		$this->curPos     = $state['pos'];
		$this->curCh      = $state['ch'];
		$this->curChOrd   = $state['ord'];
		$this->curChClass = $state['class'];	
	}
	
	/**
	 * Проверяет точное вхождение символа в текущей позиции
	 * Если символ соответствует указанному автомат сдвигается на следующий
	 *
	 * @param string $ch
	 * @return boolean
	 */
	protected function matchCh($ch, $skipSpaces = false){
		if($this->curCh == $ch) {
			$this->getCh();
			if($skipSpaces) $this->skipSpaces();
			return true;
		}
		
		return false;
	}
	
	/**
	 * Проверяет точное вхождение символа указанного класса в текущей позиции
	 * Если символ соответствует указанному классу автомат сдвигается на следующий
	 *
	 * @param int $chClass класс символа
	 * @return string найденый символ или false
	 */
	protected function matchChClass($chClass, $skipSpaces = false){
		if(($this->curChClass & $chClass) == $chClass) {
			$ch = $this->curCh;
			$this->getCh();
			if($skipSpaces) $this->skipSpaces();
			return $ch;
		}
		
		return false;
	}	
	
	/**
	 * Проверка на точное совпадение строки в текущей позиции
	 * Если строка соответствует указанной автомат сдвигается на следующий после строки символ
	 *
	 * @param string $str
	 * @return boolean
	 */
	protected function matchStr($str, $skipSpaces = false){
		$this->saveState();
		$len = strlen($str);
		$test = '';
		while($len-- && $this->curChClass){
			$test.=$this->curCh;
			$this->getCh();
		}
		
		if($test == $str) {
			if($skipSpaces) $this->skipSpaces();
			return true;
		} else {
			$this->restoreState();
			return false;
		}
	}
	
	/**
	 * Пропуск текста до нахождения указанного символа
	 *
	 * @param string $ch сиимвол
	 * @return string найденый символ или false
	 */
	protected function skipUntilCh($ch){
		$chPos = strpos($this->text, $ch, $this->curPos);
		if($chPos){
			return $this->goToPosition($chPos);
		} else {
			return false;
		}
	}
	
	/**
	 * Пропуск текста до нахождения указанной строки или символа
	 *
	 * @param string $str строка или символ ля поиска
	 * @return boolean
	 */
	protected function skipUntilStr($str){
		$str = $this->strToArray($str);
		$firstCh = $str[0];
		$len = count($str);
		while($this->curChClass){
			if($this->curCh == $firstCh){
				$this->saveState();
				$this->getCh();
				$strOK = true;
				for($i = 1; $i<$len ; $i++){
					// Конец строки
					if(!$this->curChClass){
						return false;
					}
					// текущий символ не равен текущему символу проверяемой строки?
					if($this->curCh != $str[$i]){
						$strOK = false;
						break;
					}
					// Следующий символ
					$this->getCh();
				}
				
				// При неудаче откатываемся с переходим на следующий символ
				if(!$strOK){
					$this->restoreState();
				} else {
					return true;
				}
			}
			// Следующий символ
			$this->getCh();
		}		
		return false;
	}	
	
	/**
	 * Возвращает класс символа
	 *
	 * @return int
	 */
	protected function getCharClass($ord){
		return isset($this->chClasses[$ord]) ? $this->chClasses[$ord] : self::PRINATABLE;
	}
	
	/*function isSpace(){
		return $this->curChClass == slf::SPACE;
	}*/
	
	/**
	 * Пропуск пробелов 
	 *
	 */
	protected function skipSpaces(&$count = 0){
		while($this->curChClass == self::SPACE) {
			$this->getCh();
			$count++;
		}
		return $count > 0;
	}
	
	/**
	 *  Получает име (тега, параметра) по принципу 1 сиивол далее цифра или символ
	 *
	 * @param string $name
	 */
	protected function name(&$name = '', $minus = false){
		if(($this->curChClass & self::LAT) == self::LAT){
			$name.=$this->curCh;
			$this->getCh();
		} else {
			return false;
		}
		
		while((($this->curChClass & self::NAME) == self::NAME || ($minus && $this->curCh=='-'))){
			$name.=$this->curCh;
			$this->getCh();
		}		
		
		$this->skipSpaces();
		return true;
	}
	
	protected function tag(&$tag, &$params, &$content, &$short){
		$this->saveState();	
		$params = array();
		$tag = '';
		$closeTag = '';
		$params = array();
		$short = false;
		if(!$this->tagOpen($tag, $params, $short)) return false;
		// Короткая запись тега
		if($short) return true;
		
		// Сохраняем кавычки и состояние
		//$oldQuotesopen = $this->quotesOpened;
		$oldState = $this->state;
		//$this->quotesOpened = 0;
		
		
		// Если в теге не должно быть текста, а только другие теги
		// Переходим в состояние self::STATE_INSIDE_NOTEXT_TAG
		if(!empty($this->tagsRules[$tag][self::TR_TAG_PREFORMATTED])){
			$this->state = self::STATE_INSIDE_PREFORMATTED_TAG;
		} elseif(!empty($this->tagsRules[$tag][self::TR_TAG_CONTAINER])){
			$this->state = self::STATE_INSIDE_NOTEXT_TAG;
		} else {
			$this->state = self::STATE_INSIDE_TAG;
		}
		
		// Контент тега
		array_push($this->tagsStack, $tag);
		$this->openedTag = $tag;
		$content = '';
		if($this->state == self::STATE_INSIDE_PREFORMATTED_TAG){
			$this->preformatted($content, $tag);
		} else {
			$this->anyThing($content, $tag);
		}

		$this->openedTag = array_pop($this->tagsStack);
		
		$isTagClose = $this->tagClose($closeTag);
		if($isTagClose && ($tag != $closeTag)) {
			$this->eror("Неверный закрывающийся тег $closeTag. Ожидалось закрытие $tag");
			//$this->restoreState();
		}
		
		// Восстанавливаем предыдущее состояние и счетчик кавычек
		$this->state = $oldState;
		//$this->quotesOpened = $oldQuotesopen;
		
		return true;
	}
	
	protected function fixQuotes(){
		$text = '';
		while($this->quotesOpened>0){
			$this->quotesOpened-=1;
			$text.= $this->makeQuote(true, $this->quotesOpened);
		}	
		return $text;	
	}
	
	protected function preformatted(&$content = '', $insideTag = null){
		while($this->curChClass){
			if($this->curCh == '<'){
				$tag = '';
				$this->saveState();
				// Пытаемся найти закрывающийся тег
				$isClosedTag = $this->tagClose($tag);
				// Возвращаемся назад, если тег был найден
				if($isClosedTag) $this->restoreState();
				// Если закрылось то, что открылось - заканчиваем и возвращаем true
				if($isClosedTag && $tag == $insideTag) return;
			} 
			$content.= isset($this->entities2[$this->curCh]) ? $this->entities2[$this->curCh] : $this->curCh;
			$this->getCh();
		}
	}
	
	protected function tagOpen(&$name, &$params, &$short = false){
		$restore = $this->saveState();	
		
		// Открытие
		if(!$this->matchCh('<')) return false;
		$this->skipSpaces();
		if(!$this->name($name)){
			$this->restoreState();
			return false;
		}
		
		// Пробуем получить список атрибутов тега
		if($this->curCh != '>' && $this->curCh != '/') $this->tagParams($params);
		
		// Короткая запись тега
		if($this->matchCh('/') || !empty($this->tagsRules[$name][self::TR_TAG_SHORT])) {
			if(!$short) $short = true;
		}
		$this->skipSpaces();

		// Закрытие	
		if(!$this->matchCh('>')) {
			$this->restoreState($restore);
			return false;
		}
		
		$this->skipSpaces();
		return true;
	}


	protected function tagParams(&$params = array()){
		$name = null;
		$value = null;
		while($this->tagParam($name, $value)){
			$params[$name] = $value;
			$name = ''; $value = '';
		}
		return count($params) > 0;
	}	
		
	protected function tagParam(&$name, &$value){
		$this->saveState();
		if(!$this->name($name, true)) return false;
		
		if(!$this->matchCh('=', true)){
			// Стремная штука - параметр без значения <input type="checkbox" checked>, <td nowrap class=b>
			if(($this->curCh=='>' || ($this->curChClass & self::LAT) == self::LAT)){
				$value = null;
				return true;
			} else {
				$this->restoreState();
				return false;
			}
		}		
		
		$quote = $this->matchChClass(self::TAG_QUOTE, true);
		
		if(!$this->tagParamValue($value, $quote)){
			$this->restoreState();
			return false;
		}	
		
		if($quote && !$this->matchCh($quote, true)){
			$this->restoreState();
			return false;
		}	
		
		$this->skipSpaces();
		return true;	
	}	
	
	protected function tagParamValue(&$value, $quote){
		if($quote !== false){
			// Нормальный параметр с кавычкамию Получаем пока не кавычки и не конец
			$escape = false;
			while($this->curChClass && ($this->curCh != $quote || $escape)){
				$escape = false;
				// Экранируем символы HTML которые не могут быть в параметрах
				$value.=isset($this->entities1[$this->curCh]) ? $this->entities1[$this->curCh] : $this->curCh;
				// Символ ескейпа <a href="javascript::alert(\"hello\")">
				if($this->curCh == '\\') $escape = true;
				$this->getCh();			
			}
		} else {
			// долбаный параметр без кавычек. получаем его пока не пробел и не > и не конец
			while($this->curChClass && !($this->curChClass & self::SPACE) && $this->curCh != '>'){
				// Экранируем символы HTML которые не могут быть в параметрах
				$value.=isset($this->entities1[$this->curCh]) ? $this->entities1[$this->curCh] : $this->curCh;
				$this->getCh();			
			}			
		}

		return true;
	}
	
	protected function tagClose(&$name){
		$this->saveState();	
		if(!$this->matchCh('<')) return false;
		$this->skipSpaces();
		if(!$this->matchCh('/')) {
			$this->restoreState();
			return false;
		}
		$this->skipSpaces();
		if(!$this->name($name)){
			$this->restoreState();
			return false;
		}
		$this->skipSpaces();
		if(!$this->matchCh('>')) {
			$this->restoreState();
			return false;
		}
		return true;		
	}	
	
	protected function makeTag($tag, $params, $content, $short, $parentTag = null){
		$tag = strtolower($tag);
		
		// Получаем правила фильтрации тега
		$tagRules = isset($this->tagsRules[$tag]) ? $this->tagsRules[$tag] : null;
			
		// Проверка - родительский тег - контейнер, содержащий только другие теги (ul, table, etc)
		$parentTagIsContainer = $parentTag && isset($this->tagsRules[$parentTag][self::TR_TAG_CONTAINER]);
		
		// Вырезать тег вместе с содержанием
		if($tagRules && isset($this->tagsRules[$tag][self::TR_TAG_CUT])) return '';
				
		// Позволен ли тег
		if(!$tagRules || empty($tagRules[self::TR_TAG_ALLOWED])) return $parentTagIsContainer ? '' : $content;

		// Если тег находится внутри другого - может ли он там находится?
		if($parentTagIsContainer){
			if(!isset($this->tagsRules[$parentTag][self::TR_TAG_CHILD_TAGS][$tag])) return '';
		}	

		// Тег может находится только внтури другого тега
		if(isset($tagRules[self::TR_TAG_CHILD])){
			if(!isset($tagRules[self::TR_TAG_PARENT][$parentTag])) return $content;
		}
		
		
		$resParams = array();
		foreach($params as $param=>$value){
			// Пустое значение параметра
			if(empty($value)) continue;
			$param = strtolower($param);
			// Параметр разрешён
			$paramAllowed = isset($tagRules[self::TR_PARAM_ALLOWED][$param]);
			if(!$paramAllowed) continue;
			$value = trim($value);

			switch($param){
				case 'src': 
					// Расширение должно точно соответствовать
					// GET запросы типа ...jpg?A=6 не допускаются
					/*if(!preg_match('/\.(jpg|gif|png|jpeg)$/ui', $value)) {
						$this->eror('img src: Неверное расширение файла в пути к изображению');
						continue(2);
					}*/
					// Ява-скрипт в пути к картинке
					if(preg_match('/javascript:/ui', $value)) {
						$this->eror('img src: Попытка вставить JavaScript');
						continue(2);
					}
					// HTTP в начале если нет
					if(!preg_match('/^http:\/\//ui', $value) && !preg_match('/^\//ui', $value)) $value = 'http://'.$value;						
					break;
					
				case 'href':
					// Ява-скрипт в ссылке
					if(preg_match('/javascript:/ui', $value)) {
						$this->eror('a href: Попытка вставить JavaScript');
						continue(2);
					}
					// Спец символынедопустимые в URL
					//$value = preg_replace('/[^\x20-\xFF]/u', "", $value);
					// Первый символ должен быть a-z0-9!
					if(!preg_match('/^[a-z0-9\/]/ui', $value)) {
						$this->eror('a href: Первый символ адреса должен быть буквой или цифрой');
						continue(2);
					}
					// HTTP в начале если нет
					if(!preg_match('/^http:\/\//ui', $value) && !preg_match('/^\//ui', $value)) $value = 'http://'.$value;
					break;	
					
				case 'text':
					// Параметр yext в <acut>-е
					$value = htmlspecialchars($value);
					break;	
					
				case 'width':
				case 'height':	
					// Ява-скрипт в ссылке
					if(!is_numeric($value)) {
						$this->eror('img width,size: Неверный размер изображения');
						continue(2);
					}						
					break;
										
			}
			$resParams[$param] = $value;
		}
		
		// Проверка обязятельных параметров тега
		// Если нет обязательных параметров возвращаем только контент
		$requiredParams = isset($tagRules[self::TR_PARAM_REQUIRED]) ? array_keys($tagRules[self::TR_PARAM_REQUIRED]) : array();
		if($requiredParams){
			foreach($requiredParams as $requiredParam){
				if(empty($resParams[$requiredParam])) return $content;
			}
		}
		
		// Пустой некороткий тег удаляем
		if(!$short && empty($content)) return '';
		// Собираем тег
		$text='<'.$tag;	
		// Параметры
		foreach($resParams as $param=>$value) $text.=' '.$param.'="'.$value.'"';
		// Закрытие тега (если короткий то без контента)
		$text.= $short && $this->isXHTMLMode ? '/>' : '>';
		if(isset($tagRules[self::TR_TAG_CONTAINER])) $text .= "\r\n";
		if(!$short) $text.= $content.'</'.$tag.'>';
		if($parentTagIsContainer) $text .= "\r\n";
		if($tag == 'br') $text.="\r\n";
		return $text;
	}
	
	protected function comment(){
		if(!$this->matchStr('<!--')) return false;
		return $this->skipUntilStr('-->');
	}
	
	protected function anyThing(&$content = '', $parentTag = null){
		$this->skipNL();
		while($this->curChClass){
			$tag = '';
			$params = null;
			$text = null;
			$shortTag = false;		
			$name = null;	
			
			// Если мы находимся в режиме тега без текста
			// пропускаем контент пока не встретится <
			if($this->state == self::STATE_INSIDE_NOTEXT_TAG && $this->curCh!='<'){
				$this->skipUntilCh('<');
			}
			
			// <Тег> кекст </Тег>
			if($this->curCh == '<' && $this->tag($tag, $params, $text, $shortTag)){
				$tagText = $this->makeTag($tag, $params, $text, $shortTag, $parentTag);
				$content.=$tagText;
				if(empty($tagText) || $tag=='br'){
					$this->skipNL();
				}
			
			// Коментарий <!-- -->	
			} elseif($this->curCh == '<' && $this->comment()){ 
				continue;
				
			// Конец тега или символ <
			} elseif($this->curCh == '<') {
				// Если встречается <, но это не тег
				// то это либо закрывающийся тег либо знак <
				$this->saveState();
				if($this->tagClose($name)){
					// Если это закрывающийся тег, то мы делаем откат 
					// и выходим из функции
					// Но если мы не внутри тега, то просто пропускаем его
					if($this->state == self::STATE_INSIDE_TAG || $this->state == self::STATE_INSIDE_NOTEXT_TAG) {
						$this->restoreState();
						return false;
					} else {
						$this->eror('Не ожидалось закрывающегося тега '.$name);
					}
				} else {
					if($this->state != self::STATE_INSIDE_NOTEXT_TAG) $content.=$this->entities2['<'];
					$this->getCh();					
				}
				
			// Текст
			} elseif($this->text($text)){
				$content.=$text;
			}
		}
		
		return true;
	}
	
	/**
	 * Пропуск переводов строк подсчет кол-ва
	 *
	 * @param int $count ссылка для возвращения числа переводов строк
	 * @return boolean
	 */
	protected function skipNL(&$count = 0){
		if(!($this->curChClass & self::NL)) return false;
		$count++;
		$firstNL = $this->curCh;
		$nl = $this->getCh();
		while($this->curChClass & self::NL){
			// Если символ новый строки ткой же как и первый увеличиваем счетчик
			// новых строк. Это сработает при любых сочетаниях
			// \r\n\r\n, \r\r, \n\n - две перевода
			if($nl == $firstNL) $count++;			
			$nl = $this->getCh();
			// Между переводами строки могут встречаться пробелы
			$this->skipSpaces();
		}
		return true;
	}
	
	protected function dash(&$dash){
		if($this->curCh != '-') return false;
		$dash = '';
		$this->saveState();
		$this->getCh();
		// Несколько подряд
		while($this->curCh == '-') $this->getCh();
		if(!$this->skipNL() && !$this->skipSpaces()){
			$this->restoreState();
			return false;
		}
		$dash = $this->dash;
		return true;
	}
	
	protected function punctuation(&$punctuation){
		if(!($this->curChClass & self::PUNCTUATUON)) return false;
		$this->saveState();
		$punctuation = $this->curCh;
		$this->getCh();
		
		// Проверяем ... и !!!
		if($punctuation == '.' && $this->curCh == '.'){
			while($this->curCh == '.') $this->getCh();
			$punctuation = $this->dotes;
		} elseif($punctuation == '!' && $this->curCh == '!'){
			while($this->curCh == '!') $this->getCh();
			$punctuation = '!!!';
		}
		
		// Далее идёт слово - добавляем пробел
		if($this->curChClass & self::ALPHA && $punctuation != '.') {
			//$punctuation.= ' ';
			return true;
		// Далее идёт пробел, перенос строки, конец текста
		} elseif(($this->curChClass & self::SPACE) || ($this->curChClass & self::NL) || !$this->curChClass){
			return true;
		} else {
			$this->restoreState();
			return false;
		}
	}
	
	protected function number(&$num){
		if(!(($this->curChClass & self::NUMERIC) == self::NUMERIC)) return false;
		$num = $this->curCh;
		$this->getCh();
		while(($this->curChClass & self::NUMERIC) == self::NUMERIC){
			$num.= $this->curCh;
			$this->getCh();
		}
		return true;
	}
	
	protected function htmlEntity(&$entityCh){
		if($this->curCh<>'&') return false;
		$this->saveState();
		$this->matchCh('&');
		if($this->matchCh('#')){
			$entityCode = 0;
			if(!$this->number($entityCode) || !$this->matchCh(';')){
				$this->restoreState();
				return false;
			}
			$entityCh = html_entity_decode("&#$entityCode;", ENT_COMPAT, 'UTF-8');
			return true;
		} else{
			$entityName = '';
			if(!$this->name($entityName) || !$this->matchCh(';')){
				$this->restoreState();
				return false;
			}
			$entityCh = html_entity_decode("&$entityName;", ENT_COMPAT, 'UTF-8');
			return true;
		}
	}
	
	/**
	 * Кавычка
	 *
	 * @param string $quote кавычка
	 * @param boolean $closed закрывающаяся
	 * @return boolean
	 */
	protected function quote($spacesBefore, &$quote, &$closed, $class){
		if(($this->curChClass & $class) != $class) return false;
		$quote = $this->curCh;
		$this->getCh();
		$closed = (($this->curCh & self::SPACE) == self::SPACE) || !$spacesBefore || (($this->curChClass & self::QUOTE_CLOSE) == self::QUOTE_CLOSE);
		return true;
	}
	
	protected function makeQuote($closed, $level){
		$levels = count($this->textQuotes);
		if($level > $levels) $level = $levels;
		return $this->textQuotes[$level][$closed ? 1 : 0];
	}

	
	protected function text(&$text){
		$text = '';
		//$punctuation = '';
		$dash = '';
		$newLine = true;
		$newWord = true; // Возможно начало нового слова
		$url = null;
		$href = null;
		
		// Первый символ может быть <, это значит что tag() вернул false
		// и < к тагу не относится
		while(($this->curCh != '<') && $this->curChClass){
			$brCount = 0;
			$spCount = 0;
			$quote = null; 
			$closed = false;
			$punctuation = null;
			$entity = null;

			$this->skipSpaces($spCount);
			if(!$spCount) $newWord = true;
						
			// автопреобразование сущностей...
			if($this->curCh == '&' && $this->htmlEntity($entity)){
				$text.= isset($this->entities2[$entity]) ? $this->entities2[$entity] : $entity;
			} elseif(($this->curChClass & self::PUNCTUATUON) && $this->punctuation($punctuation)){
				// Автопунктуация выключена
				// Если встретилась пунктуация - добавляем ее
				$text.=$punctuation;	
				$newWord = true;			
			} elseif(($spCount || $newLine) && $this->curCh == '-' && $this->dash($dash)){
				// Тире
				$text.=$dash; 	
				$newWord = true;
			} elseif($this->quote($spCount, $quote, $closed, self::HTML_QUOTE)){
				// Кавычки
				$this->quotesOpened+=$closed ? -1 : 1;
				// Исправляем ситуацию если кавычка закрыввается раньше чем открывается
				if($this->quotesOpened<0){
					$closed = false;
					$this->quotesOpened=1;
				}
				$quote = $this->makeQuote($closed, $closed ? $this->quotesOpened : $this->quotesOpened-1);
				if($spCount) $quote = ' '.$quote;
				$text.= $quote;
				$newWord = true;
			} elseif($spCount>0){
				$text.=' ';
				// после пробелов снова возможно новое слово
				$newWord = true;
			} elseif($this->isAutoBrMode && $this->skipNL($brCount)){
				// Перенос строки
				$br = $this->br.$this->nl;
				$text.= $brCount == 1 ? $br : $br.$br;
				// Помечаем что новая строка и новое слово
				$newLine = true;
				$newWord = true;
				// !!!Добавление слова
			} elseif($newWord && ($this->curChClass & self::LAT)){
				// начало слова на латинскую букву. Возможно начало URL
				if($this->openedTag!='a' && false && $this->url($url, $href)){
					$text.= $this->makeTag('a' , array('href' => $href), $url, false);
				} else {
					$text.=$this->curCh;
					$newLine = false;
					$newWord = false;
					$this->getCh();
				}
			} elseif($this->curChClass & self::PRINATABLE){
				// Экранируем символы HTML которые нельзя сувать внутрь тега (но не те? которые не могут быть в параметрах)
				$text.=isset($this->entities2[$this->curCh]) ? $this->entities2[$this->curCh] : $this->curCh;
				$this->getCh();
				$newWord = false;
				// !!!Добавление к слова
			} else {
				// Совершенно непечатаемые символы которые никуда не годятся
				$this->getCh();
			}
		}
		
		// Пробелы
		$this->skipSpaces();		
		return $text != '';
	}
	
	protected function url(&$url, &$href){
		$this->saveState();
		$url = '';
		//$name = $this->name();
		//switch($name)
		$urlChMask = self::URL | self::ALPHA;
		
		if($this->matchStr('http://')){
			while($this->curChClass & $urlChMask){
				$url.= $this->curCh;
				$this->getCh();
			}
			
			if(!strlen($url)) {
				$this->restoreState();
				return false;
			}
			
			$href = 'http://'.$url;
			return true;
		} elseif($this->matchStr('www.')){
			while($this->curChClass & $urlChMask){
				$url.= $this->curCh;
				$this->getCh();
			}
			
			if(!strlen($url)) {
				$this->restoreState();
				return false;
			}
			
			$url = 'www.'.$url;
			$href = 'http://'.$url;
			return true;		
		}
		$this->restoreState();
		return false;
	}
	
	protected function eror($message){
		$str = '';
		$strEnd = min($this->curPos + 8, $this->textLen);
		for($i = $this->curPos; $i < $strEnd; $i++){
			$str.=$this->textBuf[$i];
		}
		
		$this->errors[] = array(
			'message' => $message,
			'pos'     => $this->curPos,
			'ch'      => $this->curCh,
			'line'    => 0, 
			'str'     => $str,
		);
	}
}

/**
 * Функция ord() для мультибайтовы строк
 *
 * @param string $c символ utf-8
 * @return int код символа
 */
function uniord($c) {
    $h = ord($c{0});
    if ($h <= 0x7F) {
        return $h;
    } else if ($h < 0xC2) {
        return false;
    } else if ($h <= 0xDF) {
        return ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
    } else if ($h <= 0xEF) {
        return ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6
                                 | (ord($c{2}) & 0x3F);
    } else if ($h <= 0xF4) {
        return ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12
                                 | (ord($c{2}) & 0x3F) << 6
                                 | (ord($c{3}) & 0x3F);
    } else {
        return false;
    }
}

/**
 * Функция chr() для мультибайтовы строк
 *
 * @param int $c код символа
 * @return string символ utf-8
 */
function unichr($c) {
    if ($c <= 0x7F) {
        return chr($c);
    } else if ($c <= 0x7FF) {
        return chr(0xC0 | $c >> 6) . chr(0x80 | $c & 0x3F);
    } else if ($c <= 0xFFFF) {
        return chr(0xE0 | $c >> 12) . chr(0x80 | $c >> 6 & 0x3F)
                                    . chr(0x80 | $c & 0x3F);
    } else if ($c <= 0x10FFFF) {
        return chr(0xF0 | $c >> 18) . chr(0x80 | $c >> 12 & 0x3F)
                                    . chr(0x80 | $c >> 6 & 0x3F)
                                    . chr(0x80 | $c & 0x3F);
    } else {
        return false;
    }
}

/**
 * @todo eror, аппостроф', дюйм, запятая в url (обработка доменов до слэша), фильтрция class
 */
?>