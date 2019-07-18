# Kirby Twit: Work with Twitter Timelines

A small plugin that is a wrapper around [twitteroauth](https://github.com/abraham/twitteroauth). Allows you to display tweets on your website without having use Twitters embedded timelines.

Features:

* Display tweets on your site using your own markup.
* Caches results from API, in a unique file per set
* Automagically turns all links, hashtags and @ mentions into clickable links.

****

## Commerical Usage

This plugin is free but if you use it in a commercial project please consider to
- [make a donation 🍻](https://paypal.me/hashandsalt?locale.x=en_GB) or
- [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/36141?link=1170)

****


## How to use Twit

First you need access to the Twitter API, and for that you need account. Register your website as an [application here](https://developer.twitter.com/en/apps).

****

## Installation

### Download

Download and copy this repository to `/site/plugins/twit`.

### Composer

```
composer require hashandsalt/kirby-twit
```

## Setup

You wont get far without authenticating. Set the following in your config to gain access to your feed:

```
'twit.consumerkey'       => 'XXX',
'twit.consumersecret'    => 'XXX',
'twit.accesstoken'       => 'XXX',
'twit.accesstokensecret' => 'XXX',
```

## Usage

Create a collection to hold your tweets so you can get at them across the site. This will get the last 25 tweets and store it in a cache file called 'userTweets':

```
<?php
return function ($site) {
    return $site->twit('statuses/user_timeline', 25, 'userTweets');
};
```

You can access more then `statuses/user_timeline`, like `statuses/home_timeline`. Refer to the [Twitter api](https://developer.twitter.com/en/docs/tweets/timelines/api-reference/get-statuses-home_timeline) for more options.

## Get from another timeline

To get tweets from another timeline, you pass in a screen name as the 4th parameter:

```
<?php
return function ($site) {
    return $site->twit('statuses/user_timeline', 25, 'userTweets', 'getkirby');
};
```

Then use it in a loop like this:

```
<div class="block-3">
<?php foreach($kirby->collection("tweets") as $tweet): ?>
	<div class="block-col">
		<p><?= $tweet['text'] ?>
		<small>at <?= date('j.n.Y H:i', strtotime($tweet['created_at'])) ?></small></p>
	</div>
<?php endforeach ?>
</div>
```
The full information from the API is in the collection. `var_dump` the collection to see other information you may want to use.

## Kirby Tag

Use the tag like this and just give it a status id:

```
(twit: 1143433510528737280)
```

If you want to have more control over the output, copy the `snippets/twit.php` file from the plugin into the usual snippets folder and make your changes there. The default is pretty simple, but there is a lot more data you can use.

Do a `var_dump` on the `$tweetdata` var in the snippet to see everything you can work. Remember to handle missing data - some tweets have images and some don't, so watch out for that!

## Known Issues

Since this plugin uses caching, if you change the collection rule, you may have to wait up to 30 minutes to see the changes, or you can delete the cache file, or you can tell it to use a new one.

The Twitter API is a bit dumb. It counts retweets as a tweet. If you ask for 6 tweets and only got 4 back, 2 of them were probably re-tweeted. Not much to be done about that, other then asking for more then you need and only looping out the first 6, but you could still run into the problem again.


## License

MIT
