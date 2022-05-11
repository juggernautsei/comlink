<?php

/**
 * Useful globals class for Rest
 *
 * @package   OpenEMR
 * @link      http://www.open-emr.org
 * @author    Jerry Padgett <sjpadgett@gmail.com>
 * @author    Brady Miller <brady.g.miller@gmail.com>
 * @copyright Copyright (c) 2018-2020 Jerry Padgett <sjpadgett@gmail.com>
 * @copyright Copyright (c) 2019 Brady Miller <brady.g.miller@gmail.com>
 * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */

require_once __DIR__ . '/vendor/autoload.php';

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Common\Auth\OpenIDConnect\Repositories\AccessTokenRepository;
use OpenEMR\Common\Logging\EventAuditLogger;
use OpenEMR\Common\Logging\SystemLogger;
use OpenEMR\Common\Session\SessionUtil;
use OpenEMR\Common\Uuid\UuidRegistry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

// also a handy place to add utility methods
// TODO before v6 release: refactor http_response_code(); for psr responses.
//
class RestConfig
{
    /** @var routemap is an array of patterns and routes */
    public static $ROUTE_MAP;

    /** @var fhir routemap is an  of patterns and routes */
    public static $FHIR_ROUTE_MAP;

    /** @var portal routemap is an  of patterns and routes */
    public static $PORTAL_ROUTE_MAP;

    /** @var portal fhir routemap is an  of patterns and routes */
    public static $PORTAL_FHIR_ROUTE_MAP;

    /** @var app root is the root directory of the application */
    public static $APP_ROOT;

    /** @var root url of the application */
    public static $ROOT_URL;
    // you can guess what the rest are!
    public static $VENDOR_DIR;
    public static $SITE;
    public static $apisBaseFullUrl;
    public static $webserver_root;
    public static $web_root;
    public static $server_document_root;
    public static $publicKey;
    private static $INSTANCE;
    private static $IS_INITIALIZED = false;
    /**  @var set to true if local api call */
    private static $localCall = false;
    /**  @var set to true if not rest call */
    private static $notRestCall = false;

    /** prevents external construction */
    private function __construct()
    {
    }

    /**
     * Returns an instance of the RestConfig singleton
     *
     * @return RestConfig
     */
    public static function GetInstance(): \RestConfig
    {
        if (!self::$IS_INITIALIZED) {
            self::Init();
        }

        if (!self::$INSTANCE instanceof self) {
            self::$INSTANCE = new self();
        }

        return self::$INSTANCE;
    }

    /**
     * Initialize the RestConfig object
     */
    public static function Init(): void
    {
        if (self::$IS_INITIALIZED) {
            return;
        }
        // The busy stuff.
        self::setPaths();
        self::setSiteFromEndpoint();
        self::$ROOT_URL = self::$web_root . "/apis";
        self::$VENDOR_DIR = self::$webserver_root . "/vendor";
        self::$publicKey = self::$webserver_root . "/sites/" . self::$SITE . "/documents/certificates/oapublic.key";
        self::$IS_INITIALIZED = true;
    }

    /**
     * Basic paths when GLOBALS are not yet available.
     *
     * @return void
     */
    private static function SetPaths(): void
    {
        $isWindows = (stripos(PHP_OS_FAMILY, 'WIN') === 0);
        // careful if moving this class to modify where's root.
        self::$webserver_root = __DIR__;
        if ($isWindows) {
            //convert windows path separators
            self::$webserver_root = str_replace("\\", "/", self::$webserver_root);
        }
        // Collect the apache server document root (and convert to windows slashes, if needed)
        self::$server_document_root = realpath($_SERVER['DOCUMENT_ROOT']);
        if ($isWindows) {
            //convert windows path separators
            self::$server_document_root = str_replace("\\", "/", self::$server_document_root);
        }
        self::$web_root = substr(self::$webserver_root, strspn(self::$webserver_root ^ self::$server_document_root, "\0"));
        // Ensure web_root starts with a path separator
        if (preg_match("/^[^\/]/", self::$web_root)) {
            self::$web_root = "/" . self::$web_root;
        }
        // Will need these occasionally. sql init comes to mind!
        $GLOBALS['rootdir'] = self::$web_root . "/interface";
        // Absolute path to the source code include and headers file directory (Full path):
        $GLOBALS['srcdir'] = self::$webserver_root . "/library";
        // Absolute path to the location of documentroot directory for use with include statements:
        $GLOBALS['fileroot'] = self::$webserver_root;
        // Absolute path to the location of interface directory for use with include statements:
        $GLOBALS['incdir'] = self::$webserver_root . "/interface";
        // Absolute path to the location of documentroot directory for use with include statements:
        $GLOBALS['webroot'] = self::$web_root;
        // Static assets directory, relative to the webserver root.
        $GLOBALS['assets_static_relative'] = self::$web_root . "/public/assets";
        // Relative themes directory, relative to the webserver root.
        $GLOBALS['themes_static_relative'] = self::$web_root . "/public/themes";
        // Relative images directory, relative to the webserver root.
        $GLOBALS['images_static_relative'] = self::$web_root . "/public/images";
        // Static images directory, absolute to the webserver root.
        $GLOBALS['images_static_absolute'] = self::$webserver_root . "/public/images";
        //Composer vendor directory, absolute to the webserver root.
        $GLOBALS['vendor_dir'] = self::$webserver_root . "/vendor";
    }

