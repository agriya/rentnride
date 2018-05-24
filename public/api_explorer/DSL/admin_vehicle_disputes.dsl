swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_disputes':
  summary: 'Fetch Disputes'
  produces: json
  parameters:
          page:[query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search Pages']	 
          filter:[query,enum:['Open', 'Under Discussion', 'Waiting Administrator Decision', 'Closed'],string, description:'filter list of pages ']
          sort:[query,string, description:'The page Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort page by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_disputes/resolve':
  summary: 'Resolve Disputes'
  produces: json
  parameters:
          DisputeResolve:[body,required,type:"DisputeResolve"]
  responses:
    200:true    
    404:'Not found'
		

MODEL 'DisputeResolve':
	item_user_id:[int64,required,"The item user Identifier"]
	dispute_closed_type_id:[int64,required,"The closed type Identifier"]
	feedback:[string,required,"Feedback"]
    rating:[int64, required, "Rating"]
	
