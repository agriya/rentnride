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
 
namespace App\Transformers;

use League\Fractal;
use App\User;
use App\Attachment;

class UserSimpleTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     * @var array
     */
    protected $availableIncludes = [
        'Attachmentable'
    ];

    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $output = array_only($user->toArray(), ['id', 'username']);
        return $output;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function includeAttachmentable(User $user)
    {
        if ($user->attachments) {
            return $this->item($user->attachments, new AttachmentTransformer());
        } else {
            $user->attachments = Attachment::where('id', '=', config('constants.ConstAttachment.UserAvatar'))->first();
            $user->attachments->attachmentable_id = $user->id;
            return $this->item($user->attachments, new AttachmentTransformer());
        }
    }

}
