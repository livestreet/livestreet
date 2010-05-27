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

class ModuleUser_EntityCountry extends Entity 
{    
    public function getId() {
        return $this->_aData['country_id'];
    }  
    public function getName() {
        return $this->_aData['country_name'];
    }
    
    
    
	public function setId($data) {
        $this->_aData['country_id']=$data;
    }
    public function setName($data) {
        $this->_aData['country_name']=$data;
    }    
}
?>