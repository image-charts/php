<?php
// Run tests from the repository root directory:
// $ composer install && ./vendor/bin/phpunit ./ImageCharts.test.php

require './ImageCharts.php';
use PHPUnit\Framework\TestCase;


/**
 * @codeCoverageIgnore
 */
class ImageChartsTest extends TestCase
{
    public function test_CanInstance()
    {
        $this->assertInstanceOf(ImageCharts::class , new ImageCharts());
    }


    public function test_toURL_works(){
      $this->assertSame((new ImageCharts())->cht("p")->chd("t:1,2,3")->toURL(), "https://image-charts.com:443/chart?cht=p&chd=t%3A1%2C2%2C3");
    }

    public function test_exposes_parameters_and_use_them(){

      function startsWith($string, $startString){
        return (substr($string, 0, strlen($startString)) === $startString);
      }

      function endsWith($string, $endString){
        $len = strlen($endString);
        return $len == 0 ? true : (substr($string, -$len) === $endString);
      }

      function is_chart_param($method){
        return $method->isPublic() && startsWith($method->name, "c") || startsWith($method->name, "ic");
      }

      function call_method($chart, $method){
        return call_user_func(array($chart, $method->name), "plop");
      }

      function build_query($query, $method){
        $query[] = $method->name . '=plop';
        return $query;
      }

      $class = new ReflectionClass('ImageCharts');
      $chart = new ImageCharts();

      $methods = array_filter($class->getMethods(), "is_chart_param");



      $this->assertSame(array_reduce($methods, "call_method", $chart)->toURL(),
        "https://image-charts.com:443/chart?". implode('&', array_reduce($methods, "build_query", array()))
      );
    }

    public function test_adds_a_signature_when_icac_and_secrets_are_defined(){
      $this->assertSame(
        (new ImageCharts(array( "secret" => "plop")))
          ->cht("p")
          ->chd("t:1,2,3")
          ->chs("100x100")
          ->icac("test_fixture")
          ->toURL(),
        "https://image-charts.com:443/chart?cht=p&chd=t%3A1%2C2%2C3&chs=100x100&icac=test_fixture&ichm=71bd93758b49ed28fdabd23a0ff366fe7bf877296ea888b9aaf4ede7978bdc8d"
      );
    }

    public function test_rejects_if_a_chs_is_not_defined(){
      $this->expectException(ErrorException::class);
      $this->expectExceptionMessage('"chs" is required');
      (new ImageCharts())->cht("p")->chd("t:1,2,3")->toBinary();
    }

    public function test_rejects_if_a_icac_is_defined_without_ichm(){
      $this->expectException(ErrorException::class);
      $this->expectExceptionMessage("The `icac` (ACCOUNT_ID) and `ichm` (HMAC-SHA256 request signature) query parameters must both be defined if specified. [Learn more](https://bit.ly/HMACENT)");
      (new ImageCharts())
          ->cht("p")
          ->chd("t:1,2,3")
          ->chs("100x100")
          ->icac("test_fixture")
          ->toBinary();
    }

    public function test_rejects_if_timeout_is_reached(){
      $this->expectException(ErrorException::class);
      $this->expectExceptionMessage("Timeout was reached");
      (new ImageCharts(array("timeout" => 2))) // 1ms
          ->cht("p")
          ->chd("t:1,2,3")
          ->chs("100x100")
          ->chan("1200")
          ->toBinary();
    }


    /*public function test_works(){
      $this->assertSame(
        (new ImageCharts())->cht("p")->chd("t:1,2,3").chs("2x2")->toBinary()
      ).resolves.toMatchSnapshot())
    }*/

    public function test_forwards_package_name_version_as_user_agent(){
      $chart = (new ImageCharts())->cht("p")->chd("t:1,2,3")->chs("10x10");
      $chart->toBinary();
      $this->assertSame($chart->curl_options[CURLOPT_USERAGENT],"php-image-charts/latest");
    }

