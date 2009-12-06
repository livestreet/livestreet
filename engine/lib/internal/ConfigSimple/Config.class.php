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
 * Управление простым конфигом в виде массива
 */
class Config {

	/**
	 * Default instance to operate with
	 *
	 * @var string
	 */
	const DEFAULT_CONFIG_INSTANCE = 'general';
	
	/**
	 * Mapper rules for Config Path <-> Constant Name relations
	 *
	 * @var array
	 */
	static protected $aMapper = array(
		//'sys.mail.include_comment' => 'SYS_MAIL_INCLUDE_COMMENT_TEXT',
		//'sys.mail.include_talk'    => 'SYS_MAIL_INCLUDE_TALK_TEXT',
		//'path.root.server'         => 'DIR_SERVER_ROOT',
		//'path.root.engine'         => 'DIR_SERVER_ENGINE',
		//'path.root.engine_lib'     => 'DIR_WEB_ENGINE_LIB',
		//'path.static.root'         => 'DIR_STATIC_ROOT',
		//'path.static.skin'         => 'DIR_STATIC_SKIN',
		//'path.uploads.root'        => 'DIR_UPLOADS',
		//'path.uploads.images'      => 'DIR_UPLOADS_IMAGES',
		//'path.offset_request_url'  => 'SYS_OFFSET_REQUEST_URL',
		//'path.smarty.template'     => 'DIR_SMARTY_TEMPLATE',
		//'path.smarty.compiled'     => 'DIR_SMARTY_COMPILED',
		//'path.smarty.cache'        => 'DIR_SMARTY_CACHE',
		//'path.smarty.plug'         => 'DIR_SMARTY_PLUG',
		//'acl.create.blog.rating'               => 'ACL_CAN_BLOG_CREATE',
		//'acl.create.comment.rating'            => 'ACL_CAN_POST_COMMENT',
		//'acl.create.comment.limit_time'        => 'ACL_CAN_POST_COMMENT_TIME',
		//'acl.create.comment.limit_time_rating' => 'ACL_CAN_POST_COMMENT_TIME_RATING',
		//'acl.vote.blog.rating'         => 'ACL_CAN_VOTE_BLOG',
		//'acl.vote.comment.rating'      => 'ACL_CAN_VOTE_COMMENT',
		//'acl.vote.topic.rating'        => 'ACL_CAN_VOTE_TOPIC',
		//'acl.vote.user.rating'         => 'ACL_CAN_VOTE_USER',
		//'acl.vote.topic.limit_time'    => 'VOTE_LIMIT_TIME_TOPIC',
		//'acl.vote.comment.limit_time'  => 'VOTE_LIMIT_TIME_COMMENT',
		//'view.skin'                => 'SITE_SKIN',
		//'view.name'                => 'SITE_NAME',
		//'view.keywords'            => 'SITE_KEYWORDS',
		//'view.description'         => 'SITE_DESCRIPTION',
		//'view.noindex'             => 'BLOG_URL_NO_INDEX',
		//'view.tinymce'             => 'BLOG_USE_TINYMCE',
		//'view.img_resize_width'    => 'BLOG_IMG_RESIZE_WIDTH',
		//'general.close'            => 'SITE_CLOSE_MODE',
		//'general.rss_editor_mail'  => 'RSS_EDITOR_MAIL',
		//'general.reg.invite'       => 'USER_USE_INVITE',
		//'general.reg.activation'   => 'USER_USE_ACTIVATION',
		//'module.user.per_page'     => 'USER_PER_PAGE',
		//'module.topic.new_time'    => 'BLOG_TOPIC_NEW_TIME',
		//'module.topic.per_page'    => 'BLOG_TOPIC_PER_PAGE',
		//'module.comment.per_page'  => 'BLOG_COMMENT_PER_PAGE',
		//'module.comment.bad'       => 'BLOG_COMMENT_BAD',
		//'module.comment.max_tree'  => 'BLOG_COMMENT_MAX_TREE_LEVEL',
		//'module.blog.personal_good'   => 'BLOG_PERSONAL_LIMIT_GOOD',
		//'module.blog.collective_good' => 'BLOG_COLLECTIVE_LIMIT_GOOD',
		//'module.blog.index_good'      => 'BLOG_INDEX_LIMIT_GOOD',
		//'module.blog.per_page'        => 'BLOG_BLOGS_PER_PAGE',
		//'block.stream.row'         => 'BLOCK_STREAM_COUNT_ROW',
		//'block.blogs.row'          => 'BLOCK_BLOGS_COUNT_ROW',
		//'db.table.prefix'          => 'DB_PREFIX_TABLE',
		//'module.search.entity_prefix' => 'SEARCH_ENTITY_PREFIX',
		//'module.search.sphinx.host'   => 'SEARCH_SPHINX_HOST',
		//'module.search.sphinx.port'   => 'SEARCH_SPHINX_PORT'
	);
	
