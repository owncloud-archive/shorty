<?php
/**
* ownCloud shorty plugin, a URL shortener
*
* @author Christian Reiner
* @copyright 2011-2012 Christian Reiner <foss@christian-reiner.info>
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the license, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.
* If not, see <http://www.gnu.org/licenses/>.
*
*/

/**
 * @brief Routines to retrieve info about a remote url
 */
 
class OC_Shorty_Meta
{

  static function fetchMetaData ( $url )
  {
    $token = parse_url ( $url );
    // some sane fallback values, in case we cannot get the meta data
    $meta = array();
    $meta['target']    = $url;
    $meta['title']     = strtolower ( $token['host'] );
    $meta['scheme']    = strtolower ( $token['scheme'] );
    $meta['mimetype']  = 'application/octet-stream';
    $meta['schemicon'] = self::selectIcon ( 'scheme', strtolower($token['scheme']) );
    // we wont bother retrieving data about other protocols than http or ftp
    if ( ! in_array(strtolower($token['scheme']),array('http','https','ftp','ftps')) )
      return $meta;
    // to fetch meta data we rely on curl being installed
    if ( ! function_exists('curl_init') )
      return $meta;
    // try to retrieve the meta data
    $handle = curl_init ( );
    curl_setopt ( $handle, CURLOPT_URL, $url );
    curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $handle, CURLOPT_FOLLOWLOCATION, TRUE );
    curl_setopt ( $handle, CURLOPT_MAXREDIRS, 10 );
    if ( FALSE!==($page=curl_exec($handle)) )
    {
      // try to extract title from page
      preg_match ( "/<head>.*<title>(.*)<\/title>.*<\/head>/si", $page, $match );
      $meta['title']    = htmlspecialchars_decode ( $match[1] );
      $meta['staticon'] = self::selectIcon ( 'state', TRUE );
      // try to extract favicon from page
      preg_match ( '/<[^>]*link[^>]*(rel="icon"|rel="shortcut icon") .*href="([^>]*)".*>/iU', $page, $match );
      if (1<sizeof($match))
        $meta['favicon']     = htmlspecialchars_decode ( $match[2] );
      $meta['final']       = curl_getinfo ( $handle, CURLINFO_EFFECTIVE_URL );
      $meta['mimetype']    = preg_filter ( '/^([^;]+)$/i', '$0', curl_getinfo($handle,CURLINFO_CONTENT_TYPE) );
      $meta['mimicon']     = self::selectIcon ( 'mimetype', $meta['mimetype'] );
      $meta['code']        = curl_getinfo ( $handle, CURLINFO_HTTP_CODE );
      $meta['status']      = OC_Shorty_L10n::t ( self::selectCode('status',$meta['code']) );
      $meta['explanation'] = OC_Shorty_L10n::t ( self::selectCode('explanation',$meta['code']) );
    }
    curl_close ( $handle );
    // that's it !
    return $meta;
  } // function fetchMetaData

  static function selectCode ( $aspect, $identifier )
  {
    $_code_map = array
    (
      'status' => array
      (
        200 => 'Ok',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
      ),
      'explanation' => array
      (
        200 => 'Target url is valid and resolved.',
        201 => 'The request has been fulfilled and created a new ressource.',
        202 => 'The request has been accepted.',
        203 => 'The request yielded in non-authorative information.',
        204 => 'The request has been fulfilled but not produced any content.',
        205 => 'The request has been fulfilled and the view should be reset.',
        206 => 'The request has been fulfilled partially.',
      )
    );
    if ( key_exists($aspect,$_code_map) && key_exists($identifier,$_code_map[$aspect]) )
      return $_code_map[$aspect][$identifier];
    else
    {
      switch ( $aspect )
      {
        case 'status':      return '[Unknown Status]';
        case 'explanation': return '';
        default:            return '';
      } // switch
    }
  } // function selectCode

  static function selectIcon ( $aspect, $identifier )
  {
    switch ( $aspect )
    {
      case 'state':
        switch ($identifier)
        {
          case TRUE:  return OC_Helper::imagePath('shorty', 'status/good.png');
          case FALSE: return OC_Helper::imagePath('shorty', 'status/bad.png');
          default:    return OC_Helper::imagePath('shorty', 'status/neutral.png');
        } // switch identifier
      case 'scheme':
        switch ($identifier)
        {
          case 'http':
          case 'https':   return OC_Helper::imagePath('shorty', 'scheme/H.png');
          case 'ftp':
          case 'ftps':    return OC_Helper::imagePath('shorty', 'scheme/F.png');
          case 'sftp':    return OC_Helper::imagePath('shorty', 'scheme/S.png');
          case 'mailto':  return OC_Helper::imagePath('shorty', 'scheme/M.png');
          case 'gopher':  return OC_Helper::imagePath('shorty', 'scheme/G.png');
          case 'webdav':
          case 'webdavs': return OC_Helper::imagePath('shorty', 'scheme/W.png');
          default:        return OC_Helper::imagePath('shorty', 'blank.png');
        } // switch identifier
      case 'mimetype':
        $identifier = explode('/',$identifier);
        switch ($identifier[0])
        {
          case 'audio':       return OC_Helper::imagePath('core', 'filetypes/audio.png');
          case 'text':        return OC_Helper::imagePath('core', 'filetypes/text.png');
          case 'video':       return OC_Helper::imagePath('core', 'filetypes/video.png');
          case 'application':
            switch ($identifier[1])
            {
              case 'pdf':     return OC_Helper::imagePath('core', 'filetypes/application-pdf.png');
              default:        return OC_Helper::imagePath('shorty', 'blank.png');
            } // switch identifier[1]
          default:            return OC_Helper::imagePath('shorty', 'blank.png');
        } // switch identifier[0]
    } // switch aspect
  } // function selectIcon

} // class OC_Shorty_Meta
?>
