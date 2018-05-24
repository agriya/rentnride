swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_special_prices':
  summary: 'Get the list of vehicle special prices'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle special price']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for vehicle special prices']
   sort:[query,string, description:'The vehicle special price Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle special price by Ascending / Descending Order']
  produces: json
  responses:
    200:true

POST '/admin/vehicle_special_prices':
  summary: 'Store vehicle special price'
  produces: json
  parameters:
    VehicleSpecialPriceAdd:[body,required,type:"VehicleSpecialPriceAdd"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_special_prices/{id}/edit':
  summary: 'Edit the vehicle special price'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

PUT '/admin/vehicle_special_prices/{id}':
  summary: 'Update vehicle special price'
  produces: json
  parameters:
   id:[path,int64,required,description:'vehicle special price id']
   VehicleSpecialPriceEdit:[body,required,type:"VehicleSpecialPriceEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_special_prices/{id}':
  summary: 'View the vehicle special price'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

DELETE '/admin/vehicle_special_prices/{id}':
  summary: 'Delete the specified vehicle special price'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter vehicle special price ID"]
  responses:
    200:true

MODEL 'VehicleSpecialPriceAdd':
	start_date:[string,required,"start_date"]
	end_date:[string,required,"end_date"]
	vehicle_type_id:[int64,required,"vehicle_type_id"]
	discount_percentage:[double,required,"discount_percentage"]
	is_active:[int64,required,"is_active"]

MODEL "VehicleSpecialPriceEdit":
    id:[int64,required,"The Vehicle special price identifier"]
    start_date:[string,required,"start_date"]
    end_date:[string,required,"end_date"]
    vehicle_type_id:[int64,required,"vehicle_type_id"]
    discount_percentage:[double,required,"discount_percentage"]
	is_active:[int64,required,"is_active"]
