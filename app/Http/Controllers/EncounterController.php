<?php

namespace App\Http\Controllers;

use App\Filters\MarkedIndividualFilters;
use App\Http\Resources\EncounterResource;
use App\Http\Resources\MarkedIndividualResource;
use App\Models\Encounter;
use App\Models\MarkedIndividual;
use App\Models\MediaAsset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image as InterventionImage;
use Intervention\Image\ImageManagerStatic as Image;

class EncounterController extends Controller
{
    public function index(MarkedIndividualFilters $filters): AnonymousResourceCollection
    {
        $giraffes = MarkedIndividual::query()->approved()
            ->orderBy('DATETIMELATESTSIGHTING', 'DESC')
            ->filter($filters)->with('encounters.annotations.media')
            ->paginate(10);

        return MarkedIndividualResource::collection($giraffes);
    }

    /**
     * Store Capture Data
     *
     * @param Request $request
     * @return EncounterResource
     */
    public function store(Request $request): EncounterResource
    {
        $request->validate([
            'date' => 'required|date',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,raw,heif|max:2048',
            'country' => 'required',
            'locality' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        $user = Auth::user();

        $date = Carbon::parse($request->input('date'));
        $capture = Encounter::create([
            'DAY' => $date->day,
            'HOUR' => $date->hour,
            'MONTH' => $date->month,
            'OKEXPOSEVIATAPIRLINK' => false,
            'YEAR' => $date->year,
            'SUBMITTERID' => $user->USERNAME,
            'SUBMITTERPHONE' => $user->PHONENUMBER,
            'DATEINMILLISECONDS' => strtotime($date),
            'DECIMALLATITUDE' => $request->input('latitude'),
            'DECIMALLONGITUDE' => $request->input('longitude'),
        ]);
        $counter = 0;

        collect($request->file('images'))->each(function (UploadedFile $file) use ($capture, $request, $user, &$counter) {
            // Create a directory with the new name and store images there
            while (!Storage::disk('encounters')->exists('/' . $path = rand(0, 1000000) . '/' . rand(0, 1000000))) {
                break;
            }
            $path .= '/' . Str::uuid() . '/';

            // Save thumbnail image
            $thumbnailPath = $path . Str::uuid() . "-thumb.{$file->getClientOriginalExtension()}";
            $this->saveMediaAsset(Image::make($file), $thumbnailPath, $capture, $counter, [50, 75]);
            $counter++;

            // Save mid image
            $midPath = $path . Str::uuid() . "-mid.{$file->getClientOriginalExtension()}";
            $this->saveMediaAsset(Image::make($file), $midPath, $capture, $counter, [512, 768]);
            $counter++;

            // Save master image
            $masterPath = $path . Str::uuid() . "-master.{$file->getClientOriginalExtension()}";
            $this->saveMediaAsset(Image::make($file), $masterPath, $capture, $counter, [1000, 1500]);
            $counter++;

            // Save original image
            $originalPath = $path . $file->getClientOriginalName();
            $this->saveMediaAsset(Image::make($file), $originalPath, $capture, $counter);
            $counter++;
        });

        return new EncounterResource($capture->refresh()->load('annotations.mediaAsset'));
    }

    private function saveMediaAsset(InterventionImage $image, string $path, Encounter $encounter, int $counter, ?array $size = null)
    {
        if (!is_null($size)) {
            $image = $image->resize($size[0], $size[1]);
        }
        Storage::disk('encounters')->put($path, (string) $image->encode());

        $asset = MediaAsset::query()->create([
            'HASHCODE' => '/opt/tomca' . Str::lower(Str::random(64)),
            'STORE_ID_OID' => 2,
            'REVISION' => strtotime(now()) * 1000,
            'PARAMETERS' => [
                'path' => $path,
            ],
        ]);

        $annotation = $asset->annotation()->create([
            'ISEXEMPLAR' => false,
            'THETA' => 0,
            'WIDTH' => 0,
            'HEIGHT' => 0,
            'MATCHAGAINST' => false,
            'IDENTIFICATIONSTATUS' => 'pending',
            'X' => 0,
            'Y' => 0,
        ]);

        $encounter->annotations()->attach($annotation->ID, [
            'IDX' => $counter,
        ]);
    }

    public function show($individual_id): MarkedIndividualResource
    {
        $giraffe = MarkedIndividual::where('INDIVIDUALID', $individual_id)->with('encounters.annotations.media')->firstOrFail();

        return new MarkedIndividualResource($giraffe);
    }

    public function map(MarkedIndividual $individual): Factory|View
    {
        return view('map', [
            'coords' => $individual->encounters->filter(fn (Encounter $encounter) => !is_null($encounter->DECIMALLATITUDE) && !is_null($encounter->DECIMALLONGITUDE))
                ->map(fn (Encounter $encounter) => [$encounter->DECIMALLONGITUDE, $encounter->DECIMALLATITUDE]),
        ]);
    }
}
