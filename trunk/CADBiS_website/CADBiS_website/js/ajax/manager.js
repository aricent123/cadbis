function EntitiesManager(AjaxBuf,VarActionId,VarConfirmId,VarItemId){
	var _AjaxBuf = AjaxBuf;
	var _VarActionId = VarActionId;
	var _VarConfirmId = VarConfirmId;
	var _VarItemId = VarItemId;
	var _actions = ['del','add','upd','noact'];
	
		this.action = {
			'del':0,
			'add':1,
			'upd':2,
			'noact':3
		};
	
        this.setAjaxBuf = function(Id){
            _AjaxBuf = Id;
        }
        this.setVarActionId = function(Id){
            _VarActionId = Id;
        }
        this.setVarItemId = function(Id){
            _VarItemId = Id;
        }
        this.setVarConfirmId = function(Id){
            _VarConfirmId = Id;
        }        
        this.getAction = function(){        	
        	if(_AjaxBuf != null){
            	return parseInt(_AjaxBuf.get_var(_VarActionId));
        	}
        	return '';
        }
        this.getItem = function(){
        	if(_AjaxBuf != null){
            	return _AjaxBuf.get_var(_VarItemId);
        	}
        	return '';
        }
        this.getConfirm = function(){
        	if(_AjaxBuf != null){
            	return _AjaxBuf.get_var(_VarConfirmId);
        	}
        	return '';
        }        
        this.setAction = function(value){
        	if(_AjaxBuf != null){
            	_AjaxBuf.set_var(_VarActionId, value);
        	}
        }
        this.setItem = function(value){
        	if(_AjaxBuf != null){
            	_AjaxBuf.set_var(_VarItemId, value);
        	}
        }
        this.setConfirm = function(value){
        	if(_AjaxBuf != null){
            	_AjaxBuf.set_var(_VarConfirmId, value);
        	}
        }
        this.Confirmed = function(){
			this.setConfirm(true);
        }   
        this.Canceled = function(){
			this.setConfirm(false);
        }                    
        this.Update = function(){        	
        	if(_AjaxBuf != null){
            	_AjaxBuf.update();
        	}        	
        }
        this.isAnyAction = function(){
        	return this.getAction() != _actions[3];
        }
        this.deleteItem = function(data){
       		this.setAction(this.action.del);
        	this.setItem(data);
        	this.Update(); 	
        }
        this.addItem = function(data){
        	this.setAction(this.action.add);
        	this.setItem(data);
        	this.Update(); 	
        }        
        this.updateItem = function(data){
        	this.setAction(this.action.upd);
        	this.setItem(data);
        	this.Update();
        }        
};