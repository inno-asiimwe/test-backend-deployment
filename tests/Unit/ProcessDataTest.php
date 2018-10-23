<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ProcessData;


class ProcessDataTest extends TestCase
{
    public function getInputAndExpectedData($weekStatus)
    {
        $ratings = [
            "Week 1" => [
                "quantity" => "1",
                "quality" => "2",
                "initiative" => "1",
                "communication" => "1",
                "professionalism" => "1",
                "integration" => "0",
            ],
            "Week 2" => [
                "quantity" => "1",
                "quality" => "2",
                "initiative" => "1",
                "communication" => "1",
                "professionalism" => "1",
                "integration" => "0",
            ],
            "Week 3" => [
                "quantity" => "1",
                "quality" => "2",
                "initiative" => "1",
                "communication" => "1",
                "professionalism" => "1",
                "integration" => "0",
            ],
            "Week 4" => [
                "quantity" => "1",
                "quality" => "2",
                "initiative" => "1",
                "communication" => "1",
                "professionalism" => "1",
                "integration" => "0",
            ],
            "Week 5" => [
                "quantity" => "1",
                "quality" => "2",
                "initiative" => "1",
                "communication" => "1",
                "professionalism" => "1",
                "integration" => "0",
            ],
            "Week 6" => [
                "quantity" => "1",
                "quality" => "2",
                "initiative" => "1",
                "communication" => "1",
                "professionalism" => "1",
                "integration" => "0",
            ]
        ];
        $averageRating = [
            "quantity" => "1",
            "quality" => "2",
            "initiative" => "1",
            "communication" => "1",
            "professionalism" => "1",
            "integration" => "0",
        ];
        $basicInfo = [
            "id" => "AND/F/007",
            "fullName" => "Gregory Webster",
            "firstName" => "Gregory",
            "lastName" => "Webster",
            "email" => "Gregory.Webster@andela.com",
            "partnerName" => "#N/A",
            "partnerId" => "#N/A",
            "level" => "D0B",
            "cohort" => "Class 2 - LOS",
            "location" => "Lagos",
            "status" => "gteWk5OffTrack",
        ];
        $inputArray = [
            "AND/F/007" => [
                "id" => "AND/F/007",
                "fullName" => "Gregory Webster",
                "firstName" => "Gregory",
                "lastName" => "Webster",
                "email" => "Gregory.Webster@andela.com",
                "partnerName" => "#N/A",
                "partnerId" => "#N/A",
                "level" => "D0B",
                "cohort" => "Class 2 - LOS",
                "location" => "Lagos",
                "averageRatings" => [
                    "quantity" => "1",
                    "quality" => "2",
                    "initiative" => "1",
                    "communication" => "1",
                    "professionalism" => "1",
                    "integration" => "0",
                ],
            ],
        ];
        if ($weekStatus === "afterWk5") {
            $inputArray["AND/F/007"]["ratings"] = $ratings;
            $basicInfo["ratings"] = $ratings;
            $basicInfo["averageRatings"] = $averageRating;
            $expected[0]["fellow:gteWk5OffTrack-AND/F/007"] = $basicInfo;
            $expected[1] = [
                "ltWk5OffTrack" => 0,
                "gteWk5OffTrack" => 1,
                "onTrack" => 0
            ];
            return [$inputArray, $expected];
        }

        elseif ($weekStatus === "beforeWk5") {
            $inputArray["AND/F/007"]["ratings"] = array_slice($ratings, 0, 4);
            $basicInfo["ratings"] = array_slice($ratings, 0, 4);
            $basicInfo["status"] = "ltWk5OffTrack";
            $basicInfo["averageRatings"] = $averageRating;
            $expected[0]["fellow:ltWk5OffTrack-AND/F/007"] = $basicInfo;
            $expected[1] = [
                "ltWk5OffTrack" => 1,
                "gteWk5OffTrack" => 0,
                "onTrack" => 0
            ];
            return [$inputArray, $expected];
        }

        elseif ($weekStatus === "onTrack") {
            $avRating =[
                "quantity" => "1",
                "quality" => "2",
                "initiative" => "1",
                "communication" => "1",
                "professionalism" => "1",
                "integration" => "1",
            ];
            $inputArray["AND/F/007"]["ratings"] = array_slice($ratings, 0, 4);
            $inputArray["AND/F/007"]["averageRatings"] = $avRating;
            $basicInfo["ratings"] = array_slice($ratings, 0, 4);
            $basicInfo["status"] = "onTrack";
            $basicInfo["averageRatings"] = $avRating;
            $expected[0]["fellow:onTrack-AND/F/007"] = $basicInfo;
            $expected[1] = [
                "ltWk5OffTrack" => 0,
                "gteWk5OffTrack" => 0,
                "onTrack" => 1
            ];
            return [$inputArray, $expected];
        }
    }
    /**
     * Checks that the correct input array is converted to associative array with
     * the right keys
     * 
     * @return void
     */
    public function testHeadersAddedCorrectly()
    {
        $inputArray = [
            [
                "31-Aug-2018",  "1072", "UGD/TTL/2135", "Trust",
                "Birungi", "trust.birungi@andela.com", "Technical Team Lead",
                "AND/F/007", "Gregory Webster", "Gregory", "Webster",
                "Gregory.Webster@andela.com", "#N/A", "#N/A", "D0B", "Week 4",
                "Class 2 - LOS", "Lagos", "1", "2", "1", "1", "1", "0",
            ],
            [
                "31-Aug-2018", "1073", "LOS/TTL/1012", "Grace", "Samuel",
                "grace.samuel@andela.com", "Technical Team Lead", "AND/F/008",
                "Amber Gill", "Amber", "Gill", "Amber.Gill@andela.com", "#N/A",
                 "#N/A", "D0B", "Week 4", "Class 2 - LOS", "Lagos", "1", "1",
                 "1", "1", "1", "0"
              ]
            ];
        $expected = [
            [
                "eventDate" => "31-Aug-2018",
                "eventNumber" => "1072",
                "staffId" => "UGD/TTL/2135",
                "submitterFirstName" => "Trust",
                "submitterLastName" => "Birungi",
                "submitterEmail" => "trust.birungi@andela.com",
                "submitterRole" => "Technical Team Lead",
                "id" => "AND/F/007",
                "fullName" => "Gregory Webster",
                "firstName" => "Gregory",
                "lastName" => "Webster",
                "email" => "Gregory.Webster@andela.com",
                "partnerName" => "#N/A",
                "partnerId" => "#N/A",
                "level" => "D0B",
                "week" => "Week 4",
                "cohort" => "Class 2 - LOS",
                "location" => "Lagos",
                "quantity" => "1",
                "quality" => "2",
                "initiative" => "1",
                "communication" => "1",
                "professionalism" => "1",
                "integration" => "0",
            ],
            [
                "eventDate" => "31-Aug-2018",
                "eventNumber" => "1073",
                "staffId" => "LOS/TTL/1012",
                "submitterFirstName" => "Grace",
                "submitterLastName" => "Samuel",
                "submitterEmail" => "grace.samuel@andela.com",
                "submitterRole" => "Technical Team Lead",
                "id" => "AND/F/008",
                "fullName" => "Amber Gill",
                "firstName" => "Amber",
                "lastName" => "Gill",
                "email" => "Amber.Gill@andela.com",
                "partnerName" => "#N/A",
                "partnerId" => "#N/A",
                "level" => "D0B",
                "week" => "Week 4",
                "cohort" => "Class 2 - LOS",
                "location" => "Lagos",
                "quantity" => "1",
                "quality" => "1",
                "initiative" => "1",
                "communication" => "1",
                "professionalism" => "1",
                "integration" => "0",
              ]
              
            ];
            $actual = ProcessData::addHeader($inputArray);
        $this->assertEquals($expected, $actual);    
    }

