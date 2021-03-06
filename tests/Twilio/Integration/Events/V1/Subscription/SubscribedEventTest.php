<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Tests\Integration\Events\V1\Subscription;

use Twilio\Exceptions\DeserializeException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Response;
use Twilio\Tests\HolodeckTestCase;
use Twilio\Tests\Request;

class SubscribedEventTest extends HolodeckTestCase {
    public function testReadRequest(): void {
        $this->holodeck->mock(new Response(500, ''));

        try {
            $this->twilio->events->v1->subscriptions("DFXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                     ->subscribedEvents->read();
        } catch (DeserializeException $e) {}
          catch (TwilioException $e) {}

        $this->assertRequest(new Request(
            'get',
            'https://events.twilio.com/v1/Subscriptions/DFXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX/SubscribedEvents'
        ));
    }

    public function testReadEmptyResponse(): void {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "types": [],
                "meta": {
                    "page": 0,
                    "page_size": 10,
                    "first_page_url": "https://events.twilio.com/v1/Subscriptions/DFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/SubscribedEvents?PageSize=10&Page=0",
                    "previous_page_url": null,
                    "url": "https://events.twilio.com/v1/Subscriptions/DFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/SubscribedEvents?PageSize=10&Page=0",
                    "next_page_url": null,
                    "key": "types"
                }
            }
            '
        ));

        $actual = $this->twilio->events->v1->subscriptions("DFXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->subscribedEvents->read();

        $this->assertNotNull($actual);
    }

    public function testReadResultsResponse(): void {
        $this->holodeck->mock(new Response(
            200,
            '
            {
                "types": [
                    {
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "type": "Voice.Calls",
                        "version": 2,
                        "subscription_sid": "DFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "url": "https://events.twilio.com/v1/Subscriptions/DFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/SubscribedEvents/Voice.Calls"
                    },
                    {
                        "account_sid": "ACaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "type": "Video.Rooms",
                        "version": 15,
                        "subscription_sid": "DFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "url": "https://events.twilio.com/v1/Subscriptions/DFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/SubscribedEvents/Video.Rooms"
                    }
                ],
                "meta": {
                    "page": 0,
                    "page_size": 50,
                    "first_page_url": "https://events.twilio.com/v1/Subscriptions/DFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/SubscribedEvents?PageSize=50&Page=0",
                    "previous_page_url": null,
                    "url": "https://events.twilio.com/v1/Subscriptions/DFaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/SubscribedEvents?PageSize=50&Page=0",
                    "next_page_url": null,
                    "key": "types"
                }
            }
            '
        ));

        $actual = $this->twilio->events->v1->subscriptions("DFXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX")
                                           ->subscribedEvents->read();

        $this->assertNotNull($actual);
    }
}