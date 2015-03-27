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
 * Объект маппера для работы с БД
 *
 * @package application.modules.topic
 * @since 1.0
 */
class ModuleTopic_MapperTopic extends Mapper
{
    /**
     * Добавляет топик
     *
     * @param ModuleTopic_EntityTopic $oTopic Объект топика
     * @return int|bool
     */
    public function AddTopic(ModuleTopic_EntityTopic $oTopic)
    {
        $sql = "INSERT INTO " . Config::Get('db.table.topic') . "
			(blog_id,
			blog_id2,
			blog_id3,
			blog_id4,
			blog_id5,
			user_id,
			topic_type,
			topic_title,			
			topic_slug,
			topic_tags,
			topic_date_add,
			topic_date_publish,
			topic_user_ip,
			topic_publish,
			topic_publish_draft,
			topic_publish_index,
			topic_skip_index,
			topic_cut_text,
			topic_forbid_comment,			
			topic_text_hash			
			)
			VALUES(?d, ?d, ?d, ?d, ?d, ?d,	?,	?,	?,	?,  ?, ?, ?, ?d, ?d, ?d, ?d, ?, ?, ?)
		";
        if ($iId = $this->oDb->query($sql, $oTopic->getBlogId(), $oTopic->getBlogId2(), $oTopic->getBlogId3(),
            $oTopic->getBlogId4(), $oTopic->getBlogId5(), $oTopic->getUserId(), $oTopic->getType(), $oTopic->getTitle(), $oTopic->getSlug(),
            $oTopic->getTags(), $oTopic->getDateAdd(), $oTopic->getDatePublish(), $oTopic->getUserIp(), $oTopic->getPublish(),
            $oTopic->getPublishDraft(), $oTopic->getPublishIndex(), $oTopic->getSkipIndex(), $oTopic->getCutText(),
            $oTopic->getForbidComment(), $oTopic->getTextHash())
        ) {
            $oTopic->setId($iId);
            $this->AddTopicContent($oTopic);
            return $iId;
        }
        return false;
    }

    /**
     * Добавляет контент топика
     *
     * @param ModuleTopic_EntityTopic $oTopic Объект топика
     * @return int|bool
     */
    public function AddTopicContent(ModuleTopic_EntityTopic $oTopic)
    {
        $sql = "INSERT INTO " . Config::Get('db.table.topic_content') . "
			(topic_id,			
			topic_text,
			topic_text_short,
			topic_text_source,
			topic_extra			
			)
			VALUES(?d,  ?,	?,	?, ? )
		";
        if ($iId = $this->oDb->query($sql, $oTopic->getId(), $oTopic->getText(),
            $oTopic->getTextShort(), $oTopic->getTextSource(), $oTopic->getExtra())
        ) {
            return $iId;
        }
        return false;
    }

    /**
     * Добавление тега к топику
     *
     * @param ModuleTopic_EntityTopicTag $oTopicTag Объект тега топика
     * @return int
     */
    public function AddTopicTag(ModuleTopic_EntityTopicTag $oTopicTag)
    {
        $sql = "INSERT INTO " . Config::Get('db.table.topic_tag') . "
			(topic_id,
			user_id,
			blog_id,
			topic_tag_text		
			)
			VALUES(?d,  ?d,  ?d,	?)
		";
        if ($iId = $this->oDb->query($sql, $oTopicTag->getTopicId(), $oTopicTag->getUserId(), $oTopicTag->getBlogId(),
            $oTopicTag->getText())
        ) {
            return $iId;
        }
        return false;
    }

