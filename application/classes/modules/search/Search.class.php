<?php
/*
 * LiveStreet CMS
 * Copyright © 2013 OOO "ЛС-СОФТ"
 *
 * ------------------------------------------------------
 *
 * Official site: www.livestreetcms.com
 * Contact e-mail: office@livestreetcms.com
 *
 * GNU General Public License, version 2:
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * ------------------------------------------------------
 *
 * @link http://www.livestreetcms.com
 * @copyright 2013 OOO "ЛС-СОФТ"
 * @author Maxim Mzhelskiy <rus.engine@gmail.com>
 *
 */

/**
 * Модуль поиска
 *
 * @package application.modules.search
 * @since 2.0
 */
class ModuleSearch extends Module
{

    protected $oMapper;

    /**
     * Инициализация модуля
     */
    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    /**
     * Выполняет поиск топиков по регулярному выражению
     *
     * @param $sRegexp
     * @param $iCurrPage
     * @param $iPerPage
     *
     * @return array
     */
    public function SearchTopics($sRegexp, $iCurrPage, $iPerPage)
    {
        $sCacheKey = "search_topics_{$sRegexp}_{$iCurrPage}_{$iPerPage}";
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = array(
                'collection' => $this->oMapper->SearchTopics($sRegexp, $iCount, $iCurrPage, $iPerPage),
                'count'      => $iCount
            );
            $this->Cache_Set($data, $sCacheKey, array('topic_update', 'topic_new'), 60 * 60 * 24 * 1);
        }
        if ($data['collection']) {
            $data['collection'] = $this->Topic_GetTopicsAdditionalData($data['collection']);
        }
        return $data;
    }

    /**
     * Выполняет поиск комментариев по регулярному выражению
     *
     * @param $sRegexp
     * @param $iCurrPage
     * @param $iPerPage
     * @param $sTargetType
     *
     * @return array
     */
    public function SearchComments($sRegexp, $iCurrPage, $iPerPage, $sTargetType)
    {
        $sCacheKey = "search_comments_{$sRegexp}_{$iCurrPage}_{$iPerPage}_" . serialize($sTargetType);
        if (false === ($data = $this->Cache_Get($sCacheKey))) {
            $data = array(
                'collection' => $this->oMapper->SearchComments($sRegexp, $iCount, $iCurrPage, $iPerPage, $sTargetType),
                'count'      => $iCount
            );
            $this->Cache_Set($data, $sCacheKey, array('comment_new'), 60 * 60 * 24 * 1);
        }
        if ($data['collection']) {
            $data['collection'] = $this->Comment_GetCommentsAdditionalData($data['collection']);
        }
        return $data;
    }

    /**
     * Выделяет отрывки из текста с необходимыми словами (делает сниппеты)
     *
     * @param string $sText Исходный текст
     * @param array|string $aWords Список слов
     * @param array $aParams Список параметром
     *
     * @return string
     */
    public function BuildExcerpts($sText, $aWords, $aParams = array())
    {
        $iMaxLengthBetweenWords = isset($aParams['iMaxLengthBetweenWords']) ? $aParams['iMaxLengthBetweenWords'] : 200;
        $iLengthIndentSection = isset($aParams['iLengthIndentSection']) ? $aParams['iLengthIndentSection'] : 100;
        $iMaxCountSections = isset($aParams['iMaxCountSections']) ? $aParams['iMaxCountSections'] : 3;
        $sWordWrapBegin = isset($aParams['sWordWrapBegin']) ? $aParams['sWordWrapBegin'] : '<span class="searched-item">';
        $sWordWrapEnd = isset($aParams['sWordWrapEnd']) ? $aParams['sWordWrapEnd'] : '</span>';
        $sGlueSections = isset($aParams['sGlueSections']) ? $aParams['sGlueSections'] : "\r\n";

        $sText = strip_tags($sText);
        $sText = trim($sText);
        if (is_string($aWords)) {
            $aWords = preg_split('#[\W]+#u', $aWords);
        }
        $sPregWords = join('|', array_filter($aWords, 'preg_quote'));
        $aSections = array();
        if (preg_match_all("#{$sPregWords}#i", $sText, $aMatchAll, PREG_OFFSET_CAPTURE)) {
            $aSectionItems = array();
            $iCountDiff = -1;
            foreach ($aMatchAll[0] as $aMatch) {
                if ($iCountDiff == -1 or $aMatch[1] - $iCountDiff <= $iMaxLengthBetweenWords) {
                    $aSectionItems[] = $aMatch;
                    $iCountDiff = $aMatch[1];
                } else {
                    $aSections[] = array('items' => $aSectionItems);
                    $aSectionItems = array();
                    $aSectionItems[] = $aMatch;
                    $iCountDiff = $aMatch[1];
                }
            }
            if (count($aSectionItems)) {
                $aSections[] = array('items' => $aSectionItems);
            }
        }

        $aSections = array_slice($aSections, 0, $iMaxCountSections);

        $sTextResult = '';
        if ($aSections) {
            foreach ($aSections as $aSection) {
                /**
                 * Расчитываем дополнительные данные: начало и конец фрагмента, уникальный список слов
                 */
                $aItem = reset($aSection['items']);
                $aSection['begin'] = $aItem[1];
                $aItem = end($aSection['items']);
                $aSection['end'] = $aItem[1] + mb_strlen($aItem[0], 'utf-8');
                $aSection['words'] = array();

                foreach ($aSection['items'] as $aItem) {
                    $sKey = mb_strtolower($aItem[0], 'utf-8');
                    $aSection['words'][$sKey] = $aItem[0];
                }

                /**
                 * Формируем фрагменты текста
                 */

                /**
                 * Определям правую границу текста по слову
                 */
                $iEnd = $aSection['end'];
                for ($i = $iEnd; ($i <= $aSection['end'] + $iLengthIndentSection) and $i < mb_strlen($sText,
                    'utf-8'); $i++) {
                    if (preg_match('#^\s$#', mb_substr($sText, $i, 1, 'utf-8'))) {
                        $iEnd = $i;
                    }
                }
                /**
                 * Определям левую границу текста по слову
                 */
                $iBegin = $aSection['begin'];
                for ($i = $iBegin; ($i >= $aSection['begin'] - $iLengthIndentSection) and $i >= 0; $i--) {
                    if (preg_match('#^\s$#', mb_substr($sText, $i, 1, 'utf-8'))) {
                        $iBegin = $i;
                    }
                }
                /**
                 * Вырезаем фрагмент текста
                 */
                $sTextSection = trim(mb_substr($sText, $iBegin, $iEnd - $iBegin, 'utf-8'));
                if ($iBegin > 0) {
                    $sTextSection = '...' . $sTextSection;
                }
                if ($iEnd < mb_strlen($sText, 'utf-8')) {
                    $sTextSection .= '...';
                }
                $sTextSection = preg_replace("#{$sPregWords}#i", $sWordWrapBegin . '\\0' . $sWordWrapEnd,
                    $sTextSection);
                $sTextResult .= $sTextSection . $sGlueSections;
            }
        } else {
            $iLength = $iMaxLengthBetweenWords * 2;
            if ($iLength > mb_strlen($sText, 'utf-8')) {
                $iLength = mb_strlen($sText, 'utf-8');
            }
            $sTextResult = trim(mb_substr($sText, 0, $iLength - 1, 'utf-8'));
        }
        return $sTextResult;
    }

    /**
     * Возвращает массив слов из поискового запроса
     *
     * @param $sQuery
     *
     * @return array
     */
    public function GetWordsForSearch($sQuery)
    {
        /**
         * Удаляем запрещенные символы
         */
        $sQuery = preg_replace('#[^\w\sа-я\-]+#iu', ' ', $sQuery);
        /**
         * Разбиваем фразу на слова
         */
        $aWords = preg_split('#[\s]+#u', $sQuery);
        foreach ($aWords as $k => $sWord) {
            /**
             * Короткие слова удаляем
             */
            if (mb_strlen($sWord, 'utf-8') < 3) {
                unset($aWords[$k]);
            }
        }
        return $aWords;
    }

    /**
     * Возвращает регулярное выражение для поиска в БД по словам
     *
     * @param $aWords
     *
     * @return string
     */
    public function GetRegexpForWords($aWords)
    {
        return join('|', $aWords);
    }
}