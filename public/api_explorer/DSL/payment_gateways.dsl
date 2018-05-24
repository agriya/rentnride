swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/get_gateways':
  summary: 'Get the list of payment gateways'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   sort:[query,string, description:'The Log Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort Item by Ascending / Descending Order']
  produces: json
  responses:
    200:true