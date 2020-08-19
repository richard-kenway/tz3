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
        $en = $this->post->getFieldValueByLang('title', 'en');
        $ru = $this->post->getFieldValueByLang('title', 'ru');
        $this->post->save();

        $this->assertTrue($en === 'My title');
        $this->assertTrue($ru === 'Мой заголовок');
    }

    public function testPostGetNull()
    {
        $ua = $this->post->getFieldValueByLang('title', 'ua');

        $this->assertTrue($ua === null);
    }

    public function testPostGetNotJson()
    {
        $this->post->content = 'asdf';
        $this->post->save();
        $ua = $this->post->getFieldValueByLang('content', 'ua');

        $this->assertTrue($ua === null);
    }

    public function testPostSetNotJson()
    {
        $ua = $this->post->setFieldValueByLang('content', 'ua', 'Україна');
        $this->post->save();

        $this->assertTrue($ua === false);
    }
}
