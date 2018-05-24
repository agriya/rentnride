swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_type_surcharges':
  summary: 'Fetch List of vehicle_type_surcharges'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search Vehicle Type Surcharge']
          sort:[query,string,description:'The Vehicle Type Surcharge Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle_type_surcharges by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
