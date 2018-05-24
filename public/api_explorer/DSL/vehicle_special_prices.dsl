swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_special_prices':
  summary: 'Get the list of vehicle special prices'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle special price']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for vehicle special prices']
   sort:[query,string, description:'The vehicle special price Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle special price by Ascending / Descending Order']
  produces: json
  responses:
    200:true

GET '/vehicle_special_prices/{id}/edit':
  summary: 'Edit the vehicle special price'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

GET '/vehicle_special_prices/{id}':
  summary: 'View the vehicle special price'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

