swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/countries':
  summary: 'Fetch List of countries'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search Country']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of countries ']
          sort:[query,string,description:'The Country Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort Country by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/countries':
  summary: 'Store Country'  
  produces: json
  parameters:
          CountryAdd:[body,required,type:"CountryAdd"]
  responses:
    200:true    
    404:'Not found'

PUT '/admin/countries/{id}':
  summary: 'Update Country'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Country Identifier']
          CountryEdit:[body,required,type:"CountryEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/countries/{id}/edit':
  summary: 'Edit Country'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Country Identifier']		  
  responses:
    200:true    
    404:'Not found'


DELETE '/admin/countries/{id}':
  summary: 'Delete Country'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Country Identifier']
  responses:
    200:true    
    404:'Not found'
		
MODEL 'CountryAdd':	
	name:[string,required,"Country Name"]
	iso2:[string,required,"iso2"]
	iso3:[string,required,"iso3"]
	
MODEL 'CountryEdit':
    id:[string,required,"The Country identifier"]
	name:[string,required,"Country Name"]
	iso2:[string,required,"iso2"]
	iso3:[string,required,"iso3"]
	is_active:[int64, required, "is active"]
