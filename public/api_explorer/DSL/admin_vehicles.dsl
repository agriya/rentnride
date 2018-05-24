swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicles':
  summary: 'Get the list of vehicles'
  parameters:
    page: [query, int64,  description:'Give which page to be loaded']
    q:[query,string, description:'Search vehicle']
    sort:[query,string, description:'The vehicle Identifier']
    sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle by Ascending / Descending Order']
    vehicle_company_id:[query,int64,description:'Filter vehicle company Identifier']
    vehicle_model_id:[query,int64,description:'Filter vehicle Model Identifier']
    vehicle_make_id:[query,int64,description:'Filter vehicle make Identifier']
    vehicle_type_id:[query,int64,description:'Filter vehicle type Identifier']
  produces: json
  responses:
    200:true
	
POST '/admin/vehicles':
  summary: 'Store vehicle'
  produces: json
  parameters:
    VehicleAdd:[body,required,type:"VehicleAdd"]
  responses:
    200:true
    404:'Not found'	

GET '/admin/vehicles/{id}/edit':
  summary: 'Edit the vehicle'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true
	
POST '/admin/vehicles/{id}':
  summary: 'Update vehicle'
  produces: json
  parameters:
   id:[path,int64,required,description:'vehicle id']
   VehicleEdit:[body,required,type:"VehicleEdit"]
  responses:
    200:true
    404:'Not found'	

GET '/admin/vehicles/{id}':
  summary: 'View the vehicle'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

GET '/admin/vehicle/add':
  summary: 'Get the vehicle related details'
  produces: json
  responses:
    200:true

DELETE '/admin/vehicles/{id}':
  summary: 'Delete the specified vehicle'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter vehicle ID"]
  responses:
    200:true

PUT '/admin/vehicles/{id}/deactive':
  summary: 'Deactivate the vehicle'  
  produces: json
  parameters:
	  id:[path,int64,required,description:'The Vehicle Identifier']
  responses:
    200:true    
    404:'Not found'    

PUT '/admin/vehicles/{id}/active':
  summary: 'Activate the vehicle'  
  produces: json
  parameters:
	  id:[path,int64,required,description:'The Vehicle Identifier']
  responses:
    200:true    
    404:'Not found' 
	
MODEL 'VehicleAdd':
    vehicle_company_id:[int64, required, "vehicle company id"]
	vehicle_make_id:[int64, required, "vehicle_make_id"]
	vehicle_model_id:[int64, required, "vehicle_model_id"]
	vehicle_type_id:[int64, required, "vehicle_type_id"]
	pickup_counter_locations:[[ref:""], "an array of counter locations"]
	drop_counter_locations:[[ref:""], "an array of counter locations"]
	driven_kilometer:[double, "driven_kilometer"]
	vehicle_no:[string, "vehicle_no"]
	no_of_seats:[int64, "no_of_seats"]
	no_of_doors:[int64, "no_of_doors"]
	no_of_gears:[int64, "no_of_gears"]
	is_manual_transmission:[int64, "is_manual_transmission"]
	no_small_bags:[int64, "no_small_bags"]
	no_large_bags:[int64, "no_large_bags"]
	is_ac:[int64, "is_ac"]
	minimum_age_of_driver: [int64, "minimum_age_of_driver"] 
	mileage:[double, "mileage"]
	is_km:[int64, "is_km"]
	is_airbag:[int64, "is_airbag"]
	no_of_airbags:[int64, "no_of_airbags"]
	is_abs:[int64, "is_abs"]
	per_hour_amount:[double, required, "per_hour_amount"]
	per_day_amount:[double, required, "per_day_amount"]
	fuel_type_id:[int64, "fuel_type_id"]
	is_active:[int64, required, "is_active"]
	is_paid:[int64, required, "is_paid"]
	
MODEL "VehicleEdit":
    id:[int64,required,"The Vehicle identifier"]
    vehicle_company_id:[int64, required, "vehicle company id"]
    vehicle_make_id:[int64, required, "vehicle_make_id"]
    vehicle_model_id:[int64, required, "vehicle_model_id"]
    vehicle_type_id:[int64, required, "vehicle_type_id"]
    pickup_counter_locations:[[ref:""], "an array of counter locations"]
    drop_counter_locations:[[ref:""], "an array of counter locations"]
    driven_kilometer:[double, "driven_kilometer"]
    vehicle_no:[string, "vehicle_no"]
    no_of_seats:[int64, "no_of_seats"]
    no_of_doors:[int64, "no_of_doors"]
    no_of_gears:[int64, "no_of_gears"]
    is_manual_transmission:[int64, "is_manual_transmission"]
    no_small_bags:[int64, "no_small_bags"]
    no_large_bags:[int64, "no_large_bags"]
    is_ac:[int64, "is_ac"]
    minimum_age_of_driver: [int64, "minimum_age_of_driver"]
    mileage:[double, "mileage"]
    is_km:[int64, "is_km"]
    is_airbag:[int64, "is_airbag"]
    no_of_airbags:[int64, "no_of_airbags"]
    is_abs:[int64, "is_abs"]
    per_hour_amount:[double, required, "per_hour_amount"]
    per_day_amount:[double, required, "per_day_amount"]
    fuel_type_id:[int64, "fuel_type_id"]
    is_paid:[int64, required, "is_active"]
    is_active:[int64, required, "is_active"]