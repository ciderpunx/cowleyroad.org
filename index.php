<html>
<?php
$dir = "wiki/images"; // change to dir where images live

// This a list of the even/South addresses in the order that they will appear on screen
// $even_addresses = range(300,2,2);
$even_addresses = [ "300","288","286","284","282","280","278","276","274"
                  , "272","268","266","264","262","258","256","254","252"
                  , "250","248","246","242","240","236","234","232","228"
                  , "226","224","220","218","216","212","190","188","186"
                  , "Xxsouth_184a","184","182","180","178","176","174","172"
                  , "170","168","166","164","162","160","158","156","154","152"
                  , "150","148","146","142","140","138","136","Xxsouth_134b"
                  , "Xxsouth_Tyndale-House","134","132","128","126","124","122"
                  , "120","118","116","110","106","104","102","100","98","96"
                  , "94","92","90","88","86","84","82","80","78","76","74","72"
                  , "68","66","64","62","58","54","48","46","40","38","36","34"
                  , "Xxsouth_1-The-Plain"
                  ]
;
// This a list of the odd/North addresses in the order that they will appear on screen
// $odd_addresses = range (1,199,2);
$odd_addresses = [  "1","3","7","13","17","21","23","25","Xxnorth29a","33"
                 , "35","37","47","Xxnorth_51a","53","Xxnorth_53a","55"
                 , "57","59","65","93","Xxnorth_UPP","95","99","101","103","105","107"
                 , "109","Xxnorth_EOCC","119","121","125","127","129"
                 , "131","133","137","141","147","151","Xxnorth_151A","159"
                 , "169","171","173","175","179","181","183","185","187"
                 , "189","193","205","Xxnorth_Manzil_Way","207","209","211","213"
                 , "217","221","235","237","247","251","255","263","265"
                 , "267","Xxnorth_Bartlemas"
                 ]
;
$addrs = getAllImageFilenames();
$addr_hash = getLatestImages($addrs);

function getLatestImages ($filenames) {
  global $dir;
  foreach ($filenames as &$f) {
    $pathparts = pathinfo($f);

    $bits = explode('-', $pathparts['filename']);

    // TODO: We only check the numericality of the ymd fields, not if they
    // are sane numbers (1-31, 1-12 etc.)
    $f = FALSE;
    if (count($bits)==2 && is_numeric($bits[1])){
      $f = $bits[0] . "-" . $bits[1] . "-00-00." . $pathparts['extension'];
    }
    else if (count($bits)==3 && is_numeric($bits[1]) && is_numeric($bits[2])){
      $f = $bits[0] . "-" . $bits[1] . "-" . $bits[2] . "-00." . $pathparts['extension'];
    }
    else if (count($bits)>=4 && is_numeric($bits[1]) && is_numeric($bits[2]) 
             && is_numeric($bits[3])){
        $f = $pathparts['filename'] . '.' . $pathparts['extension'];
        continue;
    }
  }

  sort($filenames);

  $hash = array();
  foreach(array_filter($filenames) as &$f) {
    $f = str_replace("-00","",$f);
    $bits = explode("-",$f);
    $address = $bits[0];
  //  $north_addr = str_replace("north","1",strtolower($address)); // so addresses tagged xxnorth appear on "odd" side of road
  //  $south_north_addr = str_replace("south","2",$north_addr);    // so addresses tagged xxsouth appear on "even" side of road
  //  $numaddress = preg_replace("[^\n]","",$south_north_addr);    // filter only numeric part of address to work out if it is even or odd
    $numaddress = preg_replace("/[^\d]/","",$address);    // filter only numeric part of address to work out if it is even or odd
    if($numaddress%2 == 0 && !preg_match('/^Xxnorth/',$address)) {
      $hash['evens'][$address] = "$dir/$f";
    }
    else {
      $hash['odds'][$address] = "$dir/$f";
    }
  }
  return($hash);
}

function getAllImageFilenames() {
  global $dir;
  return scandir($dir);
}

