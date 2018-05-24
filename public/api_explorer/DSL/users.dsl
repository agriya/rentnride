swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
POST '/users/register':
  summary: 'Register Users'  
  produces: json
  parameters:
         Register:[body,required,type:"Register"]  
  responses:
    200:true    
    404:'Not found'
	
POST '/users/login':
  summary: 'Login user'  
  produces: json
  parameters:
          Login:[body,required,type:"Login"]
  responses:
    200:true    
    404:'Not found'

GET '/user':
  summary: 'view user profile'  
  produces: json
  parameters:
          ViewUser:[body,required,type:"ViewUser"]
  responses:
    200:true    
    404:'Not found'
	
GET '/users/{user_id}/attachment':
  summary: 'Get user Upload image thumb'  
  produces: json
  parameters:
          User:[path,int64,required, description:'The User Identifier']
  responses:
    200:true    
    404:'Not found'
	

PUT '/users/{user_id}/activate':
  summary: 'activate user'  
  produces: json
  parameters:
          User:[path,int64,required,description:'The User Identifier']
  responses:
    200:true    
    404:'Not found'

PUT '/users/forgot_password':
  summary: 'forgot password'  
  produces: json
  parameters:
          ForgotPassword:[body,required,type:"ForgotPassword"]
  responses:
    200:true    
    404:'Not found'

PUT '/users/{user_id}/change_password':
  summary: 'change user password'  
  produces: json
  parameters:
          User:[path,int64,required,description:'The User Identifier']
          ChangePassword:[body,required,type:"ChangePassword"]	  
  responses:
    200:true    
    404:'Not found'

GET '/users/auth':
  summary: 'get user details'  
  produces: json  
  responses:
    200:true    
    404:'Not found'

GET '/users/stats':
  summary: 'get user status'  
  produces: json  
  responses:
    200:true    
    404:'Not found'
	
MODEL 'ChangePassword':	
	old_password:[string,required,"Old Password"]
	password:[string,required,"New Password"]
	confirm_password:[string,required,"Confirm Password"]	

MODEL 'Register':
  username:[string,required,"Name of the User"]
  email:[string,required,"Email of the User"]
  password:[string,required,"Password"]
  confirm_password:[string,required,"Confirm Password"]
  is_agree_terms_conditions:[int64,required,"Accept Terms and Conditions"]
 
MODEL 'Login':
  email:[string,required,"Login Email"]
  password:[string,required,"Login Password"]
 
MODEL "ForgotPassword":
  email:[string,required,"Enter Email"]

MODEL "ViewUser":
  username:[string,required,"Enter User name"]