<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Albania;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Andorra;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Austria;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Azerbaijan;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Bahrain;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Belgium;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\BosniaAndHerzegovina;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Brazil;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\BritishVirginIslands;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Bulgaria;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\CostaRica;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Croatia;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Cyprus;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\CzechRepublic;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Denmark;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\DominicanRepublic;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\EastTimor;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Estonia;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\FaroeIslands;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Finland;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\France;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Georgia;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Germany;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Gibraltar;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Greece;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Greenland;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Guatemala;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Hungary;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Iceland;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Ireland;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Israel;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Italy;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Jordan;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Kazakhstan;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Kosovo;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Kuwait;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Latvia;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Lebanon;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Liechtenstein;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Lithuania;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Luxembourg;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Macedonia;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Malta;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Mauritania;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Mauritius;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Moldova;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Monaco;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Montenegro;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Netherlands;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Norway;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Pakistan;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Palestine;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Poland;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Portugal;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Qatar;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Romania;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\SanMarino;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\SaudiArabia;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Serbia;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Slovakia;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Slovenia;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Spain;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Sweden;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Switzerland;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Tunisia;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\Turkey;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\UnitedArabEmirates;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Country\UnitedKingdom;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

class Iban implements Rule
{
    private const COUNTRY_RULES = [
        Albania::class,
        Andorra::class,
        Austria::class,
        Azerbaijan::class,
        Bahrain::class,
        Belgium::class,
        BosniaAndHerzegovina::class,
        Brazil::class,
        BritishVirginIslands::class,
        Bulgaria::class,
        CostaRica::class,
        Croatia::class,
        Cyprus::class,
        CzechRepublic::class,
        Denmark::class,
        DominicanRepublic::class,
        EastTimor::class,
        Estonia::class,
        FaroeIslands::class,
        Finland::class,
        France::class,
        Georgia::class,
        Germany::class,
        Gibraltar::class,
        Greece::class,
        Greenland::class,
        Guatemala::class,
        Hungary::class,
        Iceland::class,
        Ireland::class,
        Israel::class,
        Italy::class,
        Jordan::class,
        Kazakhstan::class,
        Kosovo::class,
        Kuwait::class,
        Latvia::class,
        Lebanon::class,
        Liechtenstein::class,
        Lithuania::class,
        Luxembourg::class,
        Macedonia::class,
        Malta::class,
        Mauritania::class,
        Mauritius::class,
        Moldova::class,
        Monaco::class,
        Montenegro::class,
        Netherlands::class,
        Norway::class,
        Pakistan::class,
        Palestine::class,
        Poland::class,
        Portugal::class,
        Qatar::class,
        Romania::class,
        SanMarino::class,
        SaudiArabia::class,
        Serbia::class,
        Slovakia::class,
        Slovenia::class,
        Spain::class,
        Sweden::class,
        Switzerland::class,
        Tunisia::class,
        Turkey::class,
        UnitedArabEmirates::class,
        UnitedKingdom::class,
    ];
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(static function () use ($value) {
            foreach (self::COUNTRY_RULES as $ruleFullyQualifiedClassName) {
                $rule = new $ruleFullyQualifiedClassName();

                // phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
                if ((yield $rule->validate($value)) === true) {
                    return true;
                }
            }

            return false;
        });
    }
}
