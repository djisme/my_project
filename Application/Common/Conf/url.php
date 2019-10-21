<?php 
return array (
  'URL_MODEL' => 0,
  'URL_HTML_SUFFIX' => '.html',
  'URL_PATHINFO_DEPR' => '/',
  'URL_ROUTER_ON' => true,
  'URL_ROUTE_RULES' => 
  array (
    '/^jobfair\/(?!admin)(\w+)$/' => 'jobfair/index/:1',
    '/^mall\/(?!admin)(\w+)$/' => 'mall/index/:1',
  ),
  'SESSION_OPTIONS' => 
  array (
    'path' => './data/session/',
  ),
  'COOKIE_PATH' => '/',
  'QSCMS_VERSION' => '4.2.111',
  'QSCMS_RELEASE' => '2018-03-22 15:00',
);