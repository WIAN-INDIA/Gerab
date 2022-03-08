<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: application/json');
include("conn.php");

$iID = isset($_GET['fileID']) ? $_GET['fileID'] : 0;

$cResponse = "{}";

if ($iID > 0) {
	$cQuery = "SELECT pages, pages_parsed, module, parsed, name, TIMESTAMPDIFF(MINUTE,uploaded,NOW()) as mins FROM pdf WHERE pdf_id = $iID";
	$result = $mysqli -> query($cQuery);
	
	if ($result->num_rows > 0) {
		$row = $result -> fetch_row();
		$cType = $row[2];
		$cName = $row[4];
		$iMin = $row[5];
		if ($row[3] != "" || $iMin > 22) {
			$cSub = "";
			$cQuery = <<<EOF
			SELECT CONCAT('"',page,'_',data_key,'":',json_data,',') as extract, json_data, CONCAT(page,'_',data_key) as combkey FROM extraction WHERE pdf_id = $iID order by extract_id;
EOF;
			
			$res = $mysqli -> query($cQuery);
			
			if ($res->num_rows > 0) {
				$cData = "";
				$aTen = array();
				while ($row1 = $res -> fetch_assoc()) {
					if ($cType == "tenaris") {
						$aJson = json_decode($row1['json_data'], true);
						
						$i = 0;
						if (is_array($aJson)) {
							foreach($aJson as $key => $item) {
								$cKey = $row1['combkey']."_".$i;
								$i++;
								if (is_array($item)) {
									$j = 0;
									if (count($item) == 1) {
										if (is_integer($key)) {
											$cKey .= "_".$j;
											$aTen[$cKey][$j] = $item[0];
										} else {
											$cKey .= "_".$j;
											$aTen[$cKey][$j][$key] = $item[0];											
										}
									} else if (count($item) == 2) {
										$cKey .= "_".$j;
										
										if (is_array($item)) {
											//print_r($item);echo "$key --- $cKey\n\n";
											foreach ($item as $val) { 
												if (is_integer($key)) {
													$aTen[$cKey][$j][] = $val;
												} else {
													$aTen[$cKey][$j][$key][] = $val;
												}
											}
										} else {
											//echo "$cKey 11\n\n";
											$cTmp = $item[0];
											if (is_integer($key)) {
												$aTen[$cKey][$j][$cTmp] = $item[1];
											} else {
												$aTen[$cKey][$j][$cTmp][$key] = $item[1];
											}
										}
									} else {
										foreach ($item as $key1 => $val) {
											if (is_integer($key1)) {
												if (is_array($val)) {
													$aTen[$cKey][] = $val;
												} else {
													$aTen[$cKey] = $val;
												}
											} else {
												if (is_array($val)) {
													$aTen[$cKey][$key1] = $val;
												} else {
													$aTen[$cKey] = $val;
												}
											}
											$j++;
										}
									}
								} else {
									if (is_integer($key)) {
										$aTen[$cKey] = $item;
									} else {
										$aTmp = array();
										$aTmp[$key] = $item;
										$aTen[$cKey][] = $aTmp;
									}
								}
							}
						} else {
							$cKey = $row1['combkey'];
							$aTen[$cKey] = $aJson;
						}
					} else {
						$cData .= $row1['extract'];
					}
				}
				
				if ($cType == "tenaris") {
					$cResponse = '{"status":"Processed", "data": '.json_encode($aTen).'}';
				} else {
					$cResponse = '{"status":"Processed", "data": {'.substr($cData,0,-1).'}}';
				}
			}
			$res -> close();
		} else {
			$cResponse = '{"status":"Processing","data":{}}';
		}
		$result -> close();
	} else {
		$cResponse = '{"status":"Invalid","data":{}}';
	}
} else {
	$cResponse = '{"status":"Invalid","data":{}}';
}

echo $cResponse;

$mysqli -> close();
?>

