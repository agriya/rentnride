swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_type_prices':
  summary: 'Get the list of vehicle type prices'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle type price']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for vehicle type prices']
   sort:[query,string, description:'The vehicle type price Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle type price by Ascending / Descending Order']
  produces: json
  responses:
    200:true

POST '/admin/vehicle_type_prices':
  summary: 'Store vehicle type price'
  produces: json
  parameters:
    VehicleTypePriceAdd:[body,required,type:"VehicleTypePriceAdd"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_type_prices/{id}/edit':
  summary: 'Edit the vehicle type price'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

PUT '/admin/vehicle_type_prices/{id}':
  summary: 'Update vehicle type price'
  produces: json
  parameters:
   id:[path,int64,required,description:'vehicle type price id']
   VehicleTypePriceEdit:[body,required,type:"VehicleTypePriceEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_type_prices/{id}':
  summary: 'View the vehicle type price'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

DELETE '/admin/vehicle_type_prices/{id}':
  summary: 'Delete the specified vehicle type price'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter vehicle type price ID"]
  responses:
    200:true

MODEL 'VehicleTypePriceAdd':
	vehicle_type_id:[int64,required,"vehicle_type_id"]
	minimum_no_of_day:[int64,required,"minimum_no_of_day"]
	maximum_no_of_day:[int64,required,"maximum_no_of_day"]
	discount_percentage:[double,required,"discount_percentage"]
	is_active:[int64,required,"is_active"]

MODEL "VehicleTypePriceEdit":
    id:[int64,required,"The Vehicle type price identifier"]
    vehicle_type_id:[int64,required,"vehicle_type_id"]
    minimum_no_of_day:[int64,required,"minimum_no_of_day"]
    maximum_no_of_day:[int64,required,"maximum_no_of_day"]
    discount_percentage:[double,required,"discount_percentage"]
    is_active:[int64,required,"is_active"]
