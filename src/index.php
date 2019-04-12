<?php

use Abraham\TwitterOAuth\TwitterOAuth;

global $twitter;

$twitter = new TwitterOAuth(option('twit.consumerkey'), option('twit.consumersecret'), option('twit.accesstoken'), option('twit.accesstokensecret'));


/**
 * Turn all URLs in clickable links.
 *
 * @param string $value
 * @param array  $protocols  http/https, ftp, mail, twitter
 * @param array  $attributes
 * @param string $mode       normal or all
 * @return string
 */
function linkify($value, $protocols = array('http', 'https', 'twitter', 'mail'), array $attributes = array('target' => '_blank'))
{
    // Link attributes
    $attr = '';
    foreach ($attributes as $key => $val) {
        $attr = ' ' . $key . '="' . htmlentities($val) . '"';
    }

    $links = array();

    // Extract existing links and tags
    $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);

    // Extract text links for each protocol
    foreach ((array)$protocols as $protocol) {
        switch ($protocol) {
            case 'http':
            case 'https':   $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, "<a $attr href=\"$protocol://$link\">$link</a>") . '>'; }, $value); break;
            case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
            case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . "\">{$match[0]}</a>") . '>'; }, $value); break;
            default:        $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
        }
    }

    // Insert all link
    return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
}



// Clickable URLS
function setLinks ($source) {
  array_walk_recursive(
    $source,
      function (&$value, &$key) {
        if (in_array($key, array('url','text','expanded_url','description','display_url'), true ) ) {
          if (!is_array($value)) {
            $value = linkify($value);
          }
        }
      }
    );
  return $source;
}

// Get The Tweets
function twitMuncher($type, $count, $cachefile, $screenname) {
  global $twitter;

  $twitterCache = kirby()->cache('hashandsalt.kirby-twit.tweets');
  $tweetlist = $twitterCache->get($cachefile);

  // There's nothing in the cache, so let's fetch it
  if ($tweetlist === null) {
    $tweetlist = $twitter->get($type, ['count' => $count, "exclude_replies" => true, "screen_name" => $screenname]);
    $tweetlist = json_decode(json_encode($tweetlist), true);
    $twitterCache->set($cachefile, $tweetlist);
  }

  return $tweetlist;

}

// Give me the tweets
function twit($type, $count, $cachefile, $screenname) {
  $tweets = twitMuncher($type, $count, $cachefile, $screenname);
  $tweets = setLinks($tweets);

  return $tweets;
}
