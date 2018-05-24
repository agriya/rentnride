swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/sudopay_ipn_logs':
  summary: 'Get the list of sudopay ipn logs'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   sort:[query,string, description:'The Log Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort Log by Ascending / Descending Order']
  produces: json
  responses:
    200:true

GET '/admin/sudopay_ipn_logs/{id}':
  summary: 'View sudopay ipn log'
  parameters:
   id:[path,int64,required,description:'view log details']
  produces: json
  responses:
    200:true
