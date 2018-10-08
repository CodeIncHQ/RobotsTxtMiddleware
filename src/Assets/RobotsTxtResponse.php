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
// Time:     17:48
// Project:  RobotsTxtMiddleware
//
declare(strict_types=1);
namespace CodeInc\RobotsTxtMiddleware\Assets;
use Arcanedev\Robots\Robots;
use CodeInc\Psr7Responses\TextResponse;
use CodeInc\RobotsTxtMiddleware\RobotsTxtMiddleware;


/**
 * Class RobotsTxtResponse
 *
 * @uses Robots
 * @see RobotsTxtMiddleware
 * @package CodeInc\RobotsTxtMiddleware\Assets
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RobotsTxtResponse extends TextResponse
{
    /**
     * RobotsTxtResponse constructor.
     *
     * @param Robots $robots
     * @param int $code
     * @param string $reasonPhrase
     * @param array $headers
     * @param string $version
     */
    public function __construct(Robots $robots, int $code = 200, string $reasonPhrase = '',
        array $headers = self::DEFAULT_HEADERS, string $version = '1.1')
    {
        parent::__construct($robots->generate(), $code, $reasonPhrase, $headers, $version);
    }
}