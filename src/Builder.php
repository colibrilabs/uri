<?php

namespace Colibri\URI;

use Colibri\Http\Request;

/**
 * Class Builder
 * @package Colibri\URI
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
  protected $staticPath = '/';
  
  /**
   * @var string
   */
  protected $basePath = '/';
  
  /**
   * Builder constructor.
   * @param Request $request
   * @param string $base
   * @param string $static
   */
  public function __construct(Request $request, $base = '/', $static = '/')
  {
    $this->request = $request;
  }

  /**
   * @return string
   */
  public function getStaticPath()
  {
    return $this->staticPath;
  }
  
  /**
   * @param string $staticPath
   * @return $this
   */
  public function setStaticPath($staticPath)
  {
    $this->staticPath = $staticPath;
    
    return $this;
  }
  
  /**
   * @return string
   */
  public function getBasePath()
  {
    return $this->basePath;
  }
  
  /**
   * @param string $basePath
   * @return $this
   */
  public function setBasePath($basePath)
  {
    $this->basePath = $basePath;
    
    return $this;
  }
  
  /**
   * @throws UriException
   */
  public function create()
  {
    throw new UriException(
      'URL generator not implemented here. Please install package colibriphp/url-generator ' .
      'and use class Colibri\\UrlGenerator\\UrlBuilder instead'
    );
  }
  
  /**
   * @param string $path
   * @param array $query
   * @return string
   */
  public function path($path = '', array $query = [])
  {
    $path = ltrim($path, '/');
    $basepath = rtrim($this->getBasePath(), '/');
    
    return (new Parser("$basepath/$path"))->setQueryArray($query)->local();
  }
  
  /**
   * @param string $path
   * @param array $query
   * @return string
   */
  public function staticPath($path = '', array $query = [])
  {
    $path = ltrim($path, '/');
    $staticpath = rtrim($this->getStaticPath(), '/');
    
    return (new Parser("$staticpath/$path"))->local();
  }
  
  /**
   * @param string $path
   * @param array $query
   * @param null|string $fragment
   * @return string
   */
  public function full($path = '', array $query = [], $fragment = null)
  {
    $path = ltrim($path, '/');
    $basepath = rtrim($this->getBasePath(), '/');
    
    $uri = (new Parser("$basepath/$path"))
      ->setSchema($this->request->getSchema())
      ->setHost($this->request->getServerHttp('host'))
      ->setQueryArray($query);
    
    if ($fragment !== null) {
      $uri->setFragment(ltrim($fragment, '#'));
    }
    
    return $uri->full();
  }
  
}
