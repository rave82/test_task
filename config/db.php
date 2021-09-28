<?php

namespace Config;

class MySqliQueryResult
{
    private $queryResult;
    private $fetchType;
    const FETCH_ARRAY = 'array';
    const FETCH_ROW = 'row';
    const FETCH_ASSOC = 'assoc';
    const FETCH_OBJECT = 'object';
    
    public function __construct($result = FALSE)
    {
        $this->queryResult = $result;
        $this->fetchType = self::FETCH_ASSOC;
    }
    
    public function setFetchArray()
    {
        return $this->fetch_type = self::FETCH_ARRAY;
    }
    
    public function setFetchRow()
    {
        return $this->fetch_type = self::FETCH_ROW;
    }
    
    public function setFetchAssoc()
    {
        return $this->fetch_type = self::FETCH_ASSOC;
    }
    
    public function setFetchObject()
    {
        return $this->fetch_type = self::FETCH_OBJECT;
    }
    
    public function fetch()
    {
        $result = NULL;
        
        switch($this->fetchType)
        {
            case self::FETCH_ARRAY:
                $result = &mysqli_fetch_array($this->queryResult);
            break;
            case self::FETCH_ROW:
                $result = &mysqli_fetch_row($this->queryResult);
            break;
            case self::FETCH_ASSOC:
                $result = &mysqli_fetch_assoc($this->queryResult);
            break;
            case self::FETCH_OBJECT:
                $result = &mysqli_fetch_object($this->queryResult);
            break;
        }
        
        return $result;
    }
    
    public function length()
    {
        $length = 0;
        
        if($this->queryResult)
        {
            $length = mysqli_num_rows($this->queryResult);
        }
        
        return $length;
    }
}

class db
{
    private $mysqli;
    private $connected;
    
    public function __construct()
    {
        $this->mysqli = FALSE;
        $this->connected = FALSE;
        
        $this->connection();
    }
    
    public function isConnected()
    {
        return $this->connected;
    }
    
    public function connection()
    {
        if(!$this->isConnected())
        {
            $this->mysqli = mysqli_connect(DB_SERVER_NAME, DB_USER_NAME, DB_PASSWORD, DB_DATABASE_NAME);
            
            if(mysqli_connect_errno())
            {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                die;
            }
            
            $this->connected = TRUE;
        }
    }
    
    public function escape($value = '')
    {
        $escapeValue = FALSE;
        
        if($this->isConnected() && $value)
        {
            $escapeValue = mysqli_escape_string($this->mysqli, $value);
        }
        
        return $escapeValue;
    }
    
    public function query($query = '')
    {
        if($this->isConnected() && $query)
        {
            mysqli_query($this->mysqli, $query);
        }
    }
    
    public function queryResult($query = '')
    {
        if($this->isConnected() && $query)
        {
            return new MySqliQueryResult(mysqli_query($this->mysqli, $query));
        }
        
        return FALSE;
    }
    
    public function insertID()
    {
        $insertId = 0;
        
        if($this->isConnected())
        {
            mysqli_insert_id($this->mysqli);
        }
        
        return $insertId;
    }
    
    public function free(&$result = FALSE)
    {
        if($result)
        {
            mysqli_free_result($result);
        }
    }
    
    public function close()
    {
        if($this->connected)
        {
            mysqli_close($this->mysqli);
        }
        
        $this->connected = FALSE;
        $this->mysqli = FALSE;
    }
    
    
    public function __destruct()
    {
        $this->close();
    }
}
