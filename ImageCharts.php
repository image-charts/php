<?php
// must work with PHP5.3. That's why I can't get all the shinny new syntax. Life's hard {eye_roll_emoji}

class ImageCharts {
  public $protocol;
  public $host;
  public $port;
  public $pathname;
  public $timeout;
  public $query;
  public $secret;
  public $response_headers;

  /**
   * Specified curl options from last request
   * @readonly
   */
  public $curl_options;

  /*
   * Image-Charts URL builder
   * @typedef ImageCharts
   */
  function __construct($options = array(), $previous = array())
  {
    $this->protocol = isset($options["protocol"]) ? $options["protocol"] : "https";
    $this->host = isset($options["host"]) ? $options["host"] : 'image-charts.com';
    $this->port = isset($options["port"]) ? $options["port"] :  443;
    $this->pathname = isset($options["pathname"]) ? $options["pathname"] : '/chart';
    $this->timeout = isset($options["timeout"]) ? $options["timeout"] : 5000;
    $this->secret = isset($options["secret"]) ? $options["secret"] : null;
    $this->query = $previous;
  }

  private function clone($param, $value) {
    $add = array();
    $add[$param] = $value;
    return new ImageCharts(array(
      "protocol" => $this->protocol,
      "host" => $this->host,
      "port" => $this->port,
      "pathname" => $this->pathname,
      "timeout" => $this->timeout,
      "secret" => $this->secret,
    ), array_merge($this->query, $add));
  }


  /**
   * bvg= grouped bar chart, bvs= stacked bar chart, lc=line chart, ls=sparklines, p=pie chart. gv=graph viz
	*         Three-dimensional pie chart (p3) will be rendered in 2D, concentric pie chart are not supported.
	*         [Optional, line charts only] You can add :nda after the chart type in line charts to hide the default axes.
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-type/}
   * * @example
  * const chart = ImageCharts().cht("bvg");
  * const chart = ImageCharts().cht("p");
   * 
   * @param {string} value - Chart type
   * @return {ImageCharts.constructor}
   */
  public function cht($value) {
    return $this->clone('cht', $value);
  }

  /**
   * chart data
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/data-format/}
   * * @example
  * const chart = ImageCharts().chd("a:-100,200.5,75.55,110");
  * const chart = ImageCharts().chd("t:10,20,30|15,25,35");
  * const chart = ImageCharts().chd("s:BTb19_,Mn5tzb");
  * const chart = ImageCharts().chd("e:BaPoqM2s,-A__RMD6");
   * 
   * @param {string} value - chart data
   * @return {ImageCharts.constructor}
   */
  public function chd($value) {
    return $this->clone('chd', $value);
  }

  /**
   * You can configure some charts to scale automatically to fit their data with chds=a. The chart will be scaled so that the largest value is at the top of the chart and the smallest (or zero, if all values are greater than zero) will be at the bottom. Otherwise the &#34;&amp;lg;series_1_min&amp;gt;,&amp;lg;series_1_max&amp;gt;,...,&amp;lg;series_n_min&amp;gt;,&amp;lg;series_n_max&amp;gt;&#34; format set one or more minimum and maximum permitted values for each data series, separated by commas. You must supply both a max and a min. If you supply fewer pairs than there are data series, the last pair is applied to all remaining data series. Note that this does not change the axis range; to change the axis range, you must set the chxr parameter. Valid values range from (+/-)9.999e(+/-)199. You can specify values in either standard or E notation.
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/data-format/#text-format-with-custom-scaling}
   * * @example
  * const chart = ImageCharts().chds("-80,140");
   * 
   * @param {string} value - data format with custom scaling
   * @return {ImageCharts.constructor}
   */
  public function chds($value) {
    return $this->clone('chds', $value);
  }

  /**
   * How to encode the data in the QR code. &#39;UTF-8&#39; is the default and only supported value. Contact our team if you wish to have support for Shift_JIS and/or ISO-8859-1.
   * [Reference documentation]{@link https://documentation.image-charts.com/qr-codes/#data-encoding}
   * * @example
  * const chart = ImageCharts().choe("UTF-8");
   * 
   * @param {string} value - QRCode data encoding
   * @return {ImageCharts.constructor}
   */
  public function choe($value) {
    return $this->clone('choe', $value);
  }