     /**
     * Checks that the correct input array is converted to associative array with
     * the right keys
     * 
     * @return void
     */
    public function testSheetDataTransformedCorrectly()
    {
        $gWebsterWk1 = [
            "eventDate" => "31-Aug-2018",
            "eventNumber" => "1072",
            "staffId" => "UGD/TTL/2135",
            "submitterFirstName" => "Trust",
            "submitterLastName" => "Birungi",
            "submitterEmail" => "trust.birungi@andela.com",
            "submitterRole" => "Technical Team Lead",
            "id" => "AND/F/007",
            "fullName" => "Gregory Webster",
            "firstName" => "Gregory",
            "lastName" => "Webster",
            "email" => "Gregory.Webster@andela.com",
            "partnerName" => "#N/A",
            "partnerId" => "#N/A",
            "level" => "D0A",
            "week" => "Week 1",
            "cohort" => "Class 2 - LOS",
            "location" => "Lagos",
            "quantity" => "1",
            "quality" => "2",
            "initiative" => "1",
            "communication" => "1",
            "professionalism" => "1",
            "integration" => "0",
        ];
        $gWebsterWk2 = $gWebsterWk1;
        $gWebsterWk2['week'] = "Week 2";
        $aGillWk1 = [
            "eventDate" => "31-Aug-2018",
            "eventNumber" => "1073",
            "staffId" => "LOS/TTL/1012",
            "submitterFirstName" => "Grace",
            "submitterLastName" => "Samuel",
            "submitterEmail" => "grace.samuel@andela.com",
            "submitterRole" => "Technical Team Lead",
            "id" => "AND/F/008",
            "fullName" => "Amber Gill",
            "firstName" => "Amber",
            "lastName" => "Gill",
            "email" => "Amber.Gill@andela.com",
            "partnerName" => "#N/A",
            "partnerId" => "#N/A",
            "level" => "D0B",
            "week" => "Week 1",
            "cohort" => "Class 2 - LOS",
            "location" => "Lagos",
            "quantity" => "1",
            "quality" => "1",
            "initiative" => "1",
            "communication" => "1",
            "professionalism" => "1",
            "integration" => "0",
        ];
        $aGillWk2 = $aGillWk1;
        $aGillWk2['week'] = "Week 2";
        $inputArray = [
            $gWebsterWk1,
            $gWebsterWk2,
            $aGillWk1,
            $aGillWk2,
            ];

            $expected = [
                "AND/F/007" => [
                    "id" => "AND/F/007",
                    "fullName" => "Gregory Webster",
                    "firstName" => "Gregory",
                    "lastName" => "Webster",
                    "email" => "Gregory.Webster@andela.com",
                    "partnerName" => "#N/A",
                    "partnerId" => "#N/A",
                    "level" => "D0A Simulations",
                    "cohort" => "Class 2 - LOS",
                    "location" => "Lagos",
                    
                    "ratings" => [
                        "Week 1" => [
                            "quantity" => "1",
                            "quality" => "2",
                            "initiative" => "1",
                            "communication" => "1",
                            "professionalism" => "1",
                            "integration" => "0",
                        ],
                        "Week 2" => [
                            "quantity" => "1",
                            "quality" => "2",
                            "initiative" => "1",
                            "communication" => "1",
                            "professionalism" => "1",
                            "integration" => "0",
                        ],
                    ],
                    "averageRatings" => [
                        "quantity" => "1.0",
                        "quality" => "2.0",
                        "initiative" => "1.0",
                        "communication" => "1.0",
                        "professionalism" => "1.0",
                        "integration" => "0.0",
                    ],
                    "otherCriteria" => [
                        "Week 1" => [
                            "eventDate" => "31-Aug-2018",
                            "eventNumber" => "1072",
                            "staffId" => "UGD/TTL/2135",
                            "submitterFirstName" => "Trust",
                            "submitterLastName" => "Birungi",
                            "submitterEmail" => "trust.birungi@andela.com",
                            "submitterRole" => "Technical Team Lead",
                        ],
                        "Week 2" => [
                            "eventDate" => "31-Aug-2018",
                            "eventNumber" => "1072",
                            "staffId" => "UGD/TTL/2135",
                            "submitterFirstName" => "Trust",
                            "submitterLastName" => "Birungi",
                            "submitterEmail" => "trust.birungi@andela.com",
                            "submitterRole" => "Technical Team Lead",
                        ],
                    ]
                ],
                "AND/F/008" => [
                    "id" => "AND/F/008",
                    "fullName" => "Amber Gill",
                    "firstName" => "Amber",
                    "lastName" => "Gill",
                    "email" => "Amber.Gill@andela.com",
                    "partnerName" => "#N/A",
                    "partnerId" => "#N/A",
                    "level" => "D0B Apprenticeship",
                    "cohort" => "Class 2 - LOS",
                    "location" => "Lagos",
                    
                    "ratings" => [
                        "Week 1" => [
                            "quantity" => "1",
                            "quality" => "1",
                            "initiative" => "1",
                            "communication" => "1",
                            "professionalism" => "1",
                            "integration" => "0",
                        ],
                        "Week 2" => [
                            "quantity" => "1",
                            "quality" => "1",
                            "initiative" => "1",
                            "communication" => "1",
                            "professionalism" => "1",
                            "integration" => "0",
                        ],
                    ],
                    "averageRatings" => [
                            "quantity" => "1.0",
                            "quality" => "1.0",
                            "initiative" => "1.0",
                            "communication" => "1.0",
                            "professionalism" => "1.0",
                            "integration" => "0.0",
                    ],
                    "otherCriteria" => [
                        "Week 1" => [
                            "eventDate" => "31-Aug-2018",
                            "eventNumber" => "1073",
                            "staffId" => "LOS/TTL/1012",
                            "submitterFirstName" => "Grace",
                            "submitterLastName" => "Samuel",
                            "submitterEmail" => "grace.samuel@andela.com",
                            "submitterRole" => "Technical Team Lead",
                        ],
                        "Week 2" => [
                            "eventDate" => "31-Aug-2018",
                            "eventNumber" => "1073",
                            "staffId" => "LOS/TTL/1012",
                            "submitterFirstName" => "Grace",
                            "submitterLastName" => "Samuel",
                            "submitterEmail" => "grace.samuel@andela.com",
                            "submitterRole" => "Technical Team Lead",
                        ],
                    ]
                  ]
                  
                ];
        $actual = ProcessData::transformSheet($inputArray);
        $this->assertEquals($expected, $actual);    
        
    }

