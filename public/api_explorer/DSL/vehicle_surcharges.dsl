swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_surcharges':
  summary: 'Fetch List of surcharges'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search Surcharge']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of surcharges ']
          sort:[query,string,description:'The Surcharge Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort Surcharge by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
