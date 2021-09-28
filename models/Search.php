<?php

namespace Models;

class Search
{
    public function search($searchString = '', $p = 0, $limit = FALSE)
    {
        $mas = [];
        
        ($limit > 0) ? '' : $limit = 10;
        ($p >= 0) ? '' : $p = 0;
        $total = 0;
        $mas['search_string'] = htmlspecialchars($searchString);
        
        if($searchString)
        {
            $db = new \Config\db();
            
            
            $searchString = urldecode($searchString);
            $searchString = htmlspecialchars($searchString);
            $searchString = str_replace('. ', ' ', $searchString);
            $searchString = trim($searchString, " \t\r\n%");
            $searchString = str_replace(['"', "'"], '', $searchString);
            $searchWords = explode(" ", $searchString);
            
            if(sizeof($searchWords) == 0)
            {
                return $mas;
            }
            
            $params = [];
            if(sizeof($searchWords) > 1)
            {
                foreach($searchWords as $key=>$value)
                {
                    $val = preg_replace("/(^\s+)|(\s+$)/", '', $value);
                    if($val)
                    {
                        $params[] = '(p.name like "%'.$db->escape($val).'%")';
                    }
                }
            }
            else if(sizeof($searchWords) == 1)
            {
                $value = preg_replace("/(^\s+)|(\s+$)/", '', $searchWords[0]);
                ($value) ? $params[] = '(p.name like "%'.$db->escape($value).'%")' : '';
            }
            
            if(sizeof($params) > 0)
            {
                $query = 'Select SQL_CALC_FOUND_ROWS p.id, p.name, p.photo, p.product_class, p.status_id From '.self::tblName().' p 
                Where '.implode(' and ', $params).' Order By p.id asc Limit '.($db->escape($p)*$db->escape($limit)).', '.$db->escape($limit);
                $result = &$db->queryResult($query);
                
                $row_total = &$db->queryResult('Select FOUND_ROWS()')->fetch();
                $total = (isset($row_total['FOUND_ROWS()'])) ? $row_total['FOUND_ROWS()'] : 0;
                unset($row_total);
                
                if($result->length() > 0)
                {
                    $listStatus = (new ProductStatus())->listStatus();
                    
                    while($row = &$result->fetch())
                    {
                        $status = (isset($listStatus[$row['status_id']])) ? $listStatus[$row['status_id']] : '';
                        $mas['items'][] = ['id'=>$row['id'], 'name'=>$row['name'], 'photo'=>$row['photo'], 'product_class'=>$row['product_class'], 'status_id'=>$row['status_id'], 'status'=>$status];
                    }
                }
            }
            
            $db->close();
            unset($db);
            unset($params);
            unset($searchWords);
        }
        
        $mas['per_page'] = $limit;
        $mas['total'] = $total;
        
        return $mas;
    }
    
    static public function tblName()
    {
        return 'tbl_products';
    }
}