    /**
     * Checks that the average is computed from the input array with the right keys
     * 
     * @return void
     */
    public function testGetAverageRatings()
    {

        $expected = [
            "quantity" => "1.0",
            "quality" => "2.0",
            "initiative" => "1.0",
            "communication" => "1.0",
            "professionalism" => "1.0",
            "integration" => "0.0",
        ];
            $inputArray = [
                        "Week 1" => [
                            "quantity" => "1",
                            "quality" => "2",
                            "initiative" => "1",
                            "communication" => "1",
                            "professionalism" => "1",
                            "integration" => "0",
                        ],
                        "Week 2" => [
                            "quantity" => "1",
                            "quality" => "2",
                            "initiative" => "1",
                            "communication" => "1",
                            "professionalism" => "1",
                            "integration" => "0",
                        ],
                    ];
               $keys = [
                "quantity",
                "quality",
                "initiative",
                "communication",
                "professionalism",
                "integration",
               ];
        $actual = ProcessData::getAverageRatings($inputArray, $keys);
        $this->assertEquals($expected, $actual);    
        
    }

     /**
     * Checks that the the function executes properly
     * 
     * @return void
     */
    public function testSplitByWeek5AndRatingAfterWk5()
    {

        $data = $this->getInputAndExpectedData("afterWk5");
        $inputArray = $data[0];
        $expected = $data[1];
            
        $actual = ProcessData::splitByWeek5AndRating($inputArray);
    
        $this->assertEquals($expected, $actual);
    }

