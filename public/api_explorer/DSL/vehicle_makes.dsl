swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_makes':
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