  /**
   * QRCode error correction level and optional margin
   * [Reference documentation]{@link https://documentation.image-charts.com/qr-codes/#error-correction-level-and-margin}
   * * @example
  * const chart = ImageCharts().chld("L|4");
  * const chart = ImageCharts().chld("M|10");
  * const chart = ImageCharts().chld("Q|5");
  * const chart = ImageCharts().chld("H|18");
   * @default "L|4"
   * @param {string} value - QRCode error correction level and optional margin
   * @return {ImageCharts.constructor}
   */
  public function chld($value) {
    return $this->clone('chld', $value);
  }

  /**
   * You can specify the range of values that appear on each axis independently, using the chxr parameter. Note that this does not change the scale of the chart elements (use chds for that), only the scale of the axis labels.
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-axis/#axis-range}
   * * @example
  * const chart = ImageCharts().chxr("0,0,200");
  * const chart = ImageCharts().chxr("0,10,50,5");
  * const chart = ImageCharts().chxr("0,0,500|1,0,200");
   * 
   * @param {string} value - Axis data-range
   * @return {ImageCharts.constructor}
   */
  public function chxr($value) {
    return $this->clone('chxr', $value);
  }

  /**
   * Some clients like Flowdock/Facebook messenger and so on, needs an URL to ends with a valid image extension file to display the image, use this parameter at the end your URL to support them. Valid values are &#34;.png&#34;, &#34;.svg&#34; and &#34;.gif&#34;.
	*           Only QRCodes and GraphViz support svg output.
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/output-format/}
   * * @example
  * const chart = ImageCharts().chof(".png");
  * const chart = ImageCharts().chof(".svg");
  * const chart = ImageCharts().chof(".gif");
   * @default ".png"
   * @param {string} value - Image output format
   * @return {ImageCharts.constructor}
   */
  public function chof($value) {
    return $this->clone('chof', $value);
  }

  /**
   * Maximum chart size for all charts except maps is 998,001 pixels total (Google Image Charts was limited to 300,000), and maximum width or length is 999 pixels.
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-size/}
   * * @example
  * const chart = ImageCharts().chs("400x400");
   * 
   * @param {string} value - Chart size (&lt;width&gt;x&lt;height&gt;)
   * @return {ImageCharts.constructor}
   */
  public function chs($value) {
    return $this->clone('chs', $value);
  }

  /**
   * Format: &amp;lt;data_series_1_label&amp;gt;|...|&amp;lt;data_series_n_label&amp;gt;. The text for the legend entries. Each label applies to the corresponding series in the chd array. Use a + mark for a space. If you do not specify this parameter, the chart will not get a legend. There is no way to specify a line break in a label. The legend will typically expand to hold your legend text, and the chart area will shrink to accommodate the legend.
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/legend-text-and-style/}
   * * @example
  * const chart = ImageCharts().chdl("NASDAQ|FTSE100|DOW");
   * 
   * @param {string} value - Text for each series, to display in the legend
   * @return {ImageCharts.constructor}
   */
  public function chdl($value) {
    return $this->clone('chdl', $value);
  }

  /**
   * Specifies the color and font size of the legend text. &lt;color&gt;,&lt;size&gt;
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/legend-text-and-style/}
   * * @example
  * const chart = ImageCharts().chdls("9e9e9e,17");
   * @default "000000"
   * @param {string} value - Chart legend text and style
   * @return {ImageCharts.constructor}
   */
  public function chdls($value) {
    return $this->clone('chdls', $value);
  }

  /**
   * Solid or dotted grid lines
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/grid-lines/}
   * * @example
  * const chart = ImageCharts().chg("1,1");
  * const chart = ImageCharts().chg("0,1,1,5");
  * const chart = ImageCharts().chg("1,1,FF00FF");
  * const chart = ImageCharts().chg("1,1,1,1,CECECE");
   * 
   * @param {string} value - Solid or dotted grid lines
   * @return {ImageCharts.constructor}
   */
  public function chg($value) {
    return $this->clone('chg', $value);
  }

