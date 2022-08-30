<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class OptimizeMiddleware
{
    // urls to be excluded from optimization
    private const EXCEPT = [
        'user/*',
        'entries',
    ];

    private const REG_EX = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script|object|iframe)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script|object|iframe)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        /*
        if (isLocalhost()) {
            return $response;
        }
        */

        if (
            $response instanceof BinaryFileResponse ||
            $this->inExceptArray($request) ||
            $response->isRedirection() ||
            $request->isJson() ||
            $request->ajax() ||
            !$request->isMethod('GET') ||
            !$request->acceptsHtml() ||
            !Str::contains($response->headers->get('Content-Type'), 'text/html') ||
            app()->runningInConsole()
        ) {
            return $response;
        }

        $buffer = $response->getContent();

        @ini_set('pcre.recursion_limit', '16777');

        $newBuffer = preg_replace(self::REG_EX, ' ', $buffer);

        if ($newBuffer !== null) {
            $buffer = $newBuffer;
        }

        $response->setContent($buffer);

        return $response;
    }

    private function inExceptArray($request): bool
    {
        foreach (self::EXCEPT as $except) {
            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
