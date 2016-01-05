<?php
/** ---------------------------------------------------------------------
 * app/lib/ca/Attributes/Values/DigitoolUrlAttributeValue.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2009-2011 Whirl-i-Gig
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
 * @package CollectiveAccess
 * @subpackage BaseModel
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License version 3
 *
 * ----------------------------------------------------------------------
 */
 
 /**
  *
  */
 	
 	require_once(__CA_LIB_DIR__.'/ca/Attributes/Values/IAttributeValue.php');
 	require_once(__CA_LIB_DIR__.'/ca/Attributes/Values/AttributeValue.php');
 	require_once(__CA_LIB_DIR__.'/core/Configuration.php');	
 	require_once(__CA_LIB_DIR__.'/core/BaseModel.php');	// we use the BaseModel field type (FT_*) and display type (DT_*) constants
 
 	global $_ca_attribute_settings;
 	$_ca_attribute_settings['DigitoolUrlAttributeValue'] = array(		// global
		'minChars' => array(
			'formatType' => FT_NUMBER,
			'displayType' => DT_FIELD,
			'width' => 5, 'height' => 1,
			'default' => 0,
			'label' => _t('Minimum number of characters'),
			'description' => _t('The minimum number of characters to allow. Input shorter than required will be rejected.')
		),
		'maxChars' => array(
			'formatType' => FT_NUMBER,
			'displayType' => DT_FIELD,
			'width' => 5, 'height' => 1,
			'default' => 65535,
			'label' => _t('Maximum number of characters'),
			'description' => _t('The maximum number of characters to allow. Input longer than required will be rejected.')
		),
		'regex' => array(
			'formatType' => FT_TEXT,
			'displayType' => DT_FIELD,
			'width' => 60, 'height' => 1,
			'default' => '',
			'label' => _t('Regular expression to validate input with'),
			'description' => _t('A Perl-format regular expression with which to validate the input. Input not matching the expression will be rejected. Do not include the leading and trailling delimiter characters (typically "/") in your expression. Leave blank if you don\'t want to use regular expression-based validation.')
		),
		'fieldWidth' => array(
			'formatType' => FT_NUMBER,
			'displayType' => DT_FIELD,
			'default' => 40,
			'width' => 5, 'height' => 1,
			'label' => _t('Width of data entry field in user interface'),
			'description' => _t('Width, in characters, of the field when displayed in a user interface.')
		),
		'fieldHeight' => array(
			'formatType' => FT_NUMBER,
			'displayType' => DT_FIELD,
			'default' => 1,
			'width' => 5, 'height' => 1,
			'label' => _t('Height of data entry field in user interface'),
			'description' => _t('Height, in characters, of the field when displayed in a user interface.')
		),
		'doesNotTakeLocale' => array(
			'formatType' => FT_NUMBER,
			'displayType' => DT_CHECKBOXES,
			'default' => 1,
			'width' => 1, 'height' => 1,
			'label' => _t('Does not use locale setting'),
			'description' => _t('Check this option if you don\'t want your urls to be locale-specific. (The default is to not be.)')
		),
		'requireValue' => array(
			'formatType' => FT_NUMBER,
			'displayType' => DT_CHECKBOXES,
			'default' => 0,
			'width' => 1, 'height' => 1,
			'label' => _t('Require value'),
			'description' => _t('Check this option if you want an error to be thrown if the URL is left blank.')
		),
		'canBeUsedInSort' => array(
			'formatType' => FT_NUMBER,
			'displayType' => DT_CHECKBOXES,
			'default' => 1,
			'width' => 1, 'height' => 1,
			'label' => _t('Can be used for sorting'),
			'description' => _t('Check this option if this attribute value can be used for sorting of search results. (The default is to be.)')
		),
		'suggestExistingValues' => array(
			'formatType' => FT_NUMBER,
			'displayType' => DT_CHECKBOXES,
			'width' => 1, 'height' => 1,
			'default' => 0,
			'label' => _t('Suggest existing values?'),
			'description' => _t('Check this option if you want this attribute to suggest previously saved values as text is entered. This option is only effective if the display height of the element is equal to 1.')
		),
		'suggestExistingValueSort' => array(
			'formatType' => FT_TEXT,
			'displayType' => DT_SELECT,
			'default' => 'value',
			'width' => 20, 'height' => 1,
			'label' => _t('Sort suggested values by?'),
			'description' => _t('If suggestion of existing values is enabled this option determines how returned values are sorted. Choose <i>value</i> to sort alphabetically. Choose <i>most recently added </i> to sort with most recently entered values first.'),
			'options' => array(
				_t('Value') => 'value',
				_t('Most recently added') => 'recent'
			)
		),
		'canBeUsedInSearchForm' => array(
			'formatType' => FT_NUMBER,
			'displayType' => DT_CHECKBOXES,
			'default' => 1,
			'width' => 1, 'height' => 1,
			'label' => _t('Can be used in search form'),
			'description' => _t('Check this option if this attribute value can be used in search forms. (The default is to be.)')
		),
		'canBeUsedInDisplay' => array(
			'formatType' => FT_NUMBER,
			'displayType' => DT_CHECKBOXES,
			'default' => 1,
			'width' => 1, 'height' => 1,
			'label' => _t('Can be used in display'),
			'description' => _t('Check this option if this attribute value can be used for display in search results. (The default is to be.)')
		),
		'displayTemplate' => array(
			'formatType' => FT_TEXT,
			'displayType' => DT_FIELD,
			'default' => '',
			'width' => 90, 'height' => 4,
			'label' => _t('Display template'),
			'validForRootOnly' => 1,
			'description' => _t('Layout for value when used in a display (can include HTML). Element code tags prefixed with the ^ character can be used to represent the value in the template. For example: <i>^my_element_code</i>.')
		),
		'displayDelimiter' => array(
			'formatType' => FT_TEXT,
			'displayType' => DT_FIELD,
			'default' => ',',
			'width' => 10, 'height' => 1,
			'label' => _t('Value delimiter'),
			'validForRootOnly' => 1,
			'description' => _t('Delimiter to use between multiple values when used in a display.')
		)
	);
 
	class DigitoolUrlAttributeValue extends AttributeValue implements IAttributeValue {
 		# ------------------------------------------------------------------
 		private $ops_text_value;
 		# ------------------------------------------------------------------
 		public function __construct($pa_value_array=null) {
 			parent::__construct($pa_value_array);
 		}
 		# ------------------------------------------------------------------
 		public function loadTypeSpecificValueFromRow($pa_value_array) {
 			$this->ops_text_value = $pa_value_array['value_longtext1'];
 		}
 		# ------------------------------------------------------------------
 		/**
 		 * @param array $pa_options Supported options are
 		 *		asHTML - if set, URL is returned as an HTML link 
 		 * @return string The url
 		 */
		public function getDisplayValue($pa_options=null) {
			if (isset($pa_options['asHTML']) && $pa_options['asHTML']) {
				return "<a href='".$this->ops_text_value."' target='_url_details'>".$this->ops_text_value.'</a>';
			} 
			return $this->ops_text_value;
		}
 		# ------------------------------------------------------------------
 		public function parseValue($ps_value, $pa_element_info, $pa_options=null) {
 			$ps_value = trim($ps_value);
			
 			$va_settings = $this->getSettingValuesFromElementArray(
 				$pa_element_info, 
 				array('minChars', 'maxChars', 'regex', 'requireValue')
 			);
			/*
 			if (!$va_settings['requireValue'] && !$ps_value) {
 				return array(
					'value_longtext1' => ''
				);
 			}
 			*/

 			return array(
 				'value_longtext1' => $ps_value
 			);
 		}
 		# ------------------------------------------------------------------
 		public function htmlFormElement($pa_element_info, $pa_options=null) {

 			$va_settings = $this->getSettingValuesFromElementArray($pa_element_info, array('fieldWidth', 'fieldHeight', 'minChars', 'maxChars', 'suggestExistingValues'));

			JavascriptLoadManager::register('digitool');			
			$vs_element = 	'<div id="digitoolContainer_'.$pa_element_info['element_id'].'_{n}" class="digitoolContainer">';
			$vs_element .= 		'<div class="digitoolSearchBox" style="border-bottom: 1px solid #E6E6E6; padding-bottom:0.5em;  margin-bottom:0.5em;">';
			$vs_element .=			'<input type="text" class="digitoolSearchField" name="digitoolSearchField_{n}" size="60" value="'._t('PID van het originele item').'" autocomplete="off"/ style="margin: 0 10px 0 0;">';
			$vs_element .=			'<a href="#" class="button" id="searchDigitoolButton">'._t('Search Digitool').' &rsaquo;</a>';			
			$vs_element .= 		'</div>';			
			$vs_element .= 		'<div id="digitool_search_thumbnails">';
			$vs_element .= 		'</div>';						

			$vs_element .=		'Digitool PID: <input type="text" class="digitoolPID" name="{fieldNamePrefix}'.$pa_element_info['element_id'].'_{n}" size="60" style="margin: 0 10px 0 0;">';
			$vs_element .= 		'<div id="digitool_thumbnail" style="padding:0.5em;">';
			$vs_element .= 		'</div>';


 			$vs_bundle_name = $vs_lookup_url = null;
 			if (isset($pa_options['t_subject']) && is_object($pa_options['t_subject'])) {
 				$vs_bundle_name = $pa_options['t_subject']->tableName().'.'.$pa_element_info['element_code'];
 				
 				if ($pa_options['po_request']) {
 					$vs_lookup_url	= caNavUrl($pa_options['po_request'], 'lookup', 'AttributeValue', 'Get', array());
 				}
 			}
 			
			
			
 			/* ($va_settings['suggestExistingValues'] && $vs_lookup_url && $vs_bundle_name) { 
 				$vs_element .= "<script type='text/javascript'>
 					jQuery('#{fieldNamePrefix}".$pa_element_info['element_id']."_{n}').autocomplete('".$vs_lookup_url."', 
								{ minChars: 3, matchSubset: 1, matchContains: 1, delay: 800, scroll: true, max: 500, extraParams: { bundle: '".$vs_bundle_name."'}});
 				</script>\n";
 			}
 			$vs_element .= "
				<script type='text/javascript'>
					if ('{{".$pa_element_info['element_id']."}}') {
							jQuery('#{fieldNamePrefix}".$pa_element_info['element_id']."_link{n}').css('display', 'inline').attr('href', '{{".$pa_element_info['element_id']."}}');
						}
				</script>
			";
 			*/
	 		$vs_element .= 		'<script type="text/javascript">';
			$vs_element .= 		"jQuery(document).ready(function() {
									var mID_{n} = ".$pa_element_info['element_id'].";
									var digitooldata = {
										digitoolContainer : jQuery('#digitoolContainer_' + mID_{n} + '_{n}'),
										digitoolPID : \"{{{".$pa_element_info['element_id']."}}}\",
										defaultSearchText : '"._t('PID van het originele item') ."',
										processIndicator : '". __CA_URL_ROOT__ ."/themes/default/graphics/icons/indicator.gif',
										lookupUrl	: '". __CA_URL_ROOT__ ."/index.php/lookup/Digitool/Get'
										
										
									};

									digitooldata = initDigitoolApp(digitooldata);
																		
									if ('{n}'.substring(0,3) == 'new') {
										initNewDigitool(digitooldata);
									} else {
										initExistingDigitool(digitooldata);
									}


								});";
			$vs_element .= 		'</script>';
 			$vs_element .=	'</div>';			
 			return $vs_element;
 		}
 		# ------------------------------------------------------------------
 		public function getAvailableSettings($pa_element_info=null) {
 			global $_ca_attribute_settings;
 			return $_ca_attribute_settings['DigitoolUrlAttributeValue'];
 		}
 		# ------------------------------------------------------------------
		/**
		 * Returns name of field in ca_attribute_values to use for sort operations
		 * 
		 * @return string Name of sort field
		 */
		public function sortField() {
			return 'value_longtext1';
		}
 		# ------------------------------------------------------------------
	}
 ?>