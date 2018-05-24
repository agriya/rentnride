swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_disputes':
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
		
POST '/vehicle_disputes/add':
  summary: 'Resolve Disputes'
  produces: json
  parameters:
          DisputeAdd:[body,required,type:"DisputeAdd"]
  responses:
    200:true    
    404:'Not found'

GET '/vehicle_disputes/{id}':
  summary: 'View the vehicle rental dispute by vehicle rental id'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

MODEL 'DisputeAdd':
	item_user_id:[int64,required,"The item user Identifier"]
	dispute_type_id:[int64,required,"The closed type Identifier"]
	reason :[string,required,"The reason of dispute"]

