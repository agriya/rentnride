swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/settings':
  summary: 'List all settings'
  parameters:
      page:[query, int, description:'Page number for filtering']
      setting_category_id:[query, int, description:'Setting category ID']
      sort:[query,string,description:"sorting field name"]
      sortby:[query,enum:['asc', 'desc'],string,description:'sort setting category by ascending / descending order']
  produces: json
  responses:
    200:true    
    404:'Not found'


GET '/admin/setting_categories/{id}/settings':
  summary: 'List all settings for the specified category'  
  produces: json
  parameters:
          setting_category_id:[path,int64,required,description:'Setting category id']
  responses:
    200:true    
    404:'Not found'

GET '/admin/settings/{id}/edit':
  summary: 'Edit setting'  
  produces: json
  parameters:
          setting_id:[path,int64,required,description:'Setting id']
  responses:
    200:true    
    404:'Not found'	
	
PUT '/admin/settings/{id}':
  summary: 'Update setting'  
  produces: json
  parameters:
          id:[path,int64,required,description:'Setting id']
          SettingEdit:[body,required,type:"SettingEdit"]
  responses:
    200:true    
    404:'Not found'		

GET '/admin/settings/{name}/show':
  summary: 'Show details for the specified setting name'    
  produces: json
  parameters:
          name:[path,int64,required,description:'Setting name']
  responses:
    200:true    
    404:'Not found'
	
GET '/admin/plugins':
  summary: 'List all available plugin and enable plugin'    
  produces: json
  responses:
    200:true    
    404:'Not found'	
	
PUT '/admin/plugins':
  summary: 'Update plugin setting'  
  produces: json
  parameters:
		  PluginEdit:[body,required,type:"PluginEdit"]
  responses:
    200:true    
    404:'Not found'		

MODEL 'SettingEdit':	
	id:[int, required, 'Setting Id']
	value:[string,required,"Value for the setting"]
	
MODEL 'PluginEdit':	
	is_enabled: [int, required, 'Enable?']
	plugin_name:[string,required,"Plugin name?"]	