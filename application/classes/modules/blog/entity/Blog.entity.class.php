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
 * Сущность блога
 *
 * @package application.modules.blog
 * @since 1.0
 */
class ModuleBlog_EntityBlog extends Entity
{

    protected $sPrimaryKey = 'blog_id';
    /**
     * Список поведений
     *
     * @var array
     */
    protected $aBehaviors = array(
        // Категории
        'category' => array(
            'class'                          => 'ModuleCategory_BehaviorEntity',
            'target_type'                    => 'blog',
            'form_field'                     => 'category',
            'multiple'                       => false,
            'validate_require'               => false,
            'validate_only_without_children' => true,
        ),
    );

    /**
     * Инициализация
     */
    public function Init()
    {
        parent::Init();
        $this->aBehaviors['category']['validate_require'] = !Config::Get('module.blog.category_allow_empty');
        $this->aBehaviors['category']['validate_only_without_children'] = Config::Get('module.blog.category_only_without_children');
    }

    /**
     * Возвращает ID блога
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->_getDataOne('blog_id');
    }

    /**
     * Возвращает ID хозяина блога
     *
     * @return int|null
     */
    public function getOwnerId()
    {
        return $this->_getDataOne('user_owner_id');
    }

    /**
     * Возвращает название блога
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->_getDataOne('blog_title');
    }

    /**
     * Возвращает описание блога
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->_getDataOne('blog_description');
    }

    /**
     * Возвращает тип блога
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->_getDataOne('blog_type');
    }

    /**
     * Возвращает дату создания блога
     *
     * @return string|null
     */
    public function getDateAdd()
    {
        return $this->_getDataOne('blog_date_add');
    }

    /**
     * Возвращает дату редактирования блога
     *
     * @return string|null
     */
    public function getDateEdit()
    {
        return $this->_getDataOne('blog_date_edit');
    }

    /**
     * Возврщает количество проголосовавших за блог
     *
     * @return int|null
     */
    public function getCountVote()
    {
        return $this->_getDataOne('blog_count_vote');
    }

    /**
     * Возвращает количество пользователей в блоге
     *
     * @return int|null
     */
    public function getCountUser()
    {
        return $this->_getDataOne('blog_count_user');
    }

    /**
     * Возвращает количество топиков в блоге
     *
     * @return int|null
     */
    public function getCountTopic()
    {
        return $this->_getDataOne('blog_count_topic');
    }

    /**
     * Возвращает ограничение по рейтингу для постинга в блог
     *
     * @return int|null
     */
    public function getLimitRatingTopic()
    {
        return $this->_getDataOne('blog_limit_rating_topic');
    }

    /**
     * Возвращает URL блога
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->_getDataOne('blog_url');
    }

    /**
     * Возвращает полный серверный путь до аватара блога
     *
     * @return string|null
     */
    public function getAvatar()
    {
        return $this->_getDataOne('blog_avatar');
    }

    /**
     * Возвращает расширения аватра блога
     *
     * @return string|null
     */
    public function getAvatarType()
    {
        return ($sPath = $this->getAvatarPath()) ? pathinfo($sPath, PATHINFO_EXTENSION) : null;
    }


    /**
     * Возвращает объект пользователя хозяина блога
     *
     * @return ModuleUser_EntityUser|null
     */
    public function getOwner()
    {
        return $this->_getDataOne('owner');
    }

    /**
     * Возвращает объект голосования за блог
     *
     * @return ModuleVote_EntityVote|null
     */
    public function getVote()
    {
        return $this->_getDataOne('vote');
    }

    /**
     * Возвращает полный серверный путь до аватара блога определенного размера
     *
     * @param int $iSize Размер аватара
     * @return string
     */
    public function getAvatarPath($iSize = 48)
    {
        if (is_numeric($iSize)) {
            $iSize .= 'crop';
        }
        if ($sPath = $this->getAvatar()) {
            return $this->Media_GetImageWebPath($sPath, $iSize);
        } else {
            return $this->Media_GetImagePathBySize(Config::Get('path.skin.assets.web') . '/images/avatars/avatar_blog.png', $iSize);
        }
    }

    /**
     * Формирует массив с путями до аватаров
     *
     * @return array Массив с путями до аватаров
     */
    public function getAvatarsPath()
    {
        $aAvatars = array();

        foreach (Config::Get('module.blog.avatar_size') as $sSize) {
            $aAvatars[ $sSize ] = $this->getAvatarPath( $sSize );
        }

        return $aAvatars;
    }

