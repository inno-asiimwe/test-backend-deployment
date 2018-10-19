<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Fellow;
use App\SheetsService;
use Mockery;

 /**
  * @runInSeparateProcess 
  * @preserveGlobalState disabled
  */
 
class FellowsTest extends TestCase
{
    
    
    
    public function testFellowsAreListedCorrectly()
    {
        $fellow = [    
            "7-Jul-2018", "1001", "UGD/TTL/2135",  "Trust", "Birungi", "trust.birungi@andela.com",
             "Technical Team Lead", "AND/F/001", "Folajimi Ogunbadejo", "Folajimi", "Ogunbadejo",
            "folajimi.ogunbadejo@andela.com", "#N/A", "#N/A", "D0B", "Week 1", "Class 1 - LOS",
            "Lagos", "-1", "-1", "0", "0", "0","1",
            ];      
        $fellowsArray = [
                    $fellow,  $fellow, $fellow, $fellow,
                    $fellow, $fellow, $fellow
                ];
        $sheetService = Mockery::mock(SheetsService::class);
        $sheetService->shouldReceive('getSheetData')
            ->once()
            ->andReturn($fellowsArray);
            
        $this->app->instance(SheetsService::class, $sheetService);
        $response = $this->json('GET', '/api/v1/fellows')
            ->assertStatus(200)
            ->assertJsonStructure([
                            'payload'=>[
                                    '*' => [
                                            'id',
                                            'firstName',
                                            'lastName',
                                            'level',
                                            'status',
                                            'quantity',
                                            'quality',
                                            'initiative',
                                            'communication', 
                                            'professionalism', 
                                            'integration'
                                            ],
                                        ]                                       
                                ]);
    }

    public function testErrorWhenNoData()
    {
        $fellowsArray = [];
        $sheetService = Mockery::mock(SheetsService::class);
        $sheetService->shouldReceive('getSheetData')
            ->once()
            ->andReturn($fellowsArray);
            
        $this->app->instance(SheetsService::class, $sheetService);
        $response = $this->json('GET', '/api/v1/fellows')
            ->assertStatus(404)
            ->assertJson([
                'message' => 'Resource not found'
                ]);
    }
}