    private static function setSiteFromEndpoint(): void
    {
        // Get site from endpoint if available. Unsure about this though!
        // Will fail during sql init otherwise.
        $endPointParts = self::parseEndPoint(self::getRequestEndPoint());
        if (count($endPointParts) > 1) {
            $site_id = $endPointParts[0] ?? '';
            if ($site_id) {
                self::$SITE = $site_id;
            }
        }
    }

    public static function parseEndPoint($resource): array
    {
        if ($resource[0] === '/') {
            $resource = substr($resource, 1);
        }
        return explode('/', $resource);
    }

    public static function getRequestEndPoint(): string
    {
        $resource = null;
        if (!empty($_REQUEST['_REWRITE_COMMAND'])) {
            $resource = "/" . $_REQUEST['_REWRITE_COMMAND'];
        } elseif (!empty($_SERVER['REDIRECT_QUERY_STRING'])) {
            $resource = str_replace('_REWRITE_COMMAND=', '/', $_SERVER['REDIRECT_QUERY_STRING']);
        } else {
            if (!empty($_SERVER['REQUEST_URI'])) {
                if (strpos($_SERVER['REQUEST_URI'], '?') > 0) {
                    $resource = strstr($_SERVER['REQUEST_URI'], '?', true);
                } else {
                    $resource = str_replace(self::$ROOT_URL, '', $_SERVER['REQUEST_URI']);
                }
            }
        }

        return $resource;
    }

    public static function verifyAccessToken()
    {
        $logger = SystemLogger::instance();
        $response = self::createServerResponse();
        $request = self::createServerRequest();
        $server = new ResourceServer(
            new AccessTokenRepository(),
            self::$publicKey
        );
        try {
            $raw = $server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $exception) {
            $logger->error("RestConfig->verifyAccessToken() OAuthServerException", ["message" => $exception->getMessage()]);
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $logger->error("RestConfig->verifyAccessToken() Exception", ["message" => $exception->getMessage()]);
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($response);
        }

        return $raw;
    }

    public static function isTrustedUser($clientId, $userId)
    {
        $response = self::createServerResponse();
        try {
            $trusted = sqlQueryNoLog("SELECT * FROM `oauth_trusted_user` WHERE `client_id`= ? AND `user_id`= ?", array($clientId, $userId));
            if (empty($trusted['session_cache'])) {
                throw new OAuthServerException('Refresh Token revoked or logged out', 0, 'invalid _request', 400);
            }
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($response);
        }

        return $trusted;
    }

    public static function createServerResponse(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        return $psr17Factory->createResponse();
    }

    public static function createServerRequest(): ServerRequestInterface
    {
        $psr17Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        return $creator->fromGlobals();
    }

    public static function destroySession(): void
    {
        SessionUtil::apiSessionCookieDestroy();
    }

    public static function getPostData($data)
    {
        if (count($_POST)) {
            return $_POST;
        }

        if ($post_data = file_get_contents('php://input')) {
            if ($post_json = json_decode($post_data, true)) {
                return $post_json;
            }
            parse_str($post_data, $post_variables);
            if (count($post_variables)) {
                return $post_variables;
            }
        }

        return null;
    }

    public static function authorization_check($section, $value): void
    {
        $result = AclMain::aclCheckCore($section, $value);
        if (!$result) {
            if (!self::$notRestCall) {
                http_response_code(401);
            }
            exit();
        }
    }

    // Main function to check scope
    //  Use cases:
    //     Only sending $scopeType would be for something like 'openid'
    //     For using all 3 parameters would be for something like 'user/Organization.write'
    //       $scopeType = 'user', $resource = 'Organization', $permission = 'write'
    public static function scope_check($scopeType, $resource = null, $permission = null): void
    {
        if (!empty($GLOBALS['oauth_scopes'])) {
            // Need to ensure has scope
            if (empty($resource)) {
                // Simply check to see if $scopeType is an allowed scope
                $scope = $scopeType;
            } else {
                // Resource scope check
                $scope = $scopeType . '/' . $resource . '.' . $permission;
            }
            if (!in_array($scope, $GLOBALS['oauth_scopes'])) {
                http_response_code(401);
                exit;
            }
        }
    }

    public static function setLocalCall(): void
    {
        self::$localCall = true;
    }

    public static function setNotRestCall(): void
    {
        self::$notRestCall = true;
    }

