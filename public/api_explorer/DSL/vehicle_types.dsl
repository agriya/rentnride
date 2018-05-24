swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_types':
  summary: 'Get the list of vehicle types'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle type']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for vehicle types']
   sort:[query,string, description:'The vehicle type Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle type by Ascending / Descending Order']
  produces: json
  responses:
    200:true
	
GET '/vehicle_types/{id}':
  summary: 'View the vehicle type'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true	

