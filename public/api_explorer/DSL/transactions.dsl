swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/transactions':
  summary: 'Fetch all Transactions'  
  produces: json
  parameters:
    page: [query, int,  description:'Page number for filtering']
    q:[query,string, description:'Search Transaction']
    filter:[query,enum:['All', 'Today', 'This Week', 'This Month'],int, description:'filter list of requests ']
    sort:[query,string, description:'The field_name to sort']
    sortby:[query,enum:['asc', 'desc'],string, description:'Sort Requests by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
