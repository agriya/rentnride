swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_types':
  summary: 'Get the list of vehicle types'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle type']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for vehicle types']
   sort:[query,string, description:'The vehicle type Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle type by Ascending / Descending Order']
  produces: json
  responses:
    200:true

POST '/admin/vehicle_types':
  summary: 'Store vehicle type'
  produces: json
  parameters:
    VehicleTypeAdd:[body,required,type:"VehicleTypeAdd"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_types/{id}/edit':
  summary: 'Edit the vehicle type'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

PUT '/admin/vehicle_types/{id}':
  summary: 'Update vehicle type'
  produces: json
  parameters:
   id:[path,int64,required,description:'vehicle type id']
   VehicleTypeEdit:[body,required,type:"VehicleTypeEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_types/{id}':
  summary: 'View the vehicle type'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

DELETE '/admin/vehicle_types/{id}':
  summary: 'Delete the specified vehicle type'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter vehicle type ID"]
  responses:
    200:true

MODEL 'VehicleTypeAdd':
	name:[string,required,"Vehicle type Name"]
	minimum_hour_price:[int64,"minimum_hour_price"]
	maximum_hour_price:[int64,"maximum_hour_price"]
	minimum_day_price:[int64,"minimum_day_price"]
	maximum_day_price:[int64,"maximum_day_price"]
	drop_location_differ_unit_price:[int64,"drop_location_differ_unit_price"]
	drop_location_differ_additional_fee:[int64,"drop_location_differ_additional_fee"]
	deposit_amount:[int64,required,"deposit_amount"]
	is_active:[int64,required,"is active"]

MODEL "VehicleTypeEdit":
    id:[int64,required,"The Vehicle type identifier"]
    name:[string,required,"Vehicle type Name"]
  	minimum_hour_price:[int64,"minimum_hour_price"]
    maximum_hour_price:[int64,"maximum_hour_price"]
    minimum_day_price:[int64,"minimum_day_price"]
    maximum_day_price:[int64,"maximum_day_price"]
    drop_location_differ_unit_price:[int64,"drop_location_differ_unit_price"]
    drop_location_differ_additional_fee:[int64,"drop_location_differ_additional_fee"]
    deposit_amount:[int64,required,"deposit_amount"]
    is_active:[int64,required,"is active"]
