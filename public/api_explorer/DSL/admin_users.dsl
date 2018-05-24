swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/admin/users':
  summary: 'Fetch all users'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          type: [query,string, description:'Display Users By Listing Type']
          field: [query,string,description:'Give Whatever Fields Needed by "Comma Seperator"']
          q: [query,string, description:'Search Users']
          filter:[query,enum:['active', 'inactive', 'facebook', 'twitter', 'google', 'All'],string, description:'filter list of user ']
          sort:[query,string, description:'The User Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort User by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
		

POST '/admin/users':
  summary: 'Store Users'  
  produces: json
  parameters:
          User:[body,required,type:"UserAdd"]
  responses:
    200:true    
    404:'Not found'
	
DELETE '/admin/users/{id}':
  summary: 'Delete Users'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The User Identifier']
  responses:
    200:true    
    404:'Not found'	

GET '/admin/users/{id}/edit':
  summary: 'Edit Users'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The User Identifier']		  
  responses:
    200:true    
    404:'Not found'

PUT '/admin/users/{id}':
  summary: 'update users'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The User Identifier']
		  UserEdit:[body,required,type:"UserEdit"]
  responses:
    200:true    
    404:'Not found'	
	
GET '/admin/users/{id}':	
  summary: 'Show user details'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The User Identifier']		 
  responses:
    200:true    
    404:'Not found'	


PUT '/admin/users/{id}/change_password':
  summary: 'Change User Password'  
  produces: json
  parameters:
	  id:[path,int64,required,description:'The User Identifier']
	  ChangePassword:[body,required,type:"ChangePassword"]          	  
  responses:
    200:true    
    404:'Not found'

PUT '/admin/users/{id}/deactive':
  summary: 'Deactivate the user '  
  produces: json
  parameters:
	  id:[path,int64,required,description:'The User Identifier']
  responses:
    200:true    
    404:'Not found'    

PUT '/admin/users/{id}/active':
  summary: 'Activate the user '  
  produces: json
  parameters:
	  id:[path,int64,required,description:'The User Identifier']
  responses:
    200:true    
    404:'Not found'  

MODEL 'UserAdd':	
	username:[string,required,"Name of the User"]
	email:[string,required,"Email"]
	password:[string,required,"Password"]
	role_id:[int64,enum:[1,2],required,"If User role_id = 2, Admin role_id = 1"]

MODEL 'UserEdit':	
	id: [int, required, 'The User Identifier']
	username:[string,required,"Username"]
	email:[string,required,"Email"]
	role_id:[int64,enum:[1,2],required,"If User role_id = 2, Admin role_id = 1"]
	is_active:[int64,enum:[0,1],required,"If User need to  be activated then value 1 else 0"]
	is_email_confirmed:[int64,enum:[0,1],required,"If User need to  be email verified then value = 1 else value = 0"]

MODEL 'ChangePassword':
	id:[int64,required,"The User Identifier"]	
	password:[string,required,"New Password"]
	confirm_password:[string,required,"Confirm Password"]