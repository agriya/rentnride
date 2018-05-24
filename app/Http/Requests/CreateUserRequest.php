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
 
namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
	
		switch($this->method())
		{
			case 'GET':
			case 'DELETE':
			{
				return [];
			}
			case 'POST':
			{
				return [
					'username' => 'required|min:3',
					'email'    => 'required|email|unique:users',
					'password' => 'required|min:6',
					'confirm_password' => 'required|min:6|same:password',
					'is_agree_terms_conditions' => 'required'
				];
			}
			case 'PUT':
			{
				return [
					'password' => 'required|min:6',
					'confirm_password' => 'required|min:6|same:password'
				];
			}
			case 'PATCH':
			
			default:break;
		}

    }
}
