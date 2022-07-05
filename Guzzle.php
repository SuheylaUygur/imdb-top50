<?php
include_once(__DIR__ . '/../vendor/autoload.php');

use Symfony\Component\DomCrawler\Crawler;

$client = new \GuzzleHttp\Client();
$imdb_url = 'https://www.imdb.com/search/title/?groups=top_100&sort=user_rating,desc';
$response = $client->request('GET', $imdb_url);
$html = $response->getBody();
$crawler = new Crawler($html);

// top 50

$movies = [];


$movies = $crawler->filter('div.lister-item.mode-advanced')->each(function (Crawler $node) {

    $title = $node->filter('h3.lister-item-header>a')->text();
    $img = $node->filter('img')->attr('loadlate');
    $rate = $node->filter('div.inline-block.ratings-imdb-rating')->text();
    $info = $node->filter('p.text-muted')->eq(1)->text();
    $link =  $node->filter('a')->attr('href');

    return [
        'title' => $title,
        'img' => $img,
        'rate' => $rate,
        'info' => $info,
        'link' => $link
    ];
});


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport">
    <title>IMDB Top 50</title>
</head>

<body>
    <table class="table table-dark">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Image</th>
                <th>Rate</th>
                <th>Info</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < 50; $i++) : ?>
                <tr>
                    <th scope="row"><?php echo $i + 1 ?></th>
                    <th scope="row"><?php echo ($movies[$i]['title']) ?></th>
                    <th scope="row"><a href="https://www.imdb.com<?php echo($movies[$i]['link']) ?>"><img src="<?php echo ($movies[$i]['img']) ?>"></th>
                    <th scope="row"><?php echo ($movies[$i]['rate']) ?></th>
                    <th style="text-align: left;" scope="row"><?php echo ($movies[$i]['info']) ?></th>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

</body>

</html>