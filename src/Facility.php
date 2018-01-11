<?php

namespace UW\SpaceWS;

/**
 * Container class for data received from Space Web Service
 *
 * @package UW\Facility
 */
class Facility extends Space
{

    /**
     * Queries SpaceWS to populate Facility, given the Facility Number
     *
     * @return Facility
     */
    public function getFullData()
    {
        $resp = static::getConnection()->execGET("facility/{$this->attributes['FacilityNumber']}/restricted.json");

        $data = json_decode($resp->getData(), true);

        $this->fill($this, $data);
        $this->fill($this, $data['Addresses'][0]);

        return $this;
    }

    /**
     * Create a Facility from a Facility Code.
     *
     * @param string $facilityCode
     * @return null|Facility
     */
    public static function fromFacilityCode($facilityCode)
    {
        $result = static::search("facility_code=$facilityCode");

        if (count($result) != 1) {
            throw new \Exception("Not exactly one facility found for $facilityCode");
        }

        return $result[0];
    }

}
