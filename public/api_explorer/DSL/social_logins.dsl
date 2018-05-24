swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

POST '/auth/facebook':
  summary: 'Facebook Users'  
  produces: json
  parameters:
         Facebook:[body,required,type:"Facebook"]  
  responses:
    200:true    
    404:'Not found'

POST '/auth/google':
  summary: 'Google Users'  
  produces: json
  parameters:
         Google:[body,required,type:"Google"]  
  responses:
    200:true    
    404:'Not found'

POST '/auth/linkedin':
  summary: 'Linkedin Users'  
  produces: json
  parameters:
         Linkedin:[body,required,type:"Linkedin"]  
  responses:
    200:true    
    404:'Not found'
	
POST '/auth/twitter':
  summary: 'Twitter Users'  
  produces: json
  parameters:
         Twitter:[body,required,type:"Twitter"]  
  responses:
    200:true    
    404:'Not found'

POST '/auth/github':
  summary: 'Github Users'  
  produces: json
  parameters:
         Github:[body,required,type:"Github"]  
  responses:
    200:true    
    404:'Not found'
 
GET '/provider_users':
  summary: 'Fetch provider usres list'  
  produces: json
  parameters:
          filter:[query,string, description:'filter list of provider user']
          sort:[query,string, description:'The State Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort States by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'

POST '/auth/unlink':
  summary: 'Unlink Provider user'  
  produces: json
  parameters:
          Unlink:[body,required,type:"Unlink"]
  responses:
    200:true    
    404:'Not found'

POST '/update_profile':
  summary: 'Update profile image'  
  produces: json
  parameters:
          UpdateProfile:[body,required,type:"UpdateProfile"]
  responses:
    200:true    
    404:'Not found'

POST '/social_login':
  summary: 'Social login register email'  
  produces: json
  parameters:
         SocialLogin:[body,required,type:"SocialLogin"]  
  responses:
    200:true    
    404:'Not found'

MODEL 'Facebook':
  clientId:[string,required,"Facebook app id"]
  secret_key:[string,required,"Facebook secret id"]
  code: [string, required, "Authenticated code"]
  redirectUri:[string, required, "Redirect url"]
  
MODEL 'Google':
  clientId:[string,required,"Google app id"]
  secret_key:[string,required,"Google secret id"]
  code: [string, required, "Authenticated code"]
  redirectUri:[string, required, "Redirect url"]

MODEL 'Linkedin':
  clientId:[string,required,"Linkedin app id"]
  secret_key:[string,required,"Linkedin secret id"]
  code: [string, required, "Authenticated code"]
  redirectUri:[string, required, "Redirect url"]

MODEL 'Twitter':
  clientId:[string,required,"Twitter app id"]
  secret_key:[string,required,"Twitter secret id"]
  code: [string, required, "Authenticated code"]
  redirectUri:[string, required, "Redirect url"]

MODEL 'Github':
  clientId:[string,required,"Github app id"]
  secret_key:[string,required,"Github secret id"]
  code: [string, required, "Authenticated code"]
  redirectUri:[string, required, "Redirect url"]

MODEL "Unlink":
  provider:[string,required,"Enter Provider name"]

MODEL "UpdateProfile":
  source_id:[string,required,"Enter Source identifies"]

MODEL 'SocialLogin':
  email:[string,required,"Email"]
  thrid_party_profile:[string,required,"third party profile"]
