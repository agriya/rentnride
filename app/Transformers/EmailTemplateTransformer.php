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
 
namespace app\Transformers;

use League\Fractal;
use App\EmailTemplate;

/**
 * Class EmailTemplateTransformer
 * @package app\Transformers
 */
class EmailTemplateTransformer extends Fractal\TransformerAbstract
{
    /**
     * @param EmailTemplate $email_template
     * @return array
     */
    public function transform(EmailTemplate $email_template)
    {
        $output = array_only($email_template->toArray(), ['id', 'name', 'subject', 'body_content', 'from_name', 'reply_to', 'info']);
        return $output;
    }
}