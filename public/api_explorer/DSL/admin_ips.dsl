swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/ips':
  summary: 'Fetch List of Ips'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search IP']
          sort:[query,string, description:'The IP Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort IP by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
		
DELETE '/admin/ips/{id}':
  summary: 'Delete IP'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The IP Identifier']
  responses:
    200:true    
    404:'Not found'	

GET '/admin/ips/{id}':
  summary: 'Show IP Details'  
  produces: json
  parameters:
         id:[path,int64,required,description:'The IP Identifier']
  responses:
    200:true    
    404:'Not found'