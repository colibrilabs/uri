<?php

namespace Subapp\URI;

use Subapp\Http\Request;

/**
 * Class Builder
 * @package Subapp\URI
 */
class Builder
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $static = '/';

    /**
     * @var string
     */
    protected $base = '/';

    /**
     * Builder constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getStatic()
    {
        return $this->static;
    }

    /**
     * @param string $static
     * @return $this
     */
    public function setStatic($static)
    {
        $this->static = $static;

        return $this;
    }

    /**
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param string $base
     * @return $this
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * @throws UriException
     */
    public function create()
    {
        throw new UriException(
            'URL generator not implemented here. Please install package github.com/subapp/url-generator ' .
            'and use class Subapp\\UrlGenerator\\UrlBuilder instead'
        );
    }

    /**
     * @param string $path
     * @param array $query
     * @return string
     */
    public function path($path, array $query = [])
    {
        $parser = new Parser($this->toBasePath($path));
        $parser->setQuery($query);

        return $path->local();
    }

    /**
     * @param string $path
     * @return string
     */
    public function staticPath($path)
    {
        return (new Parser($this->toStaticPath($path)))->local();
    }

    /**
     * @param string $path
     * @param array $query
     * @param null|string $fragment
     * @param null|string $schema
     * @param null|string $domain
     * @return string
     */
    public function full($path = '', array $query = [], $fragment = null, $schema = null, $domain = null)
    {
        $parser = new Parser($this->toBasePath($path));
        $parser->setSchema($schema ? $schema : $this->request->getSchema());
        $parser->setHost($domain ? $domain : $this->request->getServerHttp('host'));
        $parser->setQuery($query);

        if ($fragment) {
            $parser->setFragment(ltrim($fragment, '#'));
        }

        return $parser->full();
    }

    /**
     * @param string $path
     * @return string
     */
    public function toStaticPath($path)
    {
        $path = ltrim($path, '/');
        $static = rtrim($this->getStatic(), '/');

        return "$static/$path";
    }

    /**
     * @param string $path
     * @return string
     */
    public function toBasePath($path)
    {
        $path = ltrim($path, '/');
        $base = rtrim($this->getBase(), '/');

        return "$base/$path";
    }

}
