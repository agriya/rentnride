swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/transaction_types':
  summary: 'List all transaction types'
  parameters:
      sort:[query,string,description:"sorting field name"]
      sortby:[query,enum:['asc', 'desc'],string,description:'sort transaction types by ascending / descending order']
      page:[query, int, description:'Page number for filtering']
  produces: json
  responses:
    200:true    
    404:'Not found'

GET '/admin/transaction_types/{id}/edit':
  summary: 'Edit transaction type'  
  produces: json
  parameters:
          id:[path,int64,required,'The transaction type identifier']
  responses:
    200:true    
    404:'Not found'

PUT '/admin/transaction_types/{id}':
  summary: 'Update transaction type'  
  produces: json
  parameters:
    id:[path,int64,required,description:'The transaction type Identifier']
    EditTransactionType:[body,required,type:"EditTransactionType"]
  responses:
    200:true    
    404:'Not found'

GET '/admin/transaction_types/{id}':
  summary: 'Show transaction type'  
  produces: json
  parameters:
          id:[path,int64,required,'The transaction type identifier']
  responses:
    200:true    
    404:'Not found'
	
MODEL 'EditTransactionType':
  id:[string,required,"The transaction type identifier"]
  message:[string,required,"Message"]

