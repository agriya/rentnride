swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/money_transfer_accounts':
  summary: 'Get the list of money transfer accounts'  
  parameters:
    page:[query, int, description:'Page number for filtering']
  produces: json 
  responses:
    200:true

POST '/money_transfer_accounts':
  summary: 'Store money transfer accounts'  
  produces: json
  parameters:
    MoneyTranferAccountAdd:[body,required,type:"MoneyTranferAccountAdd"]
  responses:
    200:true    
    404:'Not found'	

DELETE '/money_transfer_accounts/{id}':
  summary: 'Delete the specified money transfer account'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter money transfer account ID"]
  responses:
    200:true	

MODEL 'MoneyTranferAccountAdd':
	account:[string,required,"Money Transfer account details"]	