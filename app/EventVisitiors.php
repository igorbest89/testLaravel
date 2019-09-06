<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventVisitiors extends Model
{
    protected $table = 'event_visitors';

    protected $fillable = [
        'isLeader',
        'event_id',
        'customer_id',
    ];




    /**
     * @param $customerIds
     * @param $event_id
     */
    public static function setVisit($customerIds, $event_id)
    {
        $iterator = 0;
        foreach ($customerIds as $id) {
            $visit = new EventVisitiors();
            $visit->customer_id = $id;
            $visit->event_id    = $event_id;
            if ($iterator === 0) {
                $visit->isLeader = 1;
            } else {
                $visit->isLeader = 0;
            }
            $iterator++;
            $visit->save();
        }
    }


}
