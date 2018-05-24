swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_type_insurances':
  summary: 'Fetch List of vehicle_type_insurances'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search Vehicle Type Insurance']
          sort:[query,string,description:'The Vehicle Type Insurance Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle_type_insurances by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_type_insurances':
  summary: 'Store Vehicle Type Insurance'  
  produces: json
  parameters:
          VehicleTypeInsuranceAdd:[body,required,type:"VehicleTypeInsuranceAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/vehicle_type_insurances/{id}':
  summary: 'Update Vehicle Type Insurance'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Insurance Identifier']
		  VehicleTypeInsuranceEdit:[body,required,type:"VehicleTypeInsuranceEdit"]
  responses:
    200:true    
    404:'Not found'	

GET '/admin/vehicle_type_insurances/{id}/edit':
  summary: 'Edit Vehicle Type Insurance'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Insurance Identifier']
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_type_insurances/{id}':
  summary: 'Show Vehicle Type Insurance'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Insurance Identifier']
  responses:
    200:true
    404:'Not found'

DELETE '/admin/vehicle_type_insurances/{id}':
  summary: 'Delete Vehicle Type Insurance'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Insurance Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'VehicleTypeInsuranceAdd':
	vehicle_type_id:[int64,required,"Vehicle Type Identifier"]
	insurance_id:[int64,required,"Insurance identifier"]
	rate:[int64,required,"rate"]
	discount_type_id:[int64,required,"DiscountType identifier"]
	duration_type_id:[int64,required,"Duration Type identifier"]
	max_allowed_amount:[int64,required,"Max allowed amount"]

MODEL 'VehicleTypeInsuranceEdit':
    id:[int64,required,"The Vehicle Type Insurance identifier"]
    vehicle_type_id:[int64,required,"Vehicle Type Identifier"]
    insurance_id:[int64,required,"Insurance identifier"]
    rate:[int64,required,"Rate for this type"]
    discount_type_id:[int64,required,"DiscountType identifier"]
    duration_type_id:[int64,required,"Duration Type identifier"]
    max_allowed_amount:[int64,required,"Max allowed amount"]
