<?php

namespace Components;

class Pagination
{
    public function __construct()
    {
        
    }
    
    static public function getUrlParams($firstChar = '')
    {
        $urlParams = '';
        
        $mas = explode('?', $_SERVER['REQUEST_URI']);
        if(isset($mas[1]) && $mas[1] != '')
        {
            parse_str($mas[1], $params);
            if(isset($params['p']))
            {
                unset($params['p']);
            }
            
            $urlParams = ($params) ? $firstChar.http_build_query($params) : '';
        }
        
        return $urlParams;
    }
    
    static public function generatePagination($perPage = 0, $total = 0, $p = 0)
    {
        $mas = [];
        
        if($total > $perPage)
        {
            $urlParams = self::getUrlParams('&');
            $totalSheets = ceil($total / $perPage);
            $spread = 4;
            $amountBtn = ($spread * 2)+1;
            $items = [];
            
            if($amountBtn >= $totalSheets || ($totalSheets > $amountBtn && ($p + 1) < $amountBtn))
            {
                for($i = 0; $i < $amountBtn; $i++)
                {
                    $link = ($i > 0) ? '?p='.$i.$urlParams : '?'.ltrim($urlParams, '&');
                    $items[] = ['number'=>$i, 'value'=>(1+$i), 'link'=>$link, 'active'=>($i == $p ? 1 : 0)];
                }
            }
            else
            {
                $index = (($p+1) + $spread);
                if($index <= $totalSheets)
                {
                    $index = $spread;
                    while($index)
                    {
                        $items[] = ['number'=>($p-$index), 'value'=>(1+($p-$index)), 'link'=>'?p='.($p-$index).$urlParams, 'active'=>0];
                        --$index;
                    }
                    
                    $items[] = ['number'=>$p, 'value'=>(1+$p), 'link'=>'?p='.$p.$urlParams, 'active'=>1];
                    
                    for($i = 0; $i < $spread; $i++)
                    {
                        $items[] = ['number'=>($p+($i+1)), 'value'=>($p+($i+2)), 'link'=>'?p='.($p+($i+1)).$urlParams, 'active'=>0];
                    }
                }
                else if($index > $totalSheets)
                {
                    $index = ($totalSheets - ($spread * 2))-1;
                    
                    while($index < $totalSheets)
                    {
                        $items[] = ['number'=>$index, 'value'=>(1+$index), 'link'=>'?p='.$index.$urlParams, 'active'=>($index == $p ? 1 : 0)];
                        ++$index;
                    }
                }
            }
            
            ($items) ? $mas['items'] = $items : '';
            $mas['current_page'] = (int)$p;
            $mas['begin'] = ['number'=>0, 'value'=>1, 'link'=>'?'.ltrim($urlParams, '&')];
            $mas['end'] = ['number'=>($totalSheets-1), 'value'=>$totalSheets, 'link'=>'?p='.($totalSheets-1).$urlParams];
            
            if($p > 0)
            {
                $link = ($p-1) > 0 ? '?p='.($p-1).$urlParams : '?'.ltrim($urlParams, '&');
                $mas['prev'] = ['number'=>($p-1), 'value'=>$p, 'link'=>$link];
            }
            
            if(($p+1) < $totalSheets)
            {
                $mas['next'] = ['number'=>($p+1), 'value'=>($p+2), 'link'=>'?p='.($p+1).$urlParams];
            }
        }
        
        return $mas;
    }
}
