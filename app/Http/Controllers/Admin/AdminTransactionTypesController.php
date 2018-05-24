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

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use App\Transformers\TransactionTypeTransformer;
use App\TransactionType;

/**
 * TransactionTypes resource representation.
 * @Resource("Admin/AdminTransactionTypes")
 */
class AdminTransactionTypesController extends Controller
{
    /**
     * AdminTransactionTypesController constructor.
     */
    public function __construct()
    {
        // check whether the user is logged in or not.
        $this->middleware('jwt.auth');
        // Check the logged user role.
        $this->middleware('role');
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

    /**
     * Show the transaction type.
     * Show the transaction type with a `id`.
     * @Get("/transaction_types/{id}")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "name": "XXXX", "is_credit": 1, "message": "XXXXXX"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */
    public function show($id)
    {
        $transaction_types = TransactionType::find($id);
        if (!$transaction_types) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($transaction_types, (new TransactionTypeTransformer));
    }

    /**
     * Edit the specified transaction type.
     * Edit the transaction type with a `id`.
     * @Get("/transaction_types/{id}/edit")
     * @Transaction({
     *      @Request({"id": 1}),
     *      @Response(200, body={"id": 1, "message": "XXXXX"}),
     *      @Response(404, body={"message": "Invalid Request", "status_code": 404})
     * })
     */

    public function edit($id)
    {
        $transaction_type = TransactionType::find($id);
        if (!$transaction_type) {
            return $this->response->errorNotFound("Invalid Request");
        }
        return $this->response->item($transaction_type, (new TransactionTypeTransformer));
    }

    /**
     * Update the specified transaction type.
     * Update the transaction type with a `id`.
     * @Put("/transaction_types/{id}")
     * @Transaction({
     *      @Request({"id": 1, "message": "XXXXX"}),
     *      @Response(200, body={"success": "Record has been updated."}),
     *      @Response(422, body={"message": "Record could not be updated. Please, try again.", "errors": {}, "status_code": 422})
     * })
     */
    public function update(Request $request, $id)
    {
        $transaction_type_data = $request->only('message', 'name', 'is_credit');
        $validator = Validator::make($transaction_type_data, TransactionType::GetValidationRule(), TransactionType::GetValidationMessage());
        $transaction_type = false;
        if ($request->has('id')) {
            $transaction_type = TransactionType::find($id);
            $transaction_type = ($request->id != $id) ? false : $transaction_type;
        }
        if ($validator->passes() && $transaction_type) {
            try {
                TransactionType::where('id', '=', $id)->update($transaction_type_data);
                return response()->json(['Success' => 'Transaction Type has been updated'], 200);
            } catch (\Exception $e) {
                throw new \Dingo\Api\Exception\StoreResourceFailedException('Transaction Type could not be updated. Please, try again.');
            }
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Transaction Type could not be updated. Please, try again.', $validator->errors());
        }
    }
}
