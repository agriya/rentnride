swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/wallet_transaction_logs':
  summary: 'Get the list of wallet transaction logs'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   sort:[query,string, description:'The Log Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort log by Ascending / Descending Order']
  produces: json
  responses:
    200:true

GET '/admin/wallet_transaction_logs/{id}':
  summary: 'View wallet transaction log'
  parameters:
   id:[path,int64,required,description:'view log details']
  produces: json
  responses:
    200:true
