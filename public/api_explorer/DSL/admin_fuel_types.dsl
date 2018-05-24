swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/admin/fuel_types':
  summary: 'Get the list of fuel types'
  parameters:
   page: [query, int64,  description:'Give which page to be loaded']
   q:[query,string, description:'Search fuel type']
   filter:[query,enum:['is_active', 'all'],string,description:'Filter list for fuel types']
   sort:[query,string, description:'The fuel type Identifier']
   sortby:[query,enum:['asc', 'desc'],string, description:'Sort fuel type by Ascending / Descending Order']
  produces: json
  responses:
    200:true

POST '/admin/fuel_types':
  summary: 'Store fuel type'
  produces: json
  parameters:
    FuelTypeAdd:[body,required,type:"FuelTypeAdd"]
  responses:
    200:true
    404:'Not found'

GET '/admin/fuel_types/{id}/edit':
  summary: 'Edit the fuel type'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

PUT '/admin/fuel_types/{id}':
  summary: 'Update fuel type'
  produces: json
  parameters:
   id:[path,int64,required,description:'fuel type id']
   FuelTypeEdit:[body,required,type:"FuelTypeEdit"]
  responses:
    200:true
    404:'Not found'

GET '/admin/fuel_types/{id}':
  summary: 'View the fuel type'
  produces: json
  parameters:
   id:[path,int64,required,'id']
  responses:
    200:true

DELETE '/admin/fuel_types/{id}':
  summary: 'Delete the specified fuel type'
  produces: json
  parameters:
    id:[path,int64,required,description:"Enter fuel type ID"]
  responses:
    200:true

MODEL 'FuelTypeAdd':
	name:[string,required,"Fuel type Name"]

MODEL 'FuelTypeEdit':
    id:[int64,required,"The Fuel type identifier"]
	name:[string,required,"Fuel type Name"]
