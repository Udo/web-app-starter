<? return(function($prop) { 

	$items = array(
		'inbox' => array(
			'title' => 'Inbox',
			array('title' => 'Dashboard', 		'url' => '/', 'icon' => ''),
			array('title' => 'Inbox', 			'url' => '/inbox', 'icon' => ''),
			array('title' => 'Scans', 			'url' => '/scans', 'icon' => ''),
			array('title' => 'Issue Tracker', 	'url' => '/issues', 'icon' => ''),
		),
		'orders' => array(
			'title' => 'Orders',
			array('title' => 'Sales Orders',    'url' => '/sales', 'icon' => ''),
			array('title' => 'Purchase Orders', 'url' => '/purchase', 'icon' => ''),
			array('title' => 'Shipping Orders', 'url' => '/shipping', 'icon' => ''),
			array('title' => 'Invoices', 		'url' => '/invoices', 'icon' => ''),
			array('title' => 'Contracts',       'url' => '/contracts', 'icon' => ''),
		),
		'inventory' => array(
			'title' => 'Inventory',
			array('title' => 'Inventory Report', 'url' => '/inventory', 'icon' => ''),
			array('title' => 'Lots', 			 'url' => '/lots', 'icon' => ''),
			array('title' => 'Transaction List', 'url' => '/transactions', 'icon' => ''),
			array('title' => 'Warehouses', 		 'url' => '/warehouses', 'icon' => ''),
			array('title' => 'Assets', 			 'url' => '/assets', 'icon' => ''),
		),
		'database' => array(
			'title' => 'Database',
			array('title' => 'Companies', 		 'url' => '/db/companies', 'icon' => ''),
			array('title' => 'Products', 		 'url' => '/db/products', 'icon' => ''),
			array('title' => 'Product Families', 'url' => '/db/product_families', 'icon' => ''),
			array('title' => 'Checklists', 		 'url' => '/db/checklists', 'icon' => ''),
			array('title' => 'Countries', 		 'url' => '/db/countries', 'icon' => ''),
		),
		'tools' => array(
			'title' => 'Tools',
			array('title' => 'Reports', 		 'url' => '/reports', 'icon' => ''),
			array('title' => 'Apps', 	 		 'url' => '/apps', 'icon' => ''),
			array('title' => 'Change Tracker', 	 'url' => '/track/changes', 'icon' => ''),
			array('title' => 'Administration', 	 'url' => '/admin', 'icon' => ''),
			array('title' => 'Email Archive',    'url' => '/db/emails', 'icon' => ''),
			array('title' => 'Settings',         'url' => '/settings', 'icon' => ''),
		),
		'organization' => array(
			'title' => 'Organization',
			array('title' => 'Attendance',       'url' => '/org/attendance', 'icon' => ''),
			array('title' => 'Reimbursements',   'url' => '/org/reimbursements', 'icon' => ''),
			array('title' => 'Vacations',        'url' => '/org/vacations', 'icon' => ''),
		),
	);
	
	$current_sub = 'inbox';
	$current_path = '/'.URL::$route['l-path'];
	foreach($items as $sub_key => $subitems)
	{
		foreach($subitems as $si) if(is_array($si))
		{
			if($si['url'] == $current_path)
				$current_sub = $sub_key;
		}
	}
	
	$submenu = first($_REQUEST['m'], $current_sub, 'inbox');
	
	?><div <?= ap($prop) ?> class="menu-bar"><?php
		
	if(!$items[$submenu])
	{
		banner('Error: menu not found ('.htmlspecialchars($submenu).')');
	}
	else
	{		
		print('<h1>'.$items[$submenu]['title'].'</h1>');
		
		foreach($items[$submenu] as $item) if(is_array($item))
		{
			?><a href="<?= $item['url'] ?>" 
				class="<?= $item['url'] == $current_path ? 'active' : '' ?>"><i class=""></i> <?= htmlspecialchars($item['title']) ?></a><?
		}
	}
			
?></div><? });