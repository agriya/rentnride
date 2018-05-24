swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/languages':
  summary: 'Fetch Languages'  
  produces: json
  parameters:
          filter:[query,string, description:'filter list of languages']
          page:[query, int,  description:'Give which page to be loaded']
          type:[query,string, description:'Display Languages By Listing Type']
          field:[query,string, description:'Give Whatever Fields Needed by "Comma Seperator"']
          q:[query,string, description:'Search Languages']
          sort:[query,string, description:'The language Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort languages by Ascending / Descending Order']	  
	  	  	  
  responses:
    200:true    
    404:'Not found'
