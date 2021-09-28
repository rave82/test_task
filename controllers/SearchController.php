<?php

namespace Controllers;

class SearchController
{
    public function actionIndex()
    {
        return $this->actionSearchForm();
    }
    
    public function actionSearchForm()
    {
        $mas['view'] = 'main';
        
        return $mas;
    }
    
    public function actionSearchResult($search_string = '')
    {
        ($search_string) ? '' : $search_string = getRequest('s');
        $p = (int)getRequest('p');
        $mas = [];
        
        if($search_string != '')
        {
            $model = new \Models\Search($search_string);
            $mas = $model->search($search_string, $p);
            
            if(isset($mas['total']) && $mas['total'] > $mas['per_page'])
            {
                $mas['pagination'] = \Components\Pagination::generatePagination($mas['per_page'], $mas['total'], $p);
            }
        }
        
        $mas['view'] = 'searchResult';
        
        return $mas;
    }
    
    public function actionPreSearchResult()
    {
        $search_string = getRequest('s');
        $isJson = (int)getRequest('json');
        $mas = ['total'=>0, 'format_total'=>0];
        
        if($search_string != '')
        {
            $model = new \Models\Search($search_string);
            $result = $model->search($search_string, 0, 1);
            
            $mas['total'] = $result['total'];
            $mas['format_total'] = number_format($mas['total'], 0, '', ' ');
            unset($result);
        }
        
        return ($isJson) ? json_encode($mas) : $mas;
    }
    
    public function __toString()
    {
        return get_class($this);
    }
}
