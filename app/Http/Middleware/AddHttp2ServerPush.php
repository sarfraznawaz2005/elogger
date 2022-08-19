<?php

// https://github.com/JacobBennett/laravel-HTTP2ServerPush

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class AddHttp2ServerPush
{
    /**
     * The DomCrawler instance
     *
     * @var Crawler
     *
     */
    private $crawler;

    private const IMAGE = 'image';
    private const LINK = 'Link';

    /**
     * @var array<string, string>
     */
    private const LINK_TYPE_MAP = [
        '.CSS' => 'style',
        '.JS' => 'script',
        '.BMP' => self::IMAGE,
        '.GIF' => self::IMAGE,
        '.JPG' => self::IMAGE,
        '.JPEG' => self::IMAGE,
        '.PNG' => self::IMAGE,
        '.SVG' => self::IMAGE,
        '.TIFF' => self::IMAGE,
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $limit
     * @param null $sizeLimit
     * @param null $excludeKeywords
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $limit = null, $sizeLimit = null, $excludeKeywords = null): mixed
    {
        $response = $next($request);

        if (
            $response instanceof BinaryFileResponse ||
            $response instanceof JsonResponse ||
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

        if ($response instanceof Response) {
            $this->generateAndAttachLinkHeaders($response, $limit, $sizeLimit, $excludeKeywords);
        }

        return $response;
    }

    public function getConfig($key, $default = false)
    {
        if (!function_exists('config')) { // for tests..
            return $default;
        }

        return config('http2serverpush.' . $key, $default);
    }

    /**
     * @param Response $response
     * @param null $limit
     * @param null $sizeLimit
     * @param null $excludeKeywords
     * @return void
     */
    private function generateAndAttachLinkHeaders(Response $response, $limit = null, $sizeLimit = null, $excludeKeywords = null): void
    {
        $excludeKeywords = $excludeKeywords ?? $this->getConfig('exclude_keywords', []);

        $headers = $this->fetchLinkableNodes($response)
            ->flatten(1)
            ->map(function ($url) {
                return $this->buildLinkHeaderString($url);
            })
            ->unique()
            ->filter(function ($value) use ($excludeKeywords) {

                if (!$value) {
                    return false;
                }

                $exclude_keywords = collect($excludeKeywords)->map(function ($keyword) {
                    /* @noinspection ALL */
                    return preg_quote($keyword);
                });

                if ($exclude_keywords->count() <= 0) {
                    return true;
                }

                return !preg_match('%(' . $exclude_keywords->implode('|') . ')%i', $value);
            })
            ->take($limit);

        $sizeLimit = $sizeLimit ?? max(1, (int)$this->getConfig('size_limit', 32 * 1024));
        $headersText = trim($headers->implode(','));

        while (strlen($headersText) > $sizeLimit) {
            $headers->pop();
            $headersText = trim($headers->implode(','));
        }

        if (!empty($headersText)) {
            $this->addLinkHeader($response, $headersText);
        }

    }

    /**
     * Get the DomCrawler instance.
     *
     * @param Response $response
     *
     * @return Crawler
     */
    private function getCrawler(Response $response): Crawler
    {
        if ($this->crawler) {
            return $this->crawler;
        }

        return $this->crawler = new Crawler($response->getContent());
    }

    /**
     * Get all nodes we are interested in pushing.
     *
     * @param Response $response
     *
     * @return Collection
     */
    private function fetchLinkableNodes(Response $response): Collection
    {
        $crawler = $this->getCrawler($response);

        return collect($crawler->filter('link:not([rel*="icon"]), script[src], img[src], object[data]')->extract(['src', 'href', 'data']));
    }

    /**
     * Build out header string based on asset extension.
     *
     * @param string $url
     *
     * @return string|null
     */
    private function buildLinkHeaderString(string $url): ?string
    {
        $type = collect(self::LINK_TYPE_MAP)->first(function ($type, $extension) use ($url) {
            return Str::contains(strtoupper($url), $extension);
        });

        if ($url && !$type) {
            $type = 'fetch';
        }

        if (!preg_match('%^(https?:)?//%i', $url)) {
            $basePath = $this->getConfig('base_path', '/');
            $url = $basePath . ltrim($url, $basePath);
        }

        return is_null($type) ? null : "<$url> crossOrigin; rel=preload; as=$type";
    }

    /**
     * Add Link Header
     *
     * @param Response $response
     *
     * @param $link
     */
    private function addLinkHeader(Response $response, $link): void
    {
        if ($response->headers->get(self::LINK)) {
            $link = $response->headers->get(self::LINK) . ',' . $link;
        }

        $response->header(self::LINK, $link);
    }
}
