<?php
/**
 * Plugin
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
 
namespace Plugins\VehicleFeedbacks\Transformers;

use League\Fractal;
use Plugins\VehicleFeedbacks\Model\VehicleFeedback;
use App\Transformers\IpTransformer;

/**
 * Class VehicleFeedbackTransformer
 * @package Plugins\VehicleFeedbacks\Transformers
 */
class VehicleFeedbackTransformer extends Fractal\TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'User', 'Feedbackable', 'Ip', 'ToUser', 'VehicleRental'
    ];

    /**
     * @param VehicleFeedback $vehicle_feedback
     * @return array
     */
    public function transform(VehicleFeedback $vehicle_feedback)
    {
        $output = array_only($vehicle_feedback->toArray(), ['id', 'created_at', 'item_id', 'user_id', 'to_user_id', 'feedbackable_id', 'feedbackable_type', 'feedback', 'ip_id', 'rating']);
        return $output;
    }

    /**
     * @param VehicleFeedback $vehicle_feedback
     * @return Fractal\Resource\Item|null
     */
    public function includeUser(VehicleFeedback $vehicle_feedback)
    {
        if ($vehicle_feedback->user) {
            return $this->item($vehicle_feedback->user, (new \App\Transformers\UserSimpleTransformer())->setDefaultIncludes(['attachmentable']));
        } else {
            return null;
        }
    }

    /**
     * @param VehicleFeedback $vehicle_feedback
     * @return Fractal\Resource\Item|null
     */
    public function includeToUser(VehicleFeedback $vehicle_feedback)
    {
        if ($vehicle_feedback->to_user) {
            return $this->item($vehicle_feedback->to_user, (new \App\Transformers\UserSimpleTransformer())->setDefaultIncludes(['attachmentable']));
        } else {
            return null;
        }
    }

    /**
     * @param VehicleFeedback $vehicle_feedback
     * @return Fractal\Resource\Item|null
     */
    public function includeIp(VehicleFeedback $vehicle_feedback)
    {
        if ($vehicle_feedback->ip) {
            return $this->item($vehicle_feedback->ip, new IpTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param VehicleFeedback $vehicle_feedback
     * @return Fractal\Resource\Item|null
     */
    public function includeFeedbackable(VehicleFeedback $vehicle_feedback)
    {
        if ($vehicle_feedback->feedbackable) {
            if ($vehicle_feedback->feedbackable_type == 'MorphVehicle') {
                return $this->item($vehicle_feedback->feedbackable, (new \Plugins\Vehicles\Transformers\VehicleTransformer)->setDefaultIncludes(['user', 'vehicle_company', 'vehicle_make', 'vehicle_model']));
            } elseif ($vehicle_feedback->feedbackable_type == 'MorphUser') {
               return $this->item($vehicle_feedback->feedbackable, (new \App\Transformers\UserSimpleFeedbackTransformer));
            }
        } else {
            return null;
        }
    }

    /**
     * @param VehicleFeedback $vehicle_feedback
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleRental(VehicleFeedback $vehicle_feedback)
    {
        if ($vehicle_feedback->vehicle_rental) {
            return $this->item($vehicle_feedback->vehicle_rental, (new \Plugins\VehicleRentals\Transformers\VehicleRentalTransformer())->setDefaultIncludes(['item_userable']));
        } else {
            return null;
        }
    }
}