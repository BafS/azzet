Azzet
=====

Simple script to speed up and compact assets files.

Getting started
---------------
This script works only with PHP.

Load the script where you want and set files to load.
Write it directly in html like this :
```
<link href="azzet.php/css/main.css" rel="stylesheet">
```

#### Load files
 * One file :
<code>azzet.php/main.css</code>

 * One file in a directory :
<code>azzet.php/css/main.css</code>
or :
<code>azzet.php/main.css&dir=css</code>

 * Multiple files (separated with '&') :
<code>azzet.php/main.css&test.css&admin.css</code>

 * Multiple files in the same directory (separated files with '|') :<br>
<code>azzet.php/css[main.css|test.css|admin.css]</code><br>
or :<br>
<code>azzet.php/[main.css|test.css|admin.css]&dir=css</code><br>
or :<br>
<code>azzet.php/main.css&test.css&admin.css&dir=css</code><br>
etc...

 * Multiple files in the multiple directories :
<code>azzet.php/css[main.css|admin.css]&main.css&test/test.css</code>

##### With options :
 * One file minified :
<code>azzet.php/main.css?min</code>

 * Files minified without .css extension :
<code>azzet.php/main&admin&test?min&ext=css</code>

 * Files minified without .css extension in assets dir with debug :
<code>azzet.php/main&admin&test?min&ext=css&dir=assets&debug</code>


#### Options available
 * debug : show error comment if file doesn't exists
 * dir : set a base dir (default : /)
 * type : type of files (default : css)
 * ext : set an extension (default empty)
 * min : minify CSS files (default : false)


And don't forget "URL rewriting" if you want something cool.

Example
-------
Don't send a lot of CSS files, compact and compress these in one single CSS.

```
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Azzet - test</title>
        <link href="azzet.php/css/[laticss|csstoolkit]&main?ext=css&min" rel="stylesheet">
    </head>
  
    <body>
        <p>CSS loaded !</p>
    </body>
</html>
```
