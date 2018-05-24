swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/cancellation_types':
  summary: 'Fetch List of cancellation types'
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search cancellation types']
          sort:[query,string,description:'The cancellation types Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort cancellation types by Ascending / Descending Order']
  responses:
    200:true
    404:'Not found'

POST '/admin/cancellation_types':
  summary: 'Store cancellation types'
  produces: json
  parameters:
          CancellationTypeAdd:[body,required,type:"CancellationTypeAdd"]
  responses:
    200:true
    404:'Not found'

PUT '/admin/cancellation_types/{id}':
  summary: 'Update cancellation types'
  produces: json
  parameters:
          id:[path,int64,required,description:'The cancellation types Identifier']
          CancellationTypeEdit:[body,required,type:"CancellationTypeEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/cancellation_types/{id}/edit':
  summary: 'Edit Cancellation Type'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Cancellation Type Identifier']
  responses:
    200:true
    404:'Not found'

GET '/admin/cancellation_types/{id}':
  summary: 'Fetch particular Cancellation Type'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Cancellation Type Identifier']
  responses:
    200:true
    404:'Not found'

MODEL 'CancellationTypeAdd':
	name:[string,required,"Cancellation Type Name"]
    description:[string,required,"Description of the Cancellation Type"]
    minimum_duration:[string,required,"maximum duration"]
    maximum_duration:[string,required,"maximum duration"]
    deduct_rate:[int64,required,"Deduct rate in %"]

MODEL 'CancellationTypeEdit':
    id:[int64,required,"The Cancellation type identifier"]
	name:[string,required,"Cancellation Type Name"]
    description:[string,required,"Description of the Cancellation Type"]
    minimum_duration:[string,required,"maximum duration"]
    maximum_duration:[string,required,"maximum duration"]
    deduct_rate:[int64,required,"Deduct rate in %"]
