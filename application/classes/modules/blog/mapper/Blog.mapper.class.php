<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
 * Маппер для работы с БД по части блогов
 *
 * @package modules.blog
 * @since 1.0
 */
class ModuleBlog_MapperBlog extends Mapper {
	/**
	 * Добавляет блог в БД
	 *
	 * @param ModuleBlog_EntityBlog $oBlog	Объект блога
	 * @return int|bool
	 */
	public function AddBlog(ModuleBlog_EntityBlog $oBlog) {
		$sql = "INSERT INTO ".Config::Get('db.table.blog')." 
			(user_owner_id,
			blog_title,
			blog_description,
			blog_type,			
			category_id,
			blog_date_add,
			blog_limit_rating_topic,
			blog_url,
			blog_avatar
			)
			VALUES(?d,  ?,	?,	?, ?,  ?,	?, ?, ?)
		";
		if ($iId=$this->oDb->query($sql,$oBlog->getOwnerId(),$oBlog->getTitle(),$oBlog->getDescription(),$oBlog->getType(),$oBlog->getCategoryId(),$oBlog->getDateAdd(),$oBlog->getLimitRatingTopic(),$oBlog->getUrl(),$oBlog->getAvatar())) {
			return $iId;
		}
		return false;
	}
	/**
	 * Обновляет блог в БД
	 *
	 * @param ModuleBlog_EntityBlog $oBlog	Объект блога
	 * @return bool
	 */
	public function UpdateBlog(ModuleBlog_EntityBlog $oBlog) {
		$sql = "UPDATE ".Config::Get('db.table.blog')." 
			SET 
				blog_title= ?,
				blog_description= ?,
				blog_type= ?,
				category_id= ?,
				blog_date_edit= ?,
				blog_rating= ?f,
				blog_count_vote = ?d,
				blog_count_user= ?d,
				blog_count_topic= ?d,
				blog_limit_rating_topic= ?f ,
				blog_url= ?,
				blog_avatar= ?
			WHERE
				blog_id = ?d
		";
		$res=$this->oDb->query($sql,$oBlog->getTitle(),$oBlog->getDescription(),$oBlog->getType(),$oBlog->getCategoryId(),$oBlog->getDateEdit(),$oBlog->getRating(),$oBlog->getCountVote(),$oBlog->getCountUser(),$oBlog->getCountTopic(),$oBlog->getLimitRatingTopic(),$oBlog->getUrl(),$oBlog->getAvatar(),$oBlog->getId());
		return $res===false or is_null($res) ? false : true;
	}
	/**
	 * Получает список блогов по ID
	 *
	 * @param array $aArrayId	Список ID блогов
	 * @param array|null $aOrder	Сортировка блогов
	 * @return array
	 */
	public function GetBlogsByArrayId($aArrayId,$aOrder=null) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		if (!is_array($aOrder)) $aOrder=array($aOrder);
		$sOrder='';
		foreach ($aOrder as $key=>$value) {
			$value=(string)$value;
			if (!in_array($key,array('blog_id','blog_title','blog_type','blog_rating','blog_count_user','blog_date_add'))) {
				unset($aOrder[$key]);
			} elseif (in_array($value,array('asc','desc'))) {
				$sOrder.=" {$key} {$value},";
			}
		}
		$sOrder=trim($sOrder,',');

