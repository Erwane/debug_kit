<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.19.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace DebugKit\Test\TestCase\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\IntegrationTestCase;
use DebugKit\Controller\DebugKitController;
use Exception;

/**
 * Composer controller test.
 */
class DebugKitControllerTest extends IntegrationTestCase
{
    /**
     * tests `debug` is disabled
     *
     * @return void
     */
    public function testDebugDisabled()
    {
        $oldStatus = Configure::read('debug');
        Configure::write('debug', false);

        $request = new ServerRequest(['url' => '/debug-kit/']);
        $controller = new DebugKitController($request, new Response());
        $event = new Event('testing');

        // try/catch/finally instead of expectExcetion
        // to restore `debug` configuration
        try {
            $controller->beforeFilter($event);
        } catch (Exception $e) {
            $this->assertInstanceOf('Cake\Http\Exception\NotFoundException', $e);
            $this->assertSame('Not available without debug mode on.', $e->getMessage());
        } finally {
            Configure::write('debug', $oldStatus);
        }
    }
}
