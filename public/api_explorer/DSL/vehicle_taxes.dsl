swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_taxes':
  summary: 'Fetch List of taxes'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search Tax']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of taxes ']
          sort:[query,string,description:'The Tax Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort Tax by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'