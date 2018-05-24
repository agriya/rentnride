swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/img/{size}/{model}/{filename}':
  summary: 'Create images'  
  produces: json
  parameters:
         size:[path,int64,required,description:'Size']
         model:[path,int64,required,description:'Model']
         filename:[path,int64,required,description:'File name']
  responses:
    200:true    
    404:'Not found'