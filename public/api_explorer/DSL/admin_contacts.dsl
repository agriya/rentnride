swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET 'admin/contacts':
  summary: 'Fetch List of Contacts'
  produces: json
  parameters:
          page: [query, int64,  description:'Give which page to be loaded']          
          q:[query,string, description:'Search Contact']
          sort:[query,string, description:'The Contact Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Contact by Ascending / Descending Order']
		  
  responses:
    200:true
    404:'Not found'

DELETE '/admin/contacts/{id}':
  summary: 'Delete Contact'  
  produces: json
  parameters:
          id:[path,int64,required,description:'Delete IP Details from List']
  responses:
    200:true    
    404:'Not found'

