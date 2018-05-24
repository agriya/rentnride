swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_type_prices':
  summary: 'Get the list of vehicle type prices'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle type price']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for vehicle type prices']
   sort:[query,string, description:'The vehicle type price Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle type price by Ascending / Descending Order']
  produces: json
  responses:
    200:true

GET '/vehicle_type_prices/{id}/edit':
  summary: 'Edit the vehicle type price'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

GET '/vehicle_type_prices/{id}':
  summary: 'View the vehicle type price'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true