  /**
   * You can specify the colors of a specific series using the chco parameter.
	*       Format should be &amp;lt;series_2&amp;gt;,...,&amp;lt;series_m&amp;gt;, with each color in RRGGBB format hexadecimal number.
	*       The exact syntax and meaning can vary by chart type; see your specific chart type for details.
	*       Each entry in this string is an RRGGBB[AA] format hexadecimal number.
	*       If there are more series or elements in the chart than colors specified in your string, the API typically cycles through element colors from the start of that series (for elements) or for series colors from the start of the series list.
	*       Again, see individual chart documentation for details.
   * [Reference documentation]{@link https://documentation.image-charts.com/bar-charts/#examples}
   * * @example
  * const chart = ImageCharts().chco("FFC48C");
  * const chart = ImageCharts().chco("FF0000,00FF00,0000FF");
   * @default "F56991,FF9F80,FFC48C,D1F2A5,EFFAB4"
   * @param {string} value - series colors
   * @return {ImageCharts.constructor}
   */
  public function chco($value) {
    return $this->clone('chco', $value);
  }

  /**
   * chart title
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-title/}
   * * @example
  * const chart = ImageCharts().chtt("My beautiful chart");
   * 
   * @param {string} value - chart title
   * @return {ImageCharts.constructor}
   */
  public function chtt($value) {
    return $this->clone('chtt', $value);
  }

  /**
   * Format should be &#34;&lt;color&gt;,&lt;font_size&gt;[,&lt;opt_alignment&gt;,&lt;opt_font_family&gt;,&lt;opt_font_style&gt;]&#34;, opt_alignement is not supported
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-title/}
   * * @example
  * const chart = ImageCharts().chts("00FF00,17");
   * 
   * @param {string} value - chart title colors and font size
   * @return {ImageCharts.constructor}
   */
  public function chts($value) {
    return $this->clone('chts', $value);
  }

  /**
   * Specify which axes you want (from: &#34;x&#34;, &#34;y&#34;, &#34;t&#34; and &#34;r&#34;). You can use several of them, separated by a coma; for example: &#34;x,x,y,r&#34;. Order is important.
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-axis/#visible-axes}
   * * @example
  * const chart = ImageCharts().chxt("y");
  * const chart = ImageCharts().chxt("x,y");
  * const chart = ImageCharts().chxt("x,x,y");
  * const chart = ImageCharts().chxt("x,y,t,r,t");
   * 
   * @param {string} value - Display values on your axis lines or change which axes are shown
   * @return {ImageCharts.constructor}
   */
  public function chxt($value) {
    return $this->clone('chxt', $value);
  }

  /**
   * Specify one parameter set for each axis that you want to label. Format &#34;&lt;axis_index&gt;:|&lt;label_1&gt;|...|&lt;label_n&gt;|...|&lt;axis_index&gt;:|&lt;label_1&gt;|...|&lt;label_n&gt;&#34;. Separate multiple sets of labels using the pipe character ( | ).
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-axis/#custom-axis-labels}
   * * @example
  * const chart = ImageCharts().chxl("0:|Jan|July|Jan");
  * const chart = ImageCharts().chxl("0:|Jan|July|Jan|1|10|20|30");
   * 
   * @param {string} value - Custom string axis labels on any axis
   * @return {ImageCharts.constructor}
   */
  public function chxl($value) {
    return $this->clone('chxl', $value);
  }

