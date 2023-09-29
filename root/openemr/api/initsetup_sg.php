<?php

/**
 * api/initsetup.php perform database changes.
 *
 * API create and modify database tables.
 * 
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * API SCSMed version 1.5
 * Modified by sgiman, 2016-2019
 */

/** 
 * Copyright (C) 2012 Karl Englund <karl@mastermobileproducts.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-3.0.html>;.
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * Modified by sgiman, 2016
 *
 
$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once(dirname(dirname(__FILE__)) . "/interface/globals.php");

if(!$GLOBALS['rest_api_server']){
    echo "<openemr>
            <status>-1</status>
            <reason>Please check the REST API server settings in Administration/Globals/Connectors</reason>
        </openemr>";
    exit;
}
*/


/*
<?php
/**
 * Copyright (C) 2012 Karl Englund <karl@mastermobileproducts.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-3.0.html>;.
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * Modified by sgiman, 2016

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once(dirname(dirname(__FILE__)) . "/interface/globals.php");

//if(!$GLOBALS['rest_api_server']){
//    echo "<openemr>
//            <status>-1</status>
//            <reason>Please check the REST API server settings in Administration/Globals/Connectors</reason>
//        </openemr>";
//    exit;
//}

*/


$rest_api_server = isset($_GET['rest_api_enable']) ? $_GET['rest_api_enable'] : true;

$withurl = 
'
/** 
 * Copyright (C) 2012 Karl Englund <karl@mastermobileproducts.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-3.0.html>;.
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * Modified by sgiman, 2016
 *
 
$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once(dirname(dirname(__FILE__)) . "/interface/globals.php");

if(!$GLOBALS['rest_api_server']){
    echo "<openemr>
            <status>-1</status>
            <reason>Please check the REST API server settings in Administration/Globals/Connectors</reason>
        </openemr>";
    exit;
}';



$withouturl = 
'
<?php
/**
 * Copyright (C) 2012 Karl Englund <karl@mastermobileproducts.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-3.0.html>;.
 *
 * @package OpenEMR
 * @link    http://www.open-emr.org
 * Modified by sgiman, 2016

$fake_register_globals=false;
$sanitize_all_escapes=true;

include_once(dirname(dirname(__FILE__)) . "/interface/globals.php");

//if(!$GLOBALS['rest_api_server']){
//    echo "<openemr>
//            <status>-1</status>
//            <reason>Please check the REST API server settings in Administration/Globals/Connectors</reason>
//        </openemr>";
//    exit;
//}';

$res3 = false;

if ($rest_api_server) {
    $res3 = file_put_contents('classes.php', base64_decode($withurl));
} else {
    $res3 = file_put_contents('classes.php', base64_decode($withouturl));
}


$ignoreAuth = true;
require_once('classes.php');

$query1 = "CREATE TABLE IF NOT EXISTS `api_tokens` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `user_id` bigint(20) DEFAULT NULL,
            `token` varchar(150) DEFAULT NULL,
            `device_token` varchar(200) NOT NULL,
            `create_datetime` datetime DEFAULT NULL,
            `expire_datetime` datetime DEFAULT NULL,
            `message_badge` int(5) NOT NULL,
            `appointment_badge` int(5) NOT NULL,
            `labreports_badge` int(5) NOT NULL,
            `prescription_badge` int(5) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

$db->query($query1);
$res1 = $db->result;

$query2 = "ALTER TABLE `users` ADD `create_date` DATE NOT NULL ,
            ADD `secret_key` VARCHAR( 100 ) NULL ,
            ADD `ip_address` VARCHAR( 20 ) NULL ,
            ADD `country_code` VARCHAR( 10 ) NULL ,
            ADD `country_name` INT( 50 ) NULL ,
            ADD `latidute` VARCHAR( 20 ) NULL ,
            ADD `longitude` VARCHAR( 20 ) NULL ,
            ADD `time_zone` VARCHAR( 10 ) NULL";

$db->query($query2);
$res2 = $db->result;


if ($res1 && $res2 || $res3) {
    echo "<h1>Database Updated Successfully</h1>";
} else {
    echo "<h1>Database Failed to Update</h1>";
}
?>
