swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/admin/user_logins':
  summary: 'Fetch User Logins'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          sort:[query,string, description:'The user_login Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort User Login by Ascending / Descending Order']
		  
		  
  responses:
    200:true    
    404:'Not found'
	
DELETE '/admin/user_logins/{id}':
  summary: 'Delete User Logins'  
  produces: json
  parameters:
        id:[path,int64,required,description:'The user_login Identifier'] 
  responses:
    200:true    
    404:'Not found'

GET '/admin/user_logins/{id}':
  summary: 'Show User Login'  
  produces: json
  parameters:
         id:[path,int64,required,description:'The User Login Identifier']
  responses:
    200:true    
    404:'Not found'	