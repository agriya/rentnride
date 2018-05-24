swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/states':
  summary: 'Fetch List of states'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search State']
          filter:[query,enum:['active', 'inactive'],string, description:'filter list of states ']
          sort:[query,string,description:'The State Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort States by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/states':
  summary: 'Store State'  
  produces: json
  parameters:
          StateAdd:[body,required,type:"StateAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/states/{id}':
  summary: 'Update State'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The State Identifier']
		  StateEdit:[body,required,type:"StateEdit"]
  responses:
    200:true    
    404:'Not found'	

GET '/admin/states/{id}/edit':
  summary: 'Edit State'
  produces: json
  parameters:
          id:[path,int64,required,description:'The State Identifier']
  responses:
    200:true
    404:'Not found'

DELETE '/admin/states/{id}':
  summary: 'Delete State'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The State Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'StateAdd':	
	name:[string,required,"State Name"]
	country_id:[int64,required,"the Country identifier"]
	
MODEL 'StateEdit':
    id:[string,required,"The State identifier"]
	name:[string,required,"State Name"]
	country_id:[int64,required,"the Country identifier"]
	is_active:[int64, required, "is active"]
