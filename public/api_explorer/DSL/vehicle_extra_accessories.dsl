swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_extra_accessories':
  summary: 'Fetch List of extra accessories'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search ExtraAccessory']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of extra accessories ']
          sort:[query,string,description:'The ExtraAccessory Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort ExtraAccessory by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'