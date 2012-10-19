<?php
class ComAnnotationsModelAnnotations extends ComDefaultModelDefault
{
    public function __construct(KConfig $config)
    {
		parent::__construct($config);
        
		$this->_state
			->insert('identifier', 'identifier')
			->insert('package', 'cmd')
       		->insert('group', 'string')
			->insert('search', 'string');
    }
    
    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
		$state = $this->_state;
        
        if($state->identifier) {
        	$query->where('tbl.identifier', '=', $state->identifier);
        }
        
        if($state->package) {
        	$query->where('tbl.package', '=', $state->package);
        }
        
        if($state->search)
        {
        	$query->where('tbl.title', 'LIKE', '%'.$state->search.'%');
        	$query->where('tbl.text', 'LIKE', '%'.$state->search.'%', 'OR');
        }
        
        parent::_buildQueryWhere($query);
    }
    
    protected function _buildQueryGroup(KDatabaseQuery $query)
    {
    	if($this->_state->group) {
    		$query->group($this->_state->group);
    	}
    }
}