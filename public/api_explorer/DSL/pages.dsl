swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/languages/{iso2}/pages':
  summary: 'Fetch pages by languages'  
  produces: json
  parameters:
          iso2:[path,string,required, description:'The Language iso2']
GET '/page/{id}':
  summary: 'Show Page'
  produces: json
  parameters:
          id:[path,string,required, description:'The Page Identifier']
responses:
    200:true    
    404:'Not found'
