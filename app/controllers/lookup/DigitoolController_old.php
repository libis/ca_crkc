<?php
/* ----------------------------------------------------------------------
 * app/controllers/lookup/GeoNamesController.php :
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2009-2010 Whirl-i-Gig
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
require_once(__CA_APP_DIR__."/helpers/displayHelpers.php");
require_once(__CA_MODELS_DIR__."/ca_locales.php");


class DigitoolController extends ActionController {
 	# -------------------------------------------------------
 	public function __construct(&$po_request, &$po_response, $pa_view_paths=null) {
 		parent::__construct($po_request, $po_response, $pa_view_paths);
 	}
 	# -------------------------------------------------------
 	# AJAX handlers
 	# -------------------------------------------------------
	public function Get() {
		global $g_ui_locale_id;

		$ps_query = $this->request->getParameter('q', pString);
//		$vs_base = "http://aleph08.libis.kuleuven.be:8881/dtl-cgi/";
		$vs_base = "http://resolver.lias.be/";
		
		$va_items = array();
		if (unicode_strlen($ps_query) > 0) {
			try {
				//
				// Get up to 50 suggestions as ATOM feed
				//
//				$vs_data = @file_get_contents($vs_base."find_pid?search=".urlencode($ps_query).'%25&max_results=50');

				$cc = new cURL();
				$cc->setproxy('icts-http-gw.cc.kuleuven.be:8080');
				$vs_data = $cc->get($vs_base."find_pid?search=".urlencode($ps_query).'%25&max_results=50&filter=partitionc:CRKC,standaardcollectie');
				if 	( $cc->getHttpStatus() == "200"){
					if ($vs_data) {
						$xmlObject = new SimpleXMLElement($vs_data);
						$items = $xmlObject->item;
						foreach ($xmlObject->children() as $item) {
							$attr = $item->attributes();
							$va_items[] = array(
								'label' => (string)$attr['label'],
								'pid' => (string)$attr['pid'],
								'thumbnail' => (string)$attr['thumbnail'],
								'view' => (string)$attr['view']);	
						}
					}
				}
			} catch (Exception $e) {
				$va_items['error'] = array('displayname' => _t('ERROR').':'.$e->getMessage(), 'idno' => '');
			}
		}
		
		$this->view->setVar('digitool_list', $va_items);
		return $this->render('ajax_digitool_list_json.php');
	}
	# -------------------------------------------------------
}


class cURL {
	var $headers;
	var $user_agent;
	var $compression;
	var $proxy;
	protected $_status;
	function cURL($compression='gzip') {
		$this->headers[] = 'Accept: text/html,application/xhtml+xml,application/xml';
		$this->headers[] = 'Connection: Keep-Alive';
		$this->headers[] = 'Content-type: application/xml;charset=UTF-8';
		$this->user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:10.0) Gecko/20100101 Firefox/10.0';
		$this->compression=$compression;
	}

	function setproxy($proxy) {
		$this->proxy = $proxy;
	}

	function setuserpw($userpw) {
		$this->userpw = $userpw;
	}

	function setport($port) {
		$this->port = $port;
	}
	function setuser_agent($user_agent) {
		$this->user_agent = $user_agent;
	}	

	function get($url) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ( isset($this->userpw) ) curl_setopt($process, CURLOPT_USERPWD, $this->userpw );
		if ( isset($this->port) ) curl_setopt($process, CURLOPT_PORT,$this->port);		
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process,CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 30);
		if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);		
		if ($this->proxy) curl_setopt($process,CURLOPT_PROXYPORT, 8080); 
//		if ($this->proxy) curl_setopt($process, CURLOPT_HTTPPROXYTUNNEL, 1);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 0);
		$return = curl_exec($process);
		$this->_status = curl_getinfo($process,CURLINFO_HTTP_CODE);

		curl_close($process);		
		return $return;
	}
	

	function getHttpStatus()   {
       return $this->_status;
   	} 

	function error($error) {
		echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
		die;
	}
}