    public static function is_fhir_request($resource): bool
    {

        return stripos(strtolower($resource), "/fhir/") !== false;
    }

    public static function is_portal_request($resource): bool
    {
        return stripos(strtolower($resource), "/portal/") !== false;
    }

    public static function is_portal_fhir_request($resource): bool
    {
        return stripos(strtolower($resource), "/portalfhir/") !== false;
    }

    public static function is_api_request($resource): bool
    {
        return stripos(strtolower($resource), "/api/") !== false;
    }

    public static function skipApiAuth($resource): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            // we don't authenticate OPTIONS requests
            return true;
        }

        // ensure 1) sane site and 2) ensure the site exists on filesystem before even considering for skip api auth
        if (empty(self::$SITE) || preg_match('/[^A-Za-z0-9\\-.]/', self::$SITE) || !file_exists(__DIR__ . '/sites/' . self::$SITE)) {
            error_log("OpenEMR Error - api site error, so forced exit");
            http_response_code(400);
            exit();
        }
        // let the capability statement for FHIR or the SMART-on-FHIR through
        if (
            $resource === ("/" . self::$SITE . "/fhir/metadata") ||
            $resource === ("/" . self::$SITE . "/fhir/.well-known/smart-configuration")||
            // $resource === ("/" . self::$gitSITE . "/fhir/PatientBulkUpload")
        ) {
            return true;
        } else {
            return false;
        }
    }

    public static function apiLog($response = '', $requestBody = ''): void
    {
        // only log when using standard api calls (skip when using local api calls from within OpenEMR)
        //  and when api log option is set
        if (!$GLOBALS['is_local_api'] && !self::$notRestCall && $GLOBALS['api_log_option']) {
            if ($GLOBALS['api_log_option'] == 1) {
                // Do not log the response and requestBody
                $response = '';
                $requestBody = '';
            }

            // convert pertinent elements to json
            $requestBody = (!empty($requestBody)) ? json_encode($requestBody) : '';
            $response = (!empty($response)) ? json_encode($response) : '';

            // prepare values and call the log function
            $event = 'api';
            $category = 'api';
            $method = $_SERVER['REQUEST_METHOD'];
            $url = $_SERVER['REQUEST_URI'];
            $patientId = (int)($_SESSION['pid'] ?? 0);
            $userId = (int)($_SESSION['authUserID'] ?? 0);
            $api = [
                'user_id' => $userId,
                'patient_id' => $patientId,
                'method' => $method,
                'request' => $GLOBALS['resource'],
                'request_url' => $url,
                'request_body' => $requestBody,
                'response' => $response
            ];
            if ($patientId === 0) {
                $patientId = null; //entries in log table are blank for no patient_id, whereas in api_log are 0, which is why above $api value uses 0 when empty
            }
            EventAuditLogger::instance()->recordLogItem(1, $event, ($_SESSION['authUser'] ?? ''), ($_SESSION['authProvider'] ?? ''), 'api log', $patientId, $category, 'open-emr', null, null, '', $api);
        }
    }

    public static function emitResponse($response, $build = false): void
    {
        if (headers_sent()) {
            throw new RuntimeException('Headers already sent.');
        }
        $statusLine = sprintf(
            'HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );
        header($statusLine, true);
        foreach ($response->getHeaders() as $name => $values) {
            $responseHeader = sprintf('%s: %s', $name, $response->getHeaderLine($name));
            header($responseHeader, false);
        }
        echo $response->getBody();
    }

    public function authenticateUserToken($tokenId, $userId): bool
    {
        $ip = collectIpAddresses();

        // check for token
        $authToken = sqlQueryNoLog("SELECT `expiry` FROM `api_token` WHERE `token` = ? AND `user_id` = ?", [$tokenId, $userId]);
        if (empty($authToken) || empty($authToken['expiry'])) {
            EventAuditLogger::instance()->newEvent('api', '', '', 0, "API failure: " . $ip['ip_string'] . ". Token not found for " . $userId . ".");
            return false;
        }

        // Ensure token not expired (note an expired token should have already been caught by oauth2, however will also check here)
        $currentDateTime = date("Y-m-d H:i:s");
        $expiryDateTime = date("Y-m-d H:i:s", strtotime($authToken['expiry']));
        if ($expiryDateTime <= $currentDateTime) {
            EventAuditLogger::instance()->newEvent('api', '', '', 0, "API failure: " . $ip['ip_string'] . ". Token expired for " . $userId . ".");
            return false;
        }

        // Token authentication passed
        EventAuditLogger::instance()->newEvent('api', '', '', 1, "API success: " . $ip['ip_string'] . ". Token successfully used for " . $userId . ".");
        return true;
    }

    /** prevents external cloning */
    private function __clone()
    {
    }
}

// Include our routes and init routes global
//
require_once(__DIR__ . "/_rest_routes.inc.php");
