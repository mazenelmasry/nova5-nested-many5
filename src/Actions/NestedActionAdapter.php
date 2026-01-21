<?php

namespace Lupennat\NestedMany\Actions;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Lupennat\NestedMany\Http\Requests\NestedActionRequest;

class NestedActionAdapter extends Action
{
    /**
     * Create a new adapter instance.
     */
    public function __construct(protected NestedBaseAction $action)
    {
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return $this->action->fields($request);
    }

    /**
     * Validate the given request.
     *
     * @return array<string, mixed>
     */
    public function validateFields(ActionRequest $request): array
    {
        return $this->action->validateFields($this->asNestedRequest($request));
    }

    /**
     * Execute the action for the given request.
     *
     * @return mixed
     */
    public function handleRequest(ActionRequest $request)
    {
        return $this->action->handleRequest($this->asNestedRequest($request));
    }

    protected function asNestedRequest(ActionRequest $request): NestedActionRequest
    {
        return $request instanceof NestedActionRequest
            ? $request
            : NestedActionRequest::createFrom($request);
    }
}
