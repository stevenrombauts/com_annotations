<?php
class ComAnnotationsModelAnnotations extends ComDefaultModelDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
       $this->_state
            ->insert('referrer', 'boolean')
            ->insert('page', 'string');
    }

    public function getList()
    {
    	$list = parent::getList();

    	return $list;
    }
    
    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->_state;

        if($state->referrer === TRUE)
        {
        	$page = str_replace(JURI::root(), '', $_SERVER['HTTP_REFERER']);
        	
            $query->where('tbl.page', '=', $page);
        }
        
        if($state->page) {
        	$query->where('tbl.page', '=', $state->page);
        }
    }
}