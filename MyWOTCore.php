<?php
/**
 * @author Mikael Kundert
 * @copyright Copyright (c) 2011, Mikael Kundert
 */
 
class MyWOTCore {
  
  const API_HOST    = 'api.mywot.com';
  const API_VERSION = '0.4';
  
  static $_host = array();
  
  /**
   * Fetches the reputation and confidence of any component. This method check
   * which of hosts are already queried and which are not and returns results
   * of given hosts.
   * 
   * @param array $hosts List of hosts you wish to fetch.
   * @throws Exception When wrong variable type is given.
   * @return array Reputation and confidence results of given hosts.
   */
  public function fetch($hosts) {
    
    // When fetching just one host, we ensure variable to be array
    if (is_string($hosts)) {
      $hosts = array($hosts);
    }
    
    // Now ensure it's an array, no game if not!
    if (!is_array($hosts)) {
      throw new Exception('Wrong variable type given for hosts.');
    }
    
    // Find out which of these are already fetched
    $return = array();
    $unqueried_hosts = array();
    foreach ($hosts as $host) {
      $host = strtolower($host);
      if (isset($this->_host[$host])) {
        $return[$host] = $this->_host[$host];
      }
      else {
        $unqueried_hosts[] = $host;
      }
    }
    
    // It's actually possible that there is no hosts to be queried
    if (count($unqueried_hosts) > 0) {
      $params = array('hosts' => implode('/', $unqueried_hosts) . '/');
      $query = $this->_query($params);
      foreach ($this->_parseQuery($query) as $host => $info) {
        $return[$host] = $info;
      }
    }
    
    return $return;
    
  }
  
  /**
   * Parses the query and puts response into static variable so it doesn't
   * need to be queried next time.
   * 
   * @param object JSON decoded object by _query() method.
   * @throws Exception When wrong variable type is given.
   * @return array Structured array of parsed hosts.
   */
  private function _parseQuery($response) {
    
    // Ensure variable type
    if (!is_object($response)) throw new Exception('Wrong variable type given for response.');
    
    // Loop thru
    $return = array();
    foreach ($response as $host => $info) {
      $this->_host[strtolower($host)] = $info;
      $return[strtolower($host)] = $this->_host[strtolower($host)];
    }
    
    return $return;
    
  }
  
  /**
   * Used for do the actual query to the service. Query results will be parsed
   * by other method.
   * 
   * @param array Parameters for query. See WOT API documentation.
   * @throws Exception When query fails.
   * @return object JSON decoded response from the service.
   * @link http://www.mywot.com/wiki/API
   */
  private function _query($params) {
    
    // Find out the URL we're going to query
    $url = $this->_query_url($params);
    
    // Query
    // @todo: We should check HTTP response code and react based on that too
    // @todo: We should probably check and make sure it's JSON response
    if (!($response = file_get_contents($url))) {
      throw new Exception('Could not query.');
    }
    
    return json_decode($response);
  }
  
  /**
   * Used for building the URL address from given parameters.
   * 
   * @param array Parameters for query. See WOT API documentation.
   * @return string URL address for querying.
   * @link http://www.mywot.com/wiki/API
   */
  private function _query_url($params) {
    
    // Determine URL parts
    $url = array(
      'scheme' => 'http',
      'host' => MyWOT::API_HOST,
      'path' => MyWOT::API_VERSION . '/public_link_json',
      'query' => http_build_query($params),
    );
    
    // Return a string to query
    return $url['scheme'] . '://' . $url['host'] . '/' . $url['path'] . '?' . $url['query'];
  }
  
}
