 <?php
class Router {
  private array $get=[], $post=[];
  public function get($p,$h){ $this->get[$p]=$h; }
  public function post($p,$h){ $this->post[$p]=$h; }
  public function dispatch(){
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $base = rtrim(parse_url(APP_URL, PHP_URL_PATH) ?: '', '/');
    if ($base && str_starts_with($uri, $base)) $uri = substr($uri, strlen($base));
    $table = $_SERVER['REQUEST_METHOD']==='POST' ? $this->post : $this->get;
    $cb = $table[$uri] ?? $table[rtrim($uri,'/').'/'] ?? null;
    if(!$cb){ http_response_code(404); echo '404'; return; }
    return is_callable($cb) ? $cb() : require $cb;
  }
}

