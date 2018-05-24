swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/counter_locations':
  summary: 'Get the list of counter locations'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search counter location']
   sort:[query,string, description:'The counter location Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort counter location by Ascending / Descending Order']
   limit:[query, string, description:'Limit for records']
   vehicle_id:[query, int64, description:'Vehicle Identifier']
  produces: json
  responses:
    200:true

POST '/admin/counter_locations':
  summary: 'Store counter location'
  produces: json
  parameters:
    CounterLocationAdd:[body,required,type:"CounterLocationAdd"]
  responses:
    200:true
    404:'Not found'

GET '/admin/counter_locations/{id}/edit':
  summary: 'Edit the counter location'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

PUT '/admin/counter_locations/{id}':
  summary: 'Update counter location'
  produces: json
  parameters:
   id:[path,int64,required,description:'counter location id']
   CounterLocationEdit:[body,required,type:"CounterLocationEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/counter_locations/{id}':
  summary: 'View the counter location'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

DELETE '/admin/counter_locations/{id}':
  summary: 'Delete the specified counter location'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter counter location ID"]
  responses:
    200:true

MODEL 'CounterLocationAdd':
	address:[string,required,"Counter location address"]
	latitude:[string,"Counter location latitude"]
	longitude:[string,"Counter location longitude"]
	fax:[string, "Counter location fax"]
	phone:[string,"Counter location phone"]
	mobile:[string,required,"Counter location mobile"]
	email:[string,required,"Counter location email"]

MODEL "CounterLocationEdit":
  id:[int64,required,"The counter location identifier"]
  address:[string,required,"Counter location address"]
  latitude:[string,"Counter location latitude"]
  longitude:[string,"Counter location longitude"]
  fax:[string, "Counter location fax"]
  phone:[string,"Counter location phone"]
  mobile:[string,required,"Counter location mobile"]
  email:[string,required,"Counter location email"]