?>
<head>
<title>Cowley Road</title>
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/css/swiper.min.css">
<style>
html,body {font-family: Montserrat, sans-serif;text-align:center;margin:0}
.clear {clear:both}
#logo {width:100%;max-width:600px;margin:auto}
#front-logobar{width:100%;background-color:#000;color:#eee}
#front-logobar h1 {margin:0}
#front-logobar p {padding:0 0 3em 0;margin-top:-1em}
#front-article {width:100%; overflow-x:hidden; overflow-y:hidden}
.swiper-container {width:95%;height:350px}
.swiper-slide {width:250px;height:350px;background-color:#fff;overflow:hidden}
.swiper-slide a {line-height:350px;text-decoration:none; color:#999;}
.swiper-slide img {height:350px;object-position:center;object-fit:contain}
.swiper-button-next, .swiper-button-prev {
  width: 58px;
  height: 105px;
  background-color: rgba(255,255,255,0.5);
}
.swiper-button-next, .swiper-container-rtl .swiper-button-prev {
  background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M27%2C22L27%2C22L5%2C44l-2.1-2.1L22.8%2C22L2.9%2C2.1L5%2C0L27%2C22L27%2C22z'%20fill%3D'%23000000'%2F%3E%3C%2Fsvg%3E");
}
.swiper-button-prev, .swiper-container-rtl .swiper-button-next {
  background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg'%20viewBox%3D'0%200%2027%2044'%3E%3Cpath%20d%3D'M0%2C22L22%2C0l2.1%2C2.1L4.2%2C22l19.9%2C19.9L22%2C44L0%2C22L0%2C22L0%2C22z'%20fill%3D'%23000000'%2F%3E%3C%2Fsvg%3E");
}
.swiper-button-disabled {display:none}
<!--[if (lte IE 10)|!(IE)]><!-->
#front-article {overflow-x:scroll}
<![endif]-->
</style>

<script>
// Lazy Loading code from: http://developer.telerik.com/featured/lazy-loading-images-on-the-web/
window.addEventListener("DOMContentLoaded", lazyLoadImages);
window.addEventListener("load", lazyLoadImages);
window.addEventListener("resize", lazyLoadImages);
window.addEventListener("scroll", lazyLoadImages);

function lazyLoadImages() {
  var images = document.querySelectorAll("#main-wrapper img[data-src]"),
      item;
  // load images that have entered the viewport
  [].forEach.call(images, function (item) {
    if (isElementInViewport(item)) {
      item.setAttribute("src",item.getAttribute("data-src"));
      item.removeAttribute("data-src");
      // TODO: change parent bgcolor to white on load
    }
  })
  // if all the images are loaded, stop calling the handler
  if (images.length == 0) {
    window.removeEventListener("DOMContentLoaded", lazyLoadImages);
    window.removeEventListener("load", lazyLoadImages);
    window.removeEventListener("resize", lazyLoadImages);
    window.removeEventListener("scroll", lazyLoadImages);
  }
}
// Source: http://stackoverflow.com/questions/123999/how-to-tell-if-a-dom-element-is-visible-in-the-current-viewport/7557433#7557433
function isElementInViewport (el) {
    var rect = el.getBoundingClientRect();

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= 550 + (window.innerWidth || document.documentElement.clientWidth) 
    ); // note that adding a bit to the RHS pre-buffers 2 images, so they display quicker when scrolling right TODO check perf implications
}
</script>

</head>
<body id="main-wrapper">

<header id="front-logobar">
  <h1>
    <img id="logo" src="images/cowleyRoadOrgLogo600px.png" alt="cowleyroad.org" />
  </h1>
  <p>The hidden history of Oxford&#8217;s favourite street</p>
</header>

<article id="front-article">
  <p>Select any building to start exploring, or read <a href="/wiki/index.php?title=Main_Page">about the project</a>.<br />&nbsp;</p>
  <div class="swiper-container">
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-wrapper">
  <?php
  foreach($odd_addresses as $a){
    $a_humanized = preg_replace("/^xxnorth_?/i","",$a);
    if(isset($addr_hash['odds'][$a])) {
      echo "\t<div class=\"swiper-slide\"><a href=\"/wiki/index.php?title=$a_humanized\"><img class=\"swiper-lazy\" data-src=\"".$addr_hash['odds'][$a]."\" alt=\"".$a_humanized." Cowley Road, Oxford\" /></a></div>\n";
    }
    else {
      $a_escaped = preg_replace("/\s+/","+",$a_humanized);
      echo "\t<div class=\"swiper-slide\"><a href=\"/wiki/index.php?title=$a_humanized\"><img  class=\"swiper-lazy\" data-src=\"http://placehold.it/250x350?text=$a_escaped\" alt=\"".$a_humanized." Cowley Road, Oxford\" /></a></div>\n";
    }
  }
  ?>
    </div>
    <div class="swiper-scrollbar"></div>
  </div>
  <h3>North (odd numbers)</h3>
  <div class="clear" style="height:4em;"></div>

  <div class="swiper-container">
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-wrapper">
  <?php
  foreach($even_addresses as $a){
    $a_humanized = preg_replace("/^xxsouth_?/i","",$a);
    if(isset($addr_hash['evens'][$a])) {
      echo "\t<div class=\"swiper-slide\"><a href=\"/wiki/index.php?title=$a_humanized\"><img class=\"swiper-lazy\" data-src=\"".$addr_hash['evens'][$a]."\" alt=\"".$a_humanized." Cowley Road, Oxford\"/></a></div>\n";
    }
    else {
      $a_escaped = preg_replace("/\s+/","+",$a_humanized);
      echo "\t<div class=\"swiper-slide\"><a href=\"/wiki/index.php?title=$a_humanized\"><img class=\"swiper-lazy\" data-src=\"http://placehold.it/250x350?text=$a_escaped\" alt=\"".$a_humanized." Cowley Road, Oxford\" /></a></div>\n";
    }
  }
  ?>
    </div>
    <div class="swiper-scrollbar"></div>
  </div>
  <h3>South (even numbers)</h3>
</article>
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(["setCookieDomain", "*.cowleyroad.org"]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//piwik.charlieharvey.org.uk/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', '10']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Piwik Code -->
<!--Slider Code-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/js/swiper.min.js"></script>
<script>
var mySwiper = new Swiper ('.swiper-container', {
  direction: 'horizontal',
  loop: false,
  slidesPerView: 'auto',
  spaceBetween: 10,
  grabcursor: true,
  preloadImages: false,
  lazyLoading: true,
  pagination: '.swiper-pagination',
  nextButton: '.swiper-button-next',
  prevButton: '.swiper-button-prev',
  keyboardControl: true,
  scrollbar: '.swiper-scrollbar',
  //scrollbarHide: false, // not sure about this
  onSlideChangeStart: lazyLoadImages, // the builtin lazyloading seems glitchy, use our own implemenatation
  onSliderMove: lazyLoadImages
});
</script>
</body>
