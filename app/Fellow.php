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
        Redis::multi();
        foreach ($fellows as $id=>$fellow) {
            Redis::setex($id, $minutes * 60, json_encode($fellow)); 
        }
        Redis::exec();
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
