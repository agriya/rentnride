swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_companies':
  summary: 'Get the list of vehicle companies'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle company']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for vehicle companies']
   sort:[query,string, description:'The vehicle company Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle company by Ascending / Descending Order']
  produces: json
  responses:
    200:true

POST '/admin/vehicle_companies':
  summary: 'Store vehicle company'
  produces: json
  parameters:
    VehicleCompanyAdd:[body,required,type:"VehicleCompanyAdd"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_companies/{id}/edit':
  summary: 'Edit the vehicle company'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

PUT '/admin/vehicle_companies/{id}':
  summary: 'Update vehicle company'
  produces: json
  parameters:
   id:[path,int64,required,description:'vehicle company id']
   VehicleCompanyEdit:[body,required,type:"VehicleCompanyEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_companies/{id}':
  summary: 'View the vehicle company'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

DELETE '/admin/vehicle_companies/{id}':
  summary: 'Delete the specified vehicle company'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter vehicle company ID"]
  responses:
    200:true
	
PUT '/admin/vehicle_companies/{id}/deactive':
  summary: 'Deactivate the company '  
  produces: json
  parameters:
	  id:[path,int64,required,description:'The company Identifier']
  responses:
    200:true    
    404:'Not found'    

PUT '/admin/vehicle_companies/{id}/active':
  summary: 'Activate the company '  
  produces: json
  parameters:
	  id:[path,int64,required,description:'The company Identifier']
  responses:
    200:true    
    404:'Not found' 
	
PUT '/admin/vehicle_companies/{id}/reject':
  summary: 'Reject the company '  
  produces: json
  parameters:
	  id:[path,int64,required,description:'The company Identifier']
  responses:
    200:true    
    404:'Not found' 	

MODEL 'VehicleCompanyAdd':
    user_id:[int64,required,"User id"]
	name:[string,required,"Vehicle company Name"]
	address:[string,required,"Vehicle company address"]
	latitude:[string,"Vehicle company latitude"]
	longitude:[string,"Vehicle company longitude"]
	phone:[string,"Vehicle company phone"]
	mobile:[string,required,"Vehicle company mobile"]
	email:[string,required,"Vehicle company email"]
	is_active:[int64,required,"is active"]

MODEL "VehicleCompanyEdit":
    id:[int64,required,"The Vehicle company identifier"]
    user_id:[int64,required,"User id"]
    name:[string,required,"Vehicle company Name"]
  	address:[string,required,"Vehicle company address"]
  	latitude:[string,"Vehicle company latitude"]
  	longitude:[string,"Vehicle company longitude"]
  	phone:[string,"Vehicle company phone"]
  	mobile:[string,required,"Vehicle company mobile"]
  	email:[string,required,"Vehicle company email"]
  	is_active:[int64,required,"is active"]

