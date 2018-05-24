swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_makes':
  summary: 'Get the list of vehicle makes'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle make']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for vehicle makes']
   sort:[query,string, description:'The vehicle make Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle make by Ascending / Descending Order']
  produces: json
  responses:
    200:true

POST '/admin/vehicle_makes':
  summary: 'Store vehicle make'
  produces: json
  parameters:
    VehicleMakeAdd:[body,required,type:"VehicleMakeAdd"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_makes/{id}/edit':
  summary: 'Edit the vehicle make'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

PUT '/admin/vehicle_makes/{id}':
  summary: 'Update vehicle make'
  produces: json
  parameters:
   id:[path,int64,required,description:'vehicle make id']
   VehicleMakeEdit:[body,required,type:"VehicleMakeEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_makes/{id}':
  summary: 'View the vehicle make'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

DELETE '/admin/vehicle_makes/{id}':
  summary: 'Delete the specified vehicle make'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter vehicle make ID"]
  responses:
    200:true

MODEL 'VehicleMakeAdd':
	name:[string,required,"Vehicle make Name"]
	is_active:[int64, required, "is_active"]

MODEL 'VehicleMakeEdit':
    id:[int64,required,"The Vehicle make identifier"]
	name:[string,required,"Vehicle make Name"]
	is_active:[int64, required, "is_active"]
