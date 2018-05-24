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
use File;

class AssetsController extends Controller
{
    /** Minify all js files and copy plugin template files to public folder
     * @get("assets/js/plugins.js")
     * @Transaction({
     *      @Response(200, body={}),
     *      @Response(401, body={"message": "Error writing to file", "status_code": 401})
     * })
     */
    public function createJsTplFiles()
    {
        $dest_path = base_path('public/api/assets/js/');
        if (!File::isDirectory($dest_path)) {
            File::makeDirectory($dest_path, 0775, true);
        }
        $enabled_plugins = enabled_plugins();
        $minifiedCode = '';
        foreach($enabled_plugins as $plugins){
            $plugins = trim($plugins);
            if(empty($plugins))
                continue;
            //Load js files to public/api/js/plugins.js as minified code
            $moduleFileList = glob(base_path("client/src/app/Plugins/$plugins/*.module.js"));
            $jsFileList = glob(base_path("client/src/app/Plugins/$plugins/*.js"));
            foreach($moduleFileList as $file){
                if(array_search($file, $jsFileList)){
                    unset($jsFileList[array_search($file, $jsFileList)]);
                }
            }
            $jsFileList = array_merge($moduleFileList, $jsFileList);
            foreach($jsFileList as $jsfiles) {
                $contents = File::get($jsfiles);
                if(env('APP_DEBUG') == false) {
                    $contents = \JShrink\Minifier::minify($contents);
                }
                $minifiedCode .= $contents;
            }
            if(env('APP_DEBUG') == false) {
                $minifiedCode .= \JShrink\Minifier::minify("angular.module('BookorRent').requires.push('BookorRent.".$plugins."');");
            }else{
                $minifiedCode .= "angular.module('BookorRent').requires.push('BookorRent.".$plugins."');";
            }
            //Copy Template files from client/src/app/plugins to public/client/src/app/plugins
            foreach(glob(base_path("client/src/app/Plugins/$plugins/*.tpl.html")) as $tpl_file) {
                $destination = base_path("public/Plugins/$plugins/");
                if (!File::isDirectory($destination)) {
                    File::makeDirectory($destination, 0777, true);
                }
                $tpl_name = File::basename($tpl_file);
                $dest_file = $destination.$tpl_name;
                if(File::exists($dest_file)) {
                    @chmod($dest_file, 0777);
                    File::delete($dest_file);
                }
                File::copy($tpl_file, $dest_file);
                @chmod($dest_file, 0777);
            }
        }
        $bytes_written = File::put($dest_path.'plugins.js', $minifiedCode);
        @chmod($dest_path.'plugins.js', 0777);
        if ($bytes_written === false)
        {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Error writing to file.');
        }else {
            return redirect(asset('api/assets/js/plugins.js'));
        }
    }
}
