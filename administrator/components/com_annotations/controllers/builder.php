<?php
class ComAnnotationsControllerBuilder extends ComDefaultControllerDefault
{
	protected function _actionPost(KCommandContext $context)
	{	
		$context->action = $action;
		$context->caller = $this;
		
		$identifier = new KServiceIdentifier(KRequest::get('post.identifier', 'identifier'));
		
		if($identifier->type != 'com' || strstr($identifier->path, '.view.') === false) {
			throw new KServiceIdentifierException('Invalid identifier : '.$identifier);
		}
		
		$json = KRequest::get('post.annotations', 'json');
		$data = json_decode($json);
		$title = (strlen(trim($data->title)) ? trim($data->title) : ucfirst($identifier->package) . ' - ' . ucfirst($identifier->name));
		
		$table = $this->getService('com://admin/annotations.database.table.annotations');
		$count = 0;
		$rows = array();
		foreach($data->annotations as $annotation)
		{
			$row = $table->select((int)$annotation->id, KDatabase::FETCH_ROW);
			
			$row->title		 = empty($annotation->title) ? $title : $annotation->title;
			$row->text 		 = $annotation->text;
			$row->selector 	 = $annotation->selector;
			$row->position 	 = $annotation->position;
			$row->ordering 	 = $annotation->ordering;
			$row->identifier = (string) $identifier;
			$row->package  = $identifier->package;
			$row->save();
			
			if($row->id)
			{
				$count++;
				
				$obj = new stdClass;
				$obj->uid = $annotation->uid;
				$obj->id = $row->id;
				$rows[] = $obj;
			}
		}

		// @TODO deal with this properly
		$response = new stdClass;
		$response->count = $count;
		$response->data = $rows;

		echo json_encode($response);
		exit();
	}
}