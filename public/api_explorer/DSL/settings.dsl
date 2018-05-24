swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/settings?type=public_settings':
  summary: 'Fetch all settings'  
  produces: json
  parameters:
    page: [query, int,  description:'Page number for filtering']
    q:[query,string, description:'Search settings']
    sort:[query,string, description:'The settings to sort']
    sortby:[query,enum:['asc', 'desc'],string, description:'Sort settings by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
