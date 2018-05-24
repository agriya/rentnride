<?php
/**
 * Rent & Ride
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    RENT&RIDE
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\UserProfile;
use App\Attachment;
use JWTAuth;
use Validator;
use App\Transformers\UserProfileTransformer;
use Image;
use File;

/**
 * UserProfiles resource representation.
 * @Resource("UserProfiles")
 */
class UserProfilesController extends Controller
{
    /**
     * UserProfilesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
    }

    /**
     * Get the specified user profile.
     * @Get("/user_profiles")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "user_id": 1, "first_name": "admin", "last_name": "admin", "about_me": "I am the site admin", "website": null, "facebook_profile_link": null, "twitter_profile_link": null, "google_plus_profile_link": null, "linkedin_profile_link": null, "youtube_profile_link": null}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function edit()
    {
        $user = $this->auth->user();
        $user_profile = UserProfile::with('User.attachments')->where('user_id', '=', $user->id)->first();
        if (!$user_profile) {
            UserProfile::create(['first_name' => $user->username, 'user_id' => $user->id]);
            $user_profile = UserProfile::with('User.attachments')->where('user_id', '=', $user->id)->first();
        }
        return $this->response->item($user_profile, (new UserProfileTransformer)->setDefaultIncludes(['User']));
    }

    /**
     * Update user_profile
     * Update user_profile with a `user_id`.
     * @Put("/user_profiles")
     * @Transaction({
     *      @Request({"user_id": 1, "first_name": "admin", "last_name": "admin", "about_me": "I am the site admin", "website": null, "facebook_profile_link": null, "twitter_profile_link": null, "google_plus_profile_link": null, "linkedin_profile_link": null, "youtube_profile_link": null}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request)
    {
        $user_profile_data = $request->only('first_name', 'last_name', 'about_me', 'website', 'facebook_profile_link', 'twitter_profile_link', 'google_plus_profile_link', 'linkedin_profile_link', 'youtube_profile_link');
        $user_profile_data['website'] = ($user_profile_data['website'] == null || $user_profile_data['website'] == 'null') ? "" : $user_profile_data['website'];
        $user_profile_data['facebook_profile_link'] = ($user_profile_data['facebook_profile_link'] == null || $user_profile_data['facebook_profile_link'] == 'null') ? "" : $user_profile_data['facebook_profile_link'];
        $user_profile_data['twitter_profile_link'] = ($user_profile_data['twitter_profile_link'] == null || $user_profile_data['twitter_profile_link'] == 'null') ? "" : $user_profile_data['twitter_profile_link'];
        $user_profile_data['google_plus_profile_link'] = ($user_profile_data['google_plus_profile_link'] == null || $user_profile_data['google_plus_profile_link'] == 'null') ? "" : $user_profile_data['google_plus_profile_link'];
        $user_profile_data['linkedin_profile_link'] = ($user_profile_data['linkedin_profile_link'] == null || $user_profile_data['linkedin_profile_link'] == 'null') ? "" : $user_profile_data['linkedin_profile_link'];
        $user_profile_data['youtube_profile_link'] = ($user_profile_data['youtube_profile_link'] == null || $user_profile_data['youtube_profile_link'] == 'null') ? "" : $user_profile_data['youtube_profile_link'];
        $validator = Validator::make($user_profile_data, UserProfile::GetValidationRule(), UserProfile::GetValidationMessage());
        $user = $this->auth->user();
        if ($user) {
            if ($validator->passes()) {
                $user_profile = UserProfile::where('user_id', '=', $user->id)->first();
                try {
                    if (!empty($user_profile)) {
                        UserProfile::where('id', '=', $user_profile->id)->update($user_profile_data);
                    } else {
                        $user_profile_data['user_id'] = $user->id;
                        UserProfile::create($user_profile_data);
                    }
                    if ($request->hasFile('file')) {
                        if ($request->file('file')->isValid()) {
                            $path = storage_path('app/User/' . $user->id . '/');
                            if (!File::isDirectory($path)) {
                                File::makeDirectory($path, 0775, true);
                            }
                            $img = Image::make($_FILES['file']['tmp_name']);
                            $path = storage_path('app/User/' . $user->id . '/' . $_FILES['file']['name']);
                            if ($img->save($path)) {
                                $curuser = User::with(['attachments'])->where('id', '=', $user->id)->first();
                                $attachment = array();
                                $attachment['filename'] = $_FILES['file']['name'];
                                $attachment['dir'] = 'app/User/' . $user->id . '/';
                                $attachment['mimetype'] = $request->file('file')->getClientMimeType();
                                $attachment['filesize'] = $request->file('file')->getClientSize();
                                if ($curuser->attachments) {
                                    $curuser->attachments()->update($attachment);
                                } else {
                                    $att = Attachment::create($attachment);
                                    $curuser = User::with(['attachments'])->where('id', '=', $user->id)->first();
                                    $curuser->attachments()->save($att);
                                }
                            }
                        }
                    }
                    return response()->json(['Success' => 'UserProfile has been updated'], 200);
                } catch (\Exception $e) {
                    throw new \Dingo\Api\Exception\StoreResourceFailedException('UserProfile could not be updated. Please, try again.');
                }
            } else {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('UserProfile could not be updated. Please, try again.', $validator->errors());
            }
        }
    }

}
