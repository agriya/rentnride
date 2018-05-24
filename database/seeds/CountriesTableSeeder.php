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
use App\Country;
use App\State;
use App\City;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create([
            'name' => 'Afghanistan',
            'iso2' => 'AF',
            'iso3' => 'AFG',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Aland Islands',
            'iso2' => 'AX',
            'iso3' => 'ALA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Albania',
            'iso2' => 'AL',
            'iso3' => 'ALB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Algeria',
            'iso2' => 'DZ',
            'iso3' => 'DZA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'American Samoa',
            'iso2' => 'AS',
            'iso3' => 'ASM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Andorra',
            'iso2' => 'AD',
            'iso3' => 'AND',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Angola',
            'iso2' => 'AO',
            'iso3' => 'AGO',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Anguilla',
            'iso2' => 'AI',
            'iso3' => 'AIA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Antarctica',
            'iso2' => 'AQ',
            'iso3' => 'ATA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Antigua and Barbuda',
            'iso2' => 'AG',
            'iso3' => 'ATG',
            'is_active' => 1
        ]);

        $argentina = Country::create([
            'name' => 'Argentina',
            'iso2' => 'AR',
            'iso3' => 'ARG',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Armenia',
            'iso2' => 'AM',
            'iso3' => 'ARM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Aruba',
            'iso2' => 'AW',
            'iso3' => 'ABW',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Australia',
            'iso2' => 'AU',
            'iso3' => 'AUS',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Austria',
            'iso2' => 'AT',
            'iso3' => 'AUT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Azerbaijan',
            'iso2' => 'AZ',
            'iso3' => 'AZE',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Bahamas',
            'iso2' => 'BS',
            'iso3' => 'BHS',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Bahrain',
            'iso2' => 'BH',
            'iso3' => 'BHR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Bangladesh',
            'iso2' => 'BD',
            'iso3' => 'BGD',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Barbados',
            'iso2' => 'BB',
            'iso3' => 'BRB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Belarus',
            'iso2' => 'BY',
            'iso3' => 'BLR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Belgium',
            'iso2' => 'BE',
            'iso3' => 'BEL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Belize',
            'iso2' => 'BZ',
            'iso3' => 'BLZ',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Benin',
            'iso2' => 'BJ',
            'iso3' => 'BEN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Bermuda',
            'iso2' => 'BM',
            'iso3' => 'BMU',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Bhutan',
            'iso2' => 'BT',
            'iso3' => 'BTN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Bolivia',
            'iso2' => 'BO',
            'iso3' => 'BOL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Bonaire, Saint Eustatius and Saba',
            'iso2' => 'BQ',
            'iso3' => 'BES',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Bosnia and Herzegovina',
            'iso2' => 'BA',
            'iso3' => 'BIH',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Botswana',
            'iso2' => 'BW',
            'iso3' => 'BWA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Bouvet Island',
            'iso2' => 'BV',
            'iso3' => 'BVT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Brazil',
            'iso2' => 'BR',
            'iso3' => 'BRA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'British Indian Ocean Territory',
            'iso2' => 'IO',
            'iso3' => 'IOT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'British Virgin Islands',
            'iso2' => 'VG',
            'iso3' => 'VGB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Brunei',
            'iso2' => 'BN',
            'iso3' => 'BRN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Bulgaria',
            'iso2' => 'BG',
            'iso3' => 'BGR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Burkina Faso',
            'iso2' => 'BF',
            'iso3' => 'BFA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Burundi	',
            'iso2' => 'BI',
            'iso3' => 'BDI',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Cambodia',
            'iso2' => 'KH',
            'iso3' => 'KHM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Cameroon',
            'iso2' => 'CM',
            'iso3' => 'CMR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Canada',
            'iso2' => 'CA',
            'iso3' => 'CAN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Cape Verde',
            'iso2' => 'CV',
            'iso3' => 'CPV',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Cayman Islands',
            'iso2' => 'KY',
            'iso3' => 'CYM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Central African Republic',
            'iso2' => 'CF',
            'iso3' => 'CAF',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Chad',
            'iso2' => 'TD',
            'iso3' => 'TCD',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Chile',
            'iso2' => 'CL',
            'iso3' => 'CHL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'China',
            'iso2' => 'CN',
            'iso3' => 'CHN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Christmas Island',
            'iso2' => 'CX',
            'iso3' => 'CXR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Cocos Islands',
            'iso2' => 'CC',
            'iso3' => 'CCK',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Colombia',
            'iso2' => 'CO',
            'iso3' => 'COL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Comoros',
            'iso2' => 'KM',
            'iso3' => 'COM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Cook Islands',
            'iso2' => 'CK',
            'iso3' => 'COK',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Costa Rica',
            'iso2' => 'CR',
            'iso3' => 'CRI',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Croatia',
            'iso2' => 'HR',
            'iso3' => 'HRV',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Cuba',
            'iso2' => 'CU',
            'iso3' => 'CUB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Curacao',
            'iso2' => 'CW',
            'iso3' => 'CUW',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Cyprus',
            'iso2' => 'CY',
            'iso3' => 'CYP',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Czech Republic',
            'iso2' => 'CZ',
            'iso3' => 'CZE',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Democratic Republic of the Congo',
            'iso2' => 'CD',
            'iso3' => 'COD',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Denmark',
            'iso2' => 'DK',
            'iso3' => 'DNK',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Djibouti',
            'iso2' => 'DJ',
            'iso3' => 'DJI',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Dominica',
            'iso2' => 'DM',
            'iso3' => 'DMA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Dominican Republic',
            'iso2' => 'DO',
            'iso3' => 'DOM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'East Timor',
            'iso2' => 'TL',
            'iso3' => 'TLS',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Ecuador',
            'iso2' => 'EC',
            'iso3' => 'ECU',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Egypt',
            'iso2' => 'EG',
            'iso3' => 'EGY',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'El Salvador',
            'iso2' => 'SV',
            'iso3' => 'SLV',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Equatorial Guinea',
            'iso2' => 'GQ',
            'iso3' => 'GNQ',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Eritrea',
            'iso2' => 'ER',
            'iso3' => 'ERI',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Estonia',
            'iso2' => 'EE',
            'iso3' => 'EST',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Ethiopia',
            'iso2' => 'ET',
            'iso3' => 'ETH',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Falkland Islands',
            'iso2' => 'FK',
            'iso3' => 'FLK',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Faroe Islands',
            'iso2' => 'FO',
            'iso3' => 'FRO',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Fiji',
            'iso2' => 'FJ',
            'iso3' => 'FJI',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Finland',
            'iso2' => 'FI',
            'iso3' => 'FIN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'France',
            'iso2' => 'FR',
            'iso3' => 'FRA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'French Guiana',
            'iso2' => 'GF',
            'iso3' => 'GUF',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'French Polynesia',
            'iso2' => 'PF',
            'iso3' => 'PYF',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'French Southern Territories',
            'iso2' => 'TF',
            'iso3' => 'ATF',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Gabon',
            'iso2' => 'GA',
            'iso3' => 'GAB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Gambia',
            'iso2' => 'GM',
            'iso3' => 'GMB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Georgia',
            'iso2' => 'GE',
            'iso3' => 'GEO',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Germany',
            'iso2' => 'DE',
            'iso3' => 'DEU',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Ghana',
            'iso2' => 'GH',
            'iso3' => 'GHA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Gibraltar',
            'iso2' => 'GI',
            'iso3' => 'GIB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Greece',
            'iso2' => 'GR',
            'iso3' => 'GRC',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Greenland',
            'iso2' => 'GL',
            'iso3' => 'GRL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Grenada',
            'iso2' => 'GD',
            'iso3' => 'GRD',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Guadeloupe',
            'iso2' => 'GP',
            'iso3' => 'GLP',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Guam',
            'iso2' => 'GU',
            'iso3' => 'GUM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Guatemala',
            'iso2' => 'GT',
            'iso3' => 'GTM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Guernsey',
            'iso2' => 'GG',
            'iso3' => 'GGY',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Guinea',
            'iso2' => 'GN',
            'iso3' => 'GIN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Guinea-Bissau',
            'iso2' => 'GW',
            'iso3' => 'GNB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Guyana',
            'iso2' => 'GY',
            'iso3' => 'GUY',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Haiti',
            'iso2' => 'HT',
            'iso3' => 'HTI',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Heard Island and McDonald Islands',
            'iso2' => 'HM',
            'iso3' => 'HMD',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Honduras',
            'iso2' => 'HN',
            'iso3' => 'HND',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Hong Kong',
            'iso2' => 'HK',
            'iso3' => 'HKG',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Hungary',
            'iso2' => 'HU',
            'iso3' => 'HUN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Iceland',
            'iso2' => 'IS',
            'iso3' => 'ISL',
            'is_active' => 1
        ]);

        $india = Country::create([
            'name' => 'India',
            'iso2' => 'IN',
            'iso3' => 'IND',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Indonesia',
            'iso2' => 'ID',
            'iso3' => 'IDN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Iran',
            'iso2' => 'IR',
            'iso3' => 'IRN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Iraq',
            'iso2' => 'IQ',
            'iso3' => 'IRQ',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Ireland',
            'iso2' => 'IE',
            'iso3' => 'IRL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Isle of Man',
            'iso2' => 'IM',
            'iso3' => 'IMN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Israel',
            'iso2' => 'IL',
            'iso3' => 'ISR',
            'is_active' => 1
        ]);

        $italy = Country::create([
            'name' => 'Italy',
            'iso2' => 'IT',
            'iso3' => 'ITA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Ivory Coast',
            'iso2' => 'CI',
            'iso3' => 'CIV',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Jamaica',
            'iso2' => 'JM',
            'iso3' => 'JAM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Japan',
            'iso2' => 'JP',
            'iso3' => 'JPN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Jersey',
            'iso2' => 'JE',
            'iso3' => 'JEY',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Jordan',
            'iso2' => 'JO',
            'iso3' => 'JOR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Kazakhstan',
            'iso2' => 'KZ',
            'iso3' => 'KAZ',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Kenya',
            'iso2' => 'KE',
            'iso3' => 'KEN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Kiribati',
            'iso2' => 'KI',
            'iso3' => 'KIR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Kosovo',
            'iso2' => 'XK',
            'iso3' => 'XKX',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Kuwait',
            'iso2' => 'KW',
            'iso3' => 'KWT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Kyrgyzstan',
            'iso2' => 'KG',
            'iso3' => 'KGZ',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Laos',
            'iso2' => 'LA',
            'iso3' => 'LAO',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Latvia',
            'iso2' => 'LV',
            'iso3' => 'LVA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Lebanon',
            'iso2' => 'LB',
            'iso3' => 'LBN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Lesotho',
            'iso2' => 'LS',
            'iso3' => 'LSO',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Liberia',
            'iso2' => 'LR',
            'iso3' => 'LBR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Libya',
            'iso2' => 'LY',
            'iso3' => 'LBY',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Liechtenstein',
            'iso2' => 'LI',
            'iso3' => 'LIE',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Lithuania',
            'iso2' => 'LT',
            'iso3' => 'LTU',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Luxembourg',
            'iso2' => 'LU',
            'iso3' => 'LUX',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Macao',
            'iso2' => 'MO',
            'iso3' => 'MAC',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Macedonia',
            'iso2' => 'MK',
            'iso3' => 'MKD',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Madagascar',
            'iso2' => 'MG',
            'iso3' => 'MDG',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Malawi',
            'iso2' => 'MW',
            'iso3' => 'MWI',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Malaysia',
            'iso2' => 'MY',
            'iso3' => 'MYS',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Maldives',
            'iso2' => 'MV',
            'iso3' => 'MDV',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Mali',
            'iso2' => 'ML',
            'iso3' => 'MLI',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Malta',
            'iso2' => 'MT',
            'iso3' => 'MLT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Marshall Islands',
            'iso2' => 'MH',
            'iso3' => 'MHL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Martinique',
            'iso2' => 'MQ',
            'iso3' => 'MTQ',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Mauritania',
            'iso2' => 'MR',
            'iso3' => 'MRT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Mauritius',
            'iso2' => 'MU',
            'iso3' => 'MUS',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Mayotte',
            'iso2' => 'YT',
            'iso3' => 'MYT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Mexico',
            'iso2' => 'MX',
            'iso3' => 'MEX',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Micronesia',
            'iso2' => 'FM',
            'iso3' => 'FSM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Moldova',
            'iso2' => 'MD',
            'iso3' => 'MDA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Monaco',
            'iso2' => 'MC',
            'iso3' => 'MCO',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Mongolia',
            'iso2' => 'MN',
            'iso3' => 'MNG',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Montenegro',
            'iso2' => 'ME',
            'iso3' => 'MNE',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Montserrat',
            'iso2' => 'MS',
            'iso3' => 'MSR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Morocco',
            'iso2' => 'MA',
            'iso3' => 'MAR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Mozambique',
            'iso2' => 'MZ',
            'iso3' => 'MOZ',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Myanmar',
            'iso2' => 'MM',
            'iso3' => 'MMR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Namibia',
            'iso2' => 'NA',
            'iso3' => 'NAM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Nauru',
            'iso2' => 'NR',
            'iso3' => 'NRU',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Nepal',
            'iso2' => 'NP',
            'iso3' => 'NPL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Netherlands',
            'iso2' => 'NL',
            'iso3' => 'NLD',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Netherlands Antilles',
            'iso2' => 'AN',
            'iso3' => 'ANT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'New Caledonia',
            'iso2' => 'NC',
            'iso3' => 'NCL',
            'is_active' => 1
        ]);

        $new_zealand = Country::create([
            'name' => 'New Zealand',
            'iso2' => 'NZ',
            'iso3' => 'NZL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Nicaragua',
            'iso2' => 'NI',
            'iso3' => 'NIC',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Niger',
            'iso2' => 'NE',
            'iso3' => 'NER',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Nigeria',
            'iso2' => 'NG',
            'iso3' => 'NGA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Niue',
            'iso2' => 'NU',
            'iso3' => 'NIU',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Norfolk Island',
            'iso2' => 'NF',
            'iso3' => 'NFK',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'North Korea',
            'iso2' => 'KP',
            'iso3' => 'PRK',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Northern Mariana Islands',
            'iso2' => 'MP',
            'iso3' => 'MNP',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Norway',
            'iso2' => 'NO',
            'iso3' => 'NOR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Oman',
            'iso2' => 'OM',
            'iso3' => 'OMN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Pakistan',
            'iso2' => 'PK',
            'iso3' => 'PAK',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Palau',
            'iso2' => 'PW',
            'iso3' => 'PLW',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Palestinian Territory',
            'iso2' => 'PS',
            'iso3' => 'PSE',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Panama',
            'iso2' => 'PA',
            'iso3' => 'PAN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Papua New Guinea',
            'iso2' => 'PG',
            'iso3' => 'PNG',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Paraguay',
            'iso2' => 'PY',
            'iso3' => 'PRY',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Peru',
            'iso2' => 'PE',
            'iso3' => 'PER',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Philippines',
            'iso2' => 'PH',
            'iso3' => 'PHL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Pitcairn',
            'iso2' => 'PN',
            'iso3' => 'PCN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Poland',
            'iso2' => 'PL',
            'iso3' => 'POL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Portugal',
            'iso2' => 'PT',
            'iso3' => 'PRT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Puerto Rico',
            'iso2' => 'PR',
            'iso3' => 'PRI',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Qatar',
            'iso2' => 'QA',
            'iso3' => 'QAT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Republic of the Congo',
            'iso2' => 'CG',
            'iso3' => 'COG',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Reunion',
            'iso2' => 'RE',
            'iso3' => 'REU',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Romania',
            'iso2' => 'RO',
            'iso3' => 'ROU',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Russia',
            'iso2' => 'RU',
            'iso3' => 'RUS',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Rwanda',
            'iso2' => 'RW',
            'iso3' => 'RWA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Saint Barthelemy',
            'iso2' => 'BL',
            'iso3' => 'BLM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Saint Helena',
            'iso2' => 'SH',
            'iso3' => 'SHN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Saint Kitts and Nevis',
            'iso2' => 'KN',
            'iso3' => 'KNA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Saint Lucia',
            'iso2' => 'LC',
            'iso3' => 'LCA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Saint Martin',
            'iso2' => 'MF',
            'iso3' => 'MAF',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Saint Pierre and Miquelon',
            'iso2' => 'PM',
            'iso3' => 'SPM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Saint Vincent and the Grenadines',
            'iso2' => 'VC',
            'iso3' => 'VCT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Samoa',
            'iso2' => 'WS',
            'iso3' => 'WSM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'San Marino',
            'iso2' => 'SM',
            'iso3' => 'SMR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Sao Tome and Principe',
            'iso2' => 'ST',
            'iso3' => 'STP',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Saudi Arabia',
            'iso2' => 'SA',
            'iso3' => 'SAU',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Senegal',
            'iso2' => 'SN',
            'iso3' => 'SEN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Serbia',
            'iso2' => 'RS',
            'iso3' => 'SRB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Serbia and Montenegro',
            'iso2' => 'CS',
            'iso3' => 'SCG', 'is_active' => 1
        ]);

        Country::create([
            'name' => 'Seychelles',
            'iso2' => 'SC',
            'iso3' => 'SYC',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Sierra Leone',
            'iso2' => 'SL',
            'iso3' => 'SLE',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Singapore',
            'iso2' => 'SG',
            'iso3' => 'SGP',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Sint Maarten',
            'iso2' => 'SX',
            'iso3' => 'SXM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Slovakia',
            'iso2' => 'SK',
            'iso3' => 'SVK',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Slovenia',
            'iso2' => 'SI',
            'iso3' => 'SVN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Solomon Islands',
            'iso2' => 'SB',
            'iso3' => 'SLB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Somalia',
            'iso2' => 'SO',
            'iso3' => 'SOM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'South Africa',
            'iso2' => 'ZA',
            'iso3' => 'ZAF',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'South Georgia and the South Sandwich Islands',
            'iso2' => 'GS',
            'iso3' => 'SGS',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'South Korea',
            'iso2' => 'KR',
            'iso3' => 'KOR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'South Sudan',
            'iso2' => 'SS',
            'iso3' => 'SSD',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Spain',
            'iso2' => 'ES',
            'iso3' => 'ESP',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Sri Lanka',
            'iso2' => 'LK',
            'iso3' => 'LKA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Sudan',
            'iso2' => 'SD',
            'iso3' => 'SDN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Suriname',
            'iso2' => 'SR',
            'iso3' => 'SUR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Svalbard and Jan Mayen',
            'iso2' => 'SJ',
            'iso3' => 'SJM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Swaziland',
            'iso2' => 'SZ',
            'iso3' => 'SWZ',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Sweden',
            'iso2' => 'SE',
            'iso3' => 'SWE',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Switzerland',
            'iso2' => 'CH',
            'iso3' => 'CHE',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Syria',
            'iso2' => 'SY',
            'iso3' => 'SYR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Taiwan',
            'iso2' => 'TW',
            'iso3' => 'TWN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Tajikistan',
            'iso2' => 'TJ',
            'iso3' => 'TJK',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Tanzania',
            'iso2' => 'TZ',
            'iso3' => 'TZA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Thailand',
            'iso2' => 'TH',
            'iso3' => 'THA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Togo',
            'iso2' => 'TG',
            'iso3' => 'TGO',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Tokelau',
            'iso2' => 'TK',
            'iso3' => 'TKL',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Tonga',
            'iso2' => 'TO',
            'iso3' => 'TON',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Trinidad and Tobago',
            'iso2' => 'TT',
            'iso3' => 'TTO',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Tunisia',
            'iso2' => 'TN',
            'iso3' => 'TUN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Turkey',
            'iso2' => 'TR',
            'iso3' => 'TUR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Turkmenistan',
            'iso2' => 'TM',
            'iso3' => 'TKM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Turks and Caicos Islands',
            'iso2' => 'TC',
            'iso3' => 'TCA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Tuvalu',
            'iso2' => 'TV',
            'iso3' => 'TUV',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'U.S. Virgin Islands',
            'iso2' => 'VI',
            'iso3' => 'VIR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Uganda',
            'iso2' => 'UG',
            'iso3' => 'UGA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Ukraine',
            'iso2' => 'UA',
            'iso3' => 'UKR',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'United Arab Emirates',
            'iso2' => 'AE',
            'iso3' => 'ARE',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'United Kingdom',
            'iso2' => 'GB',
            'iso3' => 'GBR',
            'is_active' => 1
        ]);

        $america = Country::create([
            'name' => 'United States',
            'iso2' => 'US',
            'iso3' => 'USA',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'United States Minor Outlying Islands',
            'iso2' => 'UM',
            'iso3' => 'UMI',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Uruguay',
            'iso2' => 'UY',
            'iso3' => 'URY',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Uzbekistan',
            'iso2' => 'UZ',
            'iso3' => 'UZB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Vanuatu',
            'iso2' => 'VU',
            'iso3' => 'VUT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Vatican',
            'iso2' => 'VA',
            'iso3' => 'VAT',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Venezuela',
            'iso2' => 'VE',
            'iso3' => 'VEN',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Vietnam',
            'iso2' => 'VN',
            'iso3' => 'VNM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Wallis and Futuna',
            'iso2' => 'WF',
            'iso3' => 'WLF',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Western Sahara',
            'iso2' => 'EH',
            'iso3' => 'ESH',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Yemen',
            'iso2' => 'YE',
            'iso3' => 'YEM',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Zambia',
            'iso2' => 'ZM',
            'iso3' => 'ZMB',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'Zimbabwe',
            'iso2' => 'ZW',
            'iso3' => 'ZWE',
            'is_active' => 1
        ]);

        Country::create([
            'name' => 'United States Minor Outlying Islands',
            'iso2' => 'UM',
            'iso3' => 'UMI',
            'is_active' => 1
        ]);

        $buenos_aires = State::create(array(
            'country_id' => $argentina->id,
            'name' => 'Buenos Aires',
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $buenos_aires->id,
            'name' => 'Banfield',
            'latitude' => -34.75,
            'longitude' => -58.4,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $buenos_aires->id,
            'name' => 'Hurlingham',
            'latitude' => -34.6,
            'longitude' => -58.6333,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $buenos_aires->id,
            'name' => 'Isidro Casanova',
            'latitude' => -34.7,
            'longitude' => -58.5833,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $buenos_aires->id,
            'name' => 'Lanús',
            'latitude' => -34.7153,
            'longitude' => -58.4078,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $buenos_aires->id,
            'name' => 'San Miguel',
            'latitude' => -34.5239,
            'longitude' => -58.7794,
            'is_active' => 1
        ));

        $cordoba = State::create(array(
            'country_id' => $argentina->id,
            'name' => 'Córdoba',
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $cordoba->id,
            'name' => 'Alta Gracia',
            'latitude' => -31.666667,
            'longitude' => -64.433333,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $cordoba->id,
            'name' => 'Bell Ville',
            'latitude' => -32.633333,
            'longitude' => -62.683333,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $cordoba->id,
            'name' => 'Río Cuarto',
            'latitude' => -33.133333,
            'longitude' => -64.35,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $cordoba->id,
            'name' => 'San Francisco',
            'latitude' => -31.435556,
            'longitude' => -62.071389,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $cordoba->id,
            'name' => 'Villa María',
            'latitude' => -32.410278,
            'longitude' => -63.231389,
            'is_active' => 1
        ));

        $entre_rios = State::create(array(
            'country_id' => $argentina->id,
            'name' => 'Entre Ríos',
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $entre_rios->id,
            'name' => 'Concordia',
            'latitude' => -31.4,
            'longitude' => -58.033333,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $entre_rios->id,
            'name' => 'Gualeguay',
            'latitude' => -33.15,
            'longitude' => -59.333333,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $entre_rios->id,
            'name' => 'Gualeguaychú',
            'latitude' => -33.016667,
            'longitude' => -58.516667,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $entre_rios->id,
            'name' => 'Paraná',
            'latitude' => -31.733333,
            'longitude' => -60.533333,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $entre_rios->id,
            'name' => 'Villaguay',
            'latitude' => -31.85,
            'longitude' => -59.016667,
            'is_active' => 1
        ));

        $santa_fe = State::create(array(
            'country_id' => $argentina->id,
            'name' => 'Santa Fe',
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $santa_fe->id,
            'name' => 'Rafaela',
            'latitude' => -31.2667,
            'longitude' => -61.4833,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $santa_fe->id,
            'name' => 'Reconquista',
            'latitude' => -29.2333,
            'longitude' => -59.6,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $santa_fe->id,
            'name' => 'Rincon Del Pintado',
            'latitude' => -32.2333,
            'longitude' => -60.9917,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $santa_fe->id,
            'name' => 'Villa America',
            'latitude' => -33.0333,
            'longitude' => -60.7833,
            'is_active' => 1
        ));

        $salta = State::create(array(
            'country_id' => $argentina->id,
            'name' => 'Salta',
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $salta->id,
            'name' => 'Orán',
            'latitude' => -23.133333,
            'longitude' => -64.333333,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $salta->id,
            'name' => 'Salta',
            'latitude' => -24.783333, 
            'longitude' => -65.416667,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $argentina->id,
            'state_id' => $salta->id,
            'name' => 'Tartagal',
            'latitude' => -22.5,
            'longitude' => -63.833333,
            'is_active' => 1
        ));

        $brescia_bs = State::create(array(
            'country_id' => $italy->id,
            'name' => 'Brescia BS',
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $italy->id,
            'state_id' => $brescia_bs->id,
            'name' => 'Ceto',
            'latitude' => 46.0031,
            'longitude' => 10.3515,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $italy->id,
            'state_id' => $brescia_bs->id,
            'name' => 'Gardola',
            'latitude' => 45.742,
            'longitude' => 10.7189,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $italy->id,
            'state_id' => $brescia_bs->id,
            'name' => 'San Paolo',
            'latitude' => 45.3716,
            'longitude' => 10.0278,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $italy->id,
            'state_id' => $brescia_bs->id,
            'name' => 'Temu\'',
            'latitude' => 46.249,
            'longitude' => 10.469,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $italy->id,
            'state_id' => $brescia_bs->id,
            'name' => 'Valvestino',
            'latitude' => 45.7587,
            'longitude' => 10.582,
            'is_active' => 1
        ));

        $auckland = State::create(array(
            'country_id' => $new_zealand->id,
            'name' => 'Auckland',
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $new_zealand->id,
            'state_id' => $auckland->id,
            'name' => 'Avondale',
            'latitude' => -36.898,
            'longitude' => 174.6967,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $new_zealand->id,
            'state_id' => $auckland->id,
            'name' => 'Owairaka',
            'latitude' => -36.895071,
            'longitude' => 174.721551,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $new_zealand->id,
            'state_id' => $auckland->id,
            'name' => 'Ponsonby',
            'latitude' => -36.852356,
            'longitude' => 174.738689,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $new_zealand->id,
            'state_id' => $auckland->id,
            'name' => 'Saint Johns',
            'latitude' => -36.874498,
            'longitude' => 174.842433,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $new_zealand->id,
            'state_id' => $auckland->id,
            'name' => 'Waitakere',
            'latitude' => -36.85,
            'longitude' => 174.55,
            'is_active' => 1
        ));

        $tamilnadu = State::create(array(
            'country_id' => $india->id,
            'name' => 'TamilNadu',
            'is_active' => 1
        ));
		
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Karnataka',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Kerala',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Maharastra',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Madhya Pradesh',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Andhra Pradesh',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Orissa',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Punjab',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Kolkata',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Assam',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Bihar',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Gujarat',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Jharkhand',
            'is_active' => 1
        ));
		State::create(array(
            'country_id' => $india->id,
            'name' => 'Mizoram',
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $india->id,
            'state_id' => $tamilnadu->id,
            'name' => 'Chennai',
            'latitude' => 13.0826802,
            'longitude' => 80.2707184,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $india->id,
            'state_id' => $tamilnadu->id,
            'name' => 'Coimbatore',
            'latitude' => 11.0168445,
            'longitude' => 76.9558321,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $india->id,
            'state_id' => $tamilnadu->id,
            'name' => 'Madurai',
            'latitude' => 9.9252007,
            'longitude' => 78.1197754,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $india->id,
            'state_id' => $tamilnadu->id,
            'name' => 'Tiruchirappalli',
            'latitude' => 10.7904833,
            'longitude' => 78.7046725,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $india->id,
            'state_id' => $tamilnadu->id,
            'name' => 'Salem',
            'latitude' => 11.664325,
            'longitude' => 78.1460142,
            'is_active' => 1
        ));

        $new_york = State::create(array(
            'country_id' => $america->id,
            'name' => 'New York',
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $america->id,
            'state_id' => $new_york->id,
            'name' => 'Albany',
            'latitude' => 42.65030,
            'longitude' => -73.75030,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $america->id,
            'state_id' => $new_york->id,
            'name' => 'Binghamton',
            'latitude' => 42.10080,
            'longitude' => -75.91310,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $america->id,
            'state_id' => $new_york->id,
            'name' => 'Elmira',
            'latitude' => 42.08300,
            'longitude' => -76.83300,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $america->id,
            'state_id' => $new_york->id,
            'name' => 'Lewiston',
            'latitude' => 43.16917,
            'longitude' => -79.00170,
            'is_active' => 1
        ));

        City::create(array(
            'country_id' => $america->id,
            'state_id' => $new_york->id,
            'name' => 'Rochester',
            'latitude' => 43.16140,
            'longitude' => -77.60580,
            'is_active' => 1
        ));

    }
}