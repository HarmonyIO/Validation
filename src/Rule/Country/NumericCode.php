<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Country;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class NumericCode implements Rule
{
    private const CODES = [
        '004',
        '248',
        '008',
        '012',
        '016',
        '020',
        '024',
        '660',
        '010',
        '028',
        '032',
        '051',
        '533',
        '036',
        '040',
        '031',
        '044',
        '048',
        '050',
        '052',
        '112',
        '056',
        '084',
        '204',
        '060',
        '064',
        '068',
        '535',
        '070',
        '072',
        '074',
        '076',
        '086',
        '096',
        '100',
        '854',
        '108',
        '132',
        '116',
        '120',
        '124',
        '136',
        '140',
        '148',
        '152',
        '156',
        '162',
        '166',
        '170',
        '174',
        '180',
        '178',
        '184',
        '188',
        '384',
        '191',
        '192',
        '531',
        '196',
        '203',
        '208',
        '262',
        '212',
        '214',
        '218',
        '818',
        '222',
        '226',
        '232',
        '233',
        '748',
        '231',
        '238',
        '234',
        '242',
        '246',
        '250',
        '254',
        '258',
        '260',
        '266',
        '270',
        '268',
        '276',
        '288',
        '292',
        '300',
        '304',
        '308',
        '312',
        '316',
        '320',
        '831',
        '324',
        '624',
        '328',
        '332',
        '334',
        '336',
        '340',
        '344',
        '348',
        '352',
        '356',
        '360',
        '364',
        '368',
        '372',
        '833',
        '376',
        '380',
        '388',
        '392',
        '832',
        '400',
        '398',
        '404',
        '296',
        '408',
        '410',
        '414',
        '417',
        '418',
        '428',
        '422',
        '426',
        '430',
        '434',
        '438',
        '440',
        '442',
        '446',
        '807',
        '450',
        '454',
        '458',
        '462',
        '466',
        '470',
        '584',
        '474',
        '478',
        '480',
        '175',
        '484',
        '583',
        '498',
        '492',
        '496',
        '499',
        '500',
        '504',
        '508',
        '104',
        '516',
        '520',
        '524',
        '528',
        '540',
        '554',
        '558',
        '562',
        '566',
        '570',
        '574',
        '580',
        '578',
        '512',
        '586',
        '585',
        '275',
        '591',
        '598',
        '600',
        '604',
        '608',
        '612',
        '616',
        '620',
        '630',
        '634',
        '638',
        '642',
        '643',
        '646',
        '652',
        '654',
        '659',
        '662',
        '663',
        '666',
        '670',
        '882',
        '674',
        '678',
        '682',
        '686',
        '688',
        '690',
        '694',
        '702',
        '534',
        '703',
        '705',
        '090',
        '706',
        '710',
        '239',
        '728',
        '724',
        '144',
        '729',
        '740',
        '744',
        '752',
        '756',
        '760',
        '158',
        '762',
        '834',
        '764',
        '626',
        '768',
        '772',
        '776',
        '780',
        '788',
        '792',
        '795',
        '796',
        '798',
        '800',
        '804',
        '784',
        '826',
        '581',
        '840',
        '858',
        '860',
        '548',
        '862',
        '704',
        '092',
        '850',
        '876',
        '732',
        '887',
        '894',
        '716',
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
                return bubbleUp($result);
            }

            if (in_array(strtoupper($value), self::CODES, true)) {
                return succeed();
            }

            return fail(new Error('Country.NumericCode', new Parameter('codes', self::CODES)));
        });
    }
}
