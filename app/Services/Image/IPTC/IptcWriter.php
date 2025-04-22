<?php

namespace App\Services\Image\IPTC;

use InvalidArgumentException;
use RuntimeException;

class IptcWriter
{
    /**
     * @param string $filename
     */
    public function __construct(
        protected string $filename
    ) {
    }

    public function write(IptcData $iptcData): void
    {
        $content = iptcembed(
            collect($iptcData->getAll())
                ->map(function (array $values, string $key) {
                    return collect($values)->map(function (string $value) use ($key) {
                        $tag = substr($key, 2);
                        return $this->iptc_make_tag(2, $tag, $value);
                    })->implode('');
                })
                ->implode(''),
            $this->filename,
        );

        $fp = fopen($this->filename, "wb");
        fwrite($fp, $content);
        fclose($fp);
    }

    protected function iptc_make_tag($rec, $data, $value): string
    {
        $length = strlen($value);
        $retval = chr(0x1C) . chr($rec) . chr($data);

        if($length < 0x8000)
        {
            $retval .= chr($length >> 8) .  chr($length & 0xFF);
        }
        else
        {
            $retval .= chr(0x80) .
                chr(0x04) .
                chr(($length >> 24) & 0xFF) .
                chr(($length >> 16) & 0xFF) .
                chr(($length >> 8) & 0xFF) .
                chr($length & 0xFF);
        }

        return $retval . $value;
    }
}
