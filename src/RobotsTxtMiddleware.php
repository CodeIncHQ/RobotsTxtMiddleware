<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     02/05/2018
// Time:     17:30
// Project:  Psr15Middlewares
//
declare(strict_types=1);
namespace CodeInc\RobotsTxtMiddleware;
use Arcanedev\Robots\Robots;
use CodeInc\RobotsTxtMiddleware\Assets\RobotsTxtResponse;
use CodeInc\RobotsTxtMiddleware\Tests\RobotsTxtMiddlewareTest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RobotsTxtMiddleware
 *
 * @uses Robots <https://github.com/ARCANEDEV/Robots>
 * @see RobotsTxtMiddlewareTest
 * @package CodeInc\RobotsTxtMiddleware
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @license MIT <https://github.com/CodeIncHQ/Psr15Middlewares/blob/master/LICENSE>
 * @link https://github.com/CodeIncHQ/Psr15Middlewares
 */
class RobotsTxtMiddleware extends Robots implements MiddlewareInterface
{
    public const DEFAULT_URI_PATH = '/robots.txt';

    /**
     * @var string
     */
    private $uriPath;

    /**
     * RobotsTxtMiddleware constructor.
     *
     * @param string $uriPath
     */
    public function __construct(string $uriPath = self::DEFAULT_URI_PATH)
    {
        parent::__construct();
        $this->uriPath = $uriPath;
    }

    /**
     * Returns the URI path for which the robots.txt file is returned.
     *
     * @return string
     */
    public function getUriPath():string
    {
        return $this->uriPath;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        if ($this->isRobotsTxtRequest($request)) {
            return new RobotsTxtResponse($this);
        }
        else {
            return $handler->handle($request);
        }
    }

    /**
     * Verifies if the requests points toward the robots.txt file.
     *
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function isRobotsTxtRequest(ServerRequestInterface $request):bool
    {
        return $request->getUri()->getPath() == $this->uriPath;
    }

    /**
     * Disallow all robots on /.
     *
     * @param string $uriPath
     * @param string $baseUrl
     * @return RobotsTxtMiddleware
     */
    public static function disallowAll(string $uriPath = self::DEFAULT_URI_PATH, string $baseUrl = '/'):self
    {
        $robotTxtMiddlewre = new self($uriPath);
        $robotTxtMiddlewre->addUserAgent('*');
        $robotTxtMiddlewre->addDisallow($baseUrl);
        return $robotTxtMiddlewre;
    }

    /**
     * Allow all robots on /.
     *
     * @param string $uriPath
     * @param string $baseUrl
     * @return RobotsTxtMiddleware
     */
    public static function allowAll(string $uriPath = self::DEFAULT_URI_PATH, string $baseUrl = '/'):self
    {
        $robotTxtMiddlewre = new self($uriPath);
        $robotTxtMiddlewre->addUserAgent('*');
        $robotTxtMiddlewre->addAllow($baseUrl);
        return $robotTxtMiddlewre;
    }
}