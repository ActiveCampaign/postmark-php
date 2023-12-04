<?php

namespace Postmark\Models\Stats;

class PostmarkOutboundReadStats
{
    public array $Days; // DatedTrackedCount object
    public array $ReadCounts; // readcounts
}

//class DatedReadCount
//{
//    public string $Date;
//    public array $ReadCounts;
//
//    public function __construct(array $values)
//    {
//        $this->Date = !empty($values['Date']) ? $values['Date'] : '';
//        $this->Read = !empty($values['Read']) ? $values['Read'] : 0;
//    }
//}
//
//class ReadCount
//{
//
//}