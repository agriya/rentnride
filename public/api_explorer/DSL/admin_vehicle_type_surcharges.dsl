swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_type_surcharges':
  summary: 'Fetch List of vehicle_type_surcharges'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search Vehicle Type Surcharge']
          sort:[query,string,description:'The Vehicle Type Surcharge Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle_type_surcharges by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_type_surcharges':
  summary: 'Store Vehicle Type Surcharge'  
  produces: json
  parameters:
          VehicleTypeSurchargeAdd:[body,required,type:"VehicleTypeSurchargeAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/vehicle_type_surcharges/{id}':
  summary: 'Update Vehicle Type Surcharge'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Surcharge Identifier']
		  VehicleTypeSurchargeEdit:[body,required,type:"VehicleTypeSurchargeEdit"]
  responses:
    200:true    
    404:'Not found'

GET '/admin/vehicle_type_surcharges/{id}/edit':
  summary: 'Edit Vehicle Type Surcharge'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Surcharge Identifier']
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_type_surcharges/{id}':
  summary: 'Show Vehicle Type Surcharge'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Surcharge Identifier']
  responses:
    200:true
    404:'Not found'

DELETE '/admin/vehicle_type_surcharges/{id}':
  summary: 'Delete Vehicle Type Surcharge'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Surcharge Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'VehicleTypeSurchargeAdd':
	vehicle_type_id:[int64,required,"Vehicle Type Identifier"]
	surcharge_id:[int64,required,"Surcharge identifier"]
	rate:[int64,required,"rate"]
	discount_type_id:[int64,required,"DiscountType identifier"]
	duration_type_id:[int64,required,"Duration Type identifier"]
	max_allowed_amount:[int64,required,"Max allowed amount"]

MODEL 'VehicleTypeSurchargeEdit':
    id:[int64,required,"The Vehicle Type Surcharge identifier"]
    vehicle_type_id:[int64,required,"Vehicle Type Identifier"]
    surcharge_id:[int64,required,"Surcharge identifier"]
    rate:[int64,required,"Rate for this type"]
    discount_type_id:[int64,required,"DiscountType identifier"]
    duration_type_id:[int64,required,"Duration Type identifier"]
    max_allowed_amount:[int64,required,"Max allowed amount"]
