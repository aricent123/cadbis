var ajax_buffer = function(id,req,vc_pf)
{
	var _vc_pf = vc_pf; 
	var _id = id;
	var _vars = {};
	var _req = req;
	var _show_progress = true;
	var _method = 'replace';
	var _progress_class = 'alphacube';
	
	
	//--------------------------------------------------    
    var _implode_vars = function(){
    	var res = '';
    	for (var name in _vars)
    	{
    		if(res != '')
    			res += '&';
    		res += name + '=' + encodeURIComponent($(name+_vc_pf).value);
    	}	
    	return res;
    }
	//--------------------------------------------------    
    var _get_request = function()
    {
    	var res = _req;
    	res += ((res.lastIndexOf('?')<0)?'?':'&');
    	return res+_id+'=true'
    } 	
	//--------------------------------------------------	
	this.get_var = function(name){
		return $(name+_vc_pf).value;
	}
	//--------------------------------------------------	
	this.set_var = function(name, value) {
		$(name+_vc_pf).value = value;
		_vars[name] = value;
    }
	//--------------------------------------------------	
	this.set_method = function(value) {
		_method = value;
    }
	//--------------------------------------------------   
	this.set_progress_class = function(value) {
		_progress_class = value;
    }     
	//--------------------------------------------------
    this.show_progress = function(value){
    	_show_progress = (value!=false);
    }

	//--------------------------------------------------
	this.onSuccess = function()
	{
		
	}
	//--------------------------------------------------    
    this.update = function(){
    	var id = _id;
    	if(this._show_progress)
    		Dialog.info("", {width:150, height:150, className: _progress_class, showProgress: true});
    	var show_progress = _show_progress;
    	var rpl_method = _method; 
    	var successCallback = this.onSuccess;
		new Ajax.Request(_get_request(), {
			  method: 'post',
			  parameters: _implode_vars(),
			  onSuccess: function(data) 
			  {			  	
			   switch(rpl_method)
			   {
			     case 'replace':
			  		$(id).innerHTML = data.responseText;
			  		break;
			  	 case 'append_before':
			  		$(id).innerHTML = data.responseText + $(id).innerHTML;
			  		break;
			  	 case 'append_after':
			  		$(id).innerHTML += data.responseText;
			  		break;			  		
			  	}
			  	successCallback();
			  	if(show_progress)
	  				Dialog.closeInfo();
			   }});    
    } 
};