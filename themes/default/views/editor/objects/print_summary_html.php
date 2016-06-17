<?php
/* ----------------------------------------------------------------------
 * views/editor/objects/summary_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2010-2011 Whirl-i-Gig
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
require_once(__CA_APP_DIR__."/helpers/digitoolHelpers.php");

$t_item = $this->getVar('t_subject');

$va_bundle_displays = $this->getVar('bundle_displays');
$t_display = $this->getVar('t_display');
$va_placements = $this->getVar("placements");

?>
<html>
<head>
    <!-- activeren van utf-8 -->
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <style type="text/css">
        #pageHeader { background-color: #81a43b; padding: 3px 5px 2px 10px; height: 75px;width: 190mm; position: absolute;top:0;}
        .headerText { color: #FFFFFF; margin: 0px 0px 10px 25px; float:right;}
        h1 { font-family:Verdana, Arial,Helvetica,"DejaVu Sans",sans-serif;font-size:14px;}
        span.label {font-weight: bold; text-align: left;}
        span.value {text-align: left;word-warp:break-word;word-break:break-all;}
        .table {display:table;page-break-inside: auto;width:190mm; border-collapse:collapse;}
        .even,.odd { display:table-row;page-break-inside: auto;width:180mm; margin-left: 4mm; max-height: 200mm;}
        .datacell {
            display:table-cell;
            page-break-inside: auto;
            font-family:Helvetica,"DejaVu Sans",sans-serif;
            font-size: 10px;
            vertical-align: text-top;
            width: 95mm;
            padding-bottom: 2mm;
        }
        .image {
            display:table-cell;
            page-break-inside: auto;
            vertical-align: top;
            text-align: center;
            width: 50mm;
            padding: 0.5mm;
        }
        .image > img { max-height: 50mm; max-width: 50mm;}
    </style>
</head>
<body>
<?php
if($this->request->config->get('summary_header_enabled')) {
    ?>
    <div id='pageHeader'>
        <?php
        if(file_exists($this->request->getThemeDirectoryPath()."/graphics/logos/".$this->request->config->get('summary_img'))){
            ?>
            <img src="<?php print $this->request->getThemeDirectoryPath()."/graphics/logos/".$this->request->config->get('summary_img'); ?>"/>
        <?php
        }
        ?>
        <?php
        if($this->request->config->get('summary_show_timestamp')) {
            print "<span class='headerText'>".caGetLocalizedDate()."</span>";
        }

        if($this->request->config->get('summary_show_identifier')) {
            // Sam: mag verwijder worden als de KV nummers weg zijn
            if (strpos($t_item->get("idno"),'(KV_') !== false) {
                $temp = preg_split("/ - \(KV_[0-9]+\)$/", $t_item->get("idno"));
                $idno_text = $temp[0];
            } else {
                $idno_text = $t_item->get("idno");
            }
            print "<span class='headerText'>".$idno_text."</span>";
        }
        ?>
    </div>
<?php
}

if($this->request->config->get('summary_footer_enabled')) {
    ?>
    <table class="page_footer">
        <tr>
            <td style="width: 33%; text-align: left;">
                &nbsp;
            </td>
            <td style="width: 34%; text-align: center" class="footerText">
                <?php print _t("page"); ?> [[page_cu]]/[[page_nb]]
            </td>
            <td style="width: 33%; text-align: right">
                &nbsp;
            </td>
        </tr>
    </table>
<?php
}
?>
<div style="width:190mm; margin-top:80px; text-align: center;">
    <h1><?php print $t_item->getLabelForDisplay();?></h1>
</div>
<div class='table'>
    <div class='even'>
        <div class="image">
            <?php
            $digitoolPids = getDigitoolPids($t_item->get('digitoolUrl', array('returnAsArray' => true)));
            $vs_buf = "";
            $va_reps = $t_item->getRepresentations(array("thumbnail", "medium"));

            if (sizeof($va_reps) > 0 || sizeof($digitoolPids) > 0) {	//LIBIS
                // Voorlopig enkel 1 afbeelding, want er moet nog gekeken worden hoe meerdere afbeeldingen getoond worden. Nu vallen de afbeeldingen van het scherm als het groter is dan 1 pagina

                $vs_buf .= getDigitoolThumbnailBase($digitoolPids[0]);
                /*foreach(getDigitoolThumbnailsBase($digitoolUrls) as $tempImg) {
           $vs_buf .= "<p>" . $tempImg . "</p>";
                }*/
                // Sam: Extra if toegevoegd
                if(!empty($va_reps))
                {
                    foreach($va_reps as $va_rep) {
                        if(sizeof($va_reps) > 1){
                            # --- more than one rep show thumbnails
                            $vn_padding_top = ((120 - $va_rep["info"]["thumbnail"]["HEIGHT"])/2) + 5;
                            print $va_rep['tags']['thumbnail']."\n";
                        }else{
                            # --- one rep - show medium rep
                            print $va_rep['tags']['medium']."\n";
                        }
                    }
                }

            } else {
                $vs_buf .= "&nbsp;";
            }
            print $vs_buf;
            ?>
        </div><div class="datacell">
            <?php
            foreach($va_placements as $vn_placement_id => $va_bundle_info){
                if (!is_array($va_bundle_info)) break;
                if (in_array($va_bundle_info['bundle_name'], array('ca_objects.preferred_labels', 'ca_object_labels.name'))) { continue; }		// skip preferred labels because we always print it anyway
                if (!strlen($vs_display_value = $t_display->getDisplayValue($t_item, $vn_placement_id, array('purify' => true)))) {
                    if (!(bool)$t_display->getSetting('show_empty_values')) { continue; }
                    $vs_display_value = "&lt;"._t('not defined')."&gt;";
                }
                // this is a rough estimate as to what can be fit onto one page
                // as HTML2PDF doesn't do page automatic page breaks if single
                // elements are too long, we need to cut values that are too long.
                // of course the font is not monospaced so this can only be an
                // estimate with (hopefully) enough wiggle room for everything
                // that might be thrown at us. otherwise we barf here ...
                if(strlen($vs_display_value)>4500){
                    $vs_display_value = substr($vs_display_value, 0, 4495)." ...";
                }
                // Sam: mag verwijder worden als de KV nummers weg zijn
                if (strpos($vs_display_value,'(KV_') !== false) {
                    $temp = preg_split("/ - \(KV_[0-9]+\)$/", $vs_display_value);
                    $vs_display_value = $temp[0];
                }
                print "<strong class=\"label\">".$va_bundle_info['display'].":</strong><span class='value'>".$vs_display_value."</span><br/>\n";
            }
            ?>
        <!--</div></div></div>-->
<!--libis start-->
        </div></div>
<?php

    $pids = $digitoolPids;
    array_shift($pids);
    $pids = array_reverse($pids);
    $totalDivs = ceil(sizeof($pids)/3);
    if($totalDivs > 0){
        for($x = 0; $x<$totalDivs; $x++){?>
            <div class='odd'>
                <?php
                for($y=0; $y < 3; $y++) {
                    $pid = array_pop($pids);
                    if(empty($pid))
                        continue;
                    ?>
                    <div class="image">
                        <?php
                        $vs_buf = "";
                        $vs_buf .= getDigitoolThumbnailBase($pid);

                        print $vs_buf;
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        <?php }
    }

?>
<!--libis end-->
</div>
</body>
</html>
