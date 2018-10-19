<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Fellow;
use App\SheetsService;
use App\ProcessData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;


class FellowController extends Controller
{
    protected $expiryMinutes = 60;
    protected $sheetsService;

    public function __construct(SheetsService $sheetsService)
    {
        $this->sheetsService = $sheetsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param object Eloquent request object
     * @return array array of fellow values
     */
    public function index(Request $request)
    {   $perPage = $request->query('perPage') ?? 10;
        $filter = $request->query('filter') ?? 'onTrack';
        $fellowsFiltered = Fellow::all($filter);
        $fellowCountPerCategoryAll = [];
        if (empty($fellowsFiltered)) {
        
            $values =  $this->sheetsService->getSheetData();
            if (empty($values)) {
               abort(404);
            } 
            
            $result = ProcessData::addHeader($values);
            $transformedResult = ProcessData::transformSheet($result);
            [$fellows, $fellowCountPerCategoryAll] = ProcessData::splitByWeek5AndRating($transformedResult);

            Fellow::bulkCreate($fellows, $this->expiryMinutes);

            $fellowsFiltered = collect($fellows)->filter(function ($value, $key) use($filter){
                return strstr($key, $filter);});       
        }
        
        $encodedCountAll = Cache::remember('fellowCountPerCategoryAll',
         $this->expiryMinutes, function() use($fellowCountPerCategoryAll) {
            return json_encode($fellowCountPerCategoryAll);
        });
        $decodedCount = json_decode($encodedCountAll, true);
        $formattedResponse = ProcessData::formatResponse($fellowsFiltered);
        
        return response()->json(array_merge(['summary'=>$decodedCount], ['filter'=>$filter],
            collect($formattedResponse)->sortBy('id')->paginate($perPage)), 200); 
    }   
}