    public function test_forwards_package_name_version_icac_as_user_agent(){
      $chart = (new ImageCharts(array("secret" => "plop")))->cht("p")->chd("t:1,2,3")->chs("10x10")->icac("MY_ACCOUNT_ID");
      try{
        $chart->toBinary();
      }catch(Exception $ex){/* don't care */}
      $this->assertSame($chart->curl_options[CURLOPT_USERAGENT],"php-image-charts/latest (MY_ACCOUNT_ID)");
    }
    public function test_throw_error_if_account_not_found(){
      $this->expectException(ErrorException::class);
      $this->expectExceptionMessage('you must be an Image-Charts subscriber');
      (new ImageCharts(array("secret" => "plop")))->cht("p")->chd("t:1,2,3")->chs("10x10")->icac("MY_ACCOUNT_ID")->toBinary();
    }

    public function test_rejects_if_there_was_an_error(){
      $this->expectException(ErrorException::class);
      $this->expectExceptionMessage('"chs" is required');
      (new ImageCharts())->cht("p")->chd("t:1,2,3")->toDataURI();
    }

    public function test_toDataURI_works(){
      $this->assertSame(substr((new ImageCharts())->cht("p")->chd("t:1,2,3")->chs("2x2")->toDataURI(), 0, 30), "data:image/png;base64,iVBORw0K");
    }
//
//    public function test_toFile_with_bad_path_throw(){
//      $this->expectException(Exception::class);
//      $this->expectExceptionMessageRegExp('No such file or directory');
//      (new ImageCharts())->cht("p")->chd("t:1,2,3")->chs("2x2")->toFile('/tmp_bad_path_sdijsd/plop.png');
//    }

    public function test_toFile_works(){
      (new ImageCharts())->cht("p")->chd("t:1,2,3")->chs("2x2")->toFile('/tmp/plop.png');
      $this->assertFileExists('/tmp/plop.png');
    }

    public function test_support_gif(){
      $this->assertSame(substr((new ImageCharts())
        ->cht("p")
        ->chd("t:1,2,3")
        ->chan("100")
        ->chs("2x2")
        ->toDataURI(), 0, 30), "data:image/gif;base64,R0lGODlh");
    }


    public function test_expose_the_protocol(){
      $this->assertSame((new ImageCharts())->protocol, "https");
    }

    public function test_let_protocol_to_be_user_defined(){
      $this->assertSame((new ImageCharts([ "protocol" => "http" ]))->protocol,  "http");
    }

    public function test_expose_the_host(){
      $this->assertSame((new ImageCharts())->host, "image-charts.com");
    }

    public function test_let_host_to_be_user_defined(){
      $this->assertSame(
        (new ImageCharts([ "host" => "on-premise-image-charts.com" ]))->host, "on-premise-image-charts.com");
    }

    public function test_expose_the_pathname(){
      $this->assertSame((new ImageCharts())->pathname, "/chart");
    }

    public function test_expose_the_port(){
      $this->assertSame((new ImageCharts())->port, 443);
    }

    public function test_let_port_to_be_user_defined(){
      $this->assertSame((new ImageCharts([ "port" => 8080 ]))->port, 8080);
    }

    public function test_expose_the_query(){
      $this->assertSame((new ImageCharts())->query, []);
    }

    public function test_expose_the_query_user_defined(){
      $this->assertTrue(arrays_are_similar((new ImageCharts())->cht("p")->chd("t:1,2,3")->icac("plop")->query, array(
        "chd" => "t:1,2,3",
        "cht" => "p",
        "icac" => "plop",
      )));
    }
  }


/**
 * Determine if two associative arrays are similar
 *
 * Both arrays must have the same indexes with identical values
 * without respect to key ordering
 *
 * @param array $a
 * @param array $b
 * @return bool
 */
function arrays_are_similar($a, $b) {
  // if the indexes don't match, return immediately
  if (count(array_diff_assoc($a, $b))) {
    throw new Exception("$a and $b size mismatch");
  }
  // we know that the indexes, but maybe not values, match.
  // compare the values between the two arrays
  foreach($a as $k => $v) {
    if ($v !== $b[$k]) {
      throw new Exception("$k is not the same in left ($v) and right ($b[$k])");
    }
  }
  // we have identical indexes, and no unequal values
  return true;
}