  /**
   * You can specify the range of values that appear on each axis independently, using the chxr parameter. Note that this does not change the scale of the chart elements (use chds for that), only the scale of the axis labels.
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-axis/#axis-label-styles}
   * * @example
  * const chart = ImageCharts().chxs("1,0000DD");
  * const chart = ImageCharts().chxs("1N*cUSD*Mil,FF0000");
  * const chart = ImageCharts().chxs("1N*cEUR*,FF0000");
  * const chart = ImageCharts().chxs("2,0000DD,13,0,t");
  * const chart = ImageCharts().chxs("0N*p*per-month,0000FF");
  * const chart = ImageCharts().chxs("0N*e*,000000|1N*cUSD*Mil,FF0000|2N*2sz*,0000FF");
   * 
   * @param {string} value - Font size, color for axis labels, both custom labels and default label values
   * @return {ImageCharts.constructor}
   */
  public function chxs($value) {
    return $this->clone('chxs', $value);
  }

  /**
   * 
	* format should be either:
	*   - line fills (fill the area below a data line with a solid color): chm=&lt;b_or_B&gt;,&lt;color&gt;,&lt;start_line_index&gt;,&lt;end_line_index&gt;,&lt;0&gt; |...| &lt;b_or_B&gt;,&lt;color&gt;,&lt;start_line_index&gt;,&lt;end_line_index&gt;,&lt;0&gt;
	*   - line marker (add a line that traces data in your chart): chm=D,&lt;color&gt;,&lt;series_index&gt;,&lt;which_points&gt;,&lt;width&gt;,&lt;opt_z_order&gt;
	*   - Text and Data Value Markers: chm=N&lt;formatting_string&gt;,&lt;color&gt;,&lt;series_index&gt;,&lt;which_points&gt;,&lt;width&gt;,&lt;opt_z_order&gt;,&lt;font_family&gt;,&lt;font_style&gt;
	*     
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/compound-charts/}
   * * @example

   * 
   * @param {string} value - compound charts and line fills
   * @return {ImageCharts.constructor}
   */
  public function chm($value) {
    return $this->clone('chm', $value);
  }

  /**
   * line thickness and solid/dashed style
   * [Reference documentation]{@link https://documentation.image-charts.com/line-charts/#line-styles}
   * * @example
  * const chart = ImageCharts().chls("10");
  * const chart = ImageCharts().chls("3,6,3|5");
   * 
   * @param {string} value - line thickness and solid/dashed style
   * @return {ImageCharts.constructor}
   */
  public function chls($value) {
    return $this->clone('chls', $value);
  }

  /**
   * If specified it will override &#34;chdl&#34; values
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-label/}
   * * @example
  * const chart = ImageCharts().chl("label1|label2");
  * const chart = ImageCharts().chl("multi
	* line
	* label1|label2");
   * 
   * @param {string} value - bar, pie slice, doughnut slice and polar slice chart labels
   * @return {ImageCharts.constructor}
   */
  public function chl($value) {
    return $this->clone('chl', $value);
  }

  /**
   * Position and style of labels on data
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-label/#positionning-and-formatting}
   * * @example
  * const chart = ImageCharts().chlps("align,top|offset,10|color,FF00FF");
  * const chart = ImageCharts().chlps("align,top|offset,10|color,FF00FF");
   * 
   * @param {string} value - Position and style of labels on data
   * @return {ImageCharts.constructor}
   */
  public function chlps($value) {
    return $this->clone('chlps', $value);
  }

  /**
   * chart margins
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-margin/}
   * * @example
  * const chart = ImageCharts().chma("30,30,30,30");
  * const chart = ImageCharts().chma("40,20");
   * 
   * @param {string} value - chart margins
   * @return {ImageCharts.constructor}
   */
  public function chma($value) {
    return $this->clone('chma', $value);
  }

  /**
   * Position of the legend and order of the legend entries
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/legend-text-and-style/}
   * * @example

   * @default "r"
   * @param {string} value - Position of the legend and order of the legend entries
   * @return {ImageCharts.constructor}
   */
  public function chdlp($value) {
    return $this->clone('chdlp', $value);
  }

  /**
   * Background Fills
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/background-fill/}
   * * @example
  * const chart = ImageCharts().chf("b0,lg,0,f44336,0.3,03a9f4,0.8");
   * @default "bg,s,FFFFFF"
   * @param {string} value - Background Fills
   * @return {ImageCharts.constructor}
   */
  public function chf($value) {
    return $this->clone('chf', $value);
  }

