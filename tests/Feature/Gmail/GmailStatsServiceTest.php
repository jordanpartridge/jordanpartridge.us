<?php

namespace Tests\Feature\Gmail;

use App\Services\GmailStatsService;
use DateTime;
use Exception;
use Mockery;
use PartridgeRocks\GmailClient\GmailClient;
use Tests\TestCase;

class GmailStatsServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    public function test_it_can_be_constructed_with_gmail_client()
    {
        $gmailClient = Mockery::mock(GmailClient::class);
        $service = new GmailStatsService($gmailClient);

        $this->assertInstanceOf(GmailStatsService::class, $service);
    }

    public function test_get_account_statistics_returns_expected_structure()
    {
        $gmailClient = Mockery::mock(GmailClient::class);

        // Mock the getUnreadStatistics method calls
        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => 'is:unread', 'maxResults' => 100, 'includeSpamTrash' => false, 'fields' => 'messages/id'])
            ->once()
            ->andReturn(collect(range(1, 25))); // 25 unread messages

        // Mock the getTodayStatistics method calls
        $today = now()->format('Y/m/d');
        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => "after:{$today}", 'maxResults' => 100, 'includeSpamTrash' => false, 'fields' => 'messages/id,nextPageToken'])
            ->once()
            ->andReturn((object)['nextPageToken' => null]); // Mock response object with no pagination

        // Mock listLabels
        $gmailClient->shouldReceive('listLabels')
            ->once()
            ->andReturn([
                ['type' => 'system', 'name' => 'INBOX'],
                ['type' => 'system', 'name' => 'SENT'],
                ['type' => 'user', 'name' => 'Important'],
            ]);

        $service = new GmailStatsService($gmailClient);
        $stats = $service->getAccountStatistics();

        $this->assertArrayHasKey('unread_count', $stats);
        $this->assertArrayHasKey('today_count', $stats);
        $this->assertArrayHasKey('labels_count', $stats);
        $this->assertArrayHasKey('last_updated', $stats);
        $this->assertArrayHasKey('api_calls_made', $stats);

        $this->assertEquals(25, $stats['unread_count']);
        $this->assertEquals(0, $stats['today_count']); // Updated expectation
        $this->assertEquals(3, $stats['labels_count']);
    }

    public function test_get_account_statistics_handles_api_errors_gracefully()
    {
        $gmailClient = Mockery::mock(GmailClient::class);

        // Mock API failure for unread stats (first call)
        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => 'is:unread', 'maxResults' => 100, 'includeSpamTrash' => false, 'fields' => 'messages/id'])
            ->once()
            ->andThrow(new Exception('API Error'));

        // Mock the other calls that would happen after unread fails
        $today = now()->format('Y/m/d');
        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => "after:{$today}", 'maxResults' => 100, 'includeSpamTrash' => false, 'fields' => 'messages/id,nextPageToken'])
            ->once()
            ->andReturn((object)['nextPageToken' => null]);

        $gmailClient->shouldReceive('listLabels')
            ->once()
            ->andReturn([]);

        $service = new GmailStatsService($gmailClient);
        $stats = $service->getAccountStatistics();

        // Individual methods handle their own exceptions, so unread_count will be '?'
        // but the overall method will succeed with partial results
        $this->assertEquals('?', $stats['unread_count']);
        $this->assertEquals(0, $stats['today_count']); // This succeeds
        $this->assertEquals(0, $stats['labels_count']); // This succeeds
        $this->assertArrayHasKey('api_calls_made', $stats);
        // No 'error' key at top level since individual methods handle their exceptions
    }

    public function test_get_account_health_check_returns_connected_status()
    {
        $gmailClient = Mockery::mock(GmailClient::class);

        $gmailClient->shouldReceive('listLabels')
            ->once()
            ->andReturn([
                ['name' => 'INBOX'],
                ['name' => 'SENT'],
            ]);

        $service = new GmailStatsService($gmailClient);
        $health = $service->getAccountHealthCheck();

        $this->assertTrue($health['connected']);
        $this->assertTrue($health['api_responsive']);
        $this->assertEquals(2, $health['labels_available']);
        $this->assertArrayHasKey('last_check', $health);
    }

    public function test_get_account_health_check_returns_disconnected_on_error()
    {
        $gmailClient = Mockery::mock(GmailClient::class);

        $gmailClient->shouldReceive('listLabels')
            ->once()
            ->andThrow(new Exception('Connection failed'));

        $service = new GmailStatsService($gmailClient);
        $health = $service->getAccountHealthCheck();

        $this->assertFalse($health['connected']);
        $this->assertFalse($health['api_responsive']);
        $this->assertEquals('Connection failed', $health['error']);
        $this->assertArrayHasKey('last_check', $health);
    }

    public function test_get_batch_statistics_processes_multiple_queries()
    {
        $gmailClient = Mockery::mock(GmailClient::class);

        $queries = [
            'unread'  => ['q' => 'is:unread'],
            'starred' => ['q' => 'is:starred'],
        ];

        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => 'is:unread'])
            ->once()
            ->andReturn(collect(range(1, 5)));

        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => 'is:starred'])
            ->once()
            ->andReturn(collect(range(1, 3)));

        $service = new GmailStatsService($gmailClient);
        $results = $service->getBatchStatistics($queries);

        $this->assertArrayHasKey('results', $results);
        $this->assertArrayHasKey('total_api_calls', $results);
        $this->assertArrayHasKey('timestamp', $results);

        $this->assertEquals(5, $results['results']['unread']['count']);
        $this->assertEquals(3, $results['results']['starred']['count']);
        $this->assertTrue($results['results']['unread']['success']);
        $this->assertTrue($results['results']['starred']['success']);
        $this->assertEquals(2, $results['total_api_calls']);
    }

    public function test_get_batch_statistics_handles_individual_query_failures()
    {
        $gmailClient = Mockery::mock(GmailClient::class);

        $queries = [
            'valid'   => ['q' => 'is:unread'],
            'invalid' => ['q' => 'invalid:query'],
        ];

        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => 'is:unread'])
            ->once()
            ->andReturn(collect(range(1, 5)));

        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => 'invalid:query'])
            ->once()
            ->andThrow(new Exception('Invalid query'));

        $service = new GmailStatsService($gmailClient);
        $results = $service->getBatchStatistics($queries);

        $this->assertEquals(5, $results['results']['valid']['count']);
        $this->assertTrue($results['results']['valid']['success']);

        $this->assertEquals(0, $results['results']['invalid']['count']);
        $this->assertFalse($results['results']['invalid']['success']);
        $this->assertEquals('Invalid query', $results['results']['invalid']['error']);
    }

    public function test_for_account_factory_method()
    {
        // Mock the app container to return a GmailClient mock
        $gmailClient = Mockery::mock(GmailClient::class);
        $gmailClient->shouldReceive('authenticate')
            ->with('access_token', 'refresh_token', Mockery::type(\DateTime::class))
            ->once()
            ->andReturnSelf();

        $this->app->instance(GmailClient::class, $gmailClient);

        $service = GmailStatsService::forAccount(
            'access_token',
            'refresh_token',
            new DateTime('+1 hour')
        );

        $this->assertInstanceOf(GmailStatsService::class, $service);
    }

    public function test_get_exact_unread_count_with_pagination()
    {
        $gmailClient = Mockery::mock(GmailClient::class);

        // Mock the initial call that returns 100 items (triggering pagination)
        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => 'is:unread', 'maxResults' => 100, 'includeSpamTrash' => false, 'fields' => 'messages/id'])
            ->once()
            ->andReturn(collect(range(1, 100))); // Exactly 100 results triggers pagination

        // Mock the paginated calls for getExactUnreadCount
        $firstPageResponse = (object) [
            'nextPageToken' => 'page2_token'
        ];

        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => 'is:unread', 'maxResults' => 100, 'includeSpamTrash' => false, 'fields' => 'messages/id,nextPageToken'])
            ->once()
            ->andReturn($firstPageResponse);

        // Mock the second page call (no more results)
        $secondPageResponse = (object) [
            'nextPageToken' => null
        ];

        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => 'is:unread', 'maxResults' => 100, 'includeSpamTrash' => false, 'fields' => 'messages/id,nextPageToken', 'pageToken' => 'page2_token'])
            ->once()
            ->andReturn($secondPageResponse);

        // Also mock the today and labels calls
        $today = now()->format('Y/m/d');
        $gmailClient->shouldReceive('listMessages')
            ->with(['q' => "after:{$today}", 'maxResults' => 100, 'includeSpamTrash' => false, 'fields' => 'messages/id,nextPageToken'])
            ->once()
            ->andReturn((object)['nextPageToken' => null]);

        $gmailClient->shouldReceive('listLabels')
            ->once()
            ->andReturn([]);

        $service = new GmailStatsService($gmailClient);
        $stats = $service->getAccountStatistics(['unread_max_fetch' => 1000]);

        // The exact count is based on what getExactUnreadCount returns (0 in this mock)
        $this->assertEquals(0, $stats['unread_count']);
        $this->assertArrayHasKey('api_calls_made', $stats);
    }
}
