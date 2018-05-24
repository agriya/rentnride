swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET 'admin/cities':
  summary: 'Fetch List of Cities'
  produces: json
  parameters:
          page: [query, int64,  description:'Give which page to be loaded']          
          q:[query,string, description:'Search City']
          filter:[query,enum:['active', 'inactive'],string,description:'filter list of City']
          sort:[query,string, description:'The City Identifier']
          sortby:[query,enum:['asc', 'desc'],string, description:'Sort City by Ascending / Descending Order']
		  
  responses:
    200:true
    404:'Not found'

DELETE '/admin/cities/{id}':
  summary: 'Delete City'  
  produces: json
  parameters:
          id:[path,int64,required,description:'Delete IP Details from List']
  responses:
    200:true    
    404:'Not found'

POST '/admin/cities':
  summary: 'Add City Details'  
  produces: json
  parameters:
          CityAdd:[body,required,type:"CityAdd"]
  responses:
    200:true    
    404:'Not found'

GET '/admin/cities/{id}/edit':
  summary: 'Edit City'  
  produces: json
  parameters:
          id:[path,int64,required,description:'Edit IP Details from List']		  
  responses:
    200:true    
    404:'Not found'

PUT '/admin/cities/{id}':
  summary: 'Update City'  
  produces: json
  parameters:
          id:[path,int64,required,description:'Edit IP Details from List']
	  CityEdit:[body,required,type:"CityEdit"]
  responses:
    200:true    
    404:'Not found'

MODEL "CityAdd":
  name:[string,required,"City Name"]
  state_id:[int64,required,"The State identifier"]
  country_id:[int64,required,"The Country identifier"]
  latitude:[int64,required,"Latitude of Place"]
  longitude:[int64,required,"longitude of Place"]

MODEL "CityEdit":
  id:[string,required,"The City identifier"]
  name:[string,required,"City Name"]
  state_id:[int64,required,"The State identifier"]
  country_id:[int64,required,"The Country identifier"]
  latitude:[int64,required,"Latitude of Place"]
  longitude:[int64,required,"longitude of Place"]
  is_active:[int64, required, "is active"]
