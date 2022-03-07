<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EncounterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request,$path
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'catalogNo' => $this->CATALOGNUMBER,
            'age' => $this->AGE,
            'day' => $this->DAY,
            'annotations' => AnnotationResource::collection($this->whenLoaded('annotations')),
            'approved' => $this->STATE === 'approved',
            'genus' => $this->GENUS,
            'guid' => $this->GUID,
            'hour' => $this->HOUR,
            'livingStatus' => $this->LIVINGSTATUS,
            'locationId' => $this->LOCATIONID,
            'modified' => $this->MODIFIED,
            'month' => $this->MONTH,
            'size' => $this->SIZE_GUESS,
            'species' => $this->SPECIFICEPITHET,
            'state' => $this->STATE,
            'locality' => $this->VERBATIMLOCALITY,
            'year' => $this->YEAR,
            'sex' => $this->SEX ?? 'unknown',
            'behavior' => $this->BEHAVIOR,
            'bodyCondition' => $this->BODYCONDITION,
            'lifeStage' => $this->LIFESTAGE,
            'groupRole' => $this->GROUPROLE,
            'latitude' => $this->DECIMALLATITUDE,
            'longitude' => $this->DECIMALLONGITUDE,
            'distinguishingScar' => $this->DISTINGUISHINGSCAR,
            'comments' => $this->RESEARCHERCOMMENTS,
            'occurrences' => OccurrenceResource::collection($this->whenLoaded('occurrences')),
        ];
    }
}
