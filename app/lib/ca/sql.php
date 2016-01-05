<?php
/*
 * Step 1: Initialisation
 */
set_time_limit(36000);
require_once("/www/libis/html/ca_crkc/setup.php");

require_once(__CA_LIB_DIR__.'/core/Db.php');

require_once(__CA_MODELS_DIR__.'/ca_locales.php');
require_once(__CA_MODELS_DIR__.'/ca_attributes.php');
require_once(__CA_MODELS_DIR__.'/ca_objects.php');
require_once(__CA_MODELS_DIR__.'/ca_lists.php');
require_once(__CA_MODELS_DIR__.'/ca_list_items.php');
/*
require_once(__CA_LIB_DIR__.'/core/Parsers/DelimitedDataParser.php');
*/
$_ = new Zend_Translate('gettext', __CA_APP_DIR__.'/locale/en_US/messages.mo', 'nl_NL');

$t_locale = new ca_locales();
$pn_locale_id = $t_locale->loadLocaleByCode('nl_NL');

$t_list = new ca_lists();

$t_attrib = new ca_attributes();
$t_attrib->setMode(ACCESS_WRITE);

$t_object = new ca_objects();
$t_object->setMode(ACCESS_WRITE);

print "start\n";

$o_db = new Db();

$o_config = Configuration::load();

/*
$qr1 = "SELECT attribute_id, value_longtext1 FROM ca_attribute_values WHERE (element_id = 124 and value_longtext1 is not null) and attribute_id < 1974065";

$qr1 = "SELECT attribute_id, value_longtext1 
	FROM ca_attribute_values 
	WHERE (element_id = 124 and value_longtext1 is not null) and attribute_id = 1882957 ";
*/

$vn_c = 1;

$qr1 = "SELECT x.object_id, x.idno, capl.item_id, item_value FROM ca_objects x INNER JOIN ca_attributes AS cap ON cap.row_id = x.object_id INNER JOIN ca_attribute_values AS capl ON capl.attribute_id = cap.attribute_id INNER JOIN ca_list_items AS it ON it.item_id = capl.item_id WHERE capl.element_id = 167 AND x.object_id < 129600";
	
print "{$qr1} \n";

$rs = $o_db->query($qr1);

$dimension = rowcount($rs);

print "{$dimension}\n";

print "aantal te behandelen records {$dimension} \n";

while ($rs->nextRow())
{
	$object_id = $rs->get('x.object_id');
	$idno = $rs->get('x.idno');
	$item_id = $rs->get('capl.item_id');
	$item_value = $rs->get('item_value');
	
	print "{$object_id}\ t {$idno} \t {$item_id} \t {$item_value} \n";
	
	$vn_c++;
					
} 

?>
