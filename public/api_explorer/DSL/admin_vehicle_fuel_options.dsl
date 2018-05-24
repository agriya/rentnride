swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_fuel_options':
  summary: 'Fetch List of fuel options'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search Fuel Option']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of fuel options ']
          sort:[query,string,description:'The Fuel Option Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort FuelOption by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_fuel_options':
  summary: 'Store Fuel Option'  
  produces: json
  parameters:
          FuelOptionAdd:[body,required,type:"FuelOptionAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/vehicle_fuel_options/{id}':
  summary: 'Update FuelOption'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Fuel Option Identifier']
          FuelOptionEdit:[body,required,type:"FuelOptionEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_fuel_options/{id}/edit':
  summary: 'Edit Fuel Option'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Fuel Option Identifier']		  
  responses:
    200:true    
    404:'Not found'

GET '/admin/vehicle_fuel_options/{id}':
  summary: 'Show Fuel Option'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The FuelOption Identifier']		  
  responses:
    200:true    
    404:'Not found'


DELETE '/admin/vehicle_fuel_options/{id}':
  summary: 'Delete FuelOption'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The FuelOption Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'FuelOptionAdd':	
	name:[string,required,"FuelOption Name"]
	short_description:[string,required,"Short Description"]
	description:[string,required,"Description"]
	
MODEL 'FuelOptionEdit':
    id:[int64,required,"The Fuel Option identifier"]
    name:[string,required,"Fuel Option Name"]
    short_description:[string,required,"Short Description"]
    description:[string,required,"Description"]
    is_active:[int64, required, "is active"]
