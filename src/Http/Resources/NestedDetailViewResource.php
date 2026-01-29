<?php

namespace Lupennat\NestedMany\Http\Resources;

use Laravel\Nova\Http\Resources\Resource;
use Lupennat\NestedMany\Http\Requests\NestedResourceRequest;

class NestedDetailViewResource extends Resource implements NestedResourceRequest
{
    /**
     * Transform the resource into an array.
     *
     * @param \Lupennat\NestedMany\Http\Requests\NestedResourceDetailRequest $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $resource = $request->resource();
        $resources = $request->newQuery()->get();

        if (method_exists($request, 'nestedField')) {
            $nestedField = $request->nestedField();

            if ($nestedField && is_callable($nestedField->resourcesFilter)) {
                $resources = $resources->filter(
                    fn ($model) => ($nestedField->resourcesFilter)($model, $request)
                );
            }
        }
        if (method_exists($request, 'viaResource')) {
            $viaResource = $request->viaResource();

            if ($viaResource && method_exists($viaResource, 'filterNestedResources')) {
                $resources = $viaResource::filterNestedResources(
                    $request,
                    (string) $request->viaRelationship(),
                    $resources
                );
            }
        }

        return [
            'label' => $resource::label(),
            'resources' => $resources->mapInto($resource)->map->serializeForNestedDetail($request),
        ];
    }
}
