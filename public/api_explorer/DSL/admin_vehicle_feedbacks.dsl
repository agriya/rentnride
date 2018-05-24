swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_feedbacks':
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

PUT '/admin/vehicle_feedbacks/{id}':
  summary: 'Update vehicle feedbacks'
  produces: json
  parameters:
    id:[path,int64,required,description:'The vehicle feedbacks Identifier']
    FeedbackEdit:[body,required,type:"FeedbackEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_feedbacks/{id}/edit':
  summary: 'Edit vehicle feedbacks'
  produces: json
  parameters:
    id:[path,int64,required,description:'The vehicle feedbacks Identifier']
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_feedbacks/{id}':
  summary: 'Fetch particular vehicle feedback'
  produces: json
  parameters:
    id:[path,int64,required,description:'The vehicle feedbacks Identifier']
  responses:
    200:true
404:'Not found'

DELETE '/admin/vehicle_feedbacks/{id}':
  summary: 'Delete vehicle feedbacks'
  produces: json
  parameters:
    id:[path,int64,required,description:'The vehicle feedbacks Identifier']
  responses:
    200:true
    404:'Not found'

MODEL 'FeedbackEdit':
    id:[int64,required,"The vehicle feedback identifier"]
    feedback:[string,required,"Feedback"]
    rating:[int64, required, "Rating"]