    /**
     * Checks that the the function executes properly
     * 
     * @return void
     */
    public function testSplitByWeek5AndRatingBeforeWk5()
    {

        $data = $this->getInputAndExpectedData("beforeWk5");
        $inputArray = $data[0];
        $expected = $data[1];
        $actual = ProcessData::splitByWeek5AndRating($inputArray);
    
        $this->assertEquals($expected, $actual);
    }

    /**
     * Checks that the the function executes properly
     * 
     * @return void
     */
    public function testSplitByWeek5AndRatingOnTrack()
    {

        $data = $this->getInputAndExpectedData("onTrack");
        $inputArray = $data[0];
        $expected = $data[1];
        $actual = ProcessData::splitByWeek5AndRating($inputArray);
    
        $this->assertEquals($expected, $actual);
    }

     /**
     * Checks that the the function executes properly
     * 
     * @return void
     */
    public function testFormatResponseIsCorrect()
    {

        $fellow =  [
                "id" => "AND/F/007",
                "fullName" => "Gregory Webster",
                "firstName" => "Gregory",
                "lastName" => "Webster",
                "email" => "Gregory.Webster@andela.com",
                "partnerName" => "#N/A",
                "partnerId" => "#N/A",
                "level" => "D0B",
                "status" => "ltWk5OffTrack",                
                "cohort" => "Class 2 - LOS",
                "location" => "Lagos",
                "averageRatings" => [
                    "quantity" => "1",
                    "quality" => "2",
                    "initiative" => "1",
                    "communication" => "1",
                    "professionalism" => "1",
                    "integration" => "0",
                ],
            ];
        $formattedFellow = [
            "id" => "AND/F/007",
            "firstName" => "Gregory",
            "lastName" => "Webster",
            "level" => "D0B",            
            "status" => "ltWk5OffTrack",
            "quantity" => "1",
            "quality" => "2",
            "initiative" => "1",
            "communication" => "1",
            "professionalism" => "1",
            "integration" => "0",
        ];
        $inputArray = [$fellow, $fellow];
        $expected = [$formattedFellow, $formattedFellow];
        $actual = ProcessData::formatResponse($inputArray);
    
        $this->assertEquals($expected, $actual);
    }
}
