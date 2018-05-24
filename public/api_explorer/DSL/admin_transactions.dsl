swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/transactions':
  summary: 'Fetch all Transactions'  
  produces: json
  parameters:
    page: [query, int,  description:'Page number for filtering']
    q:[query,string, description:'Search Transaction']
    filter:[query,enum:['Admin', 'All'],int, description:'filter list of requests ']
    sort:[query,string, description:'The field_name to sort']
    sortby:[query,enum:['asc', 'desc'],string, description:'Sort Requests by Ascending / Descending Order']
    limit:[query,enum:['all'],string, description:'choose limit']
    transaction_type_id:[query,string,description:'comma separated list of transaction_type_id']
    to_user:[query,int64, description:'Filter user identifier']
  responses:
    200:true    
    404:'Not found'
