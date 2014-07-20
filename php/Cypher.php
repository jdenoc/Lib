<?php
/**
 * User: Denis O;Connor
 * Date: 2014-06-30
 * SRC: http://stackoverflow.com/questions/1788150/how-to-encrypt-string-in-php/19445173#19445173
 */

class Cypher{
    private static $text_keys = array();
    private $_secure_key;
    private $_iv_size;
    private $_json_file;
    private $_fail = false;
    private $_error_title = 'CYPHER ERROR:';
    private $_error_msg = '';

    /**
     * @param int $text_key_index
     * @param string $json_config_file
     */
    public function __construct($text_key_index, $json_config_file){
        $this->_json_file = $json_config_file;
        $this->get_user_keys();
        if($this->can_run()){
            if(empty(self::$text_keys[$text_key_index])){
                $this->_fail = true;
                $this->_error_msg = 'User key does not exist';
            } else {
                $this->_secure_key = hash('sha256', self::$text_keys[$text_key_index], TRUE);
                $this->_iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            }
        }
    }

    /**
     * @param string $input
     * @return bool|string
     */
    public function encrypt($input){
        if($this->can_run()){
            $iv = mcrypt_create_iv($this->_iv_size);
            return base64_encode($iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->_secure_key, $input, MCRYPT_MODE_CBC, $iv));
        } else {
            return false;
        }
    }

    /**
     * @param string $input
     * @return bool|string
     */
    public function decrypt($input){
        if($this->can_run()){
            $input = base64_decode($input);
            $iv = substr($input, 0, $this->_iv_size);
            $cipher = substr($input, $this->_iv_size);
            return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->_secure_key, $cipher, MCRYPT_MODE_CBC, $iv));
        } else {
            return false;
        }
    }

    /**
     * @param int $new_user
     * @param string $key
     */
    public function add_user_key($new_user, $key){
        self::$text_keys[$new_user] = $key;
    }

    public function save_user_keys(){
        $json = json_encode(self::$text_keys);
        file_put_contents($this->_json_file, $json);
    }

    private function get_user_keys(){
        if(empty(self::$text_keys)){
            if(file_exists($this->_json_file)){
                $json = file_get_contents($this->_json_file);
                $json_file_data = json_decode($json, true);
                if(is_array($json_file_data)){
                    self::$text_keys = $json_file_data;
                } else {
                    self::$text_keys = array();
                }
            } else {
                $this->_fail = true;
                $this->_error_msg = 'User key file not found';
                error_log($this->_error_title.' '.$this->_error_msg, 0);
            }
        }
    }

    /**
     * @return bool
     */
    private function can_run(){
        return !$this->_fail;
    }

    /**
     * @return string
     */
    public function get_error_message(){
        return $this->_error_msg;
    }
}