    /**
     * Возвращает факт присоединения пользователя к блогу
     *
     * @return bool|null
     */
    public function getUserIsJoin()
    {
        return $this->_getDataOne('user_is_join');
    }

    /**
     * Проверяет является ли пользователь администратором блога
     *
     * @return bool|null
     */
    public function getUserIsAdministrator()
    {
        return $this->_getDataOne('user_is_administrator');
    }

    /**
     * Проверяет является ли пользователь модератором блога
     *
     * @return bool|null
     */
    public function getUserIsModerator()
    {
        return $this->_getDataOne('user_is_moderator');
    }

    /**
     * Возвращает полный URL блога
     *
     * @return string
     */
    public function getUrlFull()
    {
        if ($this->getType() == 'personal') {
            return $this->getOwner()->getUserWebPath() . 'created/topics/';
        } else {
            return Router::GetPath('blog') . $this->getUrl() . '/';
        }
    }

    public function isAllowEdit()
    {
        if ($oUser = $this->User_GetUserCurrent()) {
            if ($oUser->getId() == $this->getOwnerId() or $oUser->isAdministrator() or $this->getUserIsAdministrator()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Устанавливает ID блога
     *
     * @param int $data
     */
    public function setId($data)
    {
        $this->_aData['blog_id'] = $data;
    }

    /**
     * Устанавливает ID хозяина блога
     *
     * @param int $data
     */
    public function setOwnerId($data)
    {
        $this->_aData['user_owner_id'] = $data;
    }

    /**
     * Устанавливает заголовок блога
     *
     * @param string $data
     */
    public function setTitle($data)
    {
        $this->_aData['blog_title'] = $data;
    }

    /**
     * Устанавливает описание блога
     *
     * @param string $data
     */
    public function setDescription($data)
    {
        $this->_aData['blog_description'] = $data;
    }

    /**
     * Устанавливает тип блога
     *
     * @param string $data
     */
    public function setType($data)
    {
        $this->_aData['blog_type'] = $data;
    }

    /**
     * Устанавливает дату создания блога
     *
     * @param string $data
     */
    public function setDateAdd($data)
    {
        $this->_aData['blog_date_add'] = $data;
    }

    /**
     * Устанавливает дату редактирования топика
     *
     * @param string $data
     */
    public function setDateEdit($data)
    {
        $this->_aData['blog_date_edit'] = $data;
    }

    /**
     * Устаналивает количество проголосовавших
     *
     * @param int $data
     */
    public function setCountVote($data)
    {
        $this->_aData['blog_count_vote'] = $data;
    }

    /**
     * Устанавливает количество пользователей блога
     *
     * @param int $data
     */
    public function setCountUser($data)
    {
        $this->_aData['blog_count_user'] = $data;
    }

    /**
     * Устанавливает количество топиков в блоге
     *
     * @param int $data
     */
    public function setCountTopic($data)
    {
        $this->_aData['blog_count_topic'] = $data;
    }

    /**
     * Устанавливает ограничение на постинг в блог
     *
     * @param float $data
     */
    public function setLimitRatingTopic($data)
    {
        $this->_aData['blog_limit_rating_topic'] = $data;
    }

    /**
     * Устанавливает URL блога
     *
     * @param string $data
     */
    public function setUrl($data)
    {
        $this->_aData['blog_url'] = $data;
    }

    /**
     * Устанавливает полный серверный путь до аватара блога
     *
     * @param string $data
     */
    public function setAvatar($data)
    {
        $this->_aData['blog_avatar'] = $data;
    }

    /**
     * Устанавливает автора блога
     *
     * @param ModuleUser_EntityUser $data
     */
    public function setOwner($data)
    {
        $this->_aData['owner'] = $data;
    }

    /**
     * Устанавливает статус администратора блога для текущего пользователя
     *
     * @param bool $data
     */
    public function setUserIsAdministrator($data)
    {
        $this->_aData['user_is_administrator'] = $data;
    }

    /**
     * Устанавливает статус модератора блога для текущего пользователя
     *
     * @param bool $data
     */
    public function setUserIsModerator($data)
    {
        $this->_aData['user_is_moderator'] = $data;
    }

    /**
     * Устаналивает статус присоединения польователя к блогу
     *
     * @param bool $data
     */
    public function setUserIsJoin($data)
    {
        $this->_aData['user_is_join'] = $data;
    }

    /**
     * Устанавливает объект голосования за блог
     *
     * @param ModuleVote_EntityVote $data
     */
    public function setVote($data)
    {
        $this->_aData['vote'] = $data;
    }
}