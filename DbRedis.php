<?php

/**
 * Klasa do obsługi Redisa
 * 
 * @author  Fabian Lenczewski <fabian.lenczewski@gmail.com>
 * @since   2014-04-04
 */

class DbRedis
{
    protected static $_instance = null;

    // handler połączenia
    public $db;
    
    // czy jest nawiązane połączenie
    public $isConnecton = false;
    
    // ilość zapytań
    public $queryCount = 0;
    
    // łączny czas zapytań
    public $queryTime = 0;
    
    // czy włączone debugowanie zapytań
    private $_debug = false;        // ilcznik ilości i czasu zapytań
    private $_debugQuery = false;   // podgląd zapytań

    // seperacja kluczy
    const KEY_SEPARATOR = ':';

    //
    const CODE_ERROR        = -1;   // bład bazy
    const CODE_WRONG_PARAMS = -2;   // np. złe parametry
    const CODE_NOT_FOUND    = -3;   // np. nie znaleziono klucza
    //
    
    function __construct($config, $namespace)
    {
        #$this->_debug = true;
        #$this->_debugQuery = true;
        
        if (is_object(self::$_instance[$namespace])) {
            throw new Exception('DbRedis: Please use method DbRedis::getInstance()');
        }

        try {
            $this->db = new \Redis;
            $this->db->connect($config['host'], $config['port']);
            if(isset($config['database'])) {
                $this->db->select($config['database']);
            }

            $this->isConnecton = true;
        } catch(Exception $e) {
            throw new Exception( 'DbRedis: '. $e->getMessage() );
        }
    }
    
    public static function getInstance( $config = array() )
    {
        // domyślna konfiguracja
        $default['host']     = 'localhost';
        $default['port']     = '6379';
        $default['database'] = '0';

        //
        if( !isset($config['host']) ) {
            $config = $default;
        }
        // 

        // ustalenie namespace
        $namespace[] = 'dbredis_obj';
        $namespace[] = $config['host'];
        if(isset($config['database'])) {
            $namespace[] = $config['database'];
        }
        $namespace = implode('_', $namespace);

        if( !is_object(self::$_instance[$namespace]) ) {
            self::$_instance[$namespace] =  new self($config, $namespace);
        }
        return self::$_instance[$namespace];
    }
    

    function __destruct()
    {
        if($this->_debug) {
            print_r("\n\n -- ". $this->queryCount .' req. at '. $this->queryTime .' s.');
        }

        if(isset($this->isConnecton)) {
            $this->db->close();
        }
    }
    
    public function __call($method, $args)
	  {
        if(isset($this->isConnecton)) {
            if (method_exists($this->db, $method) ) {
                
                // zwiększenie licznika zapytań
                if($this->_debug) { 
                    $this->queryCount++;
                }
                
                // pomiar czasu - start
                if($this->_debug) { 
                }
                
                // wywołanie funckji
                $result = call_user_func_array(array($this->db, $method), $args);
                
                // pomiar czasu - stop
                if($this->_debug) { 
                }
                
                // zwiększenie licznika czasu zapytań
                if($this->_debug) { 
                }

                // podgląd zapytań
                if($this->_debug && $this->_debugQuery) { 
                    $this->_showQuery($queryTime, $method, $args);
                }
                
                return $result;
            } else {
                echo 1;
            }
        } else {
        }
    }
    
    /**
     * Podgląd zapytań do bazy
     * 
     * @param string $queryTime
     * @param string $method
     * @param array $args
     */
    private function _showQuery($queryTime, $method, $args) 
    {
        print_r( $queryTime .'s.' );
        print_r( $method );
        print_r( self::_flattenArray($args) );
    }
    
    /**
     * Prasuje tablicę do stringa
     * 
     * @param array $array - tablica do prasowania
     * 
     * @return string
     */
    private static function _flattenArray( $array)
    {
        $line = '';

        if ( is_array($array) ) {
            $line .= (count($array) > 2) ? ' array(' : '';
            foreach ($array as $key => $value) {
               $line .= self::_flattenArray($value);
               if ($key == count($array)-1 && substr($line, -2) == ', ') {
                    $line = substr($line, 0, -2);
               }
            }
            $line .= (count($array) > 2) ? ') ' : '';
        } else {
             $line = $array . ', ';
        }
        
        return $line;
    }     



}
