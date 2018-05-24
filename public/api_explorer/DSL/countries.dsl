swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/countries':
  summary: 'Fetch List of countries'  
  produces: json
  parameters:
          filter:[query,string, description:'filter list of countries ']
          type:[query,string, description:'Display Country By Listing Type']
          field:[query,string, description:'Give Whatever Fields Needed by "Comma Seperator"']
          q:[query,string, description:'Search Country']
          sort:[query,string, description:'The Country Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Country by Ascending / Descending Order']
  responses:
    200:true    
    404:'Not found'
	
