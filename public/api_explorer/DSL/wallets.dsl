swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

POST '/wallets':
  summary: 'Add amount to user wallet'
  produces: json
  parameters:
    WalletAdd:[body,required,type:"WalletAdd"]
  responses:
    200:true    
    404:'Not found'

MODEL 'WalletAdd':
	amount:[int64,required,"Amount to be added to wallet"]