	/**
	 * Массив сущностей класса
	 *
	 * @var array
	 */
	static protected $aInstance=array();
	
	/**
	 * Store for configuration entries for current instance
	 *
	 * @var array
	 */
	protected $aConfig=array();
	
	/**
	 * Disabled constract process
	 */
	protected function __construct() {
		
	}
	
	/**
	 * Ограничиваем объект только одним экземпляром
	 *
	 * @return ConfigSimple
	 */
	static public function getInstance($sName=self::DEFAULT_CONFIG_INSTANCE) {
		if (isset(self::$aInstance[$sName])) {
			return self::$aInstance[$sName];
		} else {
			self::$aInstance[$sName]= new self();
			return self::$aInstance[$sName];
		}
	}
	
	/**
	 * Load configuration array from file
	 *
	 * @param  string $sFile
	 * @param  bool $bRewrite
	 * @return ConfigSimple
	 */
	static public function LoadFromFile($sFile,$bRewrite=true,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Check if file exists
		if (!file_exists($sFile)) {
			return false;
		}
		// Get config from file
		$aConfig=include($sFile);
		return self::Load($aConfig,$bRewrite,$sInstance);
	}
	
	/**
	 * Load configuration array from given array
	 *
	 * @param  string $aConfig
	 * @param  bool   $bRewrite
	 * @return ConfigSimple
	 */
	static public function Load($aConfig,$bRewrite=true,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Check if it`s array
		if(!is_array($aConfig)) {
			return false;
		}
		// Set config to current or handle instance
		self::getInstance($sInstance)->SetConfig($aConfig,$bRewrite);		
		return self::getInstance($sInstance);
	}
	
	public function GetConfig() {
		return $this->aConfig;
	}
	
	public function SetConfig($aConfig=array(),$bRewrite=true) {
		if (is_array($aConfig)) {
			if ($bRewrite) {
				$this->aConfig=$aConfig;
			} else {
				$this->aConfig=$this->ArrayEmerge($this->aConfig,$aConfig);
			}
			return true;
		}
		$this->aConfig=array();
		return false;
	}
	
	/**
	 * Retrive information from configuration array
	 *
	 * @param  string $sKey      Path to needed value
	 * @param  string $sInstance Name of needed instance
	 * @return mixed
	 */
	static public function Get($sKey='', $sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Return all config array
		if($sKey=='') {
			return self::getInstance($sInstance)->GetConfig();
		}

		return self::getInstance($sInstance)->GetValue($sKey,$sInstance);
	}
	
	/**
	 * Получает значение из конфигурации по переданному ключу
	 *
	 * @param  string $sKey
	 * @param  string $sInstance
	 * @return mixed
	 */
	public function GetValue($sKey, $sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Return config by path (separator=".")
		$aKeys=explode('.',$sKey);
		
		$cfg=$this->GetConfig();
		foreach ((array)$aKeys as $sK) {
			if(isset($cfg[$sK])) {
				$cfg=$cfg[$sK];
			} else {
				return null;
			}
		}
		
		$cfg = self::KeyReplace($cfg,$sInstance);
		return $cfg;		
	}
	
	static public function KeyReplace($cfg,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		if(is_array($cfg)) {
			foreach($cfg as $k=>$v) {
				$k_replaced = self::KeyReplace($k, $sInstance);
				if($k==$k_replaced) {
					$cfg[$k] = self::KeyReplace($v,$sInstance);
				} else {
					$cfg[$k_replaced] = self::KeyReplace($v,$sInstance);
					unset($cfg[$k]);
				}
			}
		} else { 
			if(preg_match('~___([\S|\.|]+)___~Ui',$cfg))
				$cfg = preg_replace_callback(
					'~___([\S|\.]+)___~Ui',
					create_function('$value','return Config::Get($value[1],"'.$sInstance.'");'),
					$cfg
				);
		}
		return $cfg;
	}
	
	/**
	 * Try to find element by given key
	 * Using function ARRAY_KEY_EXISTS (like in SPL)
	 * 
	 * Workaround for http://bugs.php.net/bug.php?id=40442
	 * 
	 * @param  string $sKey      Path to needed value
	 * @param  string $sInstance Name of needed instance
	 * @return bool
	 */	
	static public function isExist($sKey, $sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		// Return all config array
		if($sKey=='') {
			return (count((array)self::getInstance($sInstance)->GetConfig())>0);
		}
		// Analyze config by path (separator=".")
		$aKeys=explode('.',$sKey);
		$cfg=self::getInstance($sInstance)->GetConfig();
		foreach ((array)$aKeys as $sK) {						
			if (array_key_exists($sK, $cfg)) {
				$cfg=$cfg[$sK];
			} else {
				return false;
			}
		}		
		return true;
	}
	
	/**
	 * Add information in config array by handle path
	 *
	 * @param  string $sKey
	 * @param  mixed $value
	 * @param  string $sInstance
	 * @return bool
	 */
	static public function Set($sKey,$value,$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		$aKeys=explode('.',$sKey);
		$sEval='self::getInstance($sInstance)->aConfig';
		foreach ($aKeys as $sK) {
			$sEval.="['$sK']";
		}
		$sEval.='=$value;';
		eval($sEval);	
		
		return true;	
	}
	
	/**
	 * Find all keys recursivly in config array
	 *
	 * @return array
	 */
	public function GetKeys() {
		$cfg=$this->GetConfig();
		// If it`s not array, return key
		if(!is_array($cfg)) {
			return false;
		}
		// If it`s array, get array_keys recursive
		return $this->func_array_keys_recursive($cfg);
	}
	
