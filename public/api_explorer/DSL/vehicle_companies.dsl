swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/vehicle_companies':
  summary: 'Get the list of vehicle companies'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search vehicle company']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for vehicle companies']
   sort:[query,string, description:'The vehicle company Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort vehicle company by Ascending / Descending Order']
  produces: json
  responses:
    200:true

POST '/vehicle_companies':
  summary: 'Store vehicle company'
  produces: json
  parameters:
    VehicleCompanyAdd:[body,required,type:"VehicleCompanyAdd"]
  responses:
    200:true
    404:'Not found'

GET '/vehicle_companies/edit':
  summary: 'Edit the vehicle company'
  produces: json
  responses:
    200:true

GET '/vehicle_companies/show':
  summary: 'View the vehicle company'
  produces: json
  responses:
    200:true

DELETE '/vehicle_companies/{id}':
  summary: 'Delete the specified vehicle company'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter vehicle company ID"]
  responses:
    200:true

MODEL 'VehicleCompanyAdd':
	name:[string,required,"Vehicle company Name"]
	address:[string,required,"Vehicle company address"]
	latitude:[string,"Vehicle company latitude"]
	longitude:[string,"Vehicle company longitude"]
	phone:[string,"Vehicle company phone"]
	mobile:[string,required,"Vehicle company mobile"]
	email:[string,required,"Vehicle company email"]
	is_active:[int64,required,"is active"]

