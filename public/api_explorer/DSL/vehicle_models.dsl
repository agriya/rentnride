swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_models':
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

