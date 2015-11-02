Unholy
========

[![Build Status](https://travis-ci.org/danielbachhuber/unholy.png?branch=master)](https://travis-ci.org/danielbachhuber/unholy)

Markup testing for WordPress using jQuery-style selectors. Uses PHPUnit, the WordPress Unit Test Suite, [QueryPath](http://querypath.org/), and [HTML5-PHP](http://masterminds.github.io/html5-php/).

It's unholy, but it works.

## Installing

These instructions presuppose you're already using PHPUnit and the WordPress Unit Test Suite with your project. If you do, you can:

1. Require Unholy using Composer: `composer require danielbachhuber/unholy`
2. Install dependencies using Composer: `composer install`
3. Load Unholy into your test suite by appending `require dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php';` to the end of your `bootstrap.php`.

## Using

The `Unholy_Testcase` class extends the `WP_UnitTestcase` class. Update your project's test classes to extend `Unholy_Testcase`.

Extending the `Unholy_Testcase` class exposes two helper methods: `get_permalink_as_dom()` and `get_feed_as_dom()`. These can be used to get a DOMDocument-esque object representing the view. Then, use the `qp()` function to navigate the object using jQuery-style selectors.

As an example, see how you'd test the Twenty Fifteen theme for the site title and description in the header:

```php
update_option( 'blogname', 'Unholy Site Title' );
update_option( 'blogdescription', 'Unholy Site Description' );
$dom = $this->get_permalink_as_dom( '/' );
$this->assertEquals( 'Unholy Site Title', qp( $dom, '#masthead .site-title' )->text() );
$this->assertEquals( 'Unholy Site Description', qp( $dom, '#masthead .site-description' )->text() );
```
