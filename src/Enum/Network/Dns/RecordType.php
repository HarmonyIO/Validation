<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Enum\Network\Dns;

use Amp\Dns\Record;
use MyCLabs\Enum\Enum;

/**
 * @method static RecordType A()
 * @method static RecordType AAAA()
 * @method static RecordType AFSDB()
 * @method static RecordType CAA()
 * @method static RecordType CERT()
 * @method static RecordType CNAME()
 * @method static RecordType DHCID()
 * @method static RecordType DLV()
 * @method static RecordType DNAME()
 * @method static RecordType DNSKEY()
 * @method static RecordType DS()
 * @method static RecordType HINFO()
 * @method static RecordType KEY()
 * @method static RecordType KX()
 * @method static RecordType ISDN()
 * @method static RecordType LOC()
 * @method static RecordType MB()
 * @method static RecordType MD()
 * @method static RecordType MF()
 * @method static RecordType MG()
 * @method static RecordType MINFO()
 * @method static RecordType MR()
 * @method static RecordType MX()
 * @method static RecordType NAPTR()
 * @method static RecordType NS()
 * @method static RecordType NULL()
 * @method static RecordType PTR()
 * @method static RecordType RP()
 * @method static RecordType RT()
 * @method static RecordType SIG()
 * @method static RecordType SOA()
 * @method static RecordType SPF()
 * @method static RecordType SRV()
 * @method static RecordType TXT()
 * @method static RecordType WKS()
 * @method static RecordType X25()
 * @method static RecordType AXFR()
 * @method static RecordType MAILB()
 * @method static RecordType MAILA()
 * @method static RecordType ALL()
 */
class RecordType extends Enum
{
    // phpcs:disable SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedConstant
    private const A = Record::A;
    private const AAAA = Record::AAAA;
    private const AFSDB = Record::AFSDB;
    private const CAA = Record::CAA;
    private const CERT = Record::CERT;
    private const CNAME = Record::CNAME;
    private const DHCID = Record::DHCID;
    private const DLV = Record::DLV;
    private const DNAME = Record::DNAME;
    private const DNSKEY = Record::DNSKEY;
    private const DS = Record::DS;
    private const HINFO = Record::HINFO;
    private const KEY = Record::KEY;
    private const KX = Record::KX;
    private const ISDN = Record::ISDN;
    private const LOC = Record::LOC;
    private const MB = Record::MB;
    private const MD = Record::MD;
    private const MF = Record::MF;
    private const MG = Record::MG;
    private const MINFO = Record::MINFO;
    private const MR = Record::MR;
    private const MX = Record::MX;
    private const NAPTR = Record::NAPTR;
    private const NS = Record::NS;
    private const NULL = Record::NULL;
    private const PTR = Record::PTR;
    private const RP = Record::RP;
    private const RT = Record::RT;
    private const SIG = Record::SIG;
    private const SOA = Record::SOA;
    private const SPF = Record::SPF;
    private const SRV = Record::SRV;
    private const TXT = Record::TXT;
    private const WKS = Record::WKS;
    private const X25 = Record::X25;
    private const AXFR = Record::AXFR;
    private const MAILB = Record::MAILB;
    private const MAILA = Record::MAILA;
    private const ALL = Record::ALL;
    // phpcs:enable SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedConstant
}
