swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/email_templates':
  summary: "List of Email Templates"
  parameters:
    sort:[query,string,description:"sorting field name"]
    sortby:[query,enum:['asc', 'desc'],string,description:'sort template by ascending / descending order']
    page:[query, int, description:'Page number for filtering']
  produces: json
  responses:
    200:true	
	
GET '/admin/email_templates/{id}/edit':
  summary: 'Edit the email template by ID'  
  produces: json
  parameters:
   id:[path,int64,required,description:'Email template id']		
  responses:
    200:true	

PUT '/admin/email_templates/{id}':
  summary: 'Update the Email Template'
  produces: json
  parameters:
    id:[path,int64,required,description:'Email template id']
		  EmailTemplateEdit:[body,required,type:"EmailTemplateEdit"]
  responses:
    200:true	
  	
MODEL 'EmailTemplateEdit':
    id:[string,required,"The Email Template identifier"]
    name:[string,required,"Name of the Email Template"]
    from_name:[string,required,"From Email Address"]
    reply_to:[string,required,"Reply To email Address"]
    subject:[string,required,"Subject of the template"]
    body_content:[string,required,"Email body content"]