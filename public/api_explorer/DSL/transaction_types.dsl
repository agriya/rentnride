swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/transaction_types':
  summary: 'Fetch all transaction types'
  parameters:
      sort:[query,string,description:"sorting field name"]
      sortby:[query,enum:['asc', 'desc'],string,description:'sort transaction types by ascending / descending order']
      page:[query, int, description:'Page number for filtering']
  produces: json
  responses:
    200:true    
    404:'Not found'

