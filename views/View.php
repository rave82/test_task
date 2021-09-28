<?php

namespace Views;

class View
{
    public function render(&$params = [])
    {
        return $this->renderLayout($params, $this->renderContent($params));
    }
    
    private function renderLayout(&$params = [], &$content = '')
    {
        $path_include = CURRENT_WORK_DIR.'/templates/views/layout/index.phtml';
        
        ob_start();
        
        extract($params);
        include $path_include;
        
        return ob_get_clean();
    }
    
    private function renderContent(&$params = [])
    {
        $path_include = CURRENT_WORK_DIR.'/templates/views/'.$params['view'].'.phtml';
        
        ob_start();
        
        extract($params);
        include $path_include;
        
        return ob_get_clean();
    }
}
