swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_taxes':
  summary: 'Fetch List of taxes'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search Tax']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of taxes ']
          sort:[query,string,description:'The Tax Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort Tax by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_taxes':
  summary: 'Store Tax'  
  produces: json
  parameters:
          TaxAdd:[body,required,type:"TaxAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/vehicle_taxes/{id}':
  summary: 'Update Tax'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Tax Identifier']
          TaxEdit:[body,required,type:"TaxEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_taxes/{id}/edit':
  summary: 'Edit Tax'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Tax Identifier']		  
  responses:
    200:true    
    404:'Not found'

GET '/admin/vehicle_taxes/{id}':
  summary: 'Show Tax'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Tax Identifier']		  
  responses:
    200:true    
    404:'Not found'


DELETE '/admin/vehicle_taxes/{id}':
  summary: 'Delete Tax'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Tax Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'TaxAdd':	
	name:[string,required,"Tax Name"]
	short_description:[string,required,"Short Description"]
	description:[string,required,"Description"]
	
MODEL 'TaxEdit':
    id:[int64,required,"The Tax identifier"]
    name:[string,required,"Tax Name"]
    short_description:[string,required,"Short Description"]
    description:[string,required,"Description"]
    is_active:[int64, required, "is active"]
