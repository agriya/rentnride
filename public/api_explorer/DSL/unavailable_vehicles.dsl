swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/unavailable_vehicles':
  summary: 'Get the list of vehicles'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   vehicle_id: [query, int64,  description:'Particular vehicle ID']
   q:[query,string, description:'Search vehicle']
   sort:[query,string, description:'The vehicle Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle by Ascending / Descending Order']
  produces: json
  responses:
    200:true
	
POST '/unavailable_vehicles':
  summary: 'Store unavailable vehicle'
  produces: json
  parameters:
    UnavailableVehicleAdd:[body,required,type:"UnavailableVehicleAdd"]
  responses:
    200:true
    404:'Not found'	

GET '/unavailable_vehicles/{id}/edit':
  summary: 'Edit the unavailable vehicle'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

PUT '/unavailable_vehicles/{id}':
  summary: 'Update unavailable vehicle'
  produces: json
  parameters:
   id:[path,int64,required,description:'unavailable vehicle id']
   UnavailableVehicleEdit:[body,required,type:"UnavailableVehicleEdit"]
  responses:
    200:true
    404:'Not found'

DELETE '/unavailable_vehicles/{id}':
  summary: 'Delete the specified unavailable vehicle'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter unavailable vehicle ID"]
  responses:
    200:true

MODEL 'UnavailableVehicleAdd':
    vehicle_id:[int64, required, "vehicle_model_id"]
    start_date:[string, required, "maintenance_start_date"]
    end_date:[string, required, "maintenance_end_date"]

MODEL "UnavailableVehicleEdit":
    id:[int64,required,"The Vehicle identifier"]
    start_date:[string, required, "maintenance_start_date"]
    end_date:[string, required, "maintenance_end_date"]	