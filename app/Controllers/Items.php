<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Items extends Controller
{

	public function index(string $type, string $all = 'false')
	{
		$db = \Config\Database::connect();
		$builder = $db->table('creatures');
		$builder->join('available_months', 'creatures.id = available_months.creature_id', 'left');
		$builder->where('creatures.type', $type);
		if($all === 'false'){
			$builder->groupStart();
				$builder->where('available_months.month', date('n'));
				$builder->orWhere('available_months.month IS NULL', null, false);
			$builder->groupEnd();
		}
		$builder->groupBy('creatures.id');
		$builder->orderBy('creatures.sell', 'DESC');
		$query = $builder->get();
		$data['creatures'] = array();
		foreach($query->getResult() as $row){

			// Sanitise name for icon
			$row->sanitisedname = preg_replace('/[^a-z]/', '', strtolower($row->name));

			// Parse available time
			if($row->time_start === '0' && $row->time_end === '23'){
				$row->timereadable = 'All Day';
			}else{
				$row->timereadable = array();
				foreach (array($row->time_start, $row->time_end) as $value) {
					$value = intval($value);
					if($value > 12){
						$row->timereadable[] = ($value - 12) . 'pm';
					}else{
						$row->timereadable[] = $value . 'am';
					}
				}
				$row->timereadable = implode(' - ', $row->timereadable);
			}

			// Parse size
			switch ($row->size) {
				case '0':
					$row->sizereadable = 'Narrow';
					break;

				case '1':
					$row->sizereadable = 'Tiny';
					break;

				case '2':
					$row->sizereadable = 'Small';
					break;

				case '3':
					$row->sizereadable = 'Medium';
					break;

				case '4':
					$row->sizereadable = 'Large';
					break;

				case '5':
					$row->sizereadable = 'Very Large';
					break;

				case '6':
					$row->sizereadable = 'Huge';
					break;
				
				default:
					$row->sizereadable = $row->size;
					break;
			}

			if(!empty($row->fin)){
				$row->sizereadable .= ' (fin)';
			}

			$data['creatures'][] = $row;


		}

		echo view('layout/header');
		echo view('creatures', $data);
		echo view('layout/footer');
	}

	public function importer(){
		$importdir = FCPATH . '../../villagerdb/data/items/';
		if(file_exists($importdir)){
			$dirlist = scandir($importdir);

			$db = \Config\Database::connect();

			$iteminsert = array();

			$availablemonths = array();


			// Add creatures
			foreach($dirlist as $file){
				$filepath = $importdir . $file;
				if(!is_dir($filepath)){
					$data = file_get_contents($filepath);
					if(strpos($data, '"nh":') !== false){

						$data = json_decode($data, true);

						// Check if fish or bug then parse source
						if(isset(($data['games']['nh']['sources'])) && in_array($data['category'], array('Fish', 'Bugs'))){

							$itemid = $category = $sell = $buy = $size = $foundat = null;
							$fin = 0;
							$timestart = 0;
							$timeend = 23;

							if($data['category'] === 'Fish'){
								$category = 'fish';
							}else{
								$category = 'insect';
							}

							// Check price
							if(isset($data['games']['nh']['sellPrice']['value'])){
								$sell = $data['games']['nh']['sellPrice']['value'];
							}
							if(isset($data['games']['nh']['buyPrices'][0]['value'])){
								$buy = $data['games']['nh']['buyPrices'][0]['value'];
							}

							// Check source
							if(isset($data['games']['nh']['sources'][0])){
								$source = $data['games']['nh']['sources'][0];

								// Check size and fin for fish
								if($category === 'fish'){
									if(stripos($source, 'finned') !== false){
										$fin = 1;
										$size = 6;
									}
									if(preg_match('/^North:\s([A-Za-z\s]+)\sShadows/i', $source, $shadowsize) === 1){
										$shadowsize = trim(strtolower(end($shadowsize)));
										switch ($shadowsize) {
											case 'narrow':
												$size = '0';
												break;

											case 'tiny':
												$size = '1';
												break;
											
											case 'small':
												$size = '2';
												break;

											case 'medium':
												$size = '3';
												break;

											case 'large':
												$size = '4';
												break;

											case 'very large':
												$size = '5';
												break;

											case 'huge':
												$size = '6';
												break;
										}

									}
								}


								// Get months
								if(preg_match('/,\s([A-Za-z\s\-]+)\s\(/', $source, $months) === 1){
									$months = end($months);
									if(strtolower($months) === 'all year'){
										$availablemonths[$data['name']] = null;
									}else{
										$months = explode(' and ', $months);
										foreach($months as $range){
											$range = explode('-', $range);
											foreach($range as $mkey => $m){
												$range[$mkey] = intval(date_create_from_format('M', $m)->format('n'));
											}

											if(count($range) === 1){
												$range = array(intval(current($range)));
											}else{
												if($range[0] < $range[1]){
													$range = range($range[0], $range[1]);
												}else{
													$range = array_merge(
														range($range[0], 12),
														range(1, $range[1])
													);
												}
											}
											foreach($range as $m){
												$availablemonths[$data['name']][] = $m;
											}
										}
									}

								}

								// Get time
								if(preg_match('/\(([\d\-a-zA-Z]+)\)$/', $source, $time) === 1){
									$time = end($time);
									if(strtolower($time) !== 'all day'){
										$times = explode('-', $time);
										foreach($times as $tkey => $time){
											$i = intval(str_replace(array('am', 'pm'), '', $time));
											if(stripos($time, 'pm') !== false){
												$i = $i + 12;
											}
											if($tkey === 0){
												$timestart = $i;
											}else{
												$timeend = $i;
											}
										}
									}
								}

								// Get location
								if(preg_match('/Shadows\s(.*)\,/i', $source, $location) === 1 && $category === 'fish'){
									$location = trim(strtolower(end($location)));
									switch ($location) {
										case 'at sea':
											$foundat = 'sea';
											break;
										
										case 'on river':
											$foundat = 'river';
											break;

										case 'at pier':
											$foundat = 'pier';
											break;

										case 'in pond':
											$foundat = 'pond';
											break;

										case 'on clifftop rivers':
											$foundat = 'clifftop river';
											break;

										case 'at sea while raining':
											$foundat = 'sea (rain)';
											break;

										case 'on river mouth':
											$foundat = 'river mouth';
											break;
									}
								}elseif(preg_match('/^North:\s([A-Za-z\s]+),/i', $source, $location) && $category === 'insect'){
									$location = trim(strtolower(end($location)));
									switch($location) {
										case 'flying around':
											$foundat = 'flying';
											break;

										case 'near rotten food on the ground':
											$foundat = 'rotten food';
											break;

										case 'on the side of trees':
											$foundat = 'tree trunk';
											break;

										case 'on the ground':
										case 'on ground':
											$foundat = 'ground';
											break;

										case 'on the side of coconut trees':
											$foundat = 'coconut tree';
											break;

										case 'hit rock with shovel or axe':
											$foundat = 'hit rock';
											break;

										case 'on rocks and bushes during rain':
											$foundat = 'rocks and bushes (rain)';
											break;

										case 'rolling snowballs on ground':
											$foundat = 'rolling snowballs';
											break;

										case 'shake trees':
										case 'from nest after shaking trees':
											$foundat = 'shake trees';
											break;

										case 'bouncing on villagers heads':
											$foundat = 'villagers';
											break;

										case 'walking on top of rivers and ponds':
											$foundat = 'ponds and rivers';
											break;

										case 'flying around flowers':
										case 'on flowers':
											$foundat = 'flowers';
											break;

										case 'flying around hybrid flowers':
											$foundat = 'hybrid flowers';
											break;

										case 'on stumps':
											$foundat = 'tree stump';
											break;

										case 'on shore rocks':
											$foundat = 'shore rocks';
											break;

										case 'disguised as furniture leaf near trees':
											$foundat = 'disguised as furniture leaf';
											break;

										case 'underground (dig near chirping)':
											$foundat = 'underground (near chirping)';
											break;

										case 'flying around lights and lamps outside':
											$foundat = 'lights';
											break;


										case 'on the beach':
											$foundat = 'beach';
											break;

										case 'flying around trash or rotten food':
											$foundat = 'trash and rotten food';
											break;

									}
								}


							}

							// If new item then create
							if(!isset($items[$data['name']])){
								$iteminsert[] = array(
									'name' => $data['name'],
									'type' => $category,
									'sell' => $sell,
									'buy' => $buy,
									'size' => $size,
									'fin' => $fin,
									'location' => $foundat,
									'time_start' => $timestart,
									'time_end' => $timeend
								);
							}
						}
					}
				}
			}

			// print "<pre>";
			// var_dump($iteminsert);
			// exit();

			$db->transStart();
			// Truncate tables
				$db->query('SET FOREIGN_KEY_CHECKS = 0');
				$db->query('TRUNCATE TABLE available_months');
				$db->query('TRUNCATE TABLE creatures');
				$db->query('SET FOREIGN_KEY_CHECKS = 1');
			$db->transComplete();


			// Insert creatures
			$builder = $db->table('creatures');
			if(!empty($iteminsert)){
				$builder->insertBatch($iteminsert);
			}

			// Add month availability
			$creatures = $builder->get();
			$monthinsert = array();
			foreach($creatures->getResult() as $row){
				if(isset($availablemonths[$row->name])){
					foreach($availablemonths[$row->name] as $month){
						$monthinsert[] = array(
							'creature_id' => $row->id,
							'month' => $month
						);
					}
				}
			}
			$builder = $db->table('available_months');
			$builder->insertBatch($monthinsert);
		}

	}
}