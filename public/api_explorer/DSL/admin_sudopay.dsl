swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/sudopay/synchronize':
  summary: 'Synchronize of sudopay gateway  and update to DB'
  produces: json
  responses:
    200:true

