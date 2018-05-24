swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/messages':
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

GET '/sentMessages':
  summary: 'Get all Sent Messages'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
  produces: json
  responses:
    200:true


GET '/item_messages/{item_id}':
  summary: 'Get item activities'
  produces: json
  parameters:
    item_id:[path,int64,required,'Item Idetifier']
  responses:
    200:true
    404:'Not found'

GET '/item_user_messages/{item_user_id}':
  summary: 'Get Booking activities'
  produces: json
  parameters:
   item_user_id:[path,int64,required,'Booking Identifier']
  responses:
    200:true

POST '/messages/{user_id}/user':
  summary: 'Compose Message'
  produces: json
  parameters:
    Compose:[body,required,type:"Compose"]
  responses:
    200:true
    404:'Not found'

GET '/messages/{id}':
  summary: 'View the message'
  parameters:
    id:[path,int64,required,'Message identifier']
  produces: json
  responses:
    200:true

POST '/messages/{message_id}/reply':
  summary: 'reply Message'
  produces: json
  parameters:
    message_id:[path,int64,required,'Message identifier']
    reply:[body,required,type:"reply"]
  responses:
    200:true
    404:'Not found'

POST '/private_notes':
  summary: 'Add Private Note'  
  produces: json
  parameters:
          PrivateNoteAdd:[body,required,type:"PrivateNoteAdd"]
  responses:
    200:true    
    404:'Not found'

MODEL 'Compose':
	to_user_id:[int64,required,"To whom the message is sent to"]
	subject:[string,required,"subject for the message"]
	message:[string,required,"content of the message"]

MODEL 'reply':
    to_user_id:[int64,required,"To whom the message is sent to"]
    message_id:[int64,required,"to message the is sent"]
    subject:[string,required,"subject for the message"]
    message:[string,required,"content of the message"]

MODEL 'PrivateNoteAdd':
    id:[string,required,"The Booking identifier"]
    message:[string,required,"Message"]
