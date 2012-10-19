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
    
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
		$state = $this->_state;
		
        if($state->identifier) {
        	$query->where('tbl.identifier = :identifier')->bind(array('identifier' => $state->identifier));
        }
        
        if($state->package) {
        	$query->where('tbl.package = :package')->bind(array('package' => $state->package));
        }
        
        if($state->search)
        {
        	$query->where('tbl.title LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
        	$query->where('tbl.title LIKE :search', 'OR')->bind(array('search' => '%'.$state->search.'%'));
        }
        
        parent::_buildQueryWhere($query);
    }
    
    protected function _buildQueryGroup(KDatabaseQuerySelect $query)
    {
    	if($this->_state->group) {
    		$query->group($this->_state->group);
    	}
    }
}