# CloudFlare
CloudFlare Utilities

## CloudFlare Rule Checker
https://helloacm.com/the-php-page-rule-checker-of-cloudflare/

Examples:
```
$ php rulechecker.php https://helloacm.com/api/what-is-my-ip-address/?cached https://justyy.com/top https://codingforspeed.com/abc
Page Rules for https://helloacm.com/api/what-is-my-ip-address/?cached:
1:  https://*helloacm.com/api/*/?cached*
2:  https://*helloacm.com/api/*
Page Rules for https://justyy.com/top:
1:  https://*justyy.com/top*
**No Page Rules Match for https://codingforspeed.com/abc
```


