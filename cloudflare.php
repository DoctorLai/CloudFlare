<?php
    /* CloudFlare Access Tokens */
  $CLOUDFLARE_KEY = 'YOUR_CLOUDFLARE_APP_KEY';
  $CLOUDFLARE_EMAIL = 'YOUR_CLOUDFLARE_EMAIL';
  
  function CloudFlare_GetZones($key, $email, $page = 1, $per_page = 100) {
    $cmd = 'curl -s -X GET "https://api.cloudflare.com/client/v4/zones?'.
          'status=active&page=$page&per_page=$per_page&order=status&direction=desc&match=all" -H "X-Auth-Email: '.$email.
          '" -H "X-Auth-Key: '.$key.
          '" -H "Content-Type: application/json"';
    return json_decode(shell_exec($cmd));
  }  

  $CLOUDFLARE = CloudFlare_GetZones($CLOUDFLARE_KEY, $CLOUDFLARE_EMAIL);
