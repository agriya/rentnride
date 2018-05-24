swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_fuel_options':
  summary: 'Fetch List of Vehicle fuel options'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search FuelOption']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of vehicle fuel options ']
          sort:[query,string,description:'The Fuel Option Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort FuelOption by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'