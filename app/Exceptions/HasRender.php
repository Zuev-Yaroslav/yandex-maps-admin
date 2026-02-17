<?php

namespace App\Exceptions;

use Illuminate\Http\Request;

trait HasRender
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        $errors = [
            'message' => $this->getMessage(),
        ];

        return ($request->acceptsJson())
            ? response()->json(['errors' => $errors], $this->getCode())
            : inertia('admin/Error', compact('errors'));
    }
}
