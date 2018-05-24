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
 
namespace Plugins\Vehicles\Transformers;

use League\Fractal;
use Plugins\Vehicles\Model\Vehicle;
use App\Transformers\AttachmentTransformer;
use App\Transformers\UserSimpleTransformer;
use App\Attachment;


/**
 * Class AdminVehicleTransformer
 * @package Plugins\Vehicles\Transformers
 */
class AdminVehicleTransformer extends Fractal\TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'CounterLocation', 'VehicleMake', 'VehicleModel', 'VehicleType', 'VehicleCompany', 'FuelType', 'Attachments', 'User'
    ];

    /**
     * @param Vehicle $vehicle
     * @return array
     */
    public function transform(Vehicle $vehicle)
    {
        $output = array_only($vehicle->toArray(), ['id', 'created_at', 'name', 'slug', 'user_id', 'vehicle_company_id', 'vehicle_make_id', 'vehicle_model_id', 'vehicle_type_id', 'driven_kilometer', 'vehicle_no', 'no_of_seats', 'no_of_doors', 'no_of_gears', 'is_manual_transmission', 'no_small_bags', 'no_large_bags', 'is_ac', 'minimum_age_of_driver', 'mileage', 'is_km', 'is_airbag', 'no_of_airbags', 'is_abs', 'per_hour_amount', 'per_day_amount', 'fuel_type_id', 'vehicle_rental_count', 'feedback_count', 'feedback_rating', 'is_paid', 'is_active']);
        $output['id'] = (integer)$output['id'];
        $output['user_id'] = (integer)$output['user_id'];
        $output['vehicle_company_id'] = (integer)$output['vehicle_company_id'];
        $output['vehicle_make_id'] = (integer)$output['vehicle_make_id'];
        $output['vehicle_model_id'] = (integer)$output['vehicle_model_id'];
        $output['vehicle_type_id'] = (integer)$output['vehicle_type_id'];
        $output['fuel_type_id'] = (integer)$output['fuel_type_id'];
		$output['is_paid'] = ($output['is_paid'] == 1) ? true : false;
        $output['is_active'] = ($output['is_active'] == 1) ? true : false;
        $output['is_ac'] = ($output['is_ac'] == 1) ? 1 : 0;
        $output['is_km'] = ($output['is_km'] == 1) ? 1 : 0;
        $output['is_airbag'] = ($output['is_airbag'] == 1) ? 1 : 0;
        $output['is_abs'] = ($output['is_abs'] == 1) ? 1 : 0;
        $output['is_manual_transmission'] = ($output['is_manual_transmission'] == 1) ? 1 : 0;
        if(is_null($vehicle->feedback_count)){
            $output['feedback_count'] = 0;
        }
        if(is_null($vehicle->vehicle_rental_count)){
            $output['vehicle_rental_count'] = 0;
        }
        $pick_up_location = $drop_up_location = array();
        foreach ($vehicle->counter_location as $location) {
            if ($location->pivot->is_drop) {
                $drop_up_location[] = array_only($location->toArray(), ['id', 'address', 'latitude', 'longitude']);
            }
            if ($location->pivot->is_pickup) {
                $pick_up_location[] = array_only($location->toArray(), ['id', 'address', 'latitude', 'longitude']);
            }
        }
        $output['per_day_amount'] = (double)$output['per_day_amount'];
        $output['per_hour_amount'] = (double)$output['per_hour_amount'];
        $output['pickup_locations'] = $pick_up_location;
        $output['drop_locations'] = $drop_up_location;
        return $output;
    }

    /**
     * @param Vehicle $vehicle
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleMake(Vehicle $vehicle)
    {
        if ($vehicle->vehicle_make) {
            return $this->item($vehicle->vehicle_make, new AdminVehicleMakeTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Vehicle $vehicle
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleModel(Vehicle $vehicle)
    {
        if ($vehicle->vehicle_model) {
            return $this->item($vehicle->vehicle_model, new AdminVehicleModelTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Vehicle $vehicle
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleType(Vehicle $vehicle)
    {
        if ($vehicle->vehicle_type) {
            $enabled_includes = array();
            (isPluginEnabled('VehicleExtraAccessories')) ? $enabled_includes[] = 'vehicle_type_extra_accessory' : '';
            (isPluginEnabled('VehicleInsurances')) ? $enabled_includes[] = 'vehicle_type_insurance' : '';
            (isPluginEnabled('VehicleFuelOptions')) ? $enabled_includes[] = 'vehicle_type_fuel_option' : '';
            (isPluginEnabled('VehicleSurcharges')) ? $enabled_includes[] = 'vehicle_type_surcharge' : '';
            (isPluginEnabled('VehicleTaxes')) ? $enabled_includes[] = 'vehicle_type_tax' : '';
            return $this->item($vehicle->vehicle_type, (new AdminVehicleTypeTransformer())->setDefaultIncludes($enabled_includes));
        } else {
            return null;
        }
    }

    /**
     * @param Vehicle $vehicle
     * @return Fractal\Resource\Item|null
     */
    public function includeVehicleCompany(Vehicle $vehicle)
    {
        if ($vehicle->vehicle_company) {
            return $this->item($vehicle->vehicle_company, new AdminVehicleCompanyTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Vehicle $vehicle
     * @return Fractal\Resource\Item|null
     */
    public function includeFuelType(Vehicle $vehicle)
    {
        if ($vehicle->fuel_type) {
            return $this->item($vehicle->fuel_type, new AdminFuelTypeTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Vehicle $vehicle
     * @return Fractal\Resource\Item|null
     */
    public function includeCounterLocation(Vehicle $vehicle)
    {
        if ($vehicle->counter_location) {
            return $this->collection($vehicle->counter_location, new AdminCounterLocationTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Vehicle $vehicle
     * @return Fractal\Resource\Item|null
     */
    public function includeUser(Vehicle $vehicle)
    {
        if ($vehicle->user) {
            return $this->item($vehicle->user, new UserSimpleTransformer());
        } else {
            return null;
        }
    }

    /**
     * @param Vehicle $vehicle
     * @return mixed
     */
    public function includeAttachments(Vehicle $vehicle)
    {
        if ($vehicle->attachments) {
            return $this->item($vehicle->attachments, new AttachmentTransformer());
        } else {
            $vehicle->attachments = Attachment::where('id', '=', config('constants.ConstAttachment.VehicleAvatar'))->first();
            $vehicle->attachments->attachmentable_id = $vehicle->id;
            return $this->item($vehicle->attachments, new AttachmentTransformer());
        }
    }
}
