swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/withdrawal_statuses':
  summary: 'Fetch all withdrawal status'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          sort:[query,string, description:'The status Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort status by Ascending / Descending Order']
          q: [query,string, description:'Search status']

  responses:
    200:true    
    404:'Not found'
