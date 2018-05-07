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
// Time:     17:35
// Project:  RobotsTxtMiddleware
//
declare(strict_types=1);
namespace CodeInc\RobotsTxtMiddleware\Tests;
use CodeInc\MiddlewareTestKit\BlankResponse;
use CodeInc\MiddlewareTestKit\FakeRequestHandler;
use CodeInc\MiddlewareTestKit\FakeServerRequest;
use CodeInc\RobotsTxtMiddleware\Assets\RobotsTxtResponse;
use CodeInc\RobotsTxtMiddleware\RobotsTxtMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;


/**
 * Class RobotsTxtMiddlewareTest
 *
 * @uses  RobotsTxtMiddleware
 * @package CodeInc\RobotsTxtMiddleware\Tests
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RobotsTxtMiddlewareTest extends TestCase
{
    public function testRegularRequest():void
    {
        $robotsTxtMiddleware = new RobotsTxtMiddleware();
        $robotsTxtMiddleware->addAllow('/test.html');
        $request = FakeServerRequest::getUnsecureServerRequestWithPath('/a-page.html');
        self::assertFalse($robotsTxtMiddleware->isRobotsTxtRequest($request));
        $response = $robotsTxtMiddleware->process(
            $request,
            new FakeRequestHandler()
        );
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertInstanceOf(BlankResponse::class, $response);
    }

    public function testRobotsTxtRequest():void
    {
        foreach ([RobotsTxtMiddleware::DEFAULT_URI_PATH, '/test/robots.txt'] as $uriPath) {
            $robotsTxtMiddleware = new RobotsTxtMiddleware($uriPath);
            $robotsTxtMiddleware->addAllow('/test.html');
            $robotsTxtMiddleware->addDisallow('/private');
            $robotsTxtMiddleware->addSitemap('/sitemap.xml');

            $request = FakeServerRequest::getUnsecureServerRequestWithPath($uriPath);
            self::assertTrue($robotsTxtMiddleware->isRobotsTxtRequest($request));
            $response = $robotsTxtMiddleware->process(
                $request,
                new FakeRequestHandler()
            );

            self::assertInstanceOf(ResponseInterface::class, $response);
            self::assertInstanceOf(RobotsTxtResponse::class, $response);
            $responseBody = $response->getBody()->__toString();
            self::assertRegExp('#Sitemap:\\s+/sitemap.xml#ui', $responseBody);
            self::assertRegExp('#Disallow:\\s+/private#ui', $responseBody);
            self::assertRegExp('#Allow:\\s+/test.html#ui', $responseBody);
        }
    }

    public function testDisallowAll():void
    {
        $robotsTxtMiddleware = RobotsTxtMiddleware::disallowAll();
        $request = FakeServerRequest::getUnsecureServerRequestWithPath(RobotsTxtMiddleware::DEFAULT_URI_PATH);
        self::assertTrue($robotsTxtMiddleware->isRobotsTxtRequest($request));
        $response = $robotsTxtMiddleware->process(
            $request,
            new FakeRequestHandler()
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertInstanceOf(RobotsTxtResponse::class, $response);

        $responseBody = $response->getBody()->__toString();
        self::assertNotEmpty($responseBody);
        self::assertRegExp('#User-agent: \\*#ui', $responseBody);
        self::assertRegExp('#Disallow: /#ui', $responseBody);
    }

    public function testAllowAll():void
    {
        $robotsTxtMiddleware = RobotsTxtMiddleware::allowAll();
        $request = FakeServerRequest::getUnsecureServerRequestWithPath(RobotsTxtMiddleware::DEFAULT_URI_PATH);
        self::assertTrue($robotsTxtMiddleware->isRobotsTxtRequest($request));
        $response = $robotsTxtMiddleware->process(
            $request,
            new FakeRequestHandler()
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertInstanceOf(RobotsTxtResponse::class, $response);

        $responseBody = $response->getBody()->__toString();
        self::assertNotEmpty($responseBody);
        self::assertRegExp('#User-agent: \\*#ui', $responseBody);
        self::assertRegExp('#Allow:\ /#ui', $responseBody);
    }
}