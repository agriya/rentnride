swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/vehicle_surcharges':
  summary: 'Fetch List of surcharges'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search Surcharge']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of surcharges ']
          sort:[query,string,description:'The Surcharge Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort Surcharge by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/vehicle_surcharges':
  summary: 'Store Surcharge'  
  produces: json
  parameters:
          SurchargeAdd:[body,required,type:"SurchargeAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/vehicle_surcharges/{id}':
  summary: 'Update Surcharge'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Surcharge Identifier']
          SurchargeEdit:[body,required,type:"SurchargeEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/vehicle_surcharges/{id}/edit':
  summary: 'Edit Surcharge'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Surcharge Identifier']		  
  responses:
    200:true    
    404:'Not found'

GET '/admin/vehicle_surcharges/{id}':
  summary: 'Show Surcharge'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Surcharge Identifier']		  
  responses:
    200:true    
    404:'Not found'


DELETE '/admin/vehicle_surcharges/{id}':
  summary: 'Delete Surcharge'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Surcharge Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'SurchargeAdd':	
	name:[string,required,"Surcharge Name"]
	short_description:[string,required,"Short Description"]
	description:[string,required,"Description"]
	
MODEL 'SurchargeEdit':
    id:[int64,required,"The Surcharge identifier"]
    name:[string,required,"Surcharge Name"]
    short_description:[string,required,"Short Description"]
    description:[string,required,"Description"]
    is_active:[int64, required, "is active"]
