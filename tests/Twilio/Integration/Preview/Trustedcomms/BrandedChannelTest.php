<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Preview\Trustedcomms;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class BrandedChannelTest extends HolodeckTestCase {
    public function testFetchRequest(): void {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->preview->trustedComms->brandedChannels("BWXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://preview.twilio.com/TrustedComms/BrandedChannels/BWXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
        ));
    }

    public function testFetchResponse(): void {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "business_sid": "BXaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "brand_sid": "BZaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "sid": "BWaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "links": {
                    "channels": "https://preview.twilio.com/TrustedComms/BrandedChannels/BWaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/Channels"
                },
                "url": "https://preview.twilio.com/TrustedComms/BrandedChannels/BWaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
            }
            '
        ));

        $actual = $this->twilio->preview->trustedComms->brandedChannels("BWXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")->fetch();

        $this->assertNotNull($actual);
    }
}