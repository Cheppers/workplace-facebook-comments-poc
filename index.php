<?php

require_once __DIR__ . '/vendor/autoload.php';

try {
  $dotenv = new \Dotenv\Dotenv(__DIR__);
  $dotenv->load();
}
catch (\Dotenv\Exception\InvalidPathException $e) {
  die('No .env file was found. Please create one and set the `APP_ID`, `APP_ID` and `ACCESS_TOKEN` variables.');
}

$fb = new \Facebook\Facebook([
  'app_id' => getenv('APP_ID'),
  'app_secret' => getenv('APP_SECRET'),
  'default_access_token' => getenv('ACCESS_TOKEN'),
  'default_graph_version' => 'v3.1',
]);

// Retrieve all groups from the community.
// Note that the community itself, too, is a group and can have posts. To
// retrieve that use the /community endpoint.

/** @var \Facebook\FacebookResponse $responseGroups */
$responseGroups = request('community/groups');
say('Groups:');
/** @var \Facebook\GraphNodes\GraphEdge $groups */
$groups = $responseGroups->getGraphEdge();
/** @var \Facebook\GraphNodes\GraphNode $group */
foreach ($groups as $group) {
  say('• ' . $group->getField('name') . ' (' . $group->getField('id') . ')');
};

// Retrieve posts from the first group from the response.

/** @var \Facebook\FacebookResponse $responsePosts */
$responsePosts = request($groups[0]->getField('id') . '/feed');
say('Posts from first group from response (' . $groups[0]->getField('name') . '):');
/** @var \Facebook\GraphNodes\GraphEdge $posts */
$posts = $responsePosts->getGraphEdge();
/** @var \Facebook\GraphNodes\GraphNode $post */
foreach ($posts as $post) {
  say('• ' . $post->getField('id') . ': ' . $post->getField('message'));
}

// Retrieve comments from the first post from the response.
$responseComments = request($posts[0]->getField('id') . '/comments');
say('Comments from first post from response (' . $posts[0]->getField('id') . '):');
/** @var \Facebook\GraphNodes\GraphEdge $comments */
$comments = $responseComments->getGraphEdge();
/** @var \Facebook\GraphNodes\GraphNode $comment */
foreach ($comments as $comment) {
  // @todo Nested comments should be retrieved recursively here.
  say('• ' . $comment->getField('id') . ': ' . $comment->getField('message'));
}

// Posting comment to the first post from the response.
// Note that this will be posted on behalf of the app and not an actual user.
// You need to pass a member access token to $fb->post() in order to post on
// behalf of a member.
// @see: https://developers.facebook.com/docs/workplace/reference/graph-api#memberaccesstoken

say('Posting comment to the first post from the response (' . $posts[0]->getField('id') . '):');
$faker = Faker\Factory::create();
$comment = $faker->realText();
/** @var \Facebook\FacebookResponse $responseCommentPost */
$responseCommentPost = request($posts[0]->getField('id') . '/comments', 'post', [
  'message' => $comment,
]);
say('>>> ' . $comment);


function request($endpoint, $method = 'get', $params = []) {
  global $fb;
  try {
    if ($method == 'get') {
      /** @var Facebook\FacebookResponse $response */
      $response = $fb->get('/' . $endpoint);
    }
    else {
      /** @var Facebook\FacebookResponse $response */
      $response = $fb->post('/' . $endpoint, $params);
    }
  }
  catch(Facebook\Exceptions\FacebookResponseException $e) {
    die('Graph returned an error: ' . $e->getMessage());
  }
  catch(Facebook\Exceptions\FacebookSDKException $e) {
    die('Facebook SDK returned an error: ' . $e->getMessage());
  }
  return $response;
}

function say($output, $label = NULL) {
  if ($label) {
    echo "$label: $output\n";
  }
  else {
    echo "$output\n";
  }
}