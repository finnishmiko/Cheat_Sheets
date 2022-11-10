# JavaScript

`window.location.href` is a property that tells you the current URL location of the browser. Changing the value of the property will redirect the page.

`window.open()` is a method to open in a new window

```javascript
/**
 * If HTTP is used, redirect user to HTTPS.
 * Except while testing in localhost.
 */
if (window.location && window.location.protocol !== "https:") {
  if (
    window.location.hostname !== "localhost" &&
    window.location.hostname !== "127.0.0.1"
  ) {
    window.location.href =
      "https:" +
      window.location.href.substring(window.location.protocol.length);
  }
}
```

Validate email reg exp vs. pattern:

```js
function validateEmail(email) {
  // must have a @ and a dot in the email address
  const re =
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  return re.test(email);
}
```

```html
<form>
  <input pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required />
  <br />
  <input type="submit" value="Submit" />
</form>
```


```javascript
  let expiryDate = new Date();
  const month = (expiryDate.getMonth() + 1) % 12;
  expiryDate.setMonth(month);
  document.cookie = 'cookie_use_acceptance=all; expires=' + expiryDate.toGMTString() + ';secure';
```

```javascript
var allCookies = document.cookie.split(';');
  console.log('delete', allCookies)
  // The "expire" attribute of every cookie is 
  // Set to "Thu, 01 Jan 1970 00:00:00 GMT"
  for (var i = 0; i < allCookies.length; i++) {
     // document.cookie = allCookies[i] + "=;expires=" + new Date(0).toUTCString();
     document.cookie = allCookies[i] + "=;max-age=0;path=/;";
    console.log('loop', allCookies[i]);
  }
```

```javascript
function deleteAllCookies() {
  var cookies = document.cookie.split(";");
  for (var i = 0; i < cookies.length; i++) {
      var cookie = cookies[i];
      var eqPos = cookie.indexOf("=");
      var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
      // document.cookie = name + "=;max-age=0";
      document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
  }
}
```
