swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/admin/api_requests':
  summary: 'Fetch All Api Requests'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          sort:[query,string, description:'The Api Request Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Api Request by Ascending / Descending Order']
          q: [query,string, description:'Search Api Requests']

  responses:
    200:true    
    404:'Not found'

GET '/admin/api_requests/{id}':
  summary: 'Show Api Request'  
  produces: json
  parameters:
         id:[path,int64,required,description:'The Api Request Identifier']
  responses:
    200:true    
    404:'Not found'
	
DELETE '/admin/api_requests/{id}':
  summary: 'Delete Api Request'  
  produces: json
  parameters:
        id:[path,int64,required,description:'The Api Request Identifier'] 
  responses:
    200:true    
    404:'Not found'

	