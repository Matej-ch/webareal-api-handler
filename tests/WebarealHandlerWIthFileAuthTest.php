<?php

namespace matejch\webarealApiHandler\tests;

use matejch\webarealApiHandler\WebarealHandler;
use PHPUnit\Framework\TestCase;

class WebarealHandlerWIthFileAuthTest extends TestCase
{

    /**
     * @test
     */
    public function it_throws_exception_if_auth_file_doesnt_exists()
    {
        $this->expectException(\Exception::class);

        WebarealHandler::createFromFile('stubs/no_auth.json');
    }

    /**
     * @test
     */
    public function it_throws_exception_if_missing_one_of_the_values()
    {
        $this->expectException(\Exception::class);

        $this->expectExceptionMessage('Missing field apiKey');

        WebarealHandler::createFromFile(__DIR__.'/stubs/error_auth.json');
    }

    /**
     * @test
     */
    public function it_creates_instance_with_auth_from_file()
    {

        $handler = WebarealHandler::createFromFile('stubs/auth.json');

        $this->assertInstanceOf(WebarealHandler::class,$handler);
    }
}