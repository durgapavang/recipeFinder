<?php


$fridge_csv = file_get_contents($_FILES['fridge_csv_file']['tmp_name']);
$fridge_items = array_map("str_getcsv", explode("\n", $fridge_csv));


$recipe_json = file_get_contents($_FILES['recipes_json_file']['tmp_name']);
$recipe_items = json_decode($recipe_json);

$reciepe_dates = array();

	if(count($recipe_items) > 0)
	{
		foreach($recipe_items as $recipe)
		{
			$ing_dates = array();
			foreach($recipe->ingredients as $ing)
			{
				
				$item = $ing->item;
				foreach($fridge_items as $fitems)
				{
					if(in_array($item,array_values($fitems)))
					{
						array_push($ing_dates,$fitems[3]);
					}
				}
			}
			
			$date_arr = $ing_dates;

			$min_key = array_search(min($ing_dates), $ing_dates);
			
		

		$reciepe_dates[$recipe->name] = $ing_dates[$min_key];
		echo "<pre>";
		print_r($reciepe_dates);
		}
		$recipes = array_keys($reciepe_dates);
		$min_key = array_search(min(array_values($reciepe_dates)), array_values($reciepe_dates));
		echo $recipes[$min_key];
	}
	else
	{

	}
?>