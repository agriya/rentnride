swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicles/filters':
  summary: 'Get the list of filter details needed for vehicle search'
  produces: json
  responses:
    200:true
	
GET '/vehicles/me':
  summary: 'Get the own list of vehicles'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle']
   sort:[query,string, description:'The vehicle Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle by Ascending / Descending Order']
  produces: json
  responses:
    200:true	

POST '/vehicles/search':
  summary: 'Search the vehicles and list'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   sort:[query,enum:['price', 'rating'], string, description:'Sort Vehicle by Price / Rating Order']
   sortby:[query,enum:['asc', 'desc'], string, description:'Sort vehicle by Ascending / Descending Order']   
   VehicleSearch:[body,required,type:"VehicleSearch"]
  produces: json
  responses:
    200:true

POST '/vehicles':
  summary: 'Store vehicle'
  produces: json
  parameters:
    VehicleAdd:[body,required,type:"VehicleAdd"]
  responses:
    200:true
    404:'Not found'

GET '/vehicles/{id}/edit':
  summary: 'Edit the vehicle'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

POST '/vehicles/{id}':
  summary: 'Update vehicle'
  produces: json
  parameters:
   id:[path,int64,required,description:'vehicle id']
   VehicleEdit:[body,required,type:"VehicleEdit"]
  responses:
    200:true
    404:'Not found'

GET '/vehicles/{id}':
  summary: 'View the vehicle'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

DELETE '/vehicles/{id}':
  summary: 'Delete the specified vehicle'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter vehicle ID"]
  responses:
    200:true

GET '/vehicle/add':
  summary: 'Get the vehicle related details'
  produces: json
  responses:
    200:true
	
POST '/vehicles/paynow':
  summary: 'Pay Vehicle listing fee'
  produces: json
  parameters:
   VehicleListingFee:[body,required,type:"VehicleListingFee"]
  responses:
    200:true
    404:'Not found'	
	
MODEL 'VehicleListingFee':
	vehicle_id:[int64, required, "vehicle id"]
	gateway_id:[int64, required, "gateway id"]
	payment_id:[int64, required, "payment id"]
	address:[string, required, "address"]
	city:[string, required, "city"]
	state:[string, required, "state"]
	country:[string, required, "country"]
	zip_code:[string, required, "zip_code"]
	credit_card_code:[string, required, "credit_card_code"]
	credit_card_expire:[string, required, "credit_card_expire"]
	credit_card_expire_month:[string, required, "credit_card_expire_month"]
	credit_card_expire_year:[string, required, "credit_card_expire_year"]
	credit_card_name_on_card:[string, required, "credit_card_name_on_card"]
	credit_card_number:[string, required, "credit_card_number"]
	email:[string, required, "email"]
	phone:[string, required, "phone"]

MODEL 'VehicleAdd':
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

MODEL "VehicleEdit":
    id:[int64,required,"The Vehicle identifier"]
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

MODEL "VehicleSearch":
    start_date:[query, string, required, description:'Enter start date for your trip']
    end_date:[query, string, required:false, description:'Enter end date for your trip']
    pickup_location_id:[query, string, required:true, description:"Enter Pickup location id."]
    drop_location_id:[query, string, required:true, description:"Enter Drop location id."]
    vehicle_type:[[ref:"vehicle_type"],"an array of 'vehicle_type' objects"]
    fuel_type:[[ref:"fuel_type"],"an array of 'fuel_type' objects"]
    price_min:[int64, "Minimum price to filter"]
    price_max:[int64, "Maximum price to filter"]
    mileage_min:[int64, "Minimum Mileage to filter"]
    mileage_max:[int64, "Maximum Mileage to filter"]
    seat_min:[int64, "Minimum Seat to filter"]
    seat_max:[int64, "Maximum Seat to filter"]
    ac:[boolean, "Filter Vehicle by AC Type"]
    non_ac:[boolean, "Filter Vehicle by AC Type"]
    manual_transmission:[boolean, "Filter Vehicle by Transmission Type"]
    auto_transmission:[boolean, "Filter Vehicle by Transmission Type"]
    airbag:[boolean, "Filter Vehicle by Airbag Type"]
    non_airbag:[boolean, "Filter Vehicle by Airbag Type"]
	