    /**
     * Удаление контента топика по его номеру
     *
     * @param int $iTopicId ID топика
     * @return bool
     */
    public function DeleteTopicContentByTopicId($iTopicId)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.topic_content') . " WHERE topic_id = ?d ";
        $res = $this->oDb->query($sql, $iTopicId);
        return $this->IsSuccessful($res);
    }

    /**
     * Удаляет теги у топика
     *
     * @param int $sTopicId ID топика
     * @return bool
     */
    public function DeleteTopicTagsByTopicId($sTopicId)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.topic_tag') . "
			WHERE
				topic_id = ?d				
		";
        $res = $this->oDb->query($sql, $sTopicId);
        return $this->IsSuccessful($res);
    }

    /**
     * Удаляет топик.
     * Если тип таблиц в БД InnoDB, то удалятся всё связи по топику(комменты,голосования,избранное)
     *
     * @param int $sTopicId Объект топика или ID
     * @return bool
     */
    public function DeleteTopic($sTopicId)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.topic') . "
			WHERE
				topic_id = ?d				
		";
        $res = $this->oDb->query($sql, $sTopicId);
        return $this->IsSuccessful($res);
    }

    /**
     * Получает топик по уникальному хешу(текст топика)
     *
     * @param int $sUserId
     * @param string $sHash
     * @return int|null
     */
    public function GetTopicUnique($sUserId, $sHash)
    {
        $sql = "SELECT topic_id FROM " . Config::Get('db.table.topic') . "
			WHERE 				
				topic_text_hash =? 						
				AND
				user_id = ?d
			LIMIT 0,1
				";
        if ($aRow = $this->oDb->selectRow($sql, $sHash, $sUserId)) {
            return $aRow['topic_id'];
        }
        return null;
    }

    /**
     * Получить список топиков по списку айдишников
     *
     * @param array $aArrayId Список ID топиков
     * @return array
     */
    public function GetTopicsByArrayId($aArrayId)
    {
        if (!is_array($aArrayId) or count($aArrayId) == 0) {
            return array();
        }

        $sql = "SELECT
					t.*,
					tc.*							 
				FROM 
					" . Config::Get('db.table.topic') . " as t
					JOIN  " . Config::Get('db.table.topic_content') . " AS tc ON t.topic_id=tc.topic_id
				WHERE 
					t.topic_id IN(?a) 									
				ORDER BY FIELD(t.topic_id,?a) ";
        $aTopics = array();
        if ($aRows = $this->oDb->select($sql, $aArrayId, $aArrayId)) {
            foreach ($aRows as $aTopic) {
                $aTopics[] = Engine::GetEntity('Topic', $aTopic);
            }
        }
        return $aTopics;
    }

    /**
     * Список топиков по фильтру
     *
     * @param  array $aFilter Фильтр
     * @param  int $iCount Возвращает общее число элементов
     * @param  int $iCurrPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @return array
     */
    public function GetTopics($aFilter, &$iCount, $iCurrPage, $iPerPage)
    {
        $sWhere = $this->buildFilter($aFilter);

        if (!isset($aFilter['order'])) {
            $aFilter['order'] = 't.topic_date_publish desc';
        }
        if (!is_array($aFilter['order'])) {
            $aFilter['order'] = array($aFilter['order']);
        }

        $sql = "SELECT
						t.topic_id							
					FROM 
						" . Config::Get('db.table.topic') . " as t,
						" . Config::Get('db.table.blog') . " as b
					WHERE 
						1=1					
						" . $sWhere . "
						AND
						t.blog_id=b.blog_id										
					ORDER BY " .
            implode(', ', $aFilter['order'])
            . "
					LIMIT ?d, ?d";
        $aTopics = array();
        if ($aRows = $this->oDb->selectPage($iCount, $sql, ($iCurrPage - 1) * $iPerPage, $iPerPage)) {
            foreach ($aRows as $aTopic) {
                $aTopics[] = $aTopic['topic_id'];
            }
        }
        return $aTopics;
    }

    /**
     * Количество топиков по фильтру
     *
     * @param array $aFilter Фильтр
     * @return int
     */
    public function GetCountTopics($aFilter)
    {
        $sWhere = $this->buildFilter($aFilter);
        $sql = "SELECT
					count(t.topic_id) as count									
				FROM 
					" . Config::Get('db.table.topic') . " as t,
					" . Config::Get('db.table.blog') . " as b
				WHERE 
					1=1
					
					" . $sWhere . "
					
					AND
					t.blog_id=b.blog_id;";
        if ($aRow = $this->oDb->selectRow($sql)) {
            return $aRow['count'];
        }
        return false;
    }

    /**
     * Возвращает все топики по фильтру
     *
     * @param array $aFilter Фильтр
     * @return array
     */
    public function GetAllTopics($aFilter)
    {
        $sWhere = $this->buildFilter($aFilter);

        if (!isset($aFilter['order'])) {
            $aFilter['order'] = 't.topic_id desc';
        }
        if (!is_array($aFilter['order'])) {
            $aFilter['order'] = array($aFilter['order']);
        }

        $sql = "SELECT
						t.topic_id							
					FROM 
						" . Config::Get('db.table.topic') . " as t,
						" . Config::Get('db.table.blog') . " as b
					WHERE 
						1=1					
						" . $sWhere . "
						AND
						t.blog_id=b.blog_id										
					ORDER by " . implode(', ', $aFilter['order']) . " ";
        $aTopics = array();
        if ($aRows = $this->oDb->select($sql)) {
            foreach ($aRows as $aTopic) {
                $aTopics[] = $aTopic['topic_id'];
            }
        }

        return $aTopics;
    }

    /**
     * Получает список топиков по тегу
     *
     * @param  string $sTag Тег
     * @param  array $aExcludeBlog Список ID блогов для исключения
     * @param  int $iCount Возвращает общее количество элементов
     * @param  int $iCurrPage Номер страницы
     * @param  int $iPerPage Количество элементов на страницу
     * @return array
     */
    public function GetTopicsByTag($sTag, $aExcludeBlog, &$iCount, $iCurrPage, $iPerPage)
    {
        $sql = "
							SELECT 		
								topic_id										
							FROM 
								" . Config::Get('db.table.topic_tag') . "
							WHERE 
								topic_tag_text = ? 	
								{ AND blog_id NOT IN (?a) }
                            ORDER BY topic_id DESC	
                            LIMIT ?d, ?d ";

        $aTopics = array();
        if ($aRows = $this->oDb->selectPage(
            $iCount, $sql, $sTag,
            (is_array($aExcludeBlog) && count($aExcludeBlog)) ? $aExcludeBlog : DBSIMPLE_SKIP,
            ($iCurrPage - 1) * $iPerPage, $iPerPage
        )
        ) {
            foreach ($aRows as $aTopic) {
                $aTopics[] = $aTopic['topic_id'];
            }
        }
        return $aTopics;
    }

    /**
     * Получает топики по рейтингу и дате
     *
     * @param string $sDate Дата
     * @param int $iLimit Количество
     * @param array $aExcludeBlog Список ID блогов для исключения
     * @return array
     */
    public function GetTopicsRatingByDate($sDate, $iLimit, $aExcludeBlog = array())
    {
        $sql = "SELECT
						t.topic_id										
					FROM 
						" . Config::Get('db.table.topic') . " as t
					WHERE 					
						t.topic_publish = 1
						AND
						t.topic_date_publish >= ?
						AND
						t.topic_rating >= 0
						{ AND t.blog_id NOT IN(?a) } 																	
					ORDER by t.topic_rating desc, t.topic_id desc
					LIMIT 0, ?d ";
        $aTopics = array();
        if ($aRows = $this->oDb->select(
            $sql, $sDate,
            (is_array($aExcludeBlog) && count($aExcludeBlog)) ? $aExcludeBlog : DBSIMPLE_SKIP,
            $iLimit
        )
        ) {
            foreach ($aRows as $aTopic) {
                $aTopics[] = $aTopic['topic_id'];
            }
        }
        return $aTopics;
    }

    /**
     * Получает список тегов топиков
     *
     * @param int $iLimit Количество
     * @param array $aExcludeTopic Список ID топиков для исключения
     * @return array
     */
    public function GetTopicTags($iLimit, $aExcludeTopic = array())
    {
        $sql = "SELECT
			tt.topic_tag_text,
			count(tt.topic_tag_text)	as count		 
			FROM 
				" . Config::Get('db.table.topic_tag') . " as tt
			WHERE 
				1=1
				{AND tt.topic_id NOT IN(?a) }		
			GROUP BY 
				tt.topic_tag_text
			ORDER BY 
				count desc		
			LIMIT 0, ?d
				";
        $aReturn = array();
        $aReturnSort = array();
        if ($aRows = $this->oDb->select(
            $sql,
            (is_array($aExcludeTopic) && count($aExcludeTopic)) ? $aExcludeTopic : DBSIMPLE_SKIP,
            $iLimit
        )
        ) {
            foreach ($aRows as $aRow) {
                $aReturn[mb_strtolower($aRow['topic_tag_text'], 'UTF-8')] = $aRow;
            }
            ksort($aReturn);
            foreach ($aReturn as $aRow) {
                $aReturnSort[] = Engine::GetEntity('Topic_TopicTag', $aRow);
            }
        }
        return $aReturnSort;
    }

    /**
     * Получает список тегов из топиков открытых блогов (open,personal)
     *
     * @param  int $iLimit Количество
     * @param  int|null $iUserId ID пользователя, чью теги получаем
     * @return array
     */
    public function GetOpenTopicTags($iLimit, $iUserId = null)
    {
        $sql = "
			SELECT 
				tt.topic_tag_text,
				count(tt.topic_tag_text)	as count		 
			FROM 
				" . Config::Get('db.table.topic_tag') . " as tt,
				" . Config::Get('db.table.blog') . " as b
			WHERE
				1 = 1
				{ AND tt.user_id = ?d }
				AND
				tt.blog_id = b.blog_id
				AND
				b.blog_type <> 'close'
			GROUP BY 
				tt.topic_tag_text
			ORDER BY 
				count desc		
			LIMIT 0, ?d
				";
        $aReturn = array();
        $aReturnSort = array();
        if ($aRows = $this->oDb->select($sql, is_null($iUserId) ? DBSIMPLE_SKIP : $iUserId, $iLimit)) {
            foreach ($aRows as $aRow) {
                $aReturn[mb_strtolower($aRow['topic_tag_text'], 'UTF-8')] = $aRow;
            }
            ksort($aReturn);
            foreach ($aReturn as $aRow) {
                $aReturnSort[] = Engine::GetEntity('Topic_TopicTag', $aRow);
            }
        }
        return $aReturnSort;
    }

    /**
     * Увеличивает у топика число комментов
     *
     * @param int $sTopicId ID топика
     * @return bool
     */
    public function increaseTopicCountComment($sTopicId)
    {
        $sql = "UPDATE " . Config::Get('db.table.topic') . "
			SET 
				topic_count_comment=topic_count_comment+1
			WHERE
				topic_id = ?
		";
        $res = $this->oDb->query($sql, $sTopicId);
        return $this->IsSuccessful($res);
    }

    /**
     * Обновляет топик
     *
     * @param ModuleTopic_EntityTopic $oTopic Объект топика
     * @return bool
     */
    public function UpdateTopic(ModuleTopic_EntityTopic $oTopic)
    {
        $sql = "UPDATE " . Config::Get('db.table.topic') . "
			SET 
				blog_id= ?d,
				blog_id2= ?d,
				blog_id3= ?d,
				blog_id4= ?d,
				blog_id5= ?d,
				topic_title= ?,
				topic_slug= ?,
				topic_tags= ?,
				topic_date_add = ?,
				topic_date_edit = ?,
				topic_date_edit_content = ?,
				topic_date_publish = ?,
				topic_user_ip= ?,
				topic_publish= ?d ,
				topic_publish_draft= ?d ,
				topic_publish_index= ?d,
				topic_skip_index= ?d,
				topic_rating= ?f,
				topic_count_vote= ?d,
				topic_count_vote_up= ?d,
				topic_count_vote_down= ?d,
				topic_count_vote_abstain= ?d,
				topic_count_read= ?d,
				topic_count_comment= ?d, 
				topic_count_favourite= ?d,
				topic_cut_text = ? ,
				topic_forbid_comment = ? ,
				topic_text_hash = ? 
			WHERE
				topic_id = ?d
		";
        $res = $this->oDb->query($sql, $oTopic->getBlogId(), $oTopic->getBlogId2(), $oTopic->getBlogId3(),
            $oTopic->getBlogId4(), $oTopic->getBlogId5(), $oTopic->getTitle(), $oTopic->getSlug(), $oTopic->getTags(),
            $oTopic->getDateAdd(), $oTopic->getDateEdit(), $oTopic->getDateEditContent(), $oTopic->getDatePublish(), $oTopic->getUserIp(),
            $oTopic->getPublish(), $oTopic->getPublishDraft(), $oTopic->getPublishIndex(), $oTopic->getSkipIndex(),
            $oTopic->getRating(), $oTopic->getCountVote(), $oTopic->getCountVoteUp(), $oTopic->getCountVoteDown(),
            $oTopic->getCountVoteAbstain(), $oTopic->getCountRead(), $oTopic->getCountComment(),
            $oTopic->getCountFavourite(), $oTopic->getCutText(), $oTopic->getForbidComment(), $oTopic->getTextHash(),
            $oTopic->getId());
        if ($res !== false and !is_null($res)) {
            $this->UpdateTopicContent($oTopic);
            return true;
        }
        return false;
    }

    /**
     * Обновляет контент топика
     *
     * @param ModuleTopic_EntityTopic $oTopic Объект топика
     * @return bool
     */
    public function UpdateTopicContent(ModuleTopic_EntityTopic $oTopic)
    {
        $sql = "UPDATE " . Config::Get('db.table.topic_content') . "
			SET 				
				topic_text= ?,
				topic_text_short= ?,
				topic_text_source= ?,
				topic_extra= ?
			WHERE
				topic_id = ?d
		";
        $res = $this->oDb->query($sql, $oTopic->getText(), $oTopic->getTextShort(), $oTopic->getTextSource(),
            $oTopic->getExtra(), $oTopic->getId());
        return $this->IsSuccessful($res);
    }

    /**
     * Строит строку условий для SQL запроса топиков
     *
     * @param array $aFilter Фильтр
     * @return string
     */
    protected function buildFilter($aFilter)
    {
        $sDateNow=date('Y-m-d H:i:s');
        $sWhere = '';
        if (isset($aFilter['topic_date_more'])) {
            $sWhere .= " AND t.topic_date_publish >  " . $this->oDb->escape($aFilter['topic_date_more']);
        }
        if (isset($aFilter['topic_slug'])) {
            $sWhere .= " AND t.topic_slug =  " . $this->oDb->escape($aFilter['topic_slug']);
        }
        if (isset($aFilter['topic_publish'])) {
            $sWhere .= " AND t.topic_publish =  " . (int)$aFilter['topic_publish'] . " AND t.topic_date_publish <= '{$sDateNow}' ";
        }
        if (isset($aFilter['topic_rating']) and is_array($aFilter['topic_rating'])) {
            $sPublishIndex = '';
            if (isset($aFilter['topic_rating']['publish_index']) and $aFilter['topic_rating']['publish_index'] == 1) {
                $sPublishIndex = " or topic_publish_index = 1 ) and ( topic_skip_index = 0 ";
            }
            if ($aFilter['topic_rating']['type'] == 'top') {
                $sWhere .= " AND ( t.topic_rating >= " . (float)$aFilter['topic_rating']['value'] . " {$sPublishIndex} ) ";
            } else {
                $sWhere .= " AND ( t.topic_rating < " . (float)$aFilter['topic_rating']['value'] . "  ) ";
            }
        }
        if (isset($aFilter['topic_new'])) {
            $sWhere .= " AND t.topic_date_publish >=  '" . $aFilter['topic_new'] . "'";
        }
        if (isset($aFilter['user_id'])) {
            $sWhere .= is_array($aFilter['user_id'])
                ? " AND t.user_id IN(" . implode(', ', $aFilter['user_id']) . ")"
                : " AND t.user_id =  " . (int)$aFilter['user_id'];
        }
        if (isset($aFilter['blog_id'])) {
            if (!is_array($aFilter['blog_id'])) {
                $aFilter['blog_id'] = array($aFilter['blog_id']);
            }
            $sBlogList = join("','", $aFilter['blog_id']);
            $sWhere .= " AND ( t.blog_id IN ('{$sBlogList}') ";
            $sWhere .= " OR t.blog_id2 IN ('{$sBlogList}') ";
            $sWhere .= " OR t.blog_id3 IN ('{$sBlogList}') ";
            $sWhere .= " OR t.blog_id4 IN ('{$sBlogList}') ";
            $sWhere .= " OR t.blog_id5 IN ('{$sBlogList}') ) ";
        }
        if (isset($aFilter['blog_type']) and is_array($aFilter['blog_type'])) {
            $aBlogTypes = array();
            foreach ($aFilter['blog_type'] as $sType => $aBlogId) {
                /**
                 * Позиция вида 'type'=>array('id1', 'id2')
                 */
                if (!is_array($aBlogId) && is_string($sType)) {
                    $aBlogId = array($aBlogId);
                }
                /**
                 * Позиция вида 'type'
                 */
                if (is_string($aBlogId) && is_int($sType)) {
                    $sType = $aBlogId;
                    $aBlogId = array();
                }

                $aBlogTypes[] = (count($aBlogId) == 0)
                    ? "(b.blog_type='" . $sType . "')"
                    : "(b.blog_type='" . $sType . "' AND t.blog_id IN ('" . join("','", $aBlogId) . "'))";
            }
            $sWhere .= " AND (" . join(" OR ", (array)$aBlogTypes) . ")";
        }
        if (isset($aFilter['topic_type'])) {
            if (!is_array($aFilter['topic_type'])) {
                $aFilter['topic_type'] = array($aFilter['topic_type']);
            }
            $sWhere .= " AND t.topic_type IN (" . join(",",
                    array_map(array($this->oDb, 'escape'), $aFilter['topic_type'])) . ")";
        }
        return $sWhere;
    }

    /**
     * Получает список тегов по первым буквам тега
     *
     * @param string $sTag Тэг
     * @param int $iLimit Количество
     * @return bool
     */
    public function GetTopicTagsByLike($sTag, $iLimit)
    {
        $sTag = mb_strtolower($sTag, "UTF-8");
        $sql = "SELECT
				topic_tag_text					 
			FROM 
				" . Config::Get('db.table.topic_tag') . "
			WHERE
				topic_tag_text LIKE ?			
			GROUP BY 
				topic_tag_text					
			LIMIT 0, ?d		
				";
        $aReturn = array();
        if ($aRows = $this->oDb->select($sql, $sTag . '%', $iLimit)) {
            foreach ($aRows as $aRow) {
                $aReturn[] = Engine::GetEntity('Topic_TopicTag', $aRow);
            }
        }
        return $aReturn;
    }

    /**
     * Обновляем дату прочтения топика
     *
     * @param ModuleTopic_EntityTopicRead $oTopicRead Объект факта чтения топика
     * @return int
     */
    public function UpdateTopicRead(ModuleTopic_EntityTopicRead $oTopicRead)
    {
        $sql = "UPDATE " . Config::Get('db.table.topic_read') . "
			SET 
				comment_count_last = ? ,
				comment_id_last = ? ,
				date_read = ? 
			WHERE
				topic_id = ? 
				AND				
				user_id = ? 
		";
        $res = $this->oDb->query($sql, $oTopicRead->getCommentCountLast(), $oTopicRead->getCommentIdLast(),
            $oTopicRead->getDateRead(), $oTopicRead->getTopicId(), $oTopicRead->getUserId());
        return $this->IsSuccessful($res);
    }

    /**
     * Устанавливаем дату прочтения топика
     *
     * @param ModuleTopic_EntityTopicRead $oTopicRead Объект факта чтения топика
     * @return bool
     */
    public function AddTopicRead(ModuleTopic_EntityTopicRead $oTopicRead)
    {
        $sql = "INSERT INTO " . Config::Get('db.table.topic_read') . "
			SET 
				comment_count_last = ? ,
				comment_id_last = ? ,
				date_read = ? ,
				topic_id = ? ,							
				user_id = ? 
		";
        return $this->oDb->query($sql, $oTopicRead->getCommentCountLast(), $oTopicRead->getCommentIdLast(),
            $oTopicRead->getDateRead(), $oTopicRead->getTopicId(), $oTopicRead->getUserId());
    }

    /**
     * Удаляет записи о чтении записей по списку идентификаторов
     *
     * @param  array $aTopicId Список ID топиков
     * @return bool
     */
    public function DeleteTopicReadByArrayId($aTopicId)
    {
        $sql = "
			DELETE FROM " . Config::Get('db.table.topic_read') . "
			WHERE
				topic_id IN(?a)				
		";
        $res = $this->oDb->query($sql, $aTopicId);
        return $this->IsSuccessful($res);
    }

    /**
     * Получить список просмотром/чтения топиков по списку айдишников
     *
     * @param array $aArrayId Список ID топиков
     * @param int $sUserId ID пользователя
     * @return array
     */
    public function GetTopicsReadByArray($aArrayId, $sUserId)
    {
        if (!is_array($aArrayId) or count($aArrayId) == 0) {
            return array();
        }

        $sql = "SELECT
					t.*							 
				FROM 
					" . Config::Get('db.table.topic_read') . " as t
				WHERE 
					t.topic_id IN(?a)
					AND
					t.user_id = ?d 
				";
        $aReads = array();
        if ($aRows = $this->oDb->select($sql, $aArrayId, $sUserId)) {
            foreach ($aRows as $aRow) {
                $aReads[] = Engine::GetEntity('Topic_TopicRead', $aRow);
            }
        }
        return $aReads;
    }

    /**
     * Перемещает топики в другой блог
     *
     * @param  int $sBlogId ID старого блога
     * @param  int $sBlogIdNew ID нового блога
     * @return bool
     */
    public function MoveTopics($sBlogId, $sBlogIdNew)
    {
        $aFields=array('blog_id','blog_id2','blog_id3','blog_id4','blog_id5');
        foreach($aFields as $sField) {
            $sql = "UPDATE " . Config::Get('db.table.topic') . "
                SET
                    {$sField} = ?d
                WHERE
                    {$sField} = ?d
		    ";
            $this->oDb->query($sql, $sBlogIdNew, $sBlogId);
        }

        return true;
    }

    /**
     * Перемещает теги топиков в другой блог
     *
     * @param int $sBlogId ID старого блога
     * @param int $sBlogIdNew ID нового блога
     * @return bool
     */
    public function MoveTopicsTags($sBlogId, $sBlogIdNew)
    {
        $sql = "UPDATE " . Config::Get('db.table.topic_tag') . "
			SET 
				blog_id= ?d
			WHERE
				blog_id = ?d
		";
        $res = $this->oDb->query($sql, $sBlogIdNew, $sBlogId);
        return $this->IsSuccessful($res);
    }

    /**
     * Пересчитывает счетчик избранных топиков
     *
     * @return bool
     */
    public function RecalculateFavourite()
    {
        $sql = "
                UPDATE " . Config::Get('db.table.topic') . " t
                SET t.topic_count_favourite = (
                    SELECT count(f.user_id)
                    FROM " . Config::Get('db.table.favourite') . " f
                    WHERE 
                        f.target_id = t.topic_id
                    AND
                        f.target_publish = 1
                    AND
                        f.target_type = 'topic'
                )
            ";
        $res = $this->oDb->query($sql);
        return $this->IsSuccessful($res);
    }

    /**
     * Пересчитывает счетчики голосований
     *
     * @return bool
     */
    public function RecalculateVote()
    {
        $sql = "
                UPDATE " . Config::Get('db.table.topic') . " t
                SET t.topic_count_vote_up = (
                    SELECT count(*)
                    FROM " . Config::Get('db.table.vote') . " v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = 1
                    AND
                        v.target_type = 'topic'
                ), t.topic_count_vote_down = (
                    SELECT count(*)
                    FROM " . Config::Get('db.table.vote') . " v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = -1
                    AND
                        v.target_type = 'topic'
                ), t.topic_count_vote_abstain = (
                    SELECT count(*)
                    FROM " . Config::Get('db.table.vote') . " v
                    WHERE
                        v.target_id = t.topic_id
                    AND
                        v.vote_direction = 0
                    AND
                        v.target_type = 'topic'
                )
            ";
        $res = $this->oDb->query($sql);
        return $this->IsSuccessful($res);
    }


    public function GetTopicTypeByCode($sCode)
    {
        $sql = 'SELECT * FROM ' . Config::Get('db.table.topic_type') . ' WHERE code = ?';
        if ($aRow = $this->oDb->selectRow($sql, $sCode)) {
            return Engine::GetEntity('ModuleTopic_EntityTopicType', $aRow);
        }
        return null;
    }

    public function GetTopicTypeById($iId)
    {
        $sql = 'SELECT * FROM ' . Config::Get('db.table.topic_type') . ' WHERE id = ?d';
        if ($aRow = $this->oDb->selectRow($sql, $iId)) {
            return Engine::GetEntity('ModuleTopic_EntityTopicType', $aRow);
        }
        return null;
    }

    public function AddTopicType($oType)
    {
        $sql = "INSERT INTO " . Config::Get('db.table.topic_type') . "
			(name,
			name_many,
			code,
			allow_remove,
			date_create,
			state,
			params
			)
			VALUES(?,  ?,	?,	?d,	?,	?d,	?)
		";
        if ($iId = $this->oDb->query($sql, $oType->getName(), $oType->getNameMany(), $oType->getCode(),
            $oType->getAllowRemove(),
            $oType->getDateCreate(), $oType->getState(), $oType->getParams())
        ) {
            return $iId;
        }
        return false;
    }

    public function GetTopicTypeItems($aFilter = array())
    {
        if (isset($aFilter['code_not']) and !is_array($aFilter['code_not'])) {
            $aFilter['code_not'] = array($aFilter['code_not']);
        }
        $sql = "SELECT
				*
			FROM
				" . Config::Get('db.table.topic_type') . "
			WHERE
				1 = 1
				{ and `state` = ?d }
				{ and `code` not IN (?a) }
			ORDER BY sort desc
			LIMIT 0, 500
				";
        $aReturn = array();
        if ($aRows = $this->oDb->select($sql,
            isset($aFilter['state']) ? $aFilter['state'] : DBSIMPLE_SKIP,
            (isset($aFilter['code_not']) and $aFilter['code_not']) ? $aFilter['code_not'] : DBSIMPLE_SKIP
        )
        ) {
            foreach ($aRows as $aRow) {
                $aReturn[] = Engine::GetEntity('ModuleTopic_EntityTopicType', $aRow);
            }
        }
        return $aReturn;
    }

    public function UpdateTopicType($oType)
    {
        $sql = "UPDATE " . Config::Get('db.table.topic_type') . "
			SET
				name= ?,
				name_many= ?,
				code= ?,
				state= ?d,
				sort= ?d,
				params= ?
			WHERE
				id = ?d
		";
        $res = $this->oDb->query($sql, $oType->getName(), $oType->getNameMany(), $oType->getCode(), $oType->getState(),
            $oType->getSort(), $oType->getParams(), $oType->getId());
        return $this->IsSuccessful($res);
    }

    public function DeleteTopicType($sTypeId)
    {
        $sql = "DELETE FROM " . Config::Get('db.table.topic_type') . "
			WHERE
				id = ?d
		";
        $res = $this->oDb->query($sql, $sTypeId);
        return $this->IsSuccessful($res);
    }

    public function UpdateTopicByType($sType, $sTypeNew)
    {
        $sql = "UPDATE
                 " . Config::Get('db.table.topic') . "
                SET topic_type = ?
                WHERE
                	topic_type = ?
                	";
        if ($this->oDb->query($sql, $sTypeNew, $sType) !== false) {
            return true;
        }
        return false;
    }
}