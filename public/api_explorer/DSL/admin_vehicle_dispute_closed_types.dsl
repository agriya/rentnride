swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_dispute_closed_types':
  summary: 'Fetch Dispute closed types'
  produces: json
  parameters:
          page:[query, int,  description:'Give which page to be loaded']
          sort:[query,string, description:'The page Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort page by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
		
GET '/admin/vehicle_dispute_closed_types/{id}/edit':
  summary: 'Edit Dispute Closed Types'
  produces: json
  parameters:
          id:[path,int64,required,description:'Edit dispute closed type identifier']
  responses:
    200:true
    404:'Not found'

PUT '/admin/vehicle_dispute_closed_types/{id}':
  summary: 'Update Dispute Closed Types'
  produces: json
  parameters:
          id:[path,int64,required,description:'Edit dispute closed type Identifier']
	      DisputeClosedTypeEdit:[body,required,type:"DisputeClosedTypeEdit"]
  responses:
    200:true
    404:'Not found'


MODEL 'DisputeClosedTypeEdit':
	id:[int64,required,"The closed type identifier"]
	name:[string,required,"The name of the closed type"]
	dispute_type_id:[int64,required,"The dispute type Identifier"]
	is_booker:[int64,required,"The booker / host identifier"]
	resolved_type:[string,required,"Resolve Info about the type"]
	reason:[string,required,"Reason of the type"]

