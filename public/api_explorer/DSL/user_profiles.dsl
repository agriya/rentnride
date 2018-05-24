swagger_version 1.2
api_version 1.0
base_path '/bookorrent/public/api'

GET '/user_profiles':
  summary: 'Edit User Profile'
  produces: json
  parameters:
            user_id:[path,int64,required,description:'Edit User Profile']
    responses:
      200:true
      404:'Not found'

POST '/user_profiles':
  summary: 'Update User Profile'
  produces: json
  parameters:
          user_id:[path,int64,required,description:'Edit User profile details']
          ProfileEdit:[body,required,type:"ProfileEdit"]
  responses:
    200:true
    404:'Not found'

MODEL "ProfileEdit":
  user_id:[int64,required,"The User identifier"]
  first_name:[string,required,"First Name"]
  last_name:[string,required,"Last Name"]
  about_me:[string,required,"About me"]
  website:[string,required,"Website"]
  facebook_profile_link:[string,required,"Facebook profile link"]
  twitter_profile_link:[string,required,"Twitter profile Link"]
  google_plus_profile_link:[string,required,"Google Plus profile link"]
  linkedin_profile_link:[string,required,"LinkedIn profile link"]
  youtube_profile_link:[string,required,"Youtube profile link"]