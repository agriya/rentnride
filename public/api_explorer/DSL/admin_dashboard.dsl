swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/stats':
  summary: 'Get the count of all site activities based on duration'
  produces: json
  parameters:
          filter:[query,enum:['lastDays', 'lastWeeks', 'lastMonths', 'lastYears'],string, description:'filter stats based on duration ']
  responses:
    200:true    
    404:'Not found'
