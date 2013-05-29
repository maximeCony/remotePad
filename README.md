# RemotePad

Control your computer with your phone and build custom remotes

## Technologies used server side

* [symfony2](http://symfony.com/)
* [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)

## Technologies used client side
* [Jquery](http://jquery.com/)
* [bootstrap](http://twitter.github.io/bootstrap/)
* [keycode.js](http://jonathan.tang.name/code/js_keycode)

## Install

```shell
$ php composer.phar install
```

##set config

In /app/config/ rename parameters.yml.dist to parameters.yml

Create database
```shell
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:update --force
```
