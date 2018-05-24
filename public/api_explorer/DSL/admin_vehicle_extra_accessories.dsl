swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_extra_accessories':
  summary: 'Fetch List of extra accessories'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search Extra Accessory']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of extra accessories ']
          sort:[query,string,description:'The Extra Accessory Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort Extra Accessory by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_extra_accessories':
  summary: 'Store Extra Accessory'  
  produces: json
  parameters:
          ExtraAccessoryAdd:[body,required,type:"ExtraAccessoryAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/vehicle_extra_accessories/{id}':
  summary: 'Update ExtraAccessory'
  produces: json
  parameters:
          id:[path,int64,required,description:'The ExtraAccessory Identifier']
          ExtraAccessoryEdit:[body,required,type:"ExtraAccessoryEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_extra_accessories/{id}/edit':
  summary: 'Edit Extra Accessory'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Extra Accessory Identifier']		  
  responses:
    200:true    
    404:'Not found'

GET '/admin/vehicle_extra_accessories/{id}':
  summary: 'Show Extra Accessory'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Extra Accessory Identifier']		  
  responses:
    200:true    
    404:'Not found'


DELETE '/admin/vehicle_extra_accessories/{id}':
  summary: 'Delete ExtraAccessory'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The ExtraAccessory Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'ExtraAccessoryAdd':	
	name:[string,required,"ExtraAccessory Name"]
	short_description:[string,required,"Short Description"]
	description:[string,required,"Description"]
	
MODEL 'ExtraAccessoryEdit':
    id:[int64,required,"The ExtraAccessory identifier"]
    name:[string,required,"ExtraAccessory Name"]
    short_description:[string,required,"Short Description"]
    description:[string,required,"Description"]
    is_active:[int64, required, "is active"]
