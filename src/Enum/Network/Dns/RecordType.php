<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Enum\Network\Dns;

use Amp\Dns\Record;
use MyCLabs\Enum\Enum;

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
