<?php

class SVG
{

	static function circle_segment($midx, $midy, $radius, $start_angle, $end_angle, 
		$stroke_color = 'rgba(120,120,120,1.0)', $stroke_width = 1, $fill_color = 'rgba(0,0,0,0)', $style = '') 
	{
		$start_x = $midx + $radius * cos($start_angle);
		$start_y = $midy + $radius * sin($start_angle);
		$end_x = $midx + $radius * cos($end_angle);
		$end_y = $midy + $radius * sin($end_angle);
		
		while ($start_angle < 0) $start_angle += 2 * M_PI;
		while ($end_angle < 0) $end_angle += 2 * M_PI;
		while ($start_angle >= 2 * M_PI) $start_angle -= 2 * M_PI;
		while ($end_angle >= 2 * M_PI) $end_angle -= 2 * M_PI;
		
		$angle_diff = $end_angle - $start_angle;
		if ($angle_diff < 0) $angle_diff += 2 * M_PI;
		
		$large_arc_flag = ($angle_diff > M_PI) ? 1 : 0;
		$sweep_flag = 1; 
		
		?>
		<path d="M <?= $start_x ?> <?= $start_y ?> A <?= $radius ?> <?= $radius ?> 0 <?= $large_arc_flag ?> <?= $sweep_flag ?> <?= $end_x ?> <?= $end_y ?>" 
			style="<?= $style ?>" fill="<?= $fill_color ?>" stroke="<?= $stroke_color ?>" stroke-width="<?= $stroke_width ?>" />
		<?php
	}

}

