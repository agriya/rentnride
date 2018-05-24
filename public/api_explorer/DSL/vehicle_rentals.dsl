swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_rentals':
  summary: 'Get the list of vehicle_rentals'
  produces: json
  parameters:
          page: [query, int,  description:'Page number for filtering']
          filter:[query,enum:['upcoming', 'waiting for review', 'past', 'waiting for acceptance', 'rejected', 'expired', 'All'],int, description:'filter list of Vehicle Rental']
          sort:[query,string, description:'The Vehicle rental Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Vehicle rental by Ascending / Descending Order']
  responses:
    200:true
    404:'Not found'

POST '/vehicle_rentals':
  summary: 'Add Vehicle rental'
  produces: json
  parameters:
        AddVehicleRental:[body,required,type:"AddVehicleRental"]
  responses:
    200:true
    404:'Not found'

GET '/vehicle_rentals/{id}/edit':
  summary: 'Edit Vehicle Rental'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Rental Identifier']
  responses:
    200:true
    404:'Not found'

PUT '/vehicle_rentals/{id}':
  summary: 'Update Vehicle Rental'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Rental Identifier']
          EditVehicleRental:[body,required,type:"EditVehicleRental"]
  responses:
    200:true
    404:'Not found'

GET '/vehicle_rentals/{id}':
  summary: 'Show Vehicle Rental'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Rental Identifier']
  responses:
    200:true
    404:'Not found'

POST '/vehicle_rentals/{id}/paynow':
  summary: 'Pay Vehicle rental fee'
  produces: json
  parameters:
   id:[path,int64,required,description:'The Vehicle Rental Identifier']
   VehicleRentalFee:[body,required,type:"VehicleRentalFee"]
  responses:
    200:true
    404:'Not found'

GET '/vehicle_rentals/{id}/reject':
  summary: 'Reject Vehicle Rental'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Rental Identifier']
  responses:
    200:true
    404:'Not found'

GET '/vehicle_rentals/{id}/cancel':
  summary: 'Cancel Vehicle Rental'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Rental Identifier']
  responses:
    200:true
    404:'Not found'

GET '/vehicle_rentals/{id}/confirm':
  summary: 'Confirm Vehicle Rental'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Rental Identifier']
  responses:
    200:true
    404:'Not found'

GET '/vehicle_orders':
  summary: 'Get list of orders'
  produces: json
  parameters:
          page: [query, int,  description:'Page number for filtering']
          filter:[query,enum:['upcoming', 'waiting for review', 'past', 'waiting for acceptance', 'rejected', 'expired', 'All'],int, description:'filter list of vehicle_rental']
          sort:[query,string, description:'The Vehicle Rental Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Vehicle Rental by Ascending / Descending Order']
  responses:
    200:true
    404:'Not found'
	
GET '/vehicle_rentals/{id}/checkin':
  summary: 'Checkin / attend the  Booking'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Booking Identifier']		  
  responses:
    200:true    
    404:'Not found'
	
POST '/vehicle_rentals/{id}/checkout':
  summary: 'Checkout  the  Booking'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Booking Identifier']		  
          Checkout:[body,required,type:"Checkout"]
  responses:
    200:true    
    404:'Not found'	

MODEL 'AddVehicleRental':
    vehicle_id: [int64, required, 'The Item Identifier']
    pickup_counter_location_id:[int64, required, 'Vehicle pickup location']
    drop_counter_location_id:[int64, required, 'Vehicle drop location']
    item_booking_start_date:[datetime,required,"Starting Date & time"]
    item_booking_end_date:[datetime,required,"Ending Date & time"]

MODEL 'EditVehicleRental':
    id:[string,required,"The Vehicle Rental identifier"]
    vehicle_type_extra_accessories:[[ref:""], "an array of extra accessories"]
    vehicle_type_fuel_options:[[ref:""], "an array of fuel options"]
    vehicle_type_insurances:[[ref:""], "an array of insurances"]
    first_name:[string,required,"Booker First Name"]
    last_name:[string,required,"Booker Last Name"]
    email:[string,required,"Booker_email"]
    mobile:[string,required,"Booker Mobile"]
    address:[string,required,"Booker Address"]
	
MODEL 'Checkout':
    id:[string,required,"The Vehicle Rental identifier"]
    claim_request_amount:[int64,required,"Host claim amount if any"]

MODEL 'VehicleRentalFee':
	vehicle_rental_id:[int64, required, "vehicle rental id"]
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