  /**
   * Bar corner radius. Display bars with rounded corner.
   * [Reference documentation]{@link https://documentation.image-charts.com/bar-charts/#rounded-bar}
   * * @example
  * const chart = ImageCharts().chbr("5");
  * const chart = ImageCharts().chbr("10");
   * 
   * @param {string} value - Bar corner radius. Display bars with rounded corner.
   * @return {ImageCharts.constructor}
   */
  public function chbr($value) {
    return $this->clone('chbr', $value);
  }

  /**
   * gif configuration
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/animation/}
   * * @example
  * const chart = ImageCharts().chan("1200");
  * const chart = ImageCharts().chan("1300|easeInOutSine");
   * 
   * @param {string} value - gif configuration
   * @return {ImageCharts.constructor}
   */
  public function chan($value) {
    return $this->clone('chan', $value);
  }

  /**
   * doughnut chart inside label
   * [Reference documentation]{@link https://documentation.image-charts.com/pie-charts/#inside-label}
   * * @example
  * const chart = ImageCharts().chli("95K€");
  * const chart = ImageCharts().chli("45%");
   * 
   * @param {string} value - doughnut chart inside label
   * @return {ImageCharts.constructor}
   */
  public function chli($value) {
    return $this->clone('chli', $value);
  }

  /**
   * image-charts enterprise `account_id`
   * [Reference documentation]{@link https://documentation.image-charts.com/enterprise/}
   * * @example
  * const chart = ImageCharts().icac("accountId");
   * 
   * @param {string} value - image-charts enterprise `account_id`
   * @return {ImageCharts.constructor}
   */
  public function icac($value) {
    return $this->clone('icac', $value);
  }

  /**
   * HMAC-SHA256 signature required to activate paid features
   * [Reference documentation]{@link https://documentation.image-charts.com/enterprise/}
   * * @example
  * const chart = ImageCharts().ichm("0785cf22a0381c2e0239e27c126de4181f501d117c2c81745611e9db928b0376");
   * 
   * @param {string} value - HMAC-SHA256 signature required to activate paid features
   * @return {ImageCharts.constructor}
   */
  public function ichm($value) {
    return $this->clone('ichm', $value);
  }

  /**
   * How to use icff to define font family as Google Font : https://developers.google.com/fonts/docs/css2
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-font/}
   * * @example
  * const chart = ImageCharts().icff("Abel");
  * const chart = ImageCharts().icff("Akronim");
  * const chart = ImageCharts().icff("Alfa Slab One");
   * 
   * @param {string} value - Default font family for all text from Google Fonts. Use same syntax as Google Font CSS API
   * @return {ImageCharts.constructor}
   */
  public function icff($value) {
    return $this->clone('icff', $value);
  }

  /**
   * Default font style for all text
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/chart-font/}
   * * @example
  * const chart = ImageCharts().icfs("normal");
  * const chart = ImageCharts().icfs("italic");
   * 
   * @param {string} value - Default font style for all text
   * @return {ImageCharts.constructor}
   */
  public function icfs($value) {
    return $this->clone('icfs', $value);
  }

  /**
   * localization (ISO 639-1)
   * [Reference documentation]{@link }
   * * @example
  * const chart = ImageCharts().iclocale("en");
   * 
   * @param {string} value - localization (ISO 639-1)
   * @return {ImageCharts.constructor}
   */
  public function iclocale($value) {
    return $this->clone('iclocale', $value);
  }

  /**
   * Retina is a marketing term coined by Apple that refers to devices and monitors that have a resolution and pixel density so high — roughly 300 or more pixels per inch – that a person is unable to discern the individual pixels at a normal viewing distance.
	*           In order to generate beautiful charts for these Retina displays, Image-Charts supports a retina mode that can be activated through the icretina=1 parameter
   * [Reference documentation]{@link https://documentation.image-charts.com/reference/retina/}
   * * @example
  * const chart = ImageCharts().icretina("1");
   * 
   * @param {string} value - retina mode
   * @return {ImageCharts.constructor}
   */
  public function icretina($value) {
    return $this->clone('icretina', $value);
  }

