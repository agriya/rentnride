swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_type_taxes':
  summary: 'Fetch List of vehicle_type_taxes'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search Vehicle Type Tax']
          sort:[query,string,description:'The Vehicle Type Tax Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle_type_taxes by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_type_taxes':
  summary: 'Store Vehicle Type Tax'  
  produces: json
  parameters:
          VehicleTypeTaxAdd:[body,required,type:"VehicleTypeTaxAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/vehicle_type_taxes/{id}':
  summary: 'Update Vehicle Type Tax'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Tax Identifier']
		  VehicleTypeTaxEdit:[body,required,type:"VehicleTypeTaxEdit"]
  responses:
    200:true    
    404:'Not found'	

GET '/admin/vehicle_type_taxes/{id}/edit':
  summary: 'Edit Vehicle Type Tax'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Tax Identifier']
  responses:
    200:true
    404:'Not found'	

GET '/admin/vehicle_type_taxes/{id}':
  summary: 'Show Vehicle Type Tax'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Tax Identifier']
  responses:
    200:true
    404:'Not found'

DELETE '/admin/vehicle_type_taxes/{id}':
  summary: 'Delete Vehicle Type Tax'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type Tax Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'VehicleTypeTaxAdd':
	vehicle_type_id:[int64,required,"Vehicle Type Identifier"]
	tax_id:[int64,required,"Tax identifier"]
	rate:[int64,required,"rate"]
	discount_type_id:[int64,required,"DiscountType identifier"]
	duration_type_id:[int64,required,"Duration Type identifier"]
	max_allowed_amount:[int64,required,"Max allowed amount"]

MODEL 'VehicleTypeTaxEdit':
    id:[int64,required,"The Vehicle Type Tax identifier"]
    vehicle_type_id:[int64,required,"Vehicle Type Identifier"]
    tax_id:[int64,required,"Tax identifier"]
    rate:[int64,required,"Rate for this type"]
    discount_type_id:[int64,required,"DiscountType identifier"]
    duration_type_id:[int64,required,"Duration Type identifier"]
    max_allowed_amount:[int64,required,"Max allowed amount"]
