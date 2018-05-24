swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/languages':
  summary: 'Fetch Languages'  
  produces: json
  parameters:
          page:[query, int,  description:'Give which page to be loaded']
          q:[query,string, description:'Search Languages']
          filter:[query,enum:['active', 'inactive'],string, description:'filter list of languages']
          sort:[query,string,description:'The language Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort languages by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/languages':
  summary: 'Store Languages'  
  produces: json
  parameters:
          LanguageAdd:[body,required,type:"LanguageAdd"]
  responses:
    200:true    
    404:'Not found'

GET '/admin/languages/{language_id}/edit':
  summary: 'Edit Language'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Language Identifier']		  
  responses:
    200:true    
    404:'Not found'

PUT '/admin/languages/{language_id}':
  summary: 'update language'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The language Identifier']
	  LanguageEdit:[body,required,type:"LanguageEdit"]
  responses:
    200:true    
    404:'Not found'	

DELETE '/admin/languages/{language_id}':
  summary: 'Delete languages'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The language Identifier']
  responses:
    200:true    
    404:'Not found'	

		
		
MODEL 'LanguageAdd':	
	name:[string,required,"Name"]
	iso2:[string,required,"iso2"]
	iso3:[string,required,"iso3"]	

MODEL 'LanguageEdit':	
	id: [int, required, 'The Language Identifier']
	name:[string,required,"Name"]
	iso2:[string,required,"iso2"]
	iso3:[string,required,"iso3"]
	is_active:[int64,required,"is active"]
	
