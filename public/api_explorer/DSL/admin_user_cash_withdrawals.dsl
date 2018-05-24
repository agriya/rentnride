swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/user_cash_withdrawals':
  summary: 'Fetch all Withdrawal Requests'  
  produces: json
  parameters:
          page: [query, int,  description:'Page number for filtering']
          filter:[query,enum:['success', 'rejected', 'all'],int, description:'filter list of requests ']
          sort:[query,string, description:'The field_name to sort']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Requests by Ascending / Descending Order']
		 

  responses:
    200:true    
    404:'Not found'

DELETE '/admin/user_cash_withdrawals/{id}':
  summary: 'Delete withdrawal requests'
  produces: json
  parameters:
          id:[path,int64,required,description:'The Withdrawal request Identifier']
  responses:
    200:true
    404:'Not found'

GET '/admin/user_cash_withdrawals/{id}/edit':
  summary: 'Edit withdrawal requests'  
  produces: json
  parameters:
          id:[path,int64,required,'Withdrawal request ID']		  
  responses:
    200:true    
    404:'Not found'

PUT '/admin/user_cash_withdrawals/{id}':
  summary: 'update Withdrawal requests'  
  produces: json
  parameters:
          id:[path,int64,required,'Withdrawal request ID']
          WithdrawEdit:[body,required,type:"WithdrawEdit"]
  responses:
    200:true    
    404:'Not found'

MODEL "WithdrawEdit":
  id:[string,required,"The Withdrawal request identifier"]
  withdrawal_status_id:[string,required,"Withdrawal Status ID"]