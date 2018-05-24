swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_type_fuel_options':
  summary: 'Fetch List of vehicle_type_fuel_options'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search Vehicle Type FuelOption']
          sort:[query,string,description:'The Vehicle Type FuelOption Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle_type_fuel_options by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_type_fuel_options':
  summary: 'Store Vehicle Type FuelOption'  
  produces: json
  parameters:
          VehicleTypeFuelOptionAdd:[body,required,type:"VehicleTypeFuelOptionAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/vehicle_type_fuel_options/{id}':
  summary: 'Update Vehicle Type FuelOption'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type FuelOption Identifier']
		  VehicleTypeFuelOptionEdit:[body,required,type:"VehicleTypeFuelOptionEdit"]
  responses:
    200:true    
    404:'Not found'	

GET '/admin/vehicle_type_fuel_options/{id}/edit':
  summary: 'Edit Vehicle Type FuelOption'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type FuelOption Identifier']
  responses:
    200:true
    404:'Not found'	

GET '/admin/vehicle_type_fuel_options/{id}':
  summary: 'Show Vehicle Type FuelOption'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type FuelOption Identifier']
  responses:
    200:true
    404:'Not found'

DELETE '/admin/vehicle_type_fuel_options/{id}':
  summary: 'Delete Vehicle Type FuelOption'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type FuelOption Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'VehicleTypeFuelOptionAdd':
	vehicle_type_id:[int64,required,"Vehicle Type Identifier"]
	fuel_option_id:[int64,required,"FuelOption identifier"]
	rate:[int64,required,"rate"]
	discount_type_id:[int64,required,"DiscountType identifier"]
	duration_type_id:[int64,required,"Duration Type identifier"]
	max_allowed_amount:[int64,required,"Max allowed amount"]

MODEL 'VehicleTypeFuelOptionEdit':
    id:[int64,required,"The Vehicle Type FuelOption identifier"]
    vehicle_type_id:[int64,required,"Vehicle Type Identifier"]
    fuel_option_id:[int64,required,"FuelOption identifier"]
    rate:[int64,required,"Rate for this type"]
    discount_type_id:[int64,required,"DiscountType identifier"]
    duration_type_id:[int64,required,"Duration Type identifier"]
    max_allowed_amount:[int64,required,"Max allowed amount"]
