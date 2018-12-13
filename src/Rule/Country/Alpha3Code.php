<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Country;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Alpha3Code implements Rule
{
    private const CODES = [
        'AFG',
        'ALA',
        'ALB',
        'DZA',
        'ASM',
        'AND',
        'AGO',
        'AIA',
        'ATA',
        'ATG',
        'ARG',
        'ARM',
        'ABW',
        'AUS',
        'AUT',
        'AZE',
        'BHS',
        'BHR',
        'BGD',
        'BRB',
        'BLR',
        'BEL',
        'BLZ',
        'BEN',
        'BMU',
        'BTN',
        'BOL',
        'BES',
        'BIH',
        'BWA',
        'BVT',
        'BRA',
        'IOT',
        'BRN',
        'BGR',
        'BFA',
        'BDI',
        'CPV',
        'KHM',
        'CMR',
        'CAN',
        'CYM',
        'CAF',
        'TCD',
        'CHL',
        'CHN',
        'CXR',
        'CCK',
        'COL',
        'COM',
        'COD',
        'COG',
        'COK',
        'CRI',
        'CIV',
        'HRV',
        'CUB',
        'CUW',
        'CYP',
        'CZE',
        'DNK',
        'DJI',
        'DMA',
        'DOM',
        'ECU',
        'EGY',
        'SLV',
        'GNQ',
        'ERI',
        'EST',
        'SWZ',
        'ETH',
        'FLK',
        'FRO',
        'FJI',
        'FIN',
        'FRA',
        'GUF',
        'PYF',
        'ATF',
        'GAB',
        'GMB',
        'GEO',
        'DEU',
        'GHA',
        'GIB',
        'GRC',
        'GRL',
        'GRD',
        'GLP',
        'GUM',
        'GTM',
        'GGY',
        'GIN',
        'GNB',
        'GUY',
        'HTI',
        'HMD',
        'VAT',
        'HND',
        'HKG',
        'HUN',
        'ISL',
        'IND',
        'IDN',
        'IRN',
        'IRQ',
        'IRL',
        'IMN',
        'ISR',
        'ITA',
        'JAM',
        'JPN',
        'JEY',
        'JOR',
        'KAZ',
        'KEN',
        'KIR',
        'PRK',
        'KOR',
        'KWT',
        'KGZ',
        'LAO',
        'LVA',
        'LBN',
        'LSO',
        'LBR',
        'LBY',
        'LIE',
        'LTU',
        'LUX',
        'MAC',
        'MKD',
        'MDG',
        'MWI',
        'MYS',
        'MDV',
        'MLI',
        'MLT',
        'MHL',
        'MTQ',
        'MRT',
        'MUS',
        'MYT',
        'MEX',
        'FSM',
        'MDA',
        'MCO',
        'MNG',
        'MNE',
        'MSR',
        'MAR',
        'MOZ',
        'MMR',
        'NAM',
        'NRU',
        'NPL',
        'NLD',
        'NCL',
        'NZL',
        'NIC',
        'NER',
        'NGA',
        'NIU',
        'NFK',
        'MNP',
        'NOR',
        'OMN',
        'PAK',
        'PLW',
        'PSE',
        'PAN',
        'PNG',
        'PRY',
        'PER',
        'PHL',
        'PCN',
        'POL',
        'PRT',
        'PRI',
        'QAT',
        'REU',
        'ROU',
        'RUS',
        'RWA',
        'BLM',
        'SHN',
        'KNA',
        'LCA',
        'MAF',
        'SPM',
        'VCT',
        'WSM',
        'SMR',
        'STP',
        'SAU',
        'SEN',
        'SRB',
        'SYC',
        'SLE',
        'SGP',
        'SXM',
        'SVK',
        'SVN',
        'SLB',
        'SOM',
        'ZAF',
        'SGS',
        'SSD',
        'ESP',
        'LKA',
        'SDN',
        'SUR',
        'SJM',
        'SWE',
        'CHE',
        'SYR',
        'TWN',
        'TJK',
        'TZA',
        'THA',
        'TLS',
        'TGO',
        'TKL',
        'TON',
        'TTO',
        'TUN',
        'TUR',
        'TKM',
        'TCA',
        'TUV',
        'UGA',
        'UKR',
        'ARE',
        'GBR',
        'UMI',
        'USA',
        'URY',
        'UZB',
        'VUT',
        'VEN',
        'VNM',
        'VGB',
        'VIR',
        'WLF',
        'ESH',
        'YEM',
        'ZMB',
        'ZWE',
    ];

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if (in_array(strtoupper($value), self::CODES, true)) {
                return succeed();
            }

            return fail(new Error('Country.Alpha3Code', new Parameter('codes', self::CODES)));
        });
    }
}
