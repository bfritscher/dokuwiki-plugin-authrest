# Dokuwiki - REST Auth Plugin

Provides authentication against a REST API backend for dokuwiki.

Makes a form POST request with username&password and expects a json object parsable asdokuwiki USERINFO

```javascript
{
    "user": "username",
    "name": "full_name",
    "mail": "email",
    "grps": ["admin", "user"]
}
```