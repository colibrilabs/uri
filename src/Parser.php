<?php

namespace Subapp\URI;


/**
 * Class Parser
 * @package Subapp\URI
 */
class Parser
{
    
    /**
     * @var string
     * @url https://ru.wikipedia.org/wiki/URI
     */
    protected $pattern = '~^(([^:\/?#]+):)?(\/\/([^\/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?~ui';
    
    /**
     * @var string
     */
    protected $schema;
    
    /**
     * @var string
     */
    protected $user;
    
    /**
     * @var string
     */
    protected $password;
    
    /**
     * @var string
     */
    protected $host;
    
    /**
     * @var string
     */
    protected $port;
    
    /**
     * @var string
     */
    protected $path;
    
    /**
     * @var array
     */
    protected $query = [];
    
    /**
     * @var string
     */
    protected $fragment;
    
    /**
     * @param $uri
     */
    public function __construct($uri)
    {
        $components = parse_url($uri, -1);
        
        if (isset($components['scheme'])) {
            $this->setSchema($components['scheme']);
        }
        
        if (isset($components['host'])) {
            $this->setHost($components['host']);
        }
        
        if (isset($components['user'], $components['pass'])) {
            $this->setUser($components['user'])->setPassword($components['pass']);
        }
        
        if (isset($components['post'])) {
            $this->setPort($components['post']);
        }
        
        if (isset($components['path'])) {
            $this->setPath($components['path']);
        }
        
        if (isset($components['query'])) {
            $this->setQueryString($components['query']);
            parse_str($components['query'], $this->query);
        }
        
        if (isset($components['fragment'])) {
            $this->setFragment($components['fragment']);
        }
    }
    
    /**
     * @param array $components
     * @return string
     */
    protected function build(array $components = [])
    {
        
        $uriParts = [
            'schema' => null,
            'credentials' => null,
            'host' => null,
            'port' => null,
            'path' => null,
            'query' => null,
            'fragment' => null,
        ];
        
        if (count($components) > 0) foreach ($components as $component) {
            switch ($component) {
                case 'schema':
                    $uriParts['schema'] = !$this->getSchema() ?: "{$this->getSchema()}://";
                    break;
                case 'host':
                    $uriParts['host'] = !$this->getHost() ?: $this->getHost();
                    break;
                case 'user':
                    if ($this->getUser() && $this->getPassword()) {
                        $uriParts['credentials'] = "{$this->getUser()}:{$this->getPassword()}@";
                    } else if ($this->getUser()) {
                        $uriParts['credentials'] = "{$this->getUser()}@";
                    }
                    break;
                case 'port':
                    $uriParts['port'] = !$this->getPort() ?: ":{$this->getPort()}";
                    break;
                case 'path':
                    $uriParts['path'] = $this->getPath() ? $this->getPath() : '/';
                    break;
                case 'query':
                    $uriParts['query'] = !$this->getQuery() ?: ('?' . $this->getQueryString());
                    break;
                case 'fragment':
                    $uriParts['fragment'] = !$this->getFragment() ?: ('#' . $this->getFragment());
                    break;
                default:
                    break;
            }
        }
        
        return implode('', $uriParts);
        
    }
    
    /**
     * @return string
     */
    public function full()
    {
        return $this->build(['schema', 'user', 'host', 'port', 'path', 'query', 'fragment']);
    }
    
    /**
     * @return string
     */
    public function host()
    {
        return $this->build(['schema', 'user', 'host', 'port']);
    }
    
    /**
     * @return string
     */
    public function local()
    {
        return $this->build(['path', 'query', 'fragment']);
    }
    
    /**
     * @return mixed
     */
    public function getSchema()
    {
        return $this->schema;
    }
    
    /**
     * @param mixed $schema
     * @return $this
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @param mixed $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * @param mixed $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }
    
    /**
     * @param mixed $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }
    
    /**
     * @param mixed $port
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * @param mixed $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getQueryString()
    {
        return http_build_query($this->getQuery());
    }
    
    /**
     * @param mixed $query
     * @return $this
     */
    public function setQueryString($query)
    {
        parse_str($query, $this->query);

        return $this;
    }
    
    /**
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param array $query
     * @return $this
     */
    public function setQuery(array $query)
    {
        $this->query = $query;

        return $this;
    }
    
    /**
     * @param $name
     * @return bool
     */
    public function hasQueryParameter($name)
    {
        return isset($this->query[$name]);
    }
    
    /**
     * @param $name
     * @return null
     */
    public function getQueryParameter($name)
    {
        return $this->hasQuery($name) ? $this->query[$name] : null;
    }
    
    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function setQueryParameter($name, $value)
    {
        $this->query[$name] = $value;

        return $this;
    }
    
    /**
     * @param $name
     * @return $this
     */
    public function removeQuery($name)
    {
        if ($this->hasQuery($name)) {
            unset($name);
        }

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getFragment()
    {
        return $this->fragment;
    }
    
    /**
     * @param mixed $fragment
     * @return $this
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }
    
}