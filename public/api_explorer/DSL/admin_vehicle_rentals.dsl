swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/admin/vehicle_rentals':
  summary: 'Fetch all vehicle_rentals'
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q: [query,string, description:'Search Vehicle rentals']
          filter:[query,enum:['upcoming', 'waiting for review', 'past', 'waiting for acceptance', 'rejected', 'expired', 'All'],string, description:'filter list of vehicle_rental']
          sort:[query,string, description:'The Vehicle rental Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Vehicle rental by Ascending / Descending Order']
          start_date:[query,string,description:'Booking Start date (YYYY-MM-DD) format']
          end_date:[query,string,description:'Booking end date (YYYY-MM-DD) format']
  responses:
    200:true
    404:'Not found'

PUT '/admin/vehicle_rentals/{id}/cancelled-by-admin':
  summary: 'Cancel Vehicle rental'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle rental Identifier']
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_rentals/{id}':
  summary: 'Show Vehicle rental Details'
  produces: json
  parameters:
         id:[path,int64,required,description:'The Vehicle rental Identifier']
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_rentals/{id}/checkin':
  summary: 'Checkin / attend the  Booking'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Booking Identifier']
  responses:
    200:true
    404:'Not found'

POST '/admin/vehicle_rentals/{id}/checkout':
  summary: 'Checkout  the  Booking'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Booking Identifier']
          Checkout:[body,required,type:"Checkout"]
  responses:
    200:true
    404:'Not found'

MODEL 'Checkout':
    id:[string,required,"The Vehicle Rental identifier"]
    claim_request_amount:[int64,required,"Host claim amount if any"]
