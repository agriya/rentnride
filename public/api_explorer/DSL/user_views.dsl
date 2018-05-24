swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
	
GET '/admin/user_views':
  summary: 'Fetch user views'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          q: [query,string, description:'Search User Views']
          sort:[query,string, description:'The user_view Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort User View by Ascending / Descending Order']
		  
  responses:
    200:true    
    404:'Not found'
	
DELETE '/admin/user_views/{user_view_id}':
  summary: 'Delete User View'  
  produces: json
  parameters:
        id:[path,int64,required, description:'The user_view Identifier'] 
  responses:
    200:true    
    404:'Not found'

	