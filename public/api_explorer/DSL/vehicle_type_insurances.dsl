swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_type_insurances':
  summary: 'Fetch List of vehicle_type_insurances'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search Vehicle Type Insurance']
          sort:[query,string,description:'The Vehicle Type Insurance Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle_type_insurances by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'