swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/cities':
  summary: 'Fetch List of Cities'
  produces: json
  parameters:
          type:[query,string, description:'Display City By Listing Type']
          field:[query,string, description:'Give Whatever Fields Needed by "Comma Seperator"']
          q:[query,string, description:'Search City']
          filter:[query,string, description:'filter list of City']
          sort:[query,string, description:'The City Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort City by Ascending / Descending Order']
	  
  responses:
    200:true
    404:'Not found'