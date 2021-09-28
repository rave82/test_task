<?php

namespace Models;

class ProductStatus
{
    public function __construct()
    {
        
    }
    
    static public function tblName()
    {
        return 'tbl_status_products';
    }
    
    public function listStatus()
    {
        $mas = [];
        
        $db = new \Config\db();
        $query = 'Select id, name From '.self::tblName().' Where 1';
        $result = &$db->queryResult($query);
        
        if($result->length() > 0)
        {
            while($row = &$result->fetch())
            {
                $mas[$row['id']] = $row['name'];
            }
        }
        unset($result);
        
        $db->close();
        unset($db);
        
        return $mas;
    }
}
