swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_insurances':
  summary: 'Fetch List of insurances'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search Insurance']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of insurances ']
          sort:[query,string,description:'The Insurance Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort Insurance by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'