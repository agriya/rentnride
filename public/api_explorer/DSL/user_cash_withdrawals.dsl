swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/user_cash_withdrawals':
  summary: 'Get the list of withdrawal requests' 
  produces: json
  parameters:
          page: [query, int,  description:'Page number for filtering']
          filter:[query,enum:['pending', 'success', 'rejected', 'all'],int, description:'filter list of requests ']
          sort:[query,string, description:'The field_name to sort']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort Requests by Ascending / Descending Order']
		  
  responses:
    200:true    
    404:'Not found'
	
POST '/user_cash_withdrawals':
  summary: 'Store Withdrawal Requests' 
  produces: json
  parameters:
        UserCashWithdrawal:[body,required,type:"UserCashWithdrawal"]
  responses:
    200:true    
    404:'Not found'
	

MODEL 'UserCashWithdrawal':
	money_transfer_account_id:[int,required,"Money Transfer Account Td"],
	amount:[double,required,"Amount to be withdrawn"]