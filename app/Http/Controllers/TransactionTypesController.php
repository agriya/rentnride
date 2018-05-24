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

use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use App\Transformers\TransactionTypeTransformer;
use App\TransactionType;

/**
 * TransactionTypes resource representation.
 * @Resource("TransactionTypes")
 */
class TransactionTypesController extends Controller
{
    /**
     * TransactionTypesController constructor.
     */
    public function __construct()
    {
        // Check the logged user authentication.
        $this->middleware('jwt.auth');
    }

    /**
     * Show all transaction types.
     * Get a JSON representation of all the transaction types.
     *
     * @Get("/transaction_types?sort={sort}&sortby={sortby}&page={page}")
     * @Parameters({
     *      @Parameter("sort", type="string", required=false, description="Sort the transaction types list by sort ley.", default=null),
     *      @Parameter("sortby", type="string", required=false, description="Sort transaction types by Ascending / Descending Order.", default=null),
     *      @Parameter("page", type="integer", required=false, description="The page of results to view.", default=1)
     * })
     */
    public function index(Request $request)
    {
        $transaction_types = TransactionType::filterByRequest($request)->paginate(config('constants.ConstPageLimit'));
        return $this->response->paginator($transaction_types, (new TransactionTypeTransformer));
    }
}
