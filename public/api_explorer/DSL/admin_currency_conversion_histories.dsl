swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/admin/currency_conversion_histories':
  summary: 'Fetch all currency conversion histories'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          sort:[query,string, description:'The currency conversion history Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort currency conversion history by Ascending / Descending Order']
          q: [query,string, description:'Search currency conversion history']

  responses:
    200:true    
    404:'Not found'
