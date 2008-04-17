var ajax_buffer = function(id,req,vc_pf)
{
	this._vc_pf = vc_pf; 
	this._id = id;
	this._vars = {};
	this._req = req;
	this._show_progress = true;
	
	//--------------------------------------------------	
	this.get_var = function(name){
		return $(name+this._vc_pf).value;
	}
	//--------------------------------------------------	
	this.set_var = function(name, value) {
		$(name+this._vc_pf).value = value;
		this._vars[name] = value;
    }
	//--------------------------------------------------    
    this.implode_vars = function(){
    	var res = '';
    	for (var name in this._vars)
    	{
    		res += '&' + name + '=' + $(name+this._vc_pf).value;
    	}	
    	return res;
    }
	//--------------------------------------------------
    this.show_progress = function(value){
    	this._show_progress = (value!=false);
    }
	//--------------------------------------------------    
    this.get_request = function()
    {
    	return this._req + '?'+this._id+'=true'+this.implode_vars();
    }
	//--------------------------------------------------    
    this.update = function(){
    	var id = this._id;
    	if(this._show_progress)
    		Dialog.info("", {width:150, height:150, className: 'alphacube', showProgress: true});
    	var show_progress = this._show_progress;  
		new Ajax.Request(this.get_request(), {
			  method: 'get',
			  onSuccess: function(data) 
			  {			  	
			  	$(id).innerHTML = data.responseText;
			  	if(show_progress)
	  				Dialog.closeInfo();
			   }});    
    } 
};