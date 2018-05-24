swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/currencies':
  summary: 'Fetch List of Currencies'  
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
		  type: [query, string, description:'Give which type currency load']
          q:[query,string,description:'Search Currency']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of currencies ']
          sort:[query,string,description:'The Currency Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort Currency by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/currencies':
  summary: 'Store Currency'  
  produces: json
  parameters:
          CurrencyAdd:[body,required,type:"CurrencyAdd"]
  responses:
    200:true    
    404:'Not found'

GET '/admin/currencies/{id}/edit':
  summary: 'Edit Currency'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Currency Identifier']		  
  responses:
    200:true    
    404:'Not found'

PUT '/admin/currencies/{id}':
  summary: 'Update Currency'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Currency Identifier']
		  CurrencyEdit:[body,required,type:"CurrencyEdit"]
  responses:
    200:true    
    404:'Not found'	

DELETE '/admin/currencies/{id}':
  summary: 'Delete Currency'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Currency Identifier']
  responses:
    200:true    
    404:'Not found'

GET '/admin/currencies/{id}':
  summary: 'Show Currency'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Currency Identifier']
  responses:
    200:true    
    404:'Not found'		
		
MODEL 'CurrencyAdd':	
	name:[string,required,"Currency Name"]
	code:[string,required,"Currency Code"]
	symbol:[string,required,"Currency Symbol"]
	decimals:[string,required,"Decimal length of Currency"]
	dec_point:[string,required,"Format of decimal dot/comma"]
	thousands_sep:[string,required,"Thousands Separator of Currency"]
	prefix:[string,required,"Display symbol if currency is placed before"]
	suffix:[string,required,"Display symbol if currency is placed after"]
	is_prefix_display_on_left:[int64,required,"Whether to display currency in left"]
	is_use_graphic_symbol:[int64,required,"Need to use graphical symbols for Currency"]

MODEL 'CurrencyEdit':	
	id: [int, required, 'The Currency Identifier']
	name:[string,required,"Currency Name"]
	code:[string,required,"Currency Code"]
	symbol:[string,required,"Currency Symbol"]
	decimals:[string,required,"Decimal length of Currency"]
	dec_point:[string,required,"Format of decimal dot/comma"]
	thousands_sep:[string,required,"Thousands Separator of Currency"]
	prefix:[string,required,"Display symbol if currency is placed before"]
	suffix:[string,required,"Display symbol if currency is placed after"]
	is_prefix_display_on_left:[int64,required,"Whether to display currency in left"]
	is_use_graphic_symbol:[int64,required,"Need to use graphical symbols for Currency"]
	is_active:[int64, required, "is active currency"]
