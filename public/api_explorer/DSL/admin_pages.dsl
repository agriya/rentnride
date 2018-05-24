swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/pages?filter={filter}&sort={sort}&sortby={sortby}&page={page}&type={type}&field={field}&q={q}':
  summary: 'Fetch pages'  
  produces: json
  parameters:
          page:[query, int,  description:'Give which page to be loaded']
          type:[query,string, description:'Display Pages By Listing Type']
          field:[query,string, description:'Give Whatever Fields Needed by "Comma Seperator"']
          q:[query,string, description:'Search Pages']	 
          filter:[query,string, description:'filter list of pages ']
          sort:[query,string, description:'The page Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort page by Ascending / Descending Order']
	  
  responses:
    200:true    
    404:'Not found'
		
POST '/admin/pages':
  summary: 'Store Pages'  
  produces: json
  parameters:
          PageAdd:[body,required,type:"PageAdd"]
  responses:
    200:true    
    404:'Not found'

GET '/admin/pages/{id}/edit':
  summary: 'Edit Page'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Page Identifier']		  
  responses:
    200:true    
    404:'Not found'

PUT '/admin/pages/{id}':
  summary: 'update pages'  
  produces: json
  parameters:
	  id:[path,int64,required,description:'The Page Identifier']
	  PageEdit:[body,required,type:"PageEdit"]
  responses:
    200:true    
    404:'Not found'	

DELETE '/admin/pages/{id}':
  summary: 'Delete Pages'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Page Identifier']
  responses:
    200:true    
    404:'Not found'	

GET '/admin/pages/{id}':
  summary: 'Show Pages'  
  produces: json
  parameters:
          id:[path,int64,required,description:'The Page Identifier']
  responses:
    200:true    
    404:'Not found'

MODEL 'PageAdd':
	slug:[string,required,"Page Slug"]
	language_id:[{ref:"PageAddArray"},"an array of page objects"]

MODEL 'PageAddArray':
	language_id:[int64,required,"The Language Identifier"]
	title:[string,required,"Page Title"]
	page_content:[string,required,"Page Content"]
	slug:[string,required,"Page Slug"]

MODEL 'PageEdit':
       id: [int, required, 'The Page Identifier']
       language_id:[int64,required,"The Language Identifier"]
       title:[string,required,"Page Title"]
       page_content:[string,required,"Page Content"]
       slug:[string,required,"Page Slug"]
       is_active:[int64, required, "is active"]
	
