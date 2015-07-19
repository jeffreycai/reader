<?php
class Crawler {
  private $cookie_path;
  private $agent;
  private $header;
  
  private $use_tor;
  
  public function __construct() {
    // default values
    $this->agent = 'Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0';
    $this->use_tor = false;
    $this->header = array();
    $this->cookie_path = null;
  }
  
  public function setCookiePath($path) {
    // create file if not exist
    if (!is_file($path)) {
      $f = fopen($path, 'w');
      fclose($f);
      chmod($path, 0666);
    }
    $this->cookie_path = $path;
  }
  
  public function getCookiePath() {
    return $this->cookie_path;
  }
  
  public function removeCookie() {
    if (is_file($this->cookie_path)) {
      unlink($this->cookie_path);
    }
  }
  
  public function clearCookie() {
    // empty cookie file
    if (is_file($this->cookie_path)) {
      $f = fopen($this->cookie_path, 'w');
      fclose($f);
    }
  }
  
  public function setUserAgent($agent) {
    $this->agent = $agent;
  }
  
  public function getUserAgent() {
    return $this->agent;
  }
  
  public function setUseTor() {
    $this->use_tor = true;
  }
  
  public function setMultipart() {
    $this->setHeaderItem("Content-Type", "multipart/form-data");
  }
  
  public function setReferer($url) {
    $this->setHeaderItem("Referer", $url);
  }
  
  public function unSetUseTor() {
    $this->use_tor = false;
  }
  
  public function setHeader(Array $header) {
    $this->header = $header;
  }
  
  public function unsetHeader() {
    unset($this->header);
  }
  
  public function setHeaderItem($key, $value) {
    if (!is_array($this->header)) {
      $this->header = array();
    }
    $this->header[$key] = $value;
  }
  
  public function unsetHeaderItem($key) {
    unset($this->header[$key]);
  }
  
  public function getHeader() {
    if (isset($this->header)) {
      $headers = array();
      foreach ($this->header as $key => $value) {
        $headers[] = "$key: $value";
      }
      return $headers;
    }
    return null;
  }
  
  public function read($url) {

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // whether to print out or not when curl_exec();
    curl_setopt($ch, CURLOPT_HEADER, 0); // whether to include HEADER in output
    curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
    // set cookie if passed
    if ($this->cookie_path) {
      curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_path);
      curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_path); // where to put cookie after curl_close()
    }
    // set to use tor if passed
    if ($this->use_tor) {
      curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1:9050");
      curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    }
    // use given headers if passed
    $header = $this->getHeader();
    if ($header) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
  
  public function post($url, $post_data, $multipart = false) {
    
    if (is_array($post_data) && !$multipart) {
      $tokens = array();
      foreach ($post_data as $key => $val) {
        $tokens[] = urlencode($key) . "=" . urlencode($val);
      }
      $post_data = implode("&", $tokens);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // whether to print out or not when curl_exec();
    curl_setopt($ch, CURLOPT_HEADER, 0); // whether to include HEADER in output
    if ($this->cookie_path) {
      curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie_path);
      curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie_path); // where to put cookie after curl_close()
    }
    curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Follow redirect or not
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Max redirects to follow. Use it along with CURLOPT_FOLLOWLOCATION
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    // use given headers if passed
    $header = $this->getHeader();
    if ($header) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    // set to use tor if passed
    if ($this->use_tor) {
      curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1:9050");
      curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    }
    
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }
}