swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_models':
  summary: 'Get the list of vehicle models'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle model']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for vehicle models']
   sort:[query,string, description:'The vehicle model Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle model by Ascending / Descending Order']
  produces: json
  responses:
    200:true

POST '/admin/vehicle_models':
  summary: 'Store vehicle model'
  produces: json
  parameters:
    VehicleModelAdd:[body,required,type:"VehicleModelAdd"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_models/{id}/edit':
  summary: 'Edit the vehicle model'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

PUT '/admin/vehicle_models/{id}':
  summary: 'Update vehicle model'
  produces: json
  parameters:
   id:[path,int64,required,description:'vehicle model id']
   VehicleModelEdit:[body,required,type:"VehicleModelEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_models/{id}':
  summary: 'View the vehicle model'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

DELETE '/admin/vehicle_models/{id}':
  summary: 'Delete the specified vehicle model'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter vehicle model ID"]
  responses:
    200:true

MODEL 'VehicleModelAdd':
	name:[string,required,"Vehicle model Name"]
	vehicle_make_id:[int64,required,"Vehicle make Id"]
	is_active:[string,required,"is_active"]

MODEL 'VehicleModelEdit':
	id:[int64,required,"The Vehicle model identifier"]
	name:[string,required,"Vehicle model Name"]
    vehicle_make_id:[int64,required,"Vehicle make Id"]
    is_active:[string,required,"is_active"]
