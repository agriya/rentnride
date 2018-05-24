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

namespace app\Services;

use App\User;
use App\Attachment;
use File;

class AttachmentService
{

    /**
     * Attachment thumb url create
     * @param Attachment $attachment
     * @return array
     */
    public static function getThumbUrl(Attachment $attachment, $user_id)
    {
        $thumb = array();
        $thumb_size_array = array();
        $model = '';
        $provider_user = array();
        $file_name = $attachment->id . '.' . md5($attachment->id . config('constants.Security.salt') . $attachment->filename) . '.' . File::extension($attachment->filename);
        switch ($attachment['attachmentable_type']) {
            case 'MorphUser':
                $thumb_size_array = config('constants.thumb.user');
                $model = 'user';
                $enabledIncludes = array();
                (isPluginEnabled('SocialLogins')) ? $enabledIncludes[] = 'provider_user' : '';
                $user = User::with($enabledIncludes)->where('id', $user_id)->first();
                foreach ($user->provider_user as $value) {
                    if ($user->user_avatar_source_id == $value['provider_id']) {
                        $provider_user['provider_profile'] = $value['profile_picture_url'];
                    }
                }
                if (empty($provider_user)) {
                    $user->user_avatar_source_id = config('constants.ConstSocialLogin.User');
                }
                break;
            case 'MorphVehicle':
                $thumb_size_array = config('constants.thumb.vehicle');
                $model = 'vehicle';
                break;
            default:
                break;
        }
        if ($model == 'user') {
            if ($user->user_avatar_source_id == config('constants.ConstSocialLogin.Facebook')) {
                foreach ($thumb_size_array as $size_name => $size) {
                    $thumb[$size_name] = $provider_user['provider_profile'] . '?type=normal&amp;width=' . $size["width"] . '&amp;height=' . $size["height"];
                }
            } else if ($user->user_avatar_source_id == config('constants.ConstSocialLogin.Twitter')) {
                foreach ($thumb_size_array as $size_name => $size) {
                    $thumb[$size_name] = $provider_user['provider_profile'];
                }
            } else if ($user->user_avatar_source_id == config('constants.ConstSocialLogin.Google')) {
                foreach ($thumb_size_array as $size_name => $size) {
                    $profile_url = $provider_user['provider_profile'];
                    $url = explode('?', $provider_user['provider_profile']);
                    if ((count($url) > 1) && strpos($url[1], 'sz') !== false) {
                        $url[1] = 'sz=' . $size['width'];
                        $profile_url = implode('?', $url);
                    }
                    $thumb[$size_name] = $profile_url;
                }
            } else if ($user->user_avatar_source_id == config('constants.ConstSocialLogin.Github')) {
                foreach ($thumb_size_array as $size_name => $size) {
                    $thumb[$size_name] = $provider_user['provider_profile'];
                }
            } else {
                foreach ($thumb_size_array as $size_name => $size) {
                    $thumb[$size_name] = asset('api/img/' . $size_name . '/' . $model . '/' . $file_name);
                }
            }

        } else if ($model == 'vehicle') {
            foreach ($thumb_size_array as $size_name => $size) {
                $thumb[$size_name] = asset('api/img/' . $size_name . '/' . $model . '/' . $file_name);
            }
        } else {
            foreach ($thumb_size_array as $size_name => $size) {
                $thumb[$size_name] = asset('api/img/' . $size_name . '/' . $model . '/' . $file_name);
            }
        }

        return $thumb;
    }

    /**
     * User upload avatar OR default avatar return
     * @param Attachment $attachment
     * @param            $user_id
     * @return array
     */
    public static function getUserUploadThumb(Attachment $attachment, $user_id)
    {
        $thumb = array();
        $thumb_size_array = config('constants.thumb.user');
        $model = 'user';
        $provider_user = array();
        $file_name = $attachment->id . '.' . md5($attachment->id . config('constants.Security.salt') . $attachment->filename) . '.' . File::extension($attachment->filename);
        foreach ($thumb_size_array as $size_name => $size) {
            $thumb[$size_name] = asset('api/img/' . $size_name . '/' . $model . '/' . $file_name);
        }
        return $thumb;
    }

}