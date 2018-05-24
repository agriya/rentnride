swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'
GET '/admin/currency_conversions':
  summary: 'Fetch all currency conversions'  
  produces: json
  parameters:
          page: [query, int,  description:'Give which page to be loaded']
          sort:[query,string, description:'The currency conversion Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort currency conversion by Ascending / Descending Order']
          q: [query,string, description:'Search currency conversion']

  responses:
    200:true    
    404:'Not found'
