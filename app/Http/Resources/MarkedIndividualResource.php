<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MarkedIndividual;

class MarkedIndividualResource extends JsonResource
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = MarkedIndividual::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->INDIVIDUALID,
            'comments' => $this->COMMENTS,
            'first_identified_at' => $this->DATEFIRSTIDENTIFIED,
            'created_at' => $this->DATETIMECREATED,
            'last_seen_at' => $this->DATETIMELATESTSIGHTING,
            'genus' => $this->GENUS,
            'nickname' => $this->NICKNAME,
            'nicknamer' => $this->NICKNAMER,
            'number_of_encounters' => $this->NUMBERENCOUNTERS,
            'sex' => $this->SEX,
            'thumbnail_url' => $this->THUMBNAILURL,
            'favourite' => $this->favourite,
            'encounters' => EncounterResource::collection($this->whenLoaded('encounters')),
        ];
    }
}
