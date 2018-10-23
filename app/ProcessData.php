<?php

namespace App;


class ProcessData 
{
    public static $sharedValueKeys = ['id', 'fullName', 'firstName', 'lastName', 'email', 'partnerName',
            'partnerId', 'cohort', 'location'];
    public static $otherCriteriaKeys = ['eventDate', 'eventNumber', 'staffId', 'submitterFirstName',
    'submitterLastName', 'submitterEmail', 'submitterRole'];
    
    public static $ratingKeys = ['quality', 'quantity' , 'initiative' , 'communication' ,'professionalism' , 'integration'];
    
    /**
     * Group input array by week and fellow rating.
     * @param array fellows data aggregated by fellow in an indexed array
     * @return array associtive array with key from combination of category and fellow id
     */
     public static function splitByWeek5AndRating($fellowArray){
        $splitArray = [];
        $categoryArray = ['ltWk5OffTrack'=> 0, 'gteWk5OffTrack'=> 0, 'onTrack'=> 0];
        
        foreach($fellowArray as $fellow){
            $isBeforeWk5 = count($fellow['ratings']) < 5;
            $isOffTrack = collect($fellow['averageRatings'])->contains(function ($value, $key) {
                return $value < 1;
            });
            if ($isBeforeWk5 && $isOffTrack){
                    $fellow['status'] = 'ltWk5OffTrack';
                    $splitArray['fellow:ltWk5OffTrack-'. $fellow['id']] = $fellow; 
                    $categoryArray['ltWk5OffTrack']++;
            } elseif(!$isBeforeWk5 && $isOffTrack) {
                    $fellow['status'] = 'gteWk5OffTrack';                                      
                    $splitArray['fellow:gteWk5OffTrack-'. $fellow['id']] = $fellow; 
                    $categoryArray['gteWk5OffTrack']++;                    
            } else {
                $fellow['status'] = 'onTrack';                                        
                $splitArray['fellow:onTrack-'. $fellow['id']] = $fellow; 
                $categoryArray['onTrack']++;  
            }
        }
        
        return [$splitArray, $categoryArray];
    }

    /**
     * create average for every key in input array
     * 
     * @param array  array of arrays
     * 
     * @return array fellows data array with key for averageRatings
     */
    public static function getAverageRatings($ratingsArray, $keys){
        $averageRatings = [];
        foreach($keys as $key){
            $averageRatings[$key] = number_format(collect($ratingsArray)->avg($key), 1, '.', '');            
        }
        return $averageRatings;
    }

    /**
     * process array before returning to client
     * 
     * @param array data from Redis store
     * 
     * @return array data with selected keys exytracted
     */
    public static function formatResponse($fellowArray){
        $formattedResponse = [];
        $keys = ['id', 'firstName', 'lastName', 'level', 'status'];
        foreach($fellowArray as $fellow){
            $formattedResponse[] = collect($fellow)->only($keys)->merge($fellow['averageRatings'])->all();
        }
        return $formattedResponse;
    }

    /**
     * process array returned by Google Client
     * 
     * @param array data fetched from spreadsheet
     * 
     * @return array data from fellow aggregated into array row
     */
    public static function transformSheet($fellowArray){
        $transformedFellowArray = [];
        foreach($fellowArray as $fellowRow){
            $fellowId = $fellowRow['id'];
            if(!array_key_exists($fellowId, $transformedFellowArray)){
                $transformedFellowArray[$fellowId] = [];
            }
            foreach(ProcessData::$sharedValueKeys as $key){
                if (!array_key_exists($key, $transformedFellowArray[$fellowId])){
                    $transformedFellowArray[$fellowId][$key] = $fellowRow[$key];
                }
            }
            if (!array_key_exists('level', $transformedFellowArray[$fellowId])){
                $levelString = $fellowRow['level'] == 'D0A' ? ' Simulations': ' Apprenticeship';
                $transformedFellowArray[$fellowId]['level'] = $fellowRow['level'] . $levelString;
            }
            
            $transformedFellowArray[$fellowId]["ratings"][$fellowRow['week']] = collect($fellowRow)->only(ProcessData::$ratingKeys)->all();
           
            $transformedFellowArray[$fellowId]["otherCriteria"][$fellowRow['week']] = collect($fellowRow)->only(ProcessData::$otherCriteriaKeys)->all();

            $transformedFellowArray[$fellowId]["averageRatings"] 
            = ProcessData::getAverageRatings(
                $transformedFellowArray[$fellowId]["ratings"], ProcessData::$ratingKeys 
            );   
        }
        return $transformedFellowArray;
    }


    /**
     * transform indexed array to associative array
     * 
     * @param array indexed array
     * 
     * @return array associative array with required keys
     */
    public static function addHeader($fellowArray){
        $header = [
            'eventDate', 'eventNumber', 'staffId', 'submitterFirstName',
            'submitterLastName', 'submitterEmail', 'submitterRole', 'id',
            'fullName', 'firstName', 'lastName', 'email', 'partnerName',
            'partnerId', 'level', 'week', 'cohort', 'location', 'quantity',
            'quality', 'initiative', 'communication', 'professionalism',
            'integration'
        ];

        $result = array_map(function ($row) use($header){
            return array_combine($header, $row);
            }, $fellowArray);
        return $result;
    }
    
}