		$sql = "SELECT 
					*							 
				FROM 
					".Config::Get('db.table.blog')."
				WHERE 
					blog_id IN(?a) 		
				ORDER BY 						
					{ FIELD(blog_id,?a) } ";
		if ($sOrder!='') $sql.=$sOrder;

		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sOrder=='' ? $aArrayId : DBSIMPLE_SKIP)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=Engine::GetEntity('Blog',$aBlog);
			}
		}
		return $aBlogs;
	}

	/**
	 * Получает список категорий блогов
	 *
	 * @param int|null|bool $iPid ID родительской категории, если false, то не учитывается в выборке
	 * @return array
	 */
	public function GetCategoriesByPid($iPid) {
		$sql = "SELECT
					*
				FROM
					".Config::Get('db.table.blog_category')."
				WHERE
					1 = 1
					{ AND pid = ?d }
					{ AND pid IS NULL and 1=?d }
				ORDER by title asc
				";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$iPid ? $iPid : DBSIMPLE_SKIP,is_null($iPid) ? 1 : DBSIMPLE_SKIP)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('ModuleBlog_EntityBlogCategory',$aRow);
			}
		}
		return $aReturn;
	}

	/**
	 * Возвращает список категорий с учетом вложенности
	 *
	 * @return array|null
	 */
	public function GetCategoriesTree() {
		$sql = "SELECT
					*,
					id as ARRAY_KEY,
					pid as PARENT_KEY
				FROM
					".Config::Get('db.table.blog_category')."
				ORDER by sort desc;
					";
		if ($aRows=$this->oDb->select($sql)) {
			return $aRows;
		}
		return null;
	}
	/**
	 * Получает категорию по полному урлу
	 *
	 * @param string $sUrl УРЛ
	 * @return ModuleBlog_EntityBlogCategory|null
	 */
	public function GetCategoryByUrlFull($sUrl) {
		$sql = "SELECT * FROM ".Config::Get('db.table.blog_category')." WHERE url_full = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sUrl)) {
			return Engine::GetEntity('ModuleBlog_EntityBlogCategory',$aRow);
		}
		return null;
	}
	/**
	 * Получает категорию по ID
	 *
	 * @param int $iId УРЛ
	 * @return ModuleBlog_EntityBlogCategory|null
	 */
	public function GetCategoryById($iId) {
		$sql = "SELECT * FROM ".Config::Get('db.table.blog_category')." WHERE id = ?d ";
		if ($aRow=$this->oDb->selectRow($sql,$iId)) {
			return Engine::GetEntity('ModuleBlog_EntityBlogCategory',$aRow);
		}
		return null;
	}

	/**
	 * Получает следующую категорию по сортировке
	 *
	 * @param $iSort
	 * @param $sPid
	 * @param $sWay
	 *
	 * @return ModuleBlog_EntityBlogCategory|null
	 */
	public function GetNextCategoryBySort($iSort,$sPid,$sWay) {
		if ($sWay=='up') {
			$sWay='>';
			$sOrder='asc';
		} else {
			$sWay='<';
			$sOrder='desc';
		}
		$sPidNULL='';
		if (is_null($sPid)) {
			$sPidNULL='pid IS NULL and';
		}
		$sql = "SELECT * FROM ".Config::Get('db.table.blog_category')." WHERE { pid = ? and } {$sPidNULL} sort {$sWay} ? order by sort {$sOrder} limit 0,1";
		if ($aRow=$this->oDb->selectRow($sql,is_null($sPid) ? DBSIMPLE_SKIP : $sPid, $iSort)) {
			return Engine::GetEntity('ModuleBlog_EntityBlogCategory',$aRow);
		}
		return null;
	}

	/**
	 * Возвращает максимальное значение сортировки для родительской категории
	 *
	 * @param int|null $sPid
	 *
	 * @return int
	 */
	public function GetCategoryMaxSortByPid($sPid) {
		$sql = "SELECT max(sort) as max_sort FROM ".Config::Get('db.table.blog_category')." WHERE 1=1 { and pid = ? } { and pid IS NULL and 1=?d } ";
		if ($aRow=$this->oDb->selectRow($sql,is_null($sPid) ? DBSIMPLE_SKIP : $sPid,!is_null($sPid) ? DBSIMPLE_SKIP : 1)) {
			return $aRow['max_sort'];
		}
		return 0;
	}
	/**
	 * Обновление категории
	 *
	 * @param ModuleBlog_EntityBlogCategory $oObject Объект категории
	 *
	 * @return bool
	 */
	public function UpdateCategory($oObject) {
		$sql = "UPDATE ".Config::Get('db.table.blog_category')." SET ?a WHERE id = ?d ";
		$res=$this->oDb->query($sql,$oObject->_getData(array('pid','title','url','url_full','sort','count_blogs')),$oObject->getId());
		return $res===false or is_null($res) ? false : true;
	}

	/**
	 * Добавление категории
	 *
	 * @param ModuleBlog_EntityBlogCategory $oObject
	 *
	 * @return int|bool
	 */
	public function AddCategory($oObject) {
		$sql = "INSERT INTO ".Config::Get('db.table.blog_category')." SET ?a ";
		if ($iId=$this->oDb->query($sql,$oObject->_getData())) {
			return $iId;
		}
		return false;
	}

	/**
	 * Возвращает количество категорий
	 *
	 * @return int
	 */
	public function GetCountCategories() {
		$sql = "SELECT count(*) as count FROM ".Config::Get('db.table.blog_category')." ";
		if ($aRow=$this->oDb->selectRow($sql)) {
			return $aRow['count'];
		}
		return 0;
	}
	/**
	 * Увеличивает количество блогов у категории
	 *
	 * @param int $sId	ID категории
	 * @return bool
	 */
	public function IncreaseCategoryCountBlogs($sId) {
		$sql = "UPDATE ".Config::Get('db.table.blog_category')."
			SET
				count_blogs=count_blogs+1
			WHERE
				id = ?
		";
		$res=$this->oDb->query($sql,$sId);
		return $res===false or is_null($res) ? false : true;
	}
	/**
	 * Уменьшает количество блогов у категории
	 *
	 * @param int $sId	ID категории
	 * @return bool
	 */
	public function DecreaseCategoryCountBlogs($sId) {
		$sql = "UPDATE ".Config::Get('db.table.blog_category')."
			SET
				count_blogs=count_blogs-1
			WHERE
				id = ?
		";
		$res=$this->oDb->query($sql,$sId);
		return $res===false or is_null($res) ? false : true;
	}
	/**
	 * Удаляет категории по списку их ID
	 *
	 * @param array $aArrayId Список ID категорий
	 *
	 * @return bool
	 */
	public function DeleteCategoryByArrayId($aArrayId) {
		if (!is_array($aArrayId)) {
			$aArrayId=array($aArrayId);
		}
		$sql = "DELETE FROM ".Config::Get('db.table.blog_category')."
			WHERE
				id IN (?a)
		";
		$res=$this->oDb->query($sql,$aArrayId);
		return $res===false or is_null($res) ? false : true;
	}
	/**
	 * Заменяет категорию на новую у блогов
	 *
	 * @param int|array|null $iIdOld Старая категори
	 * @param int|null $iIdNew Новая категория
	 *
	 * @return bool
	 */
	public function ReplaceBlogsCategoryByCategoryId($iIdOld,$iIdNew) {
		if (!is_null($iIdOld) and !is_array($iIdOld)) {
			$iIdOld=array($iIdOld);
		}
		$sql = "UPDATE ".Config::Get('db.table.blog')."
			SET
				category_id = ?
			WHERE
				1 = 1
				{ and category_id IN ( ?a ) }
				{ and category_id IS NULL and 1 = ?d }
		";
		$res=$this->oDb->query($sql,$iIdNew,is_null($iIdOld) ? DBSIMPLE_SKIP : $iIdOld,!is_null($iIdOld) ? DBSIMPLE_SKIP : 1);
		return $res===false or is_null($res) ? false : true;
	}
	/**
	 * Добавляет свзяь пользователя с блогом в БД
	 *
	 * @param ModuleBlog_EntityBlogUser $oBlogUser	Объект отношения пользователя с блогом
	 * @return bool
	 */
	public function AddRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {
		$sql = "INSERT INTO ".Config::Get('db.table.blog_user')." 
			(blog_id,
			user_id,
			user_role
			)
			VALUES(?d,  ?d, ?d)
		";
		if ($this->oDb->query($sql,$oBlogUser->getBlogId(),$oBlogUser->getUserId(),$oBlogUser->getUserRole())===0) {
			return true;
		}
		return false;
	}
	/**
	 * Удаляет отношение пользователя с блогом
	 *
	 * @param ModuleBlog_EntityBlogUser $oBlogUser	Объект отношения пользователя с блогом
	 * @return bool
	 */
	public function DeleteRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {
		$sql = "DELETE FROM ".Config::Get('db.table.blog_user')." 
			WHERE
				blog_id = ?d
				AND
				user_id = ?d
		";
		$res=$this->oDb->query($sql,$oBlogUser->getBlogId(),$oBlogUser->getUserId());
		return $res===false or is_null($res) ? false : true;
	}
	/**
	 * Обновляет отношение пользователя с блогом
	 *
	 * @param ModuleBlog_EntityBlogUser $oBlogUser	Объект отношения пользователя с блогом
	 * @return bool
	 */
	public function UpdateRelationBlogUser(ModuleBlog_EntityBlogUser $oBlogUser) {
		$sql = "UPDATE ".Config::Get('db.table.blog_user')." 
			SET 
				user_role = ?d			
			WHERE
				blog_id = ?d 
				AND
				user_id = ?d
		";
		$res=$this->oDb->query($sql,$oBlogUser->getUserRole(),$oBlogUser->getBlogId(),$oBlogUser->getUserId());
		return $res===false or is_null($res) ? false : true;
	}
	/**
	 * Получает список отношений пользователей с блогами
	 *
	 * @param array $aFilter	Фильтр поиска отношений
	 * @param int $iCount	Возвращает общее количество элементов
	 * @param int $iCurrPage	Номер текущейс страницы
	 * @param int $iPerPage		Количество элементов на одну страницу
	 * @return array
	 */
	public function GetBlogUsers($aFilter,&$iCount=null,$iCurrPage=null,$iPerPage=null) {
		$sWhere=' 1=1 ';
		if (isset($aFilter['blog_id'])) {
			$sWhere.=" AND bu.blog_id =  ".(int)$aFilter['blog_id'];
		}
		if (isset($aFilter['user_id'])) {
			$sWhere.=" AND bu.user_id =  ".(int)$aFilter['user_id'];
		}
		if (isset($aFilter['user_role'])) {
			if(!is_array($aFilter['user_role'])) {
				$aFilter['user_role']=array($aFilter['user_role']);
			}
			$sWhere.=" AND bu.user_role IN ('".join("', '",$aFilter['user_role'])."')";
		} else {
			$sWhere.=" AND bu.user_role>".ModuleBlog::BLOG_USER_ROLE_GUEST;
		}

		$sql = "SELECT
					bu.*				
				FROM 
					".Config::Get('db.table.blog_user')." as bu
				WHERE 
					".$sWhere." ";

		if (is_null($iCurrPage)) {
			$aRows=$this->oDb->select($sql);
		} else {
			$sql.=" LIMIT ?d, ?d ";
			$aRows=$this->oDb->selectPage($iCount,$sql,($iCurrPage-1)*$iPerPage, $iPerPage);
		}

		$aBlogUsers=array();
		if ($aRows) {
			foreach ($aRows as $aUser) {
				$aBlogUsers[]=Engine::GetEntity('Blog_BlogUser',$aUser);
			}
		}
		return $aBlogUsers;
	}
	/**
	 * Получает список отношений пользователя к блогам
	 *
	 * @param array $aArrayId Список ID блогов
	 * @param int $sUserId ID блогов
	 * @return array
	 */
	public function GetBlogUsersByArrayBlog($aArrayId,$sUserId) {
		if (!is_array($aArrayId) or count($aArrayId)==0) {
			return array();
		}

		$sql = "SELECT 
					bu.*				
				FROM 
					".Config::Get('db.table.blog_user')." as bu
				WHERE 
					bu.blog_id IN(?a) 					
					AND
					bu.user_id = ?d ";
		$aBlogUsers=array();
		if ($aRows=$this->oDb->select($sql,$aArrayId,$sUserId)) {
			foreach ($aRows as $aUser) {
				$aBlogUsers[]=Engine::GetEntity('Blog_BlogUser',$aUser);
			}
		}
		return $aBlogUsers;
	}
	/**
	 * Получает ID персонального блога пользователя
	 *
	 * @param int $sUserId ID пользователя
	 * @return int|null
	 */
	public function GetPersonalBlogByUserId($sUserId) {
		$sql = "SELECT blog_id FROM ".Config::Get('db.table.blog')." WHERE user_owner_id = ?d and blog_type='personal'";
		if ($aRow=$this->oDb->selectRow($sql,$sUserId)) {
			return $aRow['blog_id'];
		}
		return null;
	}
	/**
	 * Получает блог по названию
	 *
	 * @param string $sTitle Нащвание блога
	 * @return ModuleBlog_EntityBlog|null
	 */
	public function GetBlogByTitle($sTitle) {
		$sql = "SELECT blog_id FROM ".Config::Get('db.table.blog')." WHERE blog_title = ? ";
		if ($aRow=$this->oDb->selectRow($sql,$sTitle)) {
			return $aRow['blog_id'];
		}
		return null;
	}
	/**
	 * Получает блог по URL
	 *
	 * @param string $sUrl URL блога
	 * @return ModuleBlog_EntityBlog|null
	 */
	public function GetBlogByUrl($sUrl) {
		$sql = "SELECT 
				b.blog_id 
			FROM 
				".Config::Get('db.table.blog')." as b
			WHERE 
				b.blog_url = ? 		
				";
		if ($aRow=$this->oDb->selectRow($sql,$sUrl)) {
			return $aRow['blog_id'];
		}
		return null;
	}
	/**
	 * Получить список блогов по хозяину
	 *
	 * @param int $sUserId ID пользователя
	 * @return array
	 */
	public function GetBlogsByOwnerId($sUserId) {
		$sql = "SELECT 
			b.blog_id			 
			FROM 
				".Config::Get('db.table.blog')." as b				
			WHERE 
				b.user_owner_id = ? 
				AND
				b.blog_type<>'personal'				
				";
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql,$sUserId)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=$aBlog['blog_id'];
			}
		}
		return $aBlogs;
	}
	/**
	 * Возвращает список всех не персональных блогов
	 *
	 * @return array
	 */
	public function GetBlogs() {
		$sql = "SELECT 
			b.blog_id			 
			FROM 
				".Config::Get('db.table.blog')." as b				
			WHERE 				
				b.blog_type<>'personal'				
				";
		$aBlogs=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aBlog) {
				$aBlogs[]=$aBlog['blog_id'];
			}
		}
		return $aBlogs;
	}
	/**
	 * Возвращает список не персональных блогов с сортировкой по рейтингу
	 *
	 * @param int $iCount Возвращает общее количество элементов
	 * @param int $iCurrPage	Номер текущей страницы
	 * @param int $iPerPage		Количество элементов на одну страницу
	 * @return array
	 */
	public function GetBlogsRating(&$iCount,$iCurrPage,$iPerPage) {
		$sql = "SELECT 
					b.blog_id													
				FROM 
					".Config::Get('db.table.blog')." as b 									 
				WHERE 									
					b.blog_type<>'personal'									
				ORDER by b.blog_rating desc
				LIMIT ?d, ?d 	";
		$aReturn=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,($iCurrPage-1)*$iPerPage, $iPerPage)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['blog_id'];
			}
		}
		return $aReturn;
	}
	/**
	 * Получает список блогов в которых состоит пользователь
	 *
	 * @param int $sUserId ID пользователя
	 * @param int $iLimit	Ограничение на выборку элементов
	 * @return array
	 */
	public function GetBlogsRatingJoin($sUserId,$iLimit) {
		$sql = "SELECT 
					b.*													
				FROM 
					".Config::Get('db.table.blog_user')." as bu,
					".Config::Get('db.table.blog')." as b	
				WHERE 	
					bu.user_id = ?d
					AND
					bu.blog_id = b.blog_id
					AND				
					b.blog_type<>'personal'							
				ORDER by b.blog_rating desc
				LIMIT 0, ?d 
				;	
					";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('Blog',$aRow);
			}
		}
		return $aReturn;
	}
	/**
	 * Получает список блогов, которые создал пользователь
	 *
	 * @param int $sUserId ID пользователя
	 * @param int $iLimit	Ограничение на выборку элементов
	 * @return array
	 */
	public function GetBlogsRatingSelf($sUserId,$iLimit) {
		$sql = "SELECT 
					b.*													
				FROM 					
					".Config::Get('db.table.blog')." as b	
				WHERE 						
					b.user_owner_id = ?d
					AND				
					b.blog_type<>'personal'													
				ORDER by b.blog_rating desc
				LIMIT 0, ?d 
			;";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql,$sUserId,$iLimit)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=Engine::GetEntity('Blog',$aRow);
			}
		}
		return $aReturn;
	}
	/**
	 * Возвращает полный список закрытых блогов
	 *
	 * @return array
	 */
	public function GetCloseBlogs() {
		$sql = "SELECT b.blog_id										
				FROM ".Config::Get('db.table.blog')." as b					
				WHERE b.blog_type='close'
			;";
		$aReturn=array();
		if ($aRows=$this->oDb->select($sql)) {
			foreach ($aRows as $aRow) {
				$aReturn[]=$aRow['blog_id'];
			}
		}
		return $aReturn;
	}
	/**
	 * Удаление блога из базы данных
	 *
	 * @param  int  $iBlogId ID блога
	 * @return bool
	 */
	public function DeleteBlog($iBlogId) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.blog')." 
			WHERE blog_id = ?d				
		";
		$res=$this->oDb->query($sql,$iBlogId);
		return $res===false or is_null($res) ? false : true;
	}
	/**
	 * Удалить пользователей блога по идентификатору блога
	 *
	 * @param  int  $iBlogId	ID блога
	 * @return bool
	 */
	public function DeleteBlogUsersByBlogId($iBlogId) {
		$sql = "
			DELETE FROM ".Config::Get('db.table.blog_user')." 
			WHERE blog_id = ?d
		";
		$res=$this->oDb->query($sql,$iBlogId);
		return $res===false or is_null($res) ? false : true;
	}
	/**
	 * Пересчитывает число топиков в блогах
	 *
	 * @param int|null $iBlogId ID блога
	 * @return bool
	 */
	public function RecalculateCountTopic($iBlogId=null) {
		$sql = "
                UPDATE ".Config::Get('db.table.blog')." b
                SET b.blog_count_topic = (
                    SELECT count(*)
                    FROM ".Config::Get('db.table.topic')." t
                    WHERE
                        t.blog_id = b.blog_id
                    AND
                        t.topic_publish = 1
                )
                WHERE 1=1
                	{ and b.blog_id = ?d }
            ";
		$res=$this->oDb->query($sql,is_null($iBlogId) ? DBSIMPLE_SKIP : $iBlogId);
		return $res===false or is_null($res) ? false : true;
	}
	/**
	 * Получает список блогов по фильтру
	 *
	 * @param array $aFilter	Фильтр выборки
	 * @param array $aOrder		Сортировка
	 * @param int $iCount		Возвращает общее количество элментов
	 * @param int $iCurrPage	Номер текущей страницы
	 * @param int $iPerPage		Количество элементов на одну страницу
	 * @return array
	 */
	public function GetBlogsByFilter($aFilter,$aOrder,&$iCount,$iCurrPage,$iPerPage) {
		$aOrderAllow=array('blog_id','blog_title','blog_rating','blog_count_user','blog_count_topic');
		$sOrder='';
		foreach ($aOrder as $key=>$value) {
			if (!in_array($key,$aOrderAllow)) {
				unset($aOrder[$key]);
			} elseif (in_array($value,array('asc','desc'))) {
				$sOrder.=" {$key} {$value},";
			}
		}
		$sOrder=trim($sOrder,',');
		if ($sOrder=='') {
			$sOrder=' blog_id desc ';
		}

		if (isset($aFilter['exclude_type']) and !is_array($aFilter['exclude_type'])) {
			$aFilter['exclude_type']=array($aFilter['exclude_type']);
		}
		if (isset($aFilter['type']) and !is_array($aFilter['type'])) {
			$aFilter['type']=array($aFilter['type']);
		}
		if (isset($aFilter['category_id']) and !is_array($aFilter['category_id'])) {
			$aFilter['category_id']=array($aFilter['category_id']);
		}

		$sql = "SELECT
					blog_id
				FROM
					".Config::Get('db.table.blog')."
				WHERE
					1 = 1
					{ AND blog_id = ?d }
					{ AND user_owner_id = ?d }
					{ AND blog_type IN (?a) }
					{ AND blog_type not IN (?a) }
					{ AND blog_url = ? }
					{ AND blog_title LIKE ? }
					{ AND category_id IN (?a) }
					{ AND category_id IS NULL and 1=?d}
				ORDER by {$sOrder}
				LIMIT ?d, ?d ;
					";
		$aResult=array();
		if ($aRows=$this->oDb->selectPage($iCount,$sql,
										  isset($aFilter['id']) ? $aFilter['id'] : DBSIMPLE_SKIP,
										  isset($aFilter['user_owner_id']) ? $aFilter['user_owner_id'] : DBSIMPLE_SKIP,
										  (isset($aFilter['type']) and count($aFilter['type']) ) ? $aFilter['type'] : DBSIMPLE_SKIP,
										  (isset($aFilter['exclude_type']) and count($aFilter['exclude_type']) ) ? $aFilter['exclude_type'] : DBSIMPLE_SKIP,
										  isset($aFilter['url']) ? $aFilter['url'] : DBSIMPLE_SKIP,
										  isset($aFilter['title']) ? $aFilter['title'] : DBSIMPLE_SKIP,
										  (isset($aFilter['category_id']) and count($aFilter['category_id'])) ? $aFilter['category_id'] : DBSIMPLE_SKIP,
										  (array_key_exists('category_id',$aFilter) and is_null($aFilter['category_id'])) ? 1 : DBSIMPLE_SKIP,
										  ($iCurrPage-1)*$iPerPage, $iPerPage
		)) {
			foreach ($aRows as $aRow) {
				$aResult[]=$aRow['blog_id'];
			}
		}
		return $aResult;
	}
}
?>