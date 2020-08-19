<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LangsTest extends TestCase
{
    private $post;

    public function setUp(): void
    {
        parent::setUp();
        $this->post = Post::orderBy('id', 'DESC')->first();
    }

    public function testPost()
    {
        $this->post = new Post();
        $this->post->setFieldValueByLang('title', 'en', 'My title');
        $this->post->setFieldValueByLang('title', 'ru', 'Мой заголовок');
        $this->post->save();
        $en = $this->post->getFieldValueByLang('title', 'en');
        $ru = $this->post->getFieldValueByLang('title', 'ru');

        var_dump($en);
        var_dump($ru);
        $this->assertTrue($en === 'My title');
        $this->assertTrue($ru === 'Мой заголовок');
    }

    public function testPostGetNull()
    {
        $ua = $this->post->getFieldValueByLang('title', 'ua');

        $this->assertTrue($ua === null);
    }
}
