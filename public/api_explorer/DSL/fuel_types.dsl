swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/fuel_types':
  summary: 'Get the list of fuel types'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search fuel type']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for fuel types']
   sort:[query,string, description:'The fuel type Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort fuel type by Ascending / Descending Order']
  produces: json
  responses:
    200:true

