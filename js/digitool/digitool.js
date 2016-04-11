/* ----------------------------------------------------------------------
 * js/digitool/digitool.js
 * ----------------------------------------------------------------------
 */

var viewerWindow;

	function initDigitoolApp(digitooldata){
		digitooldefaultdata = {
			defaultSearchText : "Search Digitool ID ... ",
//			defaultDigitoolBaseUrl : "http://aleph08.libis.kuleuven.be:8881/dtl-cgi",
			defaultDigitoolBaseUrl : "http://resolver.libis.be",
			lookupUrl	: "/ca_cag/index.php/lookup/Digitool/Get"
		};
		digitooldata = jQuery.extend(digitooldefaultdata, digitooldata);
		digitoolInitializeEvents(digitooldata);
		return digitooldata;
	}
	
	function initNewDigitool(digitooldata){
	//
	}
	
	function initExistingDigitool(digitooldata){
		digitooldata.digitoolContainer.find('.digitoolPID:first').val(digitooldata.digitoolPID)
		pids = digitooldata.digitoolPID
		thumbnail_div = jQuery(digitooldata.digitoolContainer).find('#digitool_thumbnail');
		thumbnail_div.empty()
		if (pids){
            defaultDigitoolUrl = 'http://resolver.libis.be/';
			digitoolObjectDate = pids.split('_,_');
			digitoolObjectPid = digitoolObjectDate[0];
            digitoolObjectThumb =  defaultDigitoolUrl + digitoolObjectPid + '/';
            digitoolObjectView = defaultDigitoolUrl + digitoolObjectPid + '/representation';
			thumbnail_div.append("<img src='"+ digitoolObjectThumb +"' view='"+digitoolObjectView +"'>");
		}
	}


	function digitoolInitializeEvents(digitooldata){
//		console.log(digitooldata);
		jQuery(digitooldata.digitoolContainer).find('.digitoolSearchField:first').blur(function() {
			setDefaultText(this, digitooldata.defaultSearchText);
		});
		jQuery(digitooldata.digitoolContainer).find('.digitoolSearchField:first').click(function() {
			clearDefaultText(this, digitooldata.defaultSearchText);
		});
		jQuery(digitooldata.digitoolContainer).find('#searchDigitoolButton').live('click', function(event) {
				event.preventDefault();
				search_thumbnail_div = jQuery(digitooldata.digitoolContainer).find('#digitool_search_thumbnails');
				jQuery(digitooldata.digitoolContainer).find('div.digitoolSearchBox').append( "<img src='" + digitooldata.processIndicator + "' border='0' id='processIndicator' style='margin-left:1em;'/>");
				
				searchlabel= jQuery(digitooldata.digitoolContainer).find('.digitoolSearchField:first').val();

				lookupUrl=digitooldata.lookupUrl;
				search_thumbnail_div.empty();
				
				jQuery.getJSON(lookupUrl, { q: searchlabel, _context_id:0 }, function(data) { 
				   // search_thumbnail_div.empty();
					jQuery.each(data, function(i,item){
							search_thumbnail_div.append("<div style='float: left;'><img src='"+ item.thumbnail +"' id='"+ item.pid +"'  class='digitoolImage' style='margin:0.3em; cursor:pointer; max-height: 150px;' view='"+ item.view +"'><br><p class='caption' style='display:inline;'>" + item.label + "</p></div>");
						if (item == data[data.length-1]){
							jQuery(search_thumbnail_div).find('img#'+ item.pid ).load(function () {
								jQuery(digitooldata.digitoolContainer).find('div.digitoolSearchBox').find('#processIndicator').remove();
								
								search_thumbnail_div.prepend('<h3>Search results for :'+ searchlabel +'</h3>');
								jQuery(digitooldata.digitoolContainer).find('#digitool_search_thumbnails').show();								
							});
						}
					});
					jQuery(digitooldata.digitoolContainer).find('div.digitoolSearchBox img#processIndicator').remove();					
				})

		});
		
		jQuery(digitooldata.digitoolContainer).find('#digitool_search_thumbnails').find('img.digitoolImage').live('click', function(event) {
			digitooldata.digitoolPID = jQuery(this).attr("id") +"_,_"+ jQuery(this).attr("src") +"_,_"+ jQuery(this).attr("view");
			initExistingDigitool(digitooldata)
			jQuery(digitooldata.digitoolContainer).find('#digitool_search_thumbnails').hide();
		});

		jQuery(digitooldata.digitoolContainer).find('#digitool_thumbnail').find('img').live('click', function(event) {
			if ( typeof viewerWindow != "undefined"){
			    viewerWindow.close()
			}
			viewerWindow = window.open( $(this).attr('view'), 'digitoolViewer');
			return false;
		});

		jQuery(digitooldata.digitoolContainer).find('#digitool_search_thumbnails').css({
			'background-color': '#FFF',
			'border': '1px solid #000',
			'font-size': '0.85em',
			'position': 'absolute',
			'z-index': '1',
			'display': 'none',
			'margin-top': '-1em',
			'overflow': 'auto'
		});
		
/*		
		jQuery(mapdata.mapholder).find('.mapSuggestLink').live('click mouseover mouseout', function(event) {
			if (event.type == 'click') {
				jQuery(this).attr("class",".mapSuggestLink selected");
				findSelectedSuggest(mapdata);
			} else if (event.type == 'mouseover') {
				setSelectedSuggest(mapdata,jQuery('#mapSearchSuggest .mapSuggestLink').index(jQuery(this)));
			} else {
				setSelectedSuggest(mapdata,-1); 
			}
		});
*/
	
	}
	
	
	
	function parseXml(xml){
		  jQuery(xml).find("result").each(function() {
		 		console.log ( jQuery(this) );
		  });
	}
	
	
	function setDefaultText(thisI, defaultText) {
		if (thisI.value == '') {
			thisI.value = defaultText;
		}
	}
	
	function clearDefaultText(thisI, defaultText) {

		if (thisI.value == defaultText) {
			thisI.value = '';
		}
	}
	