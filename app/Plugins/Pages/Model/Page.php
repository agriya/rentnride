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
 
namespace Plugins\Pages\Model;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Language;

/**
 * Class Page
 * @package App
 */
class Page extends Model
{
    /**
     * @var string
     */
    protected $table = "pages";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'language_id', 'slug', 'page_content'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }


    /**
     * @param         $query
     * @param Request $request
     * @return mixed
     */
    public function scopeFilterByRequest($query, Request $request)
    {
        $query->orderBy($request->input('sort', 'id'), $request->input('sortby', 'desc'));
        if ($request->has('q')) {
            $query->where('title', 'LIKE', '%' . $request->input('q') . '%');
            $query->orWhereHas('language', function ($q) use ($request) {
               $q->where('name', 'LIKE', '%' . $request->input('q') . '%');
            });
        }
        return $query;
    }

    /**
     * @return array
     */
    public function scopeGetValidationRule()
    {
        return [
            'title' => 'required|min:2',
            'language_id' => 'required|integer|exists:languages,id',
            'page_content' => 'required|min:10'
        ];
    }

    /**
     * @return array
     */
    public function scopeGetBulkAddValidationRule()
    {
        return [
            'pages.*.title' => 'sometimes|required|min:2',
            'pages.*.page_content' => 'sometimes|required|min:10',
            'slug' => 'sometimes|required'
        ];
    }

    public function scopeGetValidationMessage()
    {
        return [
            'slug.required' => 'Required',
            'title.required' => 'Required',
            'title.min' => 'Minimum length is 2',
            'language_id.required' => 'Required',
            'language_id.integer' => 'Language id must be a number',
            'language_id.exists' => 'Invalid language id',
            'page_content.required' => 'Required',
            'page_content.min' => 'Minimum length is 10',
        ];
    }

}
