# CSE330
466303

Discuss:

When the server tried to load the phpinfo.php and the browser would pop out the download screen. The reason is that, in Node.js, we need to require the modules that we're going to use, like require('http'). Although PHP, by default, will generate HTML document, but the we need to run the php script before we can have html document, and that where everything goes wrong. As the Node.js server cannot recognize the type of the phpinfo.php is, the default behavior is download the phpinfo.php.
