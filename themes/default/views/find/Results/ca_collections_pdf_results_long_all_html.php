<?php
/* ----------------------------------------------------------------------
 * themes/default/views/find/ca_collections_pdf_results_html.php
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2011 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
// Sam: 6000 sec to create the PDF
set_time_limit(600000);
require_once(__CA_APP_DIR__."/helpers/digitoolHelpers.php");

$t_display				= $this->getVar('t_display');
$va_display_list 		= $this->getVar('display_list');
$vo_result 				= $this->getVar('result');
$vn_items_per_page 		= $this->getVar('current_items_per_page');
$vs_current_sort 		= $this->getVar('current_sort');
$vs_default_action		= $this->getVar('default_action');
$vo_ar					= $this->getVar('access_restrictions');
$vo_result_context 		= $this->getVar('result_context');

$vn_num_items			= (int)$vo_result->numHits();

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        body {margin-top: 22mm; }
        #pageHeader { background-color: #81a43b; padding: 3px 5px 2px 10px; height: 75px;width: 190mm; position: fixed;top:0;}
        .headerText { color: #FFFFFF; margin: -5px 0px 10px 35px; }
        .pagingText { color: #FFFFFF; margin: -5px 0px 10px 35px; text-align: right;}
        span.label {font-weight: bold; text-align: left;}
        span.value {text-align: left;word-warp:break-word;word-break:break-all;}
        .table {display:table;page-break-inside: auto;width:190mm; border-collapse:collapse;}
        .even,.odd { display:table-row;page-break-inside: auto;width:190mm; margin-left: 4mm;}
        .datacell {
            display:table-cell;
            page-break-inside: auto;
            font-family:Helvetica,"DejaVu Sans",sans-serif;
            font-size: 10px;
            max-height: 80mm;
            vertical-align: text-top;
            width: 190mm;
            border-bottom-style:solid;
            border-bottom-width: 1px;
            padding-bottom: 2mm;
        }
        .image {
            display:table-cell;
            page-break-inside: auto;
            max-height: 80mm;
            vertical-align: middle;
            text-align: center;
            width: 0mm;
            padding: 1mm;
            border-bottom-style:solid;
            border-bottom-width: 1px;
        }
        .image > img { max-height: 50mm; max-width: 50mm;}
    </style>
    <?php
    //$vn_start = 0;
    //while($vn_start < (sizeof($va_display_list) -1))   {

    ?>
</head>
<body>
<div id='pageHeader'>
    <?php

    if($this->request->config->get('report_header_enabled')) {
        if(file_exists($this->request->getThemeDirectoryPath()."/graphics/logos/".$this->request->config->get('report_img'))){
            print '<img src="'.$this->request->getThemeDirectoryPath().'/graphics/logos/'.$this->request->config->get('report_img').'" style="float:left; margin-right: 5 mm; height: 75px;" />';
        }
        if(file_exists($this->request->getThemeDirectoryPath()."/graphics/logos/".$this->request->config->get('logoostvl'))){
            print '<img src="'.$this->request->getThemeDirectoryPath().'/graphics/logos/'.$this->request->config->get('logoostvl').'" style="float:left; margin-right: 5 mm; height: 75px;" />';
        }
        if(file_exists($this->request->getThemeDirectoryPath()."/graphics/logos/".$this->request->config->get('logoantw'))){
            print '<img src="'.$this->request->getThemeDirectoryPath().'/graphics/logos/'.$this->request->config->get('logoantw').'" style="float:left; height: 75px;" />';
        }
        if($this->request->config->get('report_show_search_term')) {
            print "<span class='headerText'>".$this->getVar('criteria_summary_truncated')."</span><br>";
        }
        if($this->request->config->get('report_show_timestamp')) {
            print "<span class='headerText'>".caGetLocalizedDate(null, array('dateFormat' => 'delimited'))."</span><br>";
        }
        if($this->request->config->get('report_show_number_results')) {
            print "<span class='headerText results'>".(($vn_num_items == 1) ? _t('%1 item', $vn_num_items) : _t('%1 items', $vn_num_items))."</span>";
        }
    }
    ?>
    <script type="text/php">
                        $font = Font_Metrics::get_font("sans-serif");
                        $text = $pdf->page_text(298, 790, "{PAGE_NUM} / {PAGE_COUNT}", $font,8, array(0,0,0));
</script>
</div>
<?php
$i = 0;
$vo_result->seek(0);

while($vo_result->nextHit()) {
    $vn_collection_id = $vo_result->get('collection_id');

    /* aangepast door Sam - libis
    require_once(__CA_MODELS_DIR__.'/ca_objects.php');
    $t_object = new ca_objects();
    $t_object->load($vn_object_id);

    $object_images = getDigitoolPids($t_object->get('digitoolUrl', array('returnAsArray' => true)));
    */  #einde libis
    ($i == 2) ? $i = 0 : "";
    ?>
    <div class='table'><div <?php print ($i ==1) ? "class='odd'" : "class='even'"; ?>>
            <?php

            #$thumb_rep = getDigitoolThumbnailBase($object_images[0]);
            $thumb_rep = '';

            if(!empty($thumb_rep))
            {
                print '<div class="image">'.  $thumb_rep . '</div><div class="datacell">';
            } else {
                print '<div class="image">&nbsp;</div><div class="datacell">';
            }

            foreach($va_display_list as $vn_placement_id => $va_display_item) {

                $vs_display_value = $t_display->getDisplayValue($vo_result, $vn_placement_id, array('forReport' => true, 'purify' => true));

                if(!empty($vs_display_value))
                {
                    if($va_display_item['display'] != "Titel") {
                        $labelToDisplay = "<br/><span class='label'>".$va_display_item['display'];
                    } else {
                        $labelToDisplay = "<span class='label'>".$va_display_item['display'];
                    }

                    // Sam: mag verwijder worden als de KV nummers weg zijn
                    if (strpos($vs_display_value,'(KV_') !== false) {
                        $temp = preg_split("/ - \(KV_[0-9]+\)$/", $vs_display_value);
                        $vs_display_value = $temp[0];
                    }
                    print $labelToDisplay . ": </span><span class='value' >".$vs_display_value."</span>";

                    if(strpos($labelToDisplay,'Inventarisnummer') !== false)
                    {print "<br />";}
                }
            }
            ?>	<br/></div></div></div>
    <?php
    $i++;
}
//$vn_start = $vn_start + 6;
?>
</body>
</html>