	/**
	 * Define constants using config-constant mapping
	 *
	 * @param  string $sKey
	 * @param  string $sInstance
	 * @return bool
	 */
	static public function DefineConstant($sKey='',$sInstance=self::DEFAULT_CONFIG_INSTANCE) {
		if($aKeys=self::getInstance($sInstance)->GetKeys()) {
			foreach($aKeys as $key) {
				// If there is key-mapping rool, replace it
				$sName = isset(self::$aMapper[$key])
					? self::$aMapper[$key]
					: strtoupper(str_replace('.','_',$key));
				if( (substr($key,0,strlen($sKey))==strtoupper($sKey)) 
						&& !defined($sName)
							&& (self::isExist($key,$sInstance)) ) 
				{
					$cfg=self::Get($key,$sInstance);
					// Define constant, if founded value is scalar or NULL
					if(is_scalar($cfg)||$cfg===NULL)define(strtoupper($sName),$cfg);		
				}
			}
			return true;
		}
		return false;
	}
	
	protected function ArrayEmerge($aArr1,$aArr2) {
		return $this->func_array_merge_assoc($aArr1,$aArr2);
	}
	
	/**
	 * Рекурсивный вариант array_keys
	 *
	 * @param  array $array
	 * @return array
	 */
	protected function func_array_keys_recursive($array) {
		if(!is_array($array)) {
			return false;
		} else {
			$keys = array_keys($array);
			foreach ($keys as $k=>$v) {
				if($append = $this->func_array_keys_recursive($array[$v])){
					unset($keys[$k]);
					foreach ($append as $new_key){
						$keys[] = $v.".".$new_key;
					}
				}
			}
			return $keys;
		}
	}	
	
	/**
	 * Сливает два ассоциативных массива
	 *
	 * @param unknown_type $aArr1
	 * @param unknown_type $aArr2
	 * @return unknown
	 */
	protected function func_array_merge_assoc($aArr1,$aArr2) {
		$aRes=$aArr1;
		foreach ($aArr2 as $k2 => $v2) {		
			$bIsKeyInt=false;
			if (is_array($v2)) {
				foreach ($v2 as $k => $v) {
					if (is_int($k)) {
						$bIsKeyInt=true;
						break;
					}
				}
			}		
			if (is_array($v2) and !$bIsKeyInt and isset($aArr1[$k2])) {
				$aRes[$k2]=$this->func_array_merge_assoc($aArr1[$k2],$v2);
			} else {
				$aRes[$k2]=$v2;
			}		
		}
		return $aRes;
	}	
}
?>