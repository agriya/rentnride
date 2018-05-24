swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/cancellation_types':
  summary: 'List cancellation types'
  produces: json
  parameters:
          page: [query, int, description:'Give which page to be loaded']
          q:[query,string,description:'Search cancellation types']
          sort:[query,string,description:'The cancellation type Identifier']
          sortby:[query,enum:['asc', 'desc'],string,description:'Sort cancellation types by Ascending / Descending Order']
  responses:
    200:true
    404:'Not found'
