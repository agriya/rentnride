swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_insurances':
  summary: 'Fetch List of insurances'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search Insurance']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of insurances ']
          sort:[query,string,description:'The Insurance Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort Insurance by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_insurances':
  summary: 'Store Insurance'  
  produces: json
  parameters:
          InsuranceAdd:[body,required,type:"InsuranceAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/vehicle_insurances/{id}':
  summary: 'Update Insurance'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Insurance Identifier']
          InsuranceEdit:[body,required,type:"InsuranceEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_insurances/{id}/edit':
  summary: 'Edit Insurance'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Insurance Identifier']		  
  responses:
    200:true    
    404:'Not found'

GET '/admin/vehicle_insurances/{id}':
  summary: 'Show Insurance'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Insurance Identifier']		  
  responses:
    200:true    
    404:'Not found'


DELETE '/admin/vehicle_insurances/{id}':
  summary: 'Delete Insurance'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Insurance Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'InsuranceAdd':	
	name:[string,required,"Insurance Name"]
	short_description:[string,required,"Short Description"]
	description:[string,required,"Description"]
	
MODEL 'InsuranceEdit':
    id:[int64,required,"The Insurance identifier"]
    name:[string,required,"Insurance Name"]
    short_description:[string,required,"Short Description"]
    description:[string,required,"Description"]
    is_active:[int64, required, "is active"]
