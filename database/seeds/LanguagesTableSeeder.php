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
use App\Language;
use Plugins\Pages\Model\Page;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Language::create([
            'name' => 'Abkhazian',
            'iso2' => 'ab',
            'iso3' => 'abk',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Afar',
            'iso2' => 'aa',
            'iso3' => 'aar',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Afrikaans',
            'iso2' => 'af',
            'iso3' => 'afr',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Akan',
            'iso2' => 'ak',
            'iso3' => 'aka',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Albanian',
            'iso2' => 'sq',
            'iso3' => 'sqi',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Amharic',
            'iso2' => 'am',
            'iso3' => 'amh',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Arabic',
            'iso2' => 'ar',
            'iso3' => 'ara',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Aragonese',
            'iso2' => 'an',
            'iso3' => 'arg',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Armenian',
            'iso2' => 'hy',
            'iso3' => 'hye',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Assamese',
            'iso2' => 'as',
            'iso3' => 'asm',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Avaric',
            'iso2' => 'av',
            'iso3' => 'ava',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Avestan',
            'iso2' => 'ae',
            'iso3' => 'ave',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Aymara',
            'iso2' => 'ay',
            'iso3' => 'aym',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Azerbaijani',
            'iso2' => 'az',
            'iso3' => 'aze',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Bambara',
            'iso2' => 'bm',
            'iso3' => 'bam',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Bashkir',
            'iso2' => 'ba',
            'iso3' => 'bak',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Basque',
            'iso2' => 'eu',
            'iso3' => 'eus',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Belarusian',
            'iso2' => 'be',
            'iso3' => 'bel',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Bengali',
            'iso2' => 'bn',
            'iso3' => 'ben',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Bihari',
            'iso2' => 'bh',
            'iso3' => 'bih',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Bislama',
            'iso2' => 'bi',
            'iso3' => 'bis',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Bosnian',
            'iso2' => 'bs',
            'iso3' => 'bos',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Breton',
            'iso2' => 'br',
            'iso3' => 'bre',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Bulgarian',
            'iso2' => 'bg',
            'iso3' => 'bul',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Burmese',
            'iso2' => 'my',
            'iso3' => 'mya',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Catalan',
            'iso2' => 'ca',
            'iso3' => 'cat',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Chamorro',
            'iso2' => 'ch',
            'iso3' => 'cha',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Chechen',
            'iso2' => 'ce',
            'iso3' => 'che',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Chichewa',
            'iso2' => 'ny',
            'iso3' => 'nya',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Chinese',
            'iso2' => 'zh',
            'iso3' => 'zho',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Church Slavic',
            'iso2' => 'cu',
            'iso3' => 'chu',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Chuvash',
            'iso2' => 'cv',
            'iso3' => 'chv',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Cornish',
            'iso2' => 'kw',
            'iso3' => 'cor',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Corsican',
            'iso2' => 'co',
            'iso3' => 'cos',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Cree',
            'iso2' => 'cr',
            'iso3' => 'cre',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Croatian',
            'iso2' => 'hr',
            'iso3' => 'hrv',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Czech',
            'iso2' => 'cs',
            'iso3' => 'ces',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Danish',
            'iso2' => 'da',
            'iso3' => 'dan',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Divehi',
            'iso2' => 'dv',
            'iso3' => 'div',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Dutch',
            'iso2' => 'nl',
            'iso3' => 'nld',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Dzongkha',
            'iso2' => 'dz',
            'iso3' => 'dzo',
            'is_active' => 0
        ]);
        $english = Language::create(array(
            'name' => 'English',
            'iso2' => 'en',
            'iso3' => 'eng',
            'is_active' => 1
        ));
        Language::create([
            'name' => 'Esperanto',
            'iso2' => 'eo',
            'iso3' => 'epo',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Estonian',
            'iso2' => 'et',
            'iso3' => 'est',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Ewe',
            'iso2' => 'ee',
            'iso3' => 'ewe',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Faroese',
            'iso2' => 'fo',
            'iso3' => 'fao',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Fijian',
            'iso2' => 'fj',
            'iso3' => 'fij',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Finnish',
            'iso2' => 'fi',
            'iso3' => 'fin',
            'is_active' => 0
        ]);
        $french = Language::create(array(
            'name' => 'French',
            'iso2' => 'fr',
            'iso3' => 'fra',
            'is_active' => 0
        ));
        Language::create([
            'name' => 'Fulah',
            'iso2' => 'ff',
            'iso3' => 'ful',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Galician',
            'iso2' => 'gl',
            'iso3' => 'glg',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Ganda',
            'iso2' => 'lg',
            'iso3' => 'lug',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Georgian',
            'iso2' => 'ka',
            'iso3' => 'kat',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'German',
            'iso2' => 'de',
            'iso3' => 'deu',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Greek',
            'iso2' => 'el',
            'iso3' => 'ell',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Guaran',
            'iso2' => 'gn',
            'iso3' => 'grn',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Gujarati',
            'iso2' => 'gu',
            'iso3' => 'guj',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Haitian',
            'iso2' => 'ht',
            'iso3' => 'hat',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Hausa',
            'iso2' => 'ha',
            'iso3' => 'hau',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Hebrew',
            'iso2' => 'he',
            'iso3' => 'heb',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Herero',
            'iso2' => 'hz',
            'iso3' => 'her',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Hindi',
            'iso2' => 'hi',
            'iso3' => 'hin',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Hiri Motu',
            'iso2' => 'ho',
            'iso3' => 'hmo',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Hungarian',
            'iso2' => 'hu',
            'iso3' => 'hun',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Icelandic',
            'iso2' => 'is',
            'iso3' => 'isl',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Ido',
            'iso2' => 'io',
            'iso3' => 'ido',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Igbo',
            'iso2' => 'ig',
            'iso3' => 'ibo',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Indonesian',
            'iso2' => 'id',
            'iso3' => 'ind',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Interlingua (International Auxiliary Language Association)',
            'iso2' => 'ia',
            'iso3' => 'ina',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Interlingue',
            'iso2' => 'ie',
            'iso3' => 'ile',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Inuktitut',
            'iso2' => 'iu',
            'iso3' => 'iku',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Inupiaq',
            'iso2' => 'ik',
            'iso3' => 'ipk',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Irish',
            'iso2' => 'ga',
            'iso3' => 'gle',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Italian',
            'iso2' => 'it',
            'iso3' => 'ita',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Japanese',
            'iso2' => 'ja',
            'iso3' => 'jpn',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Javanese',
            'iso2' => 'jv',
            'iso3' => 'jav',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kalaallisut',
            'iso2' => 'kl',
            'iso3' => 'kal',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kannada',
            'iso2' => 'kn',
            'iso3' => 'kan',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kanuri',
            'iso2' => 'kr',
            'iso3' => 'kau',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kashmiri',
            'iso2' => 'ks',
            'iso3' => 'kas',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kazakh',
            'iso2' => 'kk',
            'iso3' => 'kaz',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Khmer',
            'iso2' => 'km',
            'iso3' => 'khm',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kikuyu',
            'iso2' => 'ki',
            'iso3' => 'kik',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kinyarwanda',
            'iso2' => 'rw',
            'iso3' => 'kin',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kirghiz',
            'iso2' => 'ky',
            'iso3' => 'kir',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kirundi',
            'iso2' => 'rn',
            'iso3' => 'run',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Komi',
            'iso2' => 'kv',
            'iso3' => 'kom',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kongo',
            'iso2' => 'kg',
            'iso3' => 'kon',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Korean',
            'iso2' => 'ko',
            'iso3' => 'kor',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kurdish',
            'iso2' => 'ku',
            'iso3' => 'kur',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Kwanyama',
            'iso2' => 'kj',
            'iso3' => 'kua',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Lao',
            'iso2' => 'lo',
            'iso3' => 'lao',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Latin',
            'iso2' => 'la',
            'iso3' => 'lat',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Latvian',
            'iso2' => 'lv',
            'iso3' => 'lav',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Limburgish',
            'iso2' => 'li',
            'iso3' => 'lim',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Lingala',
            'iso2' => 'ln',
            'iso3' => 'lin',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Lithuanian',
            'iso2' => 'lt',
            'iso3' => 'lit',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Luba-Katanga',
            'iso2' => 'lu',
            'iso3' => 'lub',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Luxembourgish',
            'iso2' => 'lb',
            'iso3' => 'ltz',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Macedonian',
            'iso2' => 'mk',
            'iso3' => 'mkd',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Malagasy',
            'iso2' => 'mg',
            'iso3' => 'mlg',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Malay',
            'iso2' => 'ms',
            'iso3' => 'msa',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Malayalam',
            'iso2' => 'ml',
            'iso3' => 'mal',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Maltese',
            'iso2' => 'mt',
            'iso3' => 'mlt',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Manx',
            'iso2' => 'gv',
            'iso3' => 'glv',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'MÃƒÆ’Ã',
            'iso2' => 'mi',
            'iso3' => 'mri',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Marathi',
            'iso2' => 'mr',
            'iso3' => 'mar',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Marshallese',
            'iso2' => 'mh',
            'iso3' => 'mah',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Mongolian',
            'iso2' => 'mn',
            'iso3' => 'mon',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Nauru',
            'iso2' => 'na',
            'iso3' => 'nau',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Navajo',
            'iso2' => 'nv',
            'iso3' => 'nav',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Ndonga',
            'iso2' => 'ng',
            'iso3' => 'ndo',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Nepali',
            'iso2' => 'ne',
            'iso3' => 'nep',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'North Ndebele',
            'iso2' => 'nd',
            'iso3' => 'nde',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Northern Sami',
            'iso2' => 'se',
            'iso3' => 'sme',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Norwegian',
            'iso2' => 'no',
            'iso3' => 'nor',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Norwegian BokmÃƒÆ’Ã',
            'iso2' => 'nb',
            'iso3' => 'nob',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Norwegian Nynorsk',
            'iso2' => 'nn',
            'iso3' => 'nno',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Occitan',
            'iso2' => 'oc',
            'iso3' => 'oci',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Ojibwa',
            'iso2' => 'oj',
            'iso3' => 'oji',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Oriya',
            'iso2' => 'or',
            'iso3' => 'ori',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Oromo',
            'iso2' => 'om',
            'iso3' => 'orm',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Ossetian',
            'iso2' => 'os',
            'iso3' => 'oss',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'PÃƒÆ’Ã',
            'iso2' => 'pi',
            'iso3' => 'pli',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Panjabi',
            'iso2' => 'pa',
            'iso3' => 'pan',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Pashto',
            'iso2' => 'ps',
            'iso3' => 'pus',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Persian',
            'iso2' => 'fa',
            'iso3' => 'fas',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Polish',
            'iso2' => 'pl',
            'iso3' => 'pol',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Portuguese',
            'iso2' => 'pt',
            'iso3' => 'por',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Quechua',
            'iso2' => 'qu',
            'iso3' => 'que',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Raeto-Romance',
            'iso2' => 'rm',
            'iso3' => 'roh',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Romanian',
            'iso2' => 'ro',
            'iso3' => 'ron',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Russian',
            'iso2' => 'ru',
            'iso3' => 'rus',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Samoan',
            'iso2' => 'sm',
            'iso3' => 'smo',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Sango',
            'iso2' => 'sg',
            'iso3' => 'sag',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Sanskrit',
            'iso2' => 'sa',
            'iso3' => 'san',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Sardinian',
            'iso2' => 'sc',
            'iso3' => 'srd',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Scottish Gaelic',
            'iso2' => 'gd',
            'iso3' => 'gla',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Serbian',
            'iso2' => 'sr',
            'iso3' => 'srp',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Shona',
            'iso2' => 'sn',
            'iso3' => 'sna',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Sichuan Yi',
            'iso2' => 'ii',
            'iso3' => 'iii',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Sindhi',
            'iso2' => 'sd',
            'iso3' => 'snd',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Sinhala',
            'iso2' => 'si',
            'iso3' => 'sin',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Slovak',
            'iso2' => 'sk',
            'iso3' => 'slk',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Slovenian',
            'iso2' => 'sl',
            'iso3' => 'slv',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Somali',
            'iso2' => 'so',
            'iso3' => 'som',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'South Ndebele',
            'iso2' => 'nr',
            'iso3' => 'nbl',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Southern Sotho',
            'iso2' => 'st',
            'iso3' => 'sot',
            'is_active' => 0
        ]);
        $spanish = Language::create(array(
            'name' => 'Spanish',
            'iso2' => 'es',
            'iso3' => 'spa',
            'is_active' => 1
        ));
        Language::create([
            'name' => 'Sundanese',
            'iso2' => 'su',
            'iso3' => 'sun',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Swahili',
            'iso2' => 'sw',
            'iso3' => 'swa',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Swati',
            'iso2' => 'ss',
            'iso3' => 'ssw',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Swedish',
            'iso2' => 'sv',
            'iso3' => 'swe',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Tagalog',
            'iso2' => 'tl',
            'iso3' => 'tgl',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Tahitian',
            'iso2' => 'ty',
            'iso3' => 'tah',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Tajik',
            'iso2' => 'tg',
            'iso3' => 'tgk',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Tamil',
            'iso2' => 'ta',
            'iso3' => 'tam',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Tatar',
            'iso2' => 'tt',
            'iso3' => 'tat',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Telugu',
            'iso2' => 'te',
            'iso3' => 'tel',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Thai',
            'iso2' => 'th',
            'iso3' => 'tha',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Tibetan',
            'iso2' => 'bo',
            'iso3' => 'bod',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Tigrinya',
            'iso2' => 'ti',
            'iso3' => 'tir',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Tonga',
            'iso2' => 'to',
            'iso3' => 'ton',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Traditional Chinese',
            'iso2' => 'zh-TW',
            'iso3' => 'zh-TW',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Tsonga',
            'iso2' => 'ts',
            'iso3' => 'tso',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Tswana',
            'iso2' => 'tn',
            'iso3' => 'tsn',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Turkish',
            'iso2' => 'tr',
            'iso3' => 'tur',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Turkmen',
            'iso2' => 'tk',
            'iso3' => 'tuk',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Twi',
            'iso2' => 'tw',
            'iso3' => 'twi',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Uighur',
            'iso2' => 'ug',
            'iso3' => 'uig',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Ukrainian',
            'iso2' => 'uk',
            'iso3' => 'ukr',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Urdu',
            'iso2' => 'ur',
            'iso3' => 'urd',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Uzbek',
            'iso2' => 'uz',
            'iso3' => 'uzb',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Venda',
            'iso2' => 've',
            'iso3' => 'ven',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Vietnamese',
            'iso2' => 'vi',
            'iso3' => 'vie',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'VolapÃƒÆ’Ã',
            'iso2' => 'vo',
            'iso3' => 'vol',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Walloon',
            'iso2' => 'wa',
            'iso3' => 'wln',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Welsh',
            'iso2' => 'cy',
            'iso3' => 'cym',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Western Frisian',
            'iso2' => 'fy',
            'iso3' => 'fry',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Wolof',
            'iso2' => 'wo',
            'iso3' => 'wol',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Xhosa',
            'iso2' => 'xh',
            'iso3' => 'xho',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Yiddish',
            'iso2' => 'yi',
            'iso3' => 'yid',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Yoruba',
            'iso2' => 'yo',
            'iso3' => 'yor',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Zhuang',
            'iso2' => 'za',
            'iso3' => 'zha',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Zulu',
            'iso2' => 'zu',
            'iso3' => 'zul',
            'is_active' => 0
        ]);
        Language::create([
            'name' => 'Chinese',
            'iso2' => 'ch',
            'iso3' => '',
            'is_active' => 0
        ]);

        //Pages
        Page::create(array(
            'language_id' => $english->id,
            'title' => 'Terms and conditions',
            'slug' => 'terms-and-conditions',
            'page_content' => 'Lorem Ipsum is a dummy text that is mainly used by the printing and design industry. It is intended to show how the type will look before the end product is available.

Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500:s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.

Lorem Ipsum dummy texts was available for many years on adhesive sheets in different sizes and typefaces from a company called Letraset.

When computers came along, Aldus included lorem ipsum in its PageMaker publishing software, and you now see it wherever designers, content designers, art directors, user interface developers and web designer are at work.

They use it daily when using programs such as Adobe Photoshop, Paint Shop Pro, Dreamweaver, FrontPage, PageMaker, FrameMaker, Illustrator, Flash, Indesign etc.'
        ));
        Page::create(array(
            'language_id' => $english->id,
            'title' => 'About Us',
            'slug' => 'about-us',
            'page_content' => '<p>In posuere molestie augue, eget tincidunt libero pellentesque nec.   Aliquam erat volutpat. Aliquam a ligula nulla, at suscipit odio. Nullam   in nibh nibh, eu bibendum ligula. Morbi eu nibh dui. Vivamus  scelerisque  fermentum lacus et tristique. Sed vulputate euismod metus  porta  feugiat. Nulla varius venenatis mauris, nec ornare nisl bibendum  id. Aenean id orci nisl, in scelerisque nibh. Sed quam sapien,  tempus quis  vestibulum eu, sagittis varius sapien. Aliquam erat  volutpat. Nulla  facilisi. In egestas faucibus nunc, et venenatis purus  aliquet quis.  Nulla eget arcu turpis. Nunc pellentesque eros quis neque  sodales  hendrerit. Donec eget nibh sit amet ipsum elementum vehicula.   Pellentesque molestie diam vitae erat suscipit consequat. Pellentesque   vel arcu sit amet metus mattis congue vitae eu quam.</p>'
        ));
        Page::create(array(
            'language_id' => $english->id,
            'title' => 'Privacy Policy',
            'slug' => 'privacy_policy',
            'page_content' => '<p>Coming soon</p>'
        ));

        Page::create(array(
            'language_id' => $french->id,
            'title' => 'Terms and conditions',
            'slug' => 'terms-and-conditions',
            'page_content' => 'Lorem Ipsum is a dummy text that is mainly used by the printing and design industry. It is intended to show how the type will look before the end product is available.

Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500:s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.

Lorem Ipsum dummy texts was available for many years on adhesive sheets in different sizes and typefaces from a company called Letraset.

When computers came along, Aldus included lorem ipsum in its PageMaker publishing software, and you now see it wherever designers, content designers, art directors, user interface developers and web designer are at work.

They use it daily when using programs such as Adobe Photoshop, Paint Shop Pro, Dreamweaver, FrontPage, PageMaker, FrameMaker, Illustrator, Flash, Indesign etc.'
        ));
        Page::create(array(
            'language_id' => $french->id,
            'title' => 'About Us',
            'slug' => 'about-us',
            'page_content' => '<p>In posuere molestie augue, eget tincidunt libero pellentesque nec.   Aliquam erat volutpat. Aliquam a ligula nulla, at suscipit odio. Nullam   in nibh nibh, eu bibendum ligula. Morbi eu nibh dui. Vivamus  scelerisque  fermentum lacus et tristique. Sed vulputate euismod metus  porta  feugiat. Nulla varius venenatis mauris, nec ornare nisl bibendum  id. Aenean id orci nisl, in scelerisque nibh. Sed quam sapien,  tempus quis  vestibulum eu, sagittis varius sapien. Aliquam erat  volutpat. Nulla  facilisi. In egestas faucibus nunc, et venenatis purus  aliquet quis.  Nulla eget arcu turpis. Nunc pellentesque eros quis neque  sodales  hendrerit. Donec eget nibh sit amet ipsum elementum vehicula.   Pellentesque molestie diam vitae erat suscipit consequat. Pellentesque   vel arcu sit amet metus mattis congue vitae eu quam.</p>'
        ));
        Page::create(array(
            'language_id' => $french->id,
            'title' => 'Privacy Policy',
            'slug' => 'privacy_policy',
            'page_content' => '<p>Coming soon</p>'
        ));

        Page::create(array(
            'language_id' => $spanish->id,
            'title' => 'Terms and conditions',
            'slug' => 'terms-and-conditions',
            'page_content' => 'Lorem Ipsum is a dummy text that is mainly used by the printing and design industry. It is intended to show how the type will look before the end product is available.

Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500:s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.

Lorem Ipsum dummy texts was available for many years on adhesive sheets in different sizes and typefaces from a company called Letraset.

When computers came along, Aldus included lorem ipsum in its PageMaker publishing software, and you now see it wherever designers, content designers, art directors, user interface developers and web designer are at work.

They use it daily when using programs such as Adobe Photoshop, Paint Shop Pro, Dreamweaver, FrontPage, PageMaker, FrameMaker, Illustrator, Flash, Indesign etc.'
        ));
        Page::create(array(
            'language_id' => $spanish->id,
            'title' => 'About Us',
            'slug' => 'about-us',
            'page_content' => '<p>In posuere molestie augue, eget tincidunt libero pellentesque nec.   Aliquam erat volutpat. Aliquam a ligula nulla, at suscipit odio. Nullam   in nibh nibh, eu bibendum ligula. Morbi eu nibh dui. Vivamus  scelerisque  fermentum lacus et tristique. Sed vulputate euismod metus  porta  feugiat. Nulla varius venenatis mauris, nec ornare nisl bibendum  id. Aenean id orci nisl, in scelerisque nibh. Sed quam sapien,  tempus quis  vestibulum eu, sagittis varius sapien. Aliquam erat  volutpat. Nulla  facilisi. In egestas faucibus nunc, et venenatis purus  aliquet quis.  Nulla eget arcu turpis. Nunc pellentesque eros quis neque  sodales  hendrerit. Donec eget nibh sit amet ipsum elementum vehicula.   Pellentesque molestie diam vitae erat suscipit consequat. Pellentesque   vel arcu sit amet metus mattis congue vitae eu quam.</p>'
        ));
        Page::create(array(
            'language_id' => $spanish->id,
            'title' => 'Privacy Policy',
            'slug' => 'privacy_policy',
            'page_content' => '<p>Coming soon</p>'
        ));

    }
}


 	 	 	 
 	 	 	 
