<?php

/* get contents of two input files */
$fridge_csv = file_get_contents($_FILES['fridge_csv_file']['tmp_name']);
$fridge_items = array_map("str_getcsv", explode("\n", $fridge_csv));


$recipe_json = file_get_contents($_FILES['recipes_json_file']['tmp_name']);
$recipe_items = json_decode($recipe_json);

$reciepe_dates = array();

	if(count($recipe_items) > 0)
	{

		// arrange dates of ingredients of each reciepe
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
						array_push($ing_dates,str_replace('/','-',$fitems[3]));
					}
				}
			}
			
			$date_arr = $ing_dates;
			
			// find out smallest use by date of the ingredient

			$lowestDate = strtotime($ing_dates[0]);
			foreach($ing_dates as $date){
				
			    if(strtotime($date) < $lowestDate){
			        $lowestDate = strtotime($date);
			    }

			}
			
			// get the key of the date.
			 $min_key = array_search(date( 'j-n-Y', $lowestDate), $ing_dates);
			
		
			 // arrange the smallest date's recipe and date.
		$reciepe_dates[$recipe->name] = $ing_dates[$min_key];
		
		}

		$recipes = array_keys($reciepe_dates);
		$final_r_dates = array_values($reciepe_dates);
		// find out smallest date among the recipes
		
		$lowestDate = strtotime($final_r_dates[0]);
			foreach($final_r_dates as $date){
			    if(strtotime($date) < $lowestDate){
			        $lowestDate = strtotime($date);
			    }
			}




		$min_key = array_search(date( 'j-n-Y', $lowestDate), $final_r_dates);
		echo "Recipe Finder selects : <b>".$recipes[$min_key] ."</b>";
	}
	
?>