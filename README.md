# Tor Exit Node Detection

I love Tor and most everything it represents. However, in certain use cases, people can use Tor to be jerks.

*** Disclaimer ***

You probably shouldn't use this lib as is if you are wanting to do anything serious. 
It maintains a json file with a list of Tor exit nodes that should be stored elsewhere 
for more serious applications.

## Support

If you use this library or it's components in a commercial project please consider a [moral license](https://www.creatorlove.com/terry-harmon/tor-detect)

## Installation

```
composer require profburial/tordetect 1.1
```

## Usage

Create a writable json file to store your ip data.

```php
// Get a list of Tor exit nodes
$ips = (new ProfBurial\TorDetect\Client(
    __DIR__."/_data/torexitnodes.json", // File for storing ips
    24 // Update every 24 hours
))->get();

// array(1067) {
//   [0]=>
//   string(13) "1.169.207.157"
//   [1]=>
//   string(12) "2.107.22.186"
//   [2]=>
//   string(11) "2.111.64.26"
//   [3]=>
//   string(11) "2.221.39.34"
//   ...
// }

// Check an ip address against your list of exit nodes
$check = (new ProfBurial\TorDetect\Client(
    __DIR__."/_data/torexitnodes.json", // File for storing ips
    24 // Update every 24 hours
))->check('127.0.0.1');

// bool(false) if not found
// '127.0.0.1' if found

```

## Tests

Unit: 
```
phpunit tests/ClientUnitTest.php
```

Integration: 
```
phpunit tests/ClientIntegrationTest.php
```