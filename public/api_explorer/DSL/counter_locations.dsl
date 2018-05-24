swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/counter_locations':
  summary: 'Get the list of counter locations'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search counter location']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for counter locations']
   sort:[query,string, description:'The counter location Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort counter location by Ascending / Descending Order']
  produces: json
  responses:
    200:true

