<?php

namespace UW\SpaceWS;

use UWDOEM\Connection\Connection;

/**
 * Container class for data received from Space Web Service
 *
 * @package UW\Room
 */
class Room extends Space
{

    /**
     * Queries SpaceWS to populate Room, given the Facility Number, Floor Code, and Room Number
     *
     * @return Room
     */
    public function getFullData()
    {
        $identifier = join(',', array($this->attributes['FacilityNumber'],
                                      $this->attributes['FloorCode'],
                                      $this->attributes['RoomNumber']));
                                      
        $resp = static::getConnection()->execGET("room/{$identifier}.json");

        $data = json_decode($resp->getData(), true);

        $this->fill($this, $data);

        return $this;
    }

    /**
     * Create a Room from a Facility Code and Room Number.
     *
     * @param string $roomCode
     * @return null|Room
     */
    public static function fromFacilityCodeAndRoomNumber($facilityCode, $roomNumber)
    {
        $resp = static::getConnection()->execGET("room.json?facility_code=$facilityCode&room_number=$roomNumber");

        $data = json_decode($resp->getData(), true);

        if ($data['TotalCount'] != 1) {
            throw new \Exception("Not exactly one room found for $facilityCode $roomNumber");
        }

        $room = new static();
        $room->fill($room, $data['Rooms'][0]);

        return $room;
    }

}
