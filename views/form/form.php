<?php return(function($prop) {
	
	$prop['name'] = first($prop['name'], URL::$route['l-path']);
	
	if($_POST['form-name'] == $prop['name'])
	{
		foreach($prop['fields'] as $k => $field)
		{
			$key = first($field['field'], $k);
			$value = trim($_POST[$key]);
			$prop['data'][$key] = $value;
			if($field['validate'])
			{
				if($field['validate'] == 'mandatory')
				{
					if($value == '') 
					{
						$prop['errors'][$key] = 'please fill out this field';
					}
				}
			}
		}
		if($prop['ondata'] && @sizeof($prop['errors']) == 0) 
			$prop['ondata_result'] = $prop['ondata']($prop['data'], $prop);
	}
	
	if($prop['show'] !== false)
	{
		?><form action="<?= URL::link(URL::$route['l-path']) ?>" method="post">
			
			<input type="hidden" name="form-name" value="<?= $prop['name'] ?>"/><?php
		
		foreach($prop['fields'] as $k => $field)
		{
			$key = $field['field'] = first($field['field'], $k);
			$field['value'] = first($prop['data'][$key], $field['value']);
			$field['error'] = first($field['error'], $prop['errors'][$key]);
			print(component('form/'.$field['type'], $field));
		}
			
		?></form><?
	}
		
});
