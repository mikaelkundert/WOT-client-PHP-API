<?php
/**
 * @author Mikael Kundert
 * @copyright Copyright (c) 2011, Mikael Kundert
 */
 
class MyWOTClient extends MyWOTCore {
  
  const COMPONENT_ALL                 = NULL;
  const COMPONENT_TRUSTWORTHINESS     = 0;
  const COMPONENT_VENDOR_RELIABILITY  = 1;
  const COMPONENT_PRIVACY             = 2;
  const COMPONENT_CHILD_SAFETY        = 3;
  
  private $_defaultComponent = MyWOTCore::COMPONENT_ALL;
  
  // @todo: getAll($component)
  // @todo: getReputation($host, $component)
  // @todo: getConfidence($host, $component)
  
}
