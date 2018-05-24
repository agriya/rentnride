swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_type_extra_accessories':
  summary: 'Fetch List of vehicle_type_extra_accessories'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search Vehicle Type ExtraAccessory']
          sort:[query,string,description:'The Vehicle Type ExtraAccessory Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle_type_extra_accessories by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_type_extra_accessories':
  summary: 'Store Vehicle Type ExtraAccessory'  
  produces: json
  parameters:
          VehicleTypeExtraAccessoryAdd:[body,required,type:"VehicleTypeExtraAccessoryAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/vehicle_type_extra_accessories/{id}':
  summary: 'Update Vehicle Type ExtraAccessory'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type ExtraAccessory Identifier']
		  VehicleTypeExtraAccessoryEdit:[body,required,type:"VehicleTypeExtraAccessoryEdit"]
  responses:
    200:true    
    404:'Not found'

GET '/admin/vehicle_type_extra_accessories/{id}/edit':
  summary: 'Edit Vehicle Type Extra Accessory'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type ExtraAccessory Identifier']
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_type_extra_accessories/{id}':
  summary: 'Show Vehicle Type Extra Accessory'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type ExtraAccessory Identifier']
  responses:
    200:true
    404:'Not found'

DELETE '/admin/vehicle_type_extra_accessories/{id}':
  summary: 'Delete Vehicle Type ExtraAccessory'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Vehicle Type ExtraAccessory Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'VehicleTypeExtraAccessoryAdd':
	vehicle_type_id:[int64,required,"Vehicle Type Identifier"]
	extra_accessory_id:[int64,required,"ExtraAccessory identifier"]
	rate:[int64,required,"rate"]
	discount_type_id:[int64,required,"DiscountType identifier"]
	duration_type_id:[int64,required,"Duration Type identifier"]
	max_allowed_amount:[int64,required,"Max allowed amount"]

MODEL 'VehicleTypeExtraAccessoryEdit':
    id:[int64,required,"The Vehicle Type ExtraAccessory identifier"]
    vehicle_type_id:[int64,required,"Vehicle Type Identifier"]
    extra_accessory_id:[int64,required,"ExtraAccessory identifier"]
    rate:[int64,required,"Rate for this type"]
    discount_type_id:[int64,required,"DiscountType identifier"]
    duration_type_id:[int64,required,"Duration Type identifier"]
    max_allowed_amount:[int64,required,"Max allowed amount"]
