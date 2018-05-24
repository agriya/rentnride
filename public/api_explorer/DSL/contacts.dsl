swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

POST '/admin/contacts':
  summary: 'Add Contact Details'  
  produces: json
  parameters:
    ContactAdd:[body,required,type:"ContactAdd"]
  responses:
    200:true    
    404:'Not found'
	
	
MODEL "ContactAdd":
    first_name:[string,required,"Contact First Name"]
    last_name:[string,required,"Contact Last Name"]
    email:[string,required,"Contact Email"]
    subject:[string,required,"Contact Subject"]
    message:[string,required,"Contact Message"]
    telephone:[int64,required,"Contact Number"]