  /**
   * Background color for QR Codes
   * [Reference documentation]{@link https://documentation.image-charts.com/qr-codes/#background-color}
   * * @example
  * const chart = ImageCharts().icqrb("FFFFFF");
   * @default "FFFFFF"
   * @param {string} value - Background color for QR Codes
   * @return {ImageCharts.constructor}
   */
  public function icqrb($value) {
    return $this->clone('icqrb', $value);
  }

  /**
   * Foreground color for QR Codes
   * [Reference documentation]{@link https://documentation.image-charts.com/qr-codes/#foreground-color}
   * * @example
  * const chart = ImageCharts().icqrf("000000");
   * @default "000000"
   * @param {string} value - Foreground color for QR Codes
   * @return {ImageCharts.constructor}
   */
  public function icqrf($value) {
    return $this->clone('icqrf', $value);
  }


  /**
   * Get the full Image-Charts API url (signed and encoded if necessary)
   * @return {string} full generated url
   */
  public function toURL() {
    $url = "{$this->protocol}://{$this->host}:{$this->port}{$this->pathname}";

    $query_string = http_build_query($this->query);
    $url .= '?'. $query_string;

    if(isset($this->query["icac"], $this->secret) && strlen($this->secret) > 0) {
      $url .= '&ichm='. hash_hmac('sha256', $query_string, $this->secret);
    }

    return $url;
  }

  private function read_response_headers($curl, $header){
    $len = strlen($header);
    $header = explode(':', $header, 2);
    if (count($header) < 2){ // ignore invalid headers
      return $len;
    }
    $this->response_headers[strtolower(trim($header[0]))][] = trim($header[1]);
    return $len;
  }


  /**
   * Do blocking request to Image-Charts API with current configuration and yield the content of the Image
   * @return {String} binary image represented as a string
   */
  public function toBinary() {
    $this->response_headers = array();

    $this->curl_options = array(
        CURLOPT_URL => $this->toURL(),
        CURLOPT_RETURNTRANSFER => 1, //do not output directly, use variable
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADERFUNCTION => array($this, 'read_response_headers'),
        CURLOPT_TIMEOUT_MS => $this->timeout,
        CURLOPT_USERAGENT => "php-image-charts/latest" . (isset($this->query["icac"]) && strlen($this->query["icac"]) > 0 ? (" ({$this->query["icac"]})") : "")
    );

    $ch = curl_init();

    curl_setopt_array($ch, $this->curl_options);

    $file = curl_exec($ch); //store the content in variable

    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    $errno = curl_errno($ch);
    curl_close($ch);


    if($errno) {
        $message = curl_strerror($errno);
        throw new ErrorException($message, $errno);
    }

    if($status_code >= 200 && $status_code < 300){
      return $file;
    }

    // @codeCoverageIgnoreStart
    if(!isset($this->response_headers['x-ic-error-validation']) || count($this->response_headers['x-ic-error-validation']) == 0){
        throw new ErrorException($this->response_headers['x-ic-error-code'][0], 0);
    }
    // @codeCoverageIgnoreEnd

    $validation_message = json_decode($this->response_headers['x-ic-error-validation'][0]);

    if(!$validation_message){
        throw new ErrorException($this->response_headers['x-ic-error-code'][0], 0);
    }

    throw new ErrorException($validation_message[0]->message, 0);
  }

  /**
   * Creates a file containing generated chart image
   * @param {String} filename full path and filename
   * @example '/tmp/chart.png'
   * @return {String} binary image represented as a string
   */
  public function toFile($filePath) {
    file_put_contents($filePath, $this->toBinary());
  }

  /**
   * Do a blocking request to Image-Charts API with current configuration and a base64 encoded data URI
   * @return {String} base64 data URI
   */
  public function toDataURI() {
    $encoding = 'base64';
    $mimetype = isset($this->query["chan"]) ? 'image/gif' : 'image/png';
    $encoded = base64_encode($this->toBinary());
    return "data:{$mimetype};{$encoding},{$encoded}";
  }
}
