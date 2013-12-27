como
====

PHP CLI MVC framework

Installation
=====
git clone https://github.com/rmccullagh/como.git

php __init__.php --version
php __init__.php --test

You provide the controller as the first argument,  followed by the method. If you do not provide a method,  then the default init() method will be invoked.
Passing arguments to the method is also possible,  but it is not completelty done yet.

Methods with arguments
======================

All methods MUST be written like this: methodOne,  methodTwo

For example,  the TestController.php has a test method called "methodWithArgs"
To invoke this method, enter this on the command line:

php __init__.php --test --method-with-args --arg1 --arg2

Installing a controller via the command line
============================================

Como provides a built in CLI installation method to increase development time. In order to increase development time and make it more convienent to install controller,  you
can use this method: php __init__.php --install --init --myname Where "--myname" is the name of your controller. After that test it like this: 
php __init__.php --myname
The default install will provide an init() method which by default echo's the class and method being invoked.

