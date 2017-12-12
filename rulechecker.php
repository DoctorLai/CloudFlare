#!/usr/bin/php
<?php   
  function cloudflare_rulechecker($url, $key, $email, $data) {
    if (!$data) {
        throw new Exception("data null");
    }
    $ok = $data->success ?? false;
    if (!$ok) {
        throw new Exception("not success" . ($data->errors ?? '') . ($data->messages ?? ''));
    }   
    $result = $data->result ?? null;
    if (!$result) {
        throw new Exception("result null");
    }  
    $rules = array();  
    $domain = parse_url($url)['host'];
    $domain = str_replace("www.", "", $domain);
    $id = '';
    foreach ($result as $tmp) {
      if ($tmp->name == $domain) {
        $id = $tmp->id;
        break;
      }
    } 
    if ($id == '') {
      throw new Exception("$domain not found");
    }
    $cmd = "curl -s -X GET \"https://api.cloudflare.com/client/v4/zones/$id/pagerules?status=active&order=priority&direction=desc&match=all\" -H \"X-Auth-Email: $email\" -H \"X-Auth-Key: $key\" -H \"Content-Type: application/json\"";
    $r = shell_exec($cmd);
    $rr = json_decode($r, false);
    if (!$rr) {
      throw new Exception("failure: $r");
    }
    if (!isset($rr->success)) {
      throw new Exception($rr->errors . '  ' . $rr->messages);
    }
    if ($rr->result) {
      foreach ($rr->result as $obj) {
        if ($obj->targets) {
          foreach ($obj->targets as $obj2) {
            if ($obj2->constraint) {
              $r = $obj2->constraint->value;
              $rr = $r;
              if (!preg_match('/^https?:(.*)$/i', $rr)) {
                $rr = "https?://" . $rr;
              }
              $rr = str_replace('*', '(.*)', $rr);
              $rr = str_replace('?', '\?', $rr);
              $rr = str_replace('/', '\/', $rr);
              $rr = "/^$rr".'$/'; 
              if (preg_match($rr, $url)) {
                $rules[] = $r;
              }
            }
          }
        }
      }
    }
    return $rules;
  }     
  
  if (count($argv) > 1) {
    require('cloudflare.php');
    foreach ($argv as $url) {
      if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
        $rules = cloudflare_rulechecker($url, $CLOUDFLARE_KEY, $CLOUDFLARE_EMAIL, $CLOUDFLARE);
        if ($rules) {
          echo "Page Rules for $url: \n";
          $i = 1;
          foreach ($rules as $t) {
            echo "$i:  " . $t . "\n";
            $i ++;
          }
        } else {
          echo "**No Page Rules Match for $url \n";
        }    
      }
    }
  }
