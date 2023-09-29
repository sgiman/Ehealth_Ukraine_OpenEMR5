<?php
/***********************************************************************************
 *  api/Dictionaries/StreetTypes.php
 *  (007) STREET_TYPE (GET)
 *  https://api-preprod.ehealth.gov.ua/api/dictionaries?name=STREET_TYPE
 *
 *  @package OpenEMR
 *  @link    http://www.open-emr.org
 *
 *  API EHEALTH version 1.0
 *  Writing by sgiman, 2020
***********************************************************************************/
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json');

	require_once '../Common/config.php';
	require_once '../Common/functions.php';

	$data = 	'{"data":[{"is_active":true,"labels":["SYSTEM","EXTERNAL"],"name":"STREET_TYPE","values":{"BOULEVARD":"бульвар","GARDEN_SQUARE":"сквер","ROADWAY":"тракт","MASSIF":"масив","GROVE":"гай","SQUARE":"площа","SETTLEMENT":"селище","ROAD":"дорога","BOARGING_HOUSE":"пансіонат","MICRODISTRICT":"мікрорайон","ISLAND":"острів","TRACK":"траса","DACHA_COOPERATIVE":"Дачний кооператив","MAIDAN":"майдан","ASCENT":"узвіз","BLIND_STREET":"тупик","STATION_SETTLEMENT":"будинок МПС","LINE":"лінія","RIVER_SIDE":"набережна","PASS":"прохід","SELECTION_BASE":"селекційна станція","MINE":"шахта","LANE":"провулок","AVENUE":"проспект","BLOCK":"квартал","MILITARY_BASE":"військова частина","COUNTRY_LANE":"завулок","WAY":"шлях","STATION":"станція","PASSAGE":"проїзд","SMALL VILLAGE":"присілок","HIGHWAY":"шосе","STREET":"вулиця","TRACT":"урочище","STATE_FARM":"радгосп","ALLEY":"алея","SANATORIUM":"санаторій","ENTRANCE":"в\'їзд","HAMLET":"хутір","FORESTRY":"лісництво","PASSING_LOOP":"роз\'їзд","HOUSING_AREA":"житловий масив","CROSSROADS":"розвилка","PARK":"парк"}}],"meta":{"code":200,"type":"list"}}';

	echo request_dictionaries($data);

	//$url = "{$api}/api/dictionaries?name=STREET_TYPE";
	//echo request_ehealth($url);

?>