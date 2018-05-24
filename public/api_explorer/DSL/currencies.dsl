swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/currencies':
  summary: 'Fetch List of Currencies'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          type:[query,string, description:'Display Currency By Listing Type']
          field:[query,string, description:'Give Whatever Fields Needed by "Comma Seperator"']
          q:[query,string, description:'Search Currency']
          filter:[query,string, description:'filter list of currencies ']
          sort:[query,string, description:'The Currency Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Currency by Ascending / Descending Order']	  
	 	  
  responses:
    200:true    
    404:'Not found'
		
