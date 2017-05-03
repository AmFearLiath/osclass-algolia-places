<?php
class algolia {
    
    private static $instance ;
    
    public static function newInstance() {
        if( !self::$instance instanceof self ) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }
    
    function __construct() {        
        $this->_sect = 'plugin_algolia_places';
    }
    
    function _install() {  
        $opts = $this->_opt();        
        foreach ($opts AS $k => $v) {
            osc_set_preference($k, $v[0], $this->_sect, $v[1]);
        }
        return true;            
    }
    
    function _uninstall() {                
        Preference::newInstance()->delete(array("s_section" => $this->_sect));    
    }
    
    function _opt($param = false) {
        $enc = serialize(array('EN' => 'en'));                
        $opts = array(
            'activated' => array('1', 'BOOLEAN'),
            'appid' => array('1', 'STRING'),
            'appkey' => array('1', 'STRING'),
            'languages' => array('1', 'BOOLEAN'),
            'countries' => array('1', 'BOOLEAN'),
            'countrycodes' => str_replace(" ", "", array('de,en', 'STRING')),
            'languagecodes' => array($enc, 'STRING'),
            'standardLanguage' => array('en', 'STRING')
        );
        
        if ($param) { return $opts[$param]; }        
        return $opts;
    }

    function _get($opt = 'activated') {        
        return osc_get_preference($opt, $this->_sect);
    }
    
    function _lang($lang) {        
        $langs = @unserialize(osc_get_preference('languagecodes', $this->_sect));        
        return $langs['\''.$lang.'\''];    
    }
    
    function _countries($country = false) {        
        return osc_get_preference('countrycodes', $this->_sect);
    }

    function _save($data) {
        foreach($data as $k => $v) {
            $type = $this->_opt($k);
            if (!osc_set_preference($k, $v, $this->_sect, $type[1])) {
                return false;
            }    
        }
        return true;
    }
    
    function _saveData($data) {        
        $days = $this->_checkDays($data['catId'], $data['select_algolia_places']);    
        if (!$this->dao->update($this->_table_item, array('dt_expiration' => $date), array('pk_i_id' => $data['id']))) {
            return false;
        }        
        return true;    
    }    
}
?>
