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
use App\MessageContent;

/**
 * Class MessageContentTransformer
 * @package MessageContent\Transformers
 */
class MessageContentTransformer extends Fractal\TransformerAbstract
{
     /**
     * @param MessageContent $message_content
     * @return array
     */
    public function transform(MessageContent $message_content)
    {
        $output = array_only($message_content->toArray(), ['subject', 'message', 'admin_suspend', 'is_system_flagged', 'detected_suspicious_words']);
        return $output;
    }
}