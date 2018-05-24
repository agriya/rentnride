swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_dispute_types':
  summary: 'Fetch Dispute types'
  produces: json
  parameters:
          page:[query, int,  description:'Give which page to be loaded']
          filter:[query,enum:['active', 'inactive'],string, description:'filter list of pages ']
          sort:[query,string, description:'The page Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort page by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'

GET '/admin/vehicle_dispute_types/{id}/edit':
  summary: 'Edit Dispute Closed Types'
  produces: json
  parameters:
          id:[path,int64,required,description:'Edit dispute type identifier']
  responses:
    200:true
    404:'Not found'

PUT '/admin/vehicle_dispute_types/{id}':
  summary: 'Update Dispute Closed Types'
  produces: json
  parameters:
          id:[path,int64,required,description:'Edit dispute type Identifier']
	      DisputeTypeEdit:[body,required,type:"DisputeTypeEdit"]
  responses:
    200:true
    404:'Not found'


MODEL 'DisputeTypeEdit':
	id:[int64,required,"The closed type identifier"]
	name:[string,required,"The name of the closed type"]
	is_booker:[int64,required,"The booker / host identifier"]
	is_active:[int64,required,"is active"]
	
