<?php
	require_once(__CA_LIB_DIR__.'/core/Configuration.php');
	require_once('cURL.php');

	function getDigitoolThumbnailLink($digitoolPid)
	{
		$thumbnailUrl = getDigitoolThumbnailUrl($digitoolPid);

		if (strlen($thumbnailUrl) > 0) {			
			return '<img src="'.$thumbnailUrl.'" style="max-height: 500px;">';					
		}

		return "";
	}

    # wordt gebruikt om de afbeelding in PDF te tonen.
    # om meerdere urls op te halen
    function getDigitoolThumbnailsBase($digitoolPids)
    {
        $tempImgs = array();

        if(count($digitoolPids) > 0) {

            foreach($digitoolPids as $digitoolPid) {
                array_push($tempImgs,getDigitoolThumbnailBase($digitoolPid));
            }
        }

        return $tempImgs;
    }

    # wordt gebruikt om de afbeelding in PDF te tonen.
    # voor 1 url op te halen
    function getDigitoolThumbnailBase($digitoolPid)
	{
		$thumbnailUrl = getDigitoolThumbnailUrl($digitoolPid);
		
		if(strlen($thumbnailUrl) > 0)
        {

			$vo_http_client = new Zend_Http_Client();

			$config = array(
                'adapter'    => 'Zend_Http_Client_Adapter_Proxy',
                'proxy_host' => 'icts-http-gw.cc.kuleuven.be',
                'proxy_port' => 8080,
                'timeout'    => 30
			);

			$vo_http_client->setConfig($config);


			$vo_http_client->setUri($thumbnailUrl);
		
			$vo_http_response = $vo_http_client->request();
			$thumb = $vo_http_response->getBody();
			$try = 0;
			
			while($try < 10)
			{
				if (!$vo_http_response->isError()){
					return '<img src="data:image/jpeg;base64,'.base64_encode($thumb).'">';
					break;
				} else {
                   //retry
                   sleep(1);
                   $vo_http_client->setUri($thumbnailUrl);
                   $vo_http_response = $vo_http_client->request();

                   if (!$vo_http_response->isError()){
                        return '<img src="data:image/jpeg;base64,'.base64_encode($thumb).'">';
                   } else {
                       $try++;
                    }
				}
			}
		}

		return "";
	}


    # wordt gebruikt in de thumbnail view van de zoekresultaten vb ca_objects_results_thumbnail_html.php
    function getDigitoolThumbnailView($digitoolPid)
	{
		$thumbnailUrl = getDigitoolThumbnailUrl($digitoolPid);

		if (strlen($thumbnailUrl) > 0) {			
			return '<img src="'.$thumbnailUrl.'" width="172px">';					
		}

		return "";
	}


	function getDigitoolThumbnailUrl($digitoolPid)
	{
		if (strlen($digitoolPid) > 0) {

            $digitoolUrl = "http://resolver.lias.be/get_pid?stream&usagetype=THUMBNAIL&pid=".$digitoolPid;
			
			return $digitoolUrl;
		}

		return "";
	}


    function getDigitoolViewMainUrl($digitoolPid)
    {
        if (strlen($digitoolPid) > 0) {

            $digitoolUrl = "http://resolver.lias.be/get_pid?view&usagetype=VIEW_MAIN,VIEWL&pid=".$digitoolPid;

            return $digitoolUrl;

        }

        return "";
    }

    /*
    * GetDigitoolPids wordt gebruikt om meerdere afbeeldingen uit een afbeelding te halen.
    * Als er maar 1 afbeelding is wordt er een string teruggeven anders een array
     *
     */

    function getDigitoolPids($digitoolUrls)
    {
	    if (!is_null($digitoolUrls)) {

            foreach($digitoolUrls as $attribute) {
                foreach($attribute as $digitoolUrl) {
                    if (strpos($digitoolUrl, '_')) {
                        $digitoolPids[] = substr($digitoolUrl, 0, strpos($digitoolUrl, '_'));
                    } else {
                        $digitoolPids[] = trim($digitoolUrl);
                    }
                }
            }

            return $digitoolPids;

        }

        return "";
    }

?>

