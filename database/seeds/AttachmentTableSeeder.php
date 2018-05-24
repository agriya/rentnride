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
 
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Attachment;
use App\Eloquent;
use Illuminate\Support\Facades\Hash;

class AttachmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
	public function run()
	{
		Attachment::create([
		    'id' => 1,
		    'attachmentable_id' => 0,
		    'attachmentable_type' => 'MorphUser',
		    'filename' => 'default.jpg',
		    'dir' => 'app/User/0/',
		    'mimetype' => 'image/jpeg',
		    'filesize' => '7870'
		]);
		Attachment::create([
		    'id' => 2,
		    'attachmentable_id' => 0,
		    'attachmentable_type' => 'MorphVehicle',
		    'filename' => 'car-thumb.png',
		    'dir' => 'app/Vehicle/0/',
		    'mimetype' => 'image/png',
		    'filesize' => '40500'
		]);
	}
}
	