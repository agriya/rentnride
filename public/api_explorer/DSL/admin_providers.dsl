swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/admin/providers':
  summary: 'Fetch all providers'  
  produces: json
  parameters:
          filter:[query,enum:['active', 'inactive'],string, description:'Filter List of Providers ']
          page: [query, int,  description:'Give which page to be loaded']
          q: [query,string, description:'Search Providers']
          sort:[query,string,description:'The Provider Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Provider by Ascending / Descending Order']		  
		  
  responses:
    200:true    
    404:'Not found'


GET '/admin/providers/{id}/edit':
  summary: 'Edit Providers'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Provider Identifier']		 
  responses:
    200:true    
    404:'Not found'

PUT '/admin/providers/{id}':
  summary: 'Update Providers'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Provider Identifier']
          ProviderEdit:[body,required,type:"ProviderEdit"]
  responses:
    200:true
    404:'Not found'

DELETE '/admin/providers/{id}':
  summary: 'Delete Providers'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Provider Identifier']
  responses:
    200:true    
    404:'Not found'	

MODEL 'ProviderEdit':
    id:[int, required, 'The Provider Identifier']
    name:[string,required,"Name of the Provider"]
    secret_key:[string,required,"Secret Key of the Provider"]
    api_key:[string,required,"Api Key of the Provider"]
    display_order:[string,required,"Enter Order to Display Providers"]
    is_active:[int64, required, "is active"]
