<?php
namespace App; 
use Illuminate\Support\Facades\Redis; 
class Fellow 
{ 
    /**
     * Add records to Redis store
     * 
     */
    public static function bulkCreate($fellows, $minutes = 60)
    {
        Redis::pipeline(function ($pipe) use ($fellows, $minutes){
            foreach ($fellows as $id=>$fellow) {
                $pipe->setex($id, $minutes * 60, json_encode($fellow)); 
            }
        });
    }


    /**
     * Get filtered records from Redis
     * @codeCoverageIgnore
     * 
     * @return array ffiltered list of fellows
     */
    public static function all($filter)
    {
        $tag = $filter ?? 'onTrack';
        $keys = Redis::keys('fellow:*' . $tag . '*');
        $fellows = [];
        foreach ($keys as $key) {
            $fellows[] = json_decode(Redis::get($key), true);
        }
        return $fellows;
    }
}
