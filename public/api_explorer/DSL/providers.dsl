swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/providers':
  summary: 'Fetch all providers'  
  produces: json
  parameters:
          filter:[query,enum:['active', 'inactive'],string, description:'Filter List of Providers ']
          page: [query, int,  description:'Give which page to be loaded']
          q: [query,string, description:'Search Providers']
          sort:[query,string,description:'The Provider Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Provider by Ascending / Descending Order']		  
		  
  responses:
    200:true    
    404:'Not found'
