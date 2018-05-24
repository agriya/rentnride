swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/setting_categories':
  summary: 'List all setting categories'
  parameters:
      sort:[query,string,description:"sorting field name"]
      sortby:[query,enum:['asc', 'desc'],string,description:'sort setting category by ascending / descending order']
      page:[query, int, description:'Page number for filtering']
  produces: json
  responses:
    200:true    
    404:'Not found'
	
GET '/admin/setting_categories/{id}':
  summary: 'Show setting category'  
  produces: json
  parameters:
	  id:[path,int64,required,description:'Setting category id']
  responses:
    200:true    
    404:'Not found'		