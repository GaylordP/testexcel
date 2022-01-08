<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider dumbData
     */
    public function testUpload(
        string $path,
        bool $success,
        string $expected
    ): void {
        $client = static::createClient();
        $client->request(
            'POST',
            '/',
            [],
            [new UploadedFile(__DIR__ . '/../Resources/Csv/' . $path, 'file.csv')]
        );

        if (true === $success) {
            $this->assertResponseIsSuccessful();
        } else {
            $this->assertResponseStatusCodeSame(400);
        }

        $this->assertResponseHeaderSame(
            'content-type',
            'application/json'
        );

        $this->assertEquals(
            $expected,
            $client->getResponse()->getContent()
        );
    }

    public function dumbData(): array
    {
        return [
            [
                'path' => 'empty.csv',
                'success' => false,
                'expected' => '"Veuillez fournir un titre valide."',
            ],
            [
                'path' => 'negative.csv',
                'success' => true,
                'expected' => '{"question":{"statistics":{"min":-5,"max":-2,"mean":-3.67},"label":"Coucou Negative"}}',
            ],
            [
                'path' => 'note-without-title.csv',
                'success' => false,
                'expected' => '"Veuillez fournir un titre valide."',
            ],
            [
                'path' => 'title-without-note.csv',
                'success' => false,
                'expected' => '"Veuillez fournir au moins une note dans votre document."',
            ],
            [
                'path' => 'valid-with-string-note.csv',
                'success' => true,
                'expected' => '{"question":{"statistics":{"min":1,"max":5,"mean":2.67},"label":"Coucou Valide"}}',
            ],
            [
                'path' => 'valid.csv',
                'success' => true,
                'expected' => '{"question":{"statistics":{"min":1,"max":5,"mean":2.67},"label":"Coucou Valide"}}',
            ],
            [
                'path' => 'coucou-hibou.jpg',
                'success' => false,
                'expected' => '"Impossible de lire le fichier transmis."',
            ],
        ];
    }
}
