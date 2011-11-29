<?php
class ComAnnotationsControllerAnnotation extends ComDefaultControllerResource
{
	protected function _actionPut(KCommandContext $context)
	{
		$json = KRequest::get('post.annotations', 'raw');
		$annotations = json_decode($json);

		$page = str_replace(JURI::root(), '', $_SERVER['HTTP_REFERER']);
		
		$table = $this->getService('com://admin/annotations.database.table.annotations');
		
		$data = array();
		
		$count = 0;
		foreach($annotations as $annotation)
		{
			$row = $table->select((int)$annotation->id, KDatabase::FETCH_ROW);
			$row->text = $annotation->text;
			$row->selector = $annotation->selector;
			$row->position = $annotation->position;
			$row->ordering = $annotation->ordering;
			$row->page = $page;
			
			$row->save();
			
			if($row->id)
			{
				$count++;
				
				$obj = new stdClass;
				$obj->uid = $annotation->uid;
				$obj->id = $row->id;
				$data[] = $obj;
			}
		}

		$response = new stdClass;
		$response->count = $count;
		$response->data = $data;
		
		echo json_encode($response);
		exit();
	}
}