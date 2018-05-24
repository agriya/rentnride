swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/messages':
  summary: 'Get the list of messages'  
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']          
   q:[query,string, description:'Search messages']
   filter:[query,enum:['Favourites', 'Price Low to High', 'Price High to Low', 'Recent'],string,description:'Filter list for Messages']
   sort:[query,string, description:'The Message Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort Message by Ascending / Descending Order']
  produces: json
  responses:
    200:true

GET '/admin/messages/{id}':
  summary: 'Show Message'  
  produces: json
  parameters:
         id:[path,int64,required,description:'The Message Identifier']
  responses:
    200:true    
    404:'Not found'

GET '/admin/item_messages/{item_id}':
  summary: 'Get item activities'
  produces: json
  parameters:
    item_id:[path,int64,required,description:'Item Idetifier']
  responses:
    200:true
    404:'Not found'

GET '/admin/item_user_messages/{item_user_id}':
  summary: 'Get Booking activities'
  produces: json
  parameters:
   item_user_id:[path,int64,required,description:'Booking Identifier']
  responses:
    200:true


GET '/admin/user_messages/{user_id}':
  summary: 'Get User activities'
  produces: json
  parameters:
   user_id:[path,int64,required,description:'User Identifier']
  responses:
    200:true

DELETE '/admin/messages/{id}':
  summary: 'Delete the specified Message'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter Message ID"]
  responses:
    200:true
