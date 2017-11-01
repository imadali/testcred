({
	
	className: 'riskProfilingConfigView tleft',
    isAdmin: false,
	profilingData:null,
	grid: null,
    grid2:null,
	riskProfilingModel:null,

    events: {
        'click .editButton': 'editRow',
		'click .cancelButton': 'cancelRow',
		'click .saveButton': 'saveRow',
    },
	
    initialize: function(options) {
		self = this;
        this._super('initialize', [options]);
		
        if(app.user.get('type')=='admin')
        {
           this.isAdmin = true;
        } else {
			var errorMessage = app.lang.get('LBL_ACCESS_DENIED_ERROR', this.module);
            app.alert.show('error', {
               level: 'error',
               messages: errorMessage,
               autoClose: false
           });
        }
		
		// url = app.api.buildURL('Cases/update_meta', null, null, null);
		// app.api.call('read', url, null, {
			// success: function(serverData){
				//fields from meta
				this.grid2 = new Array;
				this.grid = new Array;
				
				app.view.View.prototype.initialize.call(this,options);
				this.riskProfilingModel = App.data.createBean('dummy');
				this.riskProfilingModel.module = this.module;
				var i = 0;
				
				_.each(self.meta.panels, function(mPanel) {
					self.grid[i] = new Array;
					self.grid[i]['name'] = mPanel.name;
					self.grid[i]['label'] = mPanel.label;
					_.each(mPanel.fields, function(feld){
						self.grid[i]['colspans'] = feld.length +1;
						
						var row_name = feld[1]['name'].split('/');
						var id_array = new Object();
						id_array["id"] = row_name[0];
						id_array["data"] = feld;
						id_array["cancelView"] = "none";
						id_array["saveView"] = "none";
						id_array["editView"] = "block";
						
						self.grid[i].push(id_array);
						self.grid2.push(feld);
					
					});
					i++;
				});
				// console.log('GRID',self.grid);
				this.riskProfilingModel.fields = self.grid2;//self.meta.panels[0].fields[0];
				// console.log(this.riskProfilingModel.fields);
				self.getRiskProfilingData(); 
			// },
		// });
		
    },

    getRiskProfilingData: function() {
		var self= this;
		
		url = app.api.buildURL('dotb9_risk_profiling/get_risk_profiling_data', null, null, null);
		app.api.call('read', url, null, {
			success: function(serverData){
				data = JSON.parse(serverData);
				//console.log(data);
				self.profilingData = data;
				
				self.setModelValues();
				//self.setRiskFactor();
				self.render();
			},
		});
    },
	
	/**
	* Set values in model
	*
	*/
	setModelValues: function() {
		var profilingData = this.profilingData;
		var self = this;
		profilingData.forEach(function (arrayElem){
			_.each(arrayElem, function(value,key) {
				if(key != 'recordId' && key != 'bankName'){
					var fieldName = key+'/'+arrayElem.recordId;
					self.riskProfilingModel.set(fieldName, value);
				}
			});
			
		});
	},
	
	/**
	* Set risk factor
	*
	*/
	setRiskFactor: function() {
		var riskProfilingData = this.profilingData;
		var dataSize = riskProfilingData.length;
		var self = this;
		_.each(this.grid, function(mPanel,index) {
			_.each(mPanel, function(feld,feldIndex){
				var fieldId = feld['id'];
				var riskFactor = '';
				var yesCounter = 0;
				for(var counter=0;counter<dataSize;counter++ ){
					if(riskProfilingData[counter][fieldId] == 'yes'){
						yesCounter++;
					}
					
				}
				if(yesCounter > 0){
					riskFactor = 'yellow';
				}
				_.each(feld['data'], function(fld1,key) {
					if( "riskFactor" in self.grid[index][feldIndex]['data'][key] ) {
						self.grid[index][feldIndex]['data'][key]['riskFactor'] = riskFactor;
					}
				
				});
			});
		});
	},
	
	/**
	* Edit button clicked
	*
	* Set field template to edit
	*/
	editRow: function(e){
		var self = this;
		row = $(e.currentTarget).closest('tr');
		var id = $(row).attr('id');

		_.each(this.grid, function(mPanel,index) {
			_.each(mPanel, function(feld,feldIndex){
				if(feld['id'] == id){
					self.grid[index][feldIndex]['cancelView'] = 'inline';
					self.grid[index][feldIndex]['saveView'] = 'block';
					self.grid[index][feldIndex]['editView'] = 'none';
					_.each(feld['data'], function(fld1,key) {
						self.grid[index][feldIndex]['data'][key]['fieldView'] = 'edit';
					
					});
				}
			});
		});
		
		self.render();
		var element_focus = document.getElementById(id);
		element_focus.scrollIntoView( true );		
	},
	
	/**
	* Cancel button clicked
	*
	* Set field template to list
	*/
	cancelRow: function(e){
		var self = this;
		row = $(e.currentTarget).closest('tr');
		var id = $(row).attr('id');
		
		_.each(this.grid, function(mPanel,index) {
			_.each(mPanel, function(feld,feldIndex){
				if(feld['id'] == id){
					self.grid[index][feldIndex]['cancelView'] = 'none';
					self.grid[index][feldIndex]['saveView'] = 'none';
					self.grid[index][feldIndex]['editView'] = 'block';
					_.each(feld['data'], function(fld1,key) {
						self.grid[index][feldIndex]['data'][key]['fieldView'] = 'list';
					
					});
				}
			});
		});
		self.setModelValues();
		self.render();
		var element_focus = document.getElementById(id);
		element_focus.scrollIntoView( true );		
	},
	
	/**
	* Save button clicked
	*
	* Get all the row values and save
	*/
	saveRow: function(e){
		var self = this;
		row = $(e.currentTarget).closest('tr');
		var id = $(row).attr('id');
		var saveParams = new Object();
		
		_.each(this.grid, function(mPanel,index) {
			_.each(mPanel, function(feld,feldIndex){
				if(feld['id'] == id){
					self.grid[index][feldIndex]['cancelView'] = 'none';
					self.grid[index][feldIndex]['saveView'] = 'none';
					self.grid[index][feldIndex]['editView'] = 'block';
					_.each(feld['data'], function(fld1,key) {
						if(key != '0'){
							saveParams[fld1['name']] = self.riskProfilingModel.get(fld1['name']);
						}
						self.grid[index][feldIndex]['data'][key]['fieldView'] = 'list';
					
					});
				}
			});
		});
		
		app.alert.show('saveRow', {
			level: 'process',
		});
		url = app.api.buildURL('dotb9_risk_profiling/save_risk_profiling_data', null, null, saveParams);
		app.api.call('read', url, null, {
			success: function(serverData){
				app.alert.dismiss('saveRow');
				var savedMessage = app.lang.get('LBL_SAVED', this.module);
				app.alert.show('row_saved', {
					level: 'success',
					messages: savedMessage,
					autoClose: true,
				});
				self.getRiskProfilingData();
				var element_focus = document.getElementById(id);
				element_focus.scrollIntoView( true );
			},
		});
		//self.render();
	},
})