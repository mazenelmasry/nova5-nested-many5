<?php

namespace Lupennat\NestedMany\Fields;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ResourceRelationshipGuesser;
use Stringable;

/**
 * @method static static make(mixed $name, string|null $attribute = null, string|null $resource = null)
 */
class HasManyNested extends Nested
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'has-many-nested-field';

    /**
     * The class name of the related resource.
     *
     * @var class-string<\Laravel\Nova\Resource>
     */
    public $resourceClass;

    /**
     * The URI key of the related resource.
     *
     * @var string
     */
    public $resourceName;

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var (callable():(bool))|bool
     */
    public $showOnIndex = false;

    /**
     * The name of the Eloquent "has many" relationship.
     *
     * @var string
     */
    public $hasManyRelationship;

    /**
     * The displayable singular label of the relation.
     *
     * @var string|null
     */
    public $singularLabel;

    /**
     * Create a new field.
     *
     * @param \Stringable|string                        $name
     * @param string|null                               $attribute
     * @param class-string<\Laravel\Nova\Resource>|null $resource
     *
     * @return void
     */
    public function __construct($name, ?string $attribute = null, ?string $resource = null)
    {
        parent::__construct($name, $attribute);

        $resource = $resource ?? ResourceRelationshipGuesser::guessResource($name);

        $this->resourceClass = $resource;
        $this->resourceName = $resource::uriKey();
        $this->hasManyRelationship = $this->attribute = $attribute ?? ResourceRelationshipGuesser::guessRelation($name);
        $this->validateNestableModel();
    }

    /**
     * Get the relationship name.
     *
     * @return string
     */
    public function relationshipName(): string
    {
        return $this->hasManyRelationship;
    }

    /**
     * Get the relationship type.
     *
     * @return string
     */
    public function relationshipType(): string
    {
        return 'hasMany';
    }

    /**
     * Determine if the field should be displayed for the given request.
     *
     * @return bool
     */
    #[\Override]
    public function authorize(Request $request)
    {
        return call_user_func(
            [$this->resourceClass, 'authorizedToViewAny'],
            $request
        ) && parent::authorize($request);
    }

    /**
     * Resolve the field's value.
     *
     * @param mixed       $resource
     * @param string|null $attribute
     *
     * @return void
     */
    #[\Override]
    public function resolve($resource, ?string $attribute = null): void
    {
    }

    /**
     * Set the displayable singular label of the resource.
     *
     * @param \Stringable|string $singularLabel
     *
     * @return $this
     */
    public function singularLabel(Stringable|string $singularLabel)
    {
        $this->singularLabel = $singularLabel;

        return $this;
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return array_merge([
            'collapsable' => $this->collapsable,
            'collapsedByDefault' => $this->collapsedByDefault,
            'hasManyRelationship' => $this->hasManyRelationship,
            'relatable' => true,
            'resourceName' => $this->resourceName,
            'resourceClass' => $this->resourceClass,
            'singularLabel' => $this->singularLabel ?? $this->resourceClass::singularLabel(),
        ], parent::jsonSerialize());
    }
}
