swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_feedbacks':
  summary: 'Fetch List of vehicle feedback'
  produces: json
  parameters:
    page: [query, int, description:'Give which page to be loaded']
    q:[query,string,description:'Search vehicle feedback']
    sort:[query,string,description:'The vehicle feedback Identifier']
    sortby:[query,enum:['asc', 'desc'],string,description:'Sort vehicle feedback by Ascending / Descending Order']
    user_id:[query,int64,description:'Filter user Identifier']
    vehicle_id:[query,int64,description:'Filter vehicle Identifier']
  responses:
    200:true
    404:'Not found'

GET '/vehicle_feedbacks/{id}':
  summary: 'Fetch particular feedback'
  produces: json
  parameters:
    id:[path,int64,required,description:'The vehicle feedback Identifier']
  responses:
    200:true
    404:'Not found'

POST '/booker/review':
  summary: 'Add booker Review' 
  produces: json
  parameters:
    AddReview:[body,required,type:"AddReview"]
  responses:
    200:true    
    404:'Not found'
	
POST '/host/review':
  summary: 'Add host Review' 
  produces: json
  parameters:
    AddReview:[body,required,type:"AddReview"]
  responses:
    200:true    
    404:'Not found'		
	
MODEL 'AddReview':
    item_user_id:[int64,required,"The item user id"]
    feedback:[string,required,"Feedback"]
    rating:[int64,"Rating"]	