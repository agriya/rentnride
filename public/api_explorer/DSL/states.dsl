swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/states':
  summary: 'Fetch List of states'  
  produces: json
  parameters:
          filter:[query,string, description:'filter list of states ']
          type:[query,string, description:'Display State By Listing Type']
          field:[query,string, description:'Give Whatever Fields Needed by "Comma Seperator"']
          q:[query,string, description:'Search State']
          sort:[query,string, description:'The State Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort States by Ascending / Descending Order']
  
responses:
    200:true    
    404:'Not found'
		
