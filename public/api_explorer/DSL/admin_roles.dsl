swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/admin/roles':
  summary: 'Fetch all roles'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          sort:[query,string, description:'The role Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort role by Ascending / Descending Order']
          q: [query,string, description:'Search roles']

  responses:
    200:true    
    404:'Not